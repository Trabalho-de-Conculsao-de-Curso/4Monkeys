<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Conjunto;
use App\Models\Software;
use App\Models\User;
use App\Services\GeminiAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;


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
        return view('dashboard', compact('softwares'));
    }

    public function createFree()
    {
        $softwares = Software::all();
        return view('home', compact('softwares'));
    }

    public function selecionar(Request $request)
    {
        $softwaresSelecionados = $this->obterSoftwaresSelecionados($request);
        $produtos = $this->obterTodosProdutos();
        $user = auth()->user(); // Obtém o usuário autenticado'

        do {
            DB::beginTransaction();

            try {
                $produtoNaoEncontrado = false;
                $generatedConjuntoIds = [];
                $conjuntos = []; // Array para armazenar conjuntos com seus totais

                $recommendations = $this->geminiAPIService->getRecommendations($softwaresSelecionados, $produtos);


                foreach ($recommendations['desktops'] as $desktop) {
                    $categoria = $this->buscarCategoriaPorId($desktop['categoria']);

                    if (!$categoria) {
                        Log::warning("Categoria não encontrada para o ID: {$desktop['categoria']}");
                        $produtoNaoEncontrado = true;
                        break;
                    }

                    // Cria o conjunto associado ao usuário autenticado
                    $conjunto = $this->criarConjunto($categoria, $user);
                    $generatedConjuntoIds[] = $conjunto->id;

                    if (!$this->associarProdutosAoConjunto($conjunto, $desktop['componentes'])) {
                        $produtoNaoEncontrado = true;
                        break;
                    }

                    $this->associarSoftwaresAoConjunto($conjunto, $softwaresSelecionados);


                }
                $conjuntos = $recommendations;



                if (!$produtoNaoEncontrado) {
                    DB::commit();
                    return response()->json(['conjuntos' => $conjuntos]);
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


    protected function obterSoftwaresSelecionados(Request $request)
    {
        return Software::find($request->input('softwares'))->toArray();
    }

    protected function obterTodosProdutos()
    {
        return Produto::all()->toArray();
    }

    protected function buscarCategoriaPorId($categoryId)
    {
        return Categoria::find($categoryId);
    }

    protected function criarConjunto(Categoria $categoria, $user)
    {
        return $user->conjuntos()->create([
            'nome' => 'Conjunto ' . ucfirst($categoria->nome),
            'categoria_id' => $categoria->id,
        ]);
    }


    protected function associarProdutosAoConjunto(Conjunto $conjunto, array $componentes)
    {
        foreach ($componentes as $componentName) {
            $productId = $this->geminiAPIService->findProductIdBySimilarity($componentName);

            if ($productId) {
                $conjunto->produtos()->attach($productId);
                Log::info("Produto: $componentName associado ao Conjunto.");
            } else {
                Log::warning("Produto não encontrado Controller: $componentName");
                return false;
            }
        }
        return true;
    }

    protected function associarSoftwaresAoConjunto(Conjunto $conjunto, array $softwaresSelecionados)
    {
        foreach ($softwaresSelecionados as $softwareSelecionado) {
            $conjunto->softwares()->attach($softwareSelecionado['id']);
            Log::info("Software ID: {$softwareSelecionado['id']} associado ao Conjunto.");
        }
    }
}
