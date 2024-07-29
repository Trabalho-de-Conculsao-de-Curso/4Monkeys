<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\ProdutoFinals;
use App\Models\Software;
use App\Services\GeminiAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProdutoFinalController extends Controller
{
    protected $geminiAPIService;

    public function __construct(GeminiAPIService $geminiAPIService)
    {
        $this->geminiAPIService = $geminiAPIService;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $softwares = Software::all();
        return view('home', compact('softwares'));
    }

    public function selecionar(Request $request)
    {
        $softwaresSelecionados = Software::find($request->input('softwares'));
        $produtos = Produto::all();

        // Preparar dados para enviar ao GeminiAPI
        $softwaresData = $softwaresSelecionados->toArray();
        $produtosData = $produtos->toArray();

        do {
            DB::beginTransaction();

            try {
                $produtoNaoEncontrado = false;
                $generatedProdutoFinalIds = [];

                $recommendations = $this->geminiAPIService->getRecommendations($softwaresData, $produtosData);
                $categoriasExistentes = ['bronze' => false, 'silver' => false, 'gold' => false];

                foreach ($recommendations['desktops'] as $desktop) {
                    $category = strtolower($desktop['categoria']);

                    // Verifica se a categoria já foi processada
                    if (isset($categoriasExistentes[$category]) && $categoriasExistentes[$category]) {
                        continue;
                    }

                    $produtoFinal = ProdutoFinals::create([
                        'nome' => 'Produto Final ' . ucfirst($category),
                        'categoria' => $category,
                        'preco_total' => $desktop['total'] / 100 // Dividir por 100 para formatar corretamente
                    ]);

                    $generatedProdutoFinalIds[] = $produtoFinal->id;

                    // Marca a categoria como processada
                    $categoriasExistentes[$category] = true;

                    // Associar produtos ao ProdutoFinals
                    $componentes = $desktop['componentes'];
                    foreach ($componentes as $componentName) {
                        $produto = Produto::whereHas('especificacoes', function ($query) use ($componentName) {
                            $query->where('detalhes', 'like', "%$componentName%");
                        })->first();

                        if ($produto) {
                            $produtoFinal->produtos()->attach($produto);
                            Log::info("Produto: $componentName associado ao ProdutoFinals.");
                        } else {
                            Log::warning("Produto não encontrado: $componentName");
                            $produtoNaoEncontrado = true;
                            break;
                        }
                    }

                    if ($produtoNaoEncontrado) {
                        ProdutoFinals::destroy($generatedProdutoFinalIds);
                        Log::info("Produtos finais deletados devido a produtos não encontrados.");
                        break;
                    }

                    // Associar softwares ao ProdutoFinals
                    foreach ($softwaresSelecionados as $softwareSelecionado) {
                        $software = Software::where('nome', $softwareSelecionado->nome)
                            ->orWhere('tipo', $softwareSelecionado->tipo)
                            ->orWhere('descricao', 'like', "%{$softwareSelecionado->descricao}%")
                            ->first();

                        if ($software) {
                            $produtoFinal->softwares()->attach($software);
                            Log::info("Software: {$softwareSelecionado->nome} associado ao ProdutoFinal.");
                        } else {
                            Log::warning("Software não encontrado: {$softwareSelecionado->nome}");
                            $produtoNaoEncontrado = true;
                            break;
                        }
                    }

                    if ($produtoNaoEncontrado) {
                        ProdutoFinals::destroy($generatedProdutoFinalIds);
                        Log::info("Produtos finais deletados devido a softwares não encontrados.");
                        break;
                    }
                }

                if (!$produtoNaoEncontrado) {
                    DB::commit();
                    $produtoFinals = ProdutoFinals::with('produtos', 'softwares')
                        ->whereIn('id', $generatedProdutoFinalIds)
                        ->get();

                    // Retornar a view com os dados
                    return view('resultado', compact('produtoFinals'));
                } else {
                    DB::rollBack();
                }

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Erro ao processar: " . $e->getMessage());
                return redirect()->back()->withErrors('Erro ao processar a seleção.');
            }

        } while ($produtoNaoEncontrado);
    }




}
