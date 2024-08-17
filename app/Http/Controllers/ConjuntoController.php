<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Conjunto;
use App\Models\Software;
use App\Services\GeminiAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConjuntoController extends Controller
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
                $generatedConjuntoIds = [];
                $categoriasExistentes = ['1' => false, '2' => false, '3' => false];
                $conjuntos = []; // Array para armazenar conjuntos com seus totais

                $recommendations = $this->geminiAPIService->getRecommendations($softwaresData, $produtosData);

                foreach ($recommendations['desktops'] as $desktop) {
                    $categoryId = $desktop['categoria'];  // ID da categoria retornado pela API

                    // Buscar a categoria pelo ID
                    $categoria = Categoria::find($categoryId);

                    if (!$categoria) {
                        Log::warning("Categoria não encontrada para o ID: $categoryId");
                        $produtoNaoEncontrado = true;
                        break;
                    }

                    // Criar o Conjunto
                    $conjunto = Conjunto::create([
                        'nome' => 'Conjunto ' . ucfirst($categoria->nome),
                        'categoria_id' => $categoria->id,
                    ]);

                    $generatedConjuntoIds[] = $conjunto->id;
                    $categoriasExistentes[$categoria->nome] = true;

                    // Associar produtos ao Conjunto
                    $componentes = $desktop['componentes'];
                    foreach ($componentes as $componentName) {
                        // Busca pelo produto associado
                        $produto = Produto::where('nome', 'like', "%$componentName%")->first();

                        if (!$produto) {
                            $possiveisProdutos = Produto::all(); // Carrega todos os produtos para comparar similaridade

                            if ($possiveisProdutos->isNotEmpty()) {
                                $produto = $possiveisProdutos->sortBy(function ($produto) use ($componentName) {
                                    return levenshtein($componentName, $produto->nome);
                                })->first();

                                Log::info("Produto mais semelhante encontrado CONTROLLER: '{$produto->nome}'");
                            } else {
                                Log::warning("Nenhum produto semelhante encontrado para CONTROLLER: $componentName");
                            }
                        }

                        if ($produto) {
                            $conjunto->produtos()->attach($produto->id);
                            Log::info("Produto: $componentName associado ao Conjunto.");
                        } else {
                            Log::warning("Produto não encontrado Controller: $componentName");
                            $produtoNaoEncontrado = true;
                            break;
                        }
                    }

                    if ($produtoNaoEncontrado) {
                        Conjunto::destroy($generatedConjuntoIds);
                        Log::info("Conjuntos deletados devido a produtos não encontrados.");
                        break;
                    }

                    // Associar softwares ao Conjunto usando IDs diretamente
                    foreach ($softwaresSelecionados as $softwareSelecionado) {
                        $conjunto->softwares()->attach($softwareSelecionado->id);
                        Log::info("Software ID: {$softwareSelecionado->id} associado ao Conjunto.");
                    }

                    // Armazenar conjunto com o total retornado da API
                    $conjuntos[] = [
                        'conjunto' => $conjunto,
                        'total' => $desktop['total'],  // Inclua o total da API
                    ];
                }

                if (!$produtoNaoEncontrado) {
                    DB::commit();

                    // Retornar a view com os dados
                    return view('resultado', compact('conjuntos'));
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
