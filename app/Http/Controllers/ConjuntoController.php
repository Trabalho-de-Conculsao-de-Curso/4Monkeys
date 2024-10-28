<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Estoque;
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


    public function obterSoftwaresSelecionados(Request $request)
    {
        return Software::find($request->input('softwares'))->toArray();
    }

    public function obterTodosProdutos()
    {
        return Produto::whereHas('estoque')->get()->toArray();
    }

    public function buscarCategoriaPorId($categoryId)
    {
        return Categoria::find($categoryId);
    }

    public function criarConjunto(Categoria $categoria, $user)
    {
        return $user->conjuntos()->create([
            'nome' => 'Conjunto ' . ucfirst($categoria->nome),
            'categoria_id' => $categoria->id,
        ]);
    }


    protected function associarProdutosAoConjunto(Conjunto $conjunto, array $componentes)
    {
        foreach ($componentes as $componentName) {
            // Encontrar o produto pelo nome do componente usando similaridade
            $productId = $this->geminiAPIService->findProductIdBySimilarity($componentName);

            if ($productId) {
                // Associa o produto ao conjunto
                $conjunto->produtos()->attach($productId);
                Log::info("Produto: $componentName associado ao Conjunto.");

                // Recupera o produto e o valor (preço) da loja online associada ao produto
                $produto = Produto::find($productId);
                $lojaOnline = $produto->lojaOnline; // Acessa a loja online relacionada ao produto

                // Verifica se a loja online existe e recupera o valor (preço)
                if ($lojaOnline && $lojaOnline->valor) {
                    $preco = $lojaOnline->valor;  // Valor obtido da tabela loja_online
                    // Salva o histórico do produto com o preço congelado da loja online, incluindo o conjunto_id
                    $this->salvarConjuntoHistorico($productId, $preco, $conjunto->id); // Passa o conjunto_id
                    Log::info("Histórico do produto ID: $productId salvo com o preço: $preco.");
                } else {
                    Log::warning("Preço não encontrado para o produto ID: $productId na loja online.");
                }
            } else {
                Log::warning("Produto não encontrado Controller: $componentName");
                return false;  // Se não encontrar um produto, a execução é interrompida
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
            ->with(['produtos.lojaOnline', 'softwares']) // Carregar produtos com suas lojas online e softwares relacionados
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
                // Pegar os IDs dos produtos do conjunto atual
                $produtoIds = $conjunto->produtos->pluck('id');

                // Calcular o total dos valores mais recentes dos produtos no conjunto específico (com base no conjunto_id)
                $totalConjunto = DB::table('conjunto_historicos')
                    ->where('conjunto_id', $conjunto->id) // Filtrar pelo conjunto atual
                    ->whereIn('produto_id', $produtoIds) // Somente os produtos relacionados ao conjunto
                    ->sum('valor'); // Somar os valores

                // Adicionar o conjunto ao histórico por data
                $historicoPorData['conjuntos'][] = [
                    'id' => $conjunto->id,
                    'nome' => $conjunto->nome,
                    'categoria' => $conjunto->categoria_id,
                    'total' => $totalConjunto, // Adiciona o total calculado
                    'produtos' => $conjunto->produtos->map(function ($produto) {
                        return [
                            'id' => $produto->id,
                            'nome' => $produto->nome,
                            'url' => $produto->lojaOnline->urlLoja ?? 'URL não disponível', // Adiciona a URL da loja online
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

    protected function salvarConjuntoHistorico($produtoId, $valor, $conjuntoId)
    {
        // Verifica se o produto, o valor e o conjunto_id são válidos
        if ($produtoId && $valor && $conjuntoId) {
            // Insere um registro na tabela conjunto_historicos
            DB::table('conjunto_historicos')->insert([
                'produto_id' => $produtoId,
                'conjunto_id' => $conjuntoId,  // Adiciona o conjunto_id
                'valor' => $valor,
                'created_at' => now(),
            ]);
        } else {
            Log::warning("Produto ID, valor ou conjunto ID inválidos ao salvar no histórico.");
        }
    }


}
