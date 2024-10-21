<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Conjunto;
use App\Models\Software;
use App\Services\GeminiAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Obtem todos os softwares
        $softwares = Software::all();

        // Verifica se o usuário está logado
        if (Auth::check()) {
            // Se o usuário estiver autenticado, renderiza a view do dashboard
            return view('dashboard', compact('softwares'));
        }

        // Se o usuário não estiver autenticado, renderiza a view da home
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

    public function historicoConjuntos()
    {
        $userId = auth()->id(); // Obter o ID do usuário autenticado

        // Buscar todos os conjuntos do usuário específico SEM filtrar por nome
        $conjuntos = Conjunto::where('user_id', $userId)
            ->orderBy('created_at', 'asc') // Ordenar por data de criação
            ->with(['produtos', 'softwares']) // Carregar produtos e softwares relacionados
            ->get();

        // Verificar se há conjuntos retornados
        if ($conjuntos->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum conjunto encontrado para o usuário.',
            ], 404);
        }

        // Agrupar os conjuntos pela data de criação
        $agrupadosPorData = $conjuntos->groupBy(function ($conjunto) {
            return $conjunto->created_at->format('Y-m-d H:i:s'); // Agrupar por data de criação completa
        });

        // Preparar o histórico
        $historico = [];

        foreach ($agrupadosPorData as $data => $conjuntosPorData) {
            $historicoPorData = [
                'data' => $data,
                'conjuntos' => [],
            ];

            foreach ($conjuntosPorData as $conjunto) {
                $historicoPorData['conjuntos'][] = [
                    'id' => $conjunto->id,
                    'nome' => $conjunto->nome,
                    'categoria' => $conjunto->categoria_id,
                    'produtos' => $conjunto->produtos->map(function ($produto) {
                        return [
                            'id' => $produto->id,
                            'nome' => $produto->nome,
                            'descricao' => $produto->descricao,
                            // Adicione mais campos de produto aqui, se necessário
                        ];
                    }),
                    'softwares' => $conjunto->softwares->map(function ($software) {
                        return [
                            'id' => $software->id,
                            'nome' => $software->nome,
                            'descricao' => $software->descricao,
                            'requisitos' => $software->requisitos->map(function ($requisito) {
                                return [
                                    'nivel' => $requisito->requisito_nivel,
                                    'cpu' => $requisito->cpu,
                                    'gpu' => $requisito->gpu,
                                    'ram' => $requisito->ram,
                                    'placa_mae' => $requisito->placa_mae,
                                    'ssd' => $requisito->ssd,
                                    'cooler' => $requisito->cooler,
                                    'fonte' => $requisito->fonte,
                                ];
                            }),
                        ];
                    }),
                ];
            }

            // Adicionar o histórico do conjunto por data à lista geral
            $historico[] = $historicoPorData;
        }

        // Retornar o histórico em formato JSON
        return response()->json([
            'historico' => $historico,
        ]);
    }


}
