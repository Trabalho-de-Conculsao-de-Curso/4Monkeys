<?php

use App\Models\Categoria;
use App\Models\Estoque;
use App\Models\Produto;
use App\Services\GeminiAPIService;
use App\Http\Controllers\ConjuntoController;
use App\Models\Software;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;


uses(RefreshDatabase::class);
//Create
test('usuário autenticado pode acessar a página de criação do conjunto', function () {
// Cria um usuário e autentica
$user = User::factory()->create();

// Cria alguns softwares para o teste
$softwares = Software::factory()->count(3)->create();

// Simula a autenticação do usuário e faz a requisição para o método create
$response = $this->actingAs($user)->get(route('home.create'));

// Verifica se a view correta foi renderizada
$response->assertViewIs('dashboard');

// Verifica se a view contém a variável correta com os softwares
$response->assertViewHas('softwares', function ($viewSoftwares) use ($softwares) {
return $softwares->pluck('id')->diff($viewSoftwares->pluck('id'))->isEmpty();
});
});

test('usuário não autenticado é redirecionado para a página de home', function () {
    // Cria alguns softwares para o teste
    $softwares = Software::factory()->count(3)->create();

    // Faz a requisição sem autenticação
    $response = $this->get(route('home.create'));

    // Verifica se a view correta foi renderizada
    $response->assertViewIs('home');

    // Verifica se a view contém a variável correta com os softwares
    $response->assertViewHas('softwares', function ($viewSoftwares) use ($softwares) {
        return $softwares->pluck('id')->diff($viewSoftwares->pluck('id'))->isEmpty();
    });
});

test('a lista de softwares é retornada corretamente', function () {
    // Cria alguns softwares para o teste
    $softwares = Software::factory()->count(5)->create();

    // Autentica um usuário
    $user = User::factory()->create();

    // Faz a requisição autenticada
    $response = $this->actingAs($user)->get(route('home.create'));

    // Verifica se a variável 'softwares' contém os softwares criados
    $response->assertViewHas('softwares', function ($viewSoftwares) use ($softwares) {
        return $softwares->pluck('id')->diff($viewSoftwares->pluck('id'))->isEmpty();
    });

    // Faz a requisição sem autenticação
    $response = $this->get(route('home.create'));

    // Verifica se a variável 'softwares' contém os softwares criados
    $response->assertViewHas('softwares', function ($viewSoftwares) use ($softwares) {
        return $softwares->pluck('id')->diff($viewSoftwares->pluck('id'))->isEmpty();
    });
});

test('usuário autenticado vê o dashboard', function () {
    // Cria e autentica um usuário
    $user = User::factory()->create();

    // Faz a requisição autenticada
    $response = $this->actingAs($user)->get(route('home.create'));

    // Verifica se a view renderizada é o dashboard
    $response->assertViewIs('dashboard');
});

test('usuário não autenticado vê a home', function () {
    // Faz a requisição sem autenticação
    $response = $this->get(route('home.create'));

    // Verifica se a view renderizada é a home
    $response->assertViewIs('home');
});

test('não há softwares disponíveis', function () {
    // Autentica um usuário
    $user = User::factory()->create();

    // Faz a requisição sem criar softwares
    $response = $this->actingAs($user)->get(route('home.create'));

    // Verifica se a view correta foi renderizada
    $response->assertViewIs('dashboard');

    // Verifica se a variável softwares está vazia
    $response->assertViewHas('softwares', function ($viewSoftwares) {
        return $viewSoftwares->isEmpty();
    });
});

// Mock do serviço GeminiApiService
beforeEach(function () {
    // Mock do serviço GeminiAPIService
    $this->geminiAPIServiceMock = Mockery::mock(GeminiAPIService::class);

    // Instância do controller
    $this->controller = new ConjuntoController($this->geminiAPIServiceMock);
});

//ObterSoftwaresSelecionados
it('retorna os softwares corretos quando IDs válidos são passados', function () {
    // Criar softwares no banco de dados
    $software1 = Software::factory()->create();
    $software2 = Software::factory()->create();

    // Mock da request com os IDs válidos
    $request = Request::create('/home', 'POST', ['softwares' => [$software1->id, $software2->id]]);

    // Chame o método do controller
    $response = $this->controller->obterSoftwaresSelecionados($request);

    // Verifique se os softwares retornados estão corretos
    expect($response)->toBeArray()->toHaveCount(2)
        ->and($response[0]['id'])->toBe($software1->id)
        ->and($response[1]['id'])->toBe($software2->id);
});

it('retorna um array vazio quando IDs inválidos são passados', function () {
    // Mock da request com IDs inválidos
    $request = Request::create('/home', 'POST', ['softwares' => [999, 1000]]);

    // Chame o método do controller
    $response = $this->controller->obterSoftwaresSelecionados($request);

    // Verifique se o retorno é um array vazio
    expect($response)->toBeArray()->toBeEmpty();
});

it('retorna um array vazio quando um array vazio é passado', function () {
    // Mock da request com um array vazio
    $request = Request::create('/home', 'POST', ['softwares' => []]);

    // Chame o método do controller
    $response = $this->controller->obterSoftwaresSelecionados($request);

    // Verifique se o retorno é um array vazio
    expect($response)->toBeArray()->toBeEmpty();
});

it('garante que o retorno é sempre um array, mesmo sem softwares', function () {
    // Mock da request com um array vazio
    $request = Request::create('/home', 'POST', ['softwares' => []]);

    // Chame o método do controller
    $response = $this->controller->obterSoftwaresSelecionados($request);

    // Verifique se o retorno é um array
    expect($response)->toBeArray();
});

it('retorna softwares corretamente quando IDs de software são passados como strings', function () {
    // Criar softwares no banco de dados
    $software1 = Software::factory()->create();
    $software2 = Software::factory()->create();

    // Mock da request com IDs de software como strings
    $request = Request::create('/home', 'POST', ['softwares' => [(string)$software1->id, (string)$software2->id]]);

    // Chame o método do controller
    $response = $this->controller->obterSoftwaresSelecionados($request);

    // Verifique se os softwares retornados estão corretos
    expect($response)->toBeArray()->toHaveCount(2)
        ->and($response[0]['id'])->toBe($software1->id)
        ->and($response[1]['id'])->toBe($software2->id);
});

it('retorna softwares corretamente com múltiplas chamadas simultâneas', function () {
    // Criar softwares no banco de dados
    $software1 = Software::factory()->create();
    $software2 = Software::factory()->create();

    // Mock da request com IDs válidos
    $request1 = Request::create('/home', 'POST', ['softwares' => [$software1->id]]);
    $request2 = Request::create('/home', 'POST', ['softwares' => [$software2->id]]);

    // Chame o método do controller simultaneamente
    $response1 = $this->controller->obterSoftwaresSelecionados($request1);
    $response2 = $this->controller->obterSoftwaresSelecionados($request2);

    // Verifique se os softwares retornados estão corretos
    expect($response1)->toBeArray()->toHaveCount(1)
        ->and($response1[0]['id'])->toBe($software1->id);

    expect($response2)->toBeArray()->toHaveCount(1)
        ->and($response2[0]['id'])->toBe($software2->id);
});

//ObterTodosProdutos
it('retorna produtos que possuem estoque', function () {
    // Criar produtos com estoque no banco de dados
    $produto1 = Produto::factory()->create();
    $produto2 = Produto::factory()->create();

    // Criar o relacionamento de estoque
    Estoque::factory()->create(['produto_id' => $produto1->id]);
    Estoque::factory()->create(['produto_id' => $produto2->id]);

    // Chame o método do controller
    $response = $this->controller->obterTodosProdutos();

    // Verifique se os produtos retornados estão corretos
    expect($response)->toBeArray()->toHaveCount(2)
        ->and($response[0]['id'])->toBe($produto1->id)
        ->and($response[1]['id'])->toBe($produto2->id);
});

it('retorna um array vazio quando nenhum produto tem estoque', function () {
    // Criar produtos sem estoque no banco de dados
    Produto::factory()->create();
    Produto::factory()->create();

    // Chame o método do controller
    $response = $this->controller->obterTodosProdutos();

    // Verifique se o retorno é um array vazio
    expect($response)->toBeArray()->toBeEmpty();
});

it('retorna sempre um array, mesmo quando não há produtos com estoque', function () {
    // Não criar produtos nem estoque

    // Chame o método do controller
    $response = $this->controller->obterTodosProdutos();

    // Verifique se o retorno é um array
    expect($response)->toBeArray()->toBeEmpty();
});

it('retorna corretamente produtos com e sem estoque', function () {
    // Criar produtos com e sem estoque
    $produto1 = Produto::factory()->create();
    $produto2 = Produto::factory()->create();
    $produtoSemEstoque = Produto::factory()->create();

    // Criar o relacionamento de estoque
    Estoque::factory()->create(['produto_id' => $produto1->id]);
    Estoque::factory()->create(['produto_id' => $produto2->id]);

    // Chame o método do controller
    $response = $this->controller->obterTodosProdutos();

    // Verifique se os produtos retornados são os que têm estoque
    expect($response)->toBeArray()->toHaveCount(2)
        ->and($response[0]['id'])->toBe($produto1->id)
        ->and($response[1]['id'])->toBe($produto2->id);
});

it('não retorna produtos sem estoque', function () {
    // Criar um produto sem estoque
    $produtoSemEstoque = Produto::factory()->create();

    // Chame o método do controller
    $response = $this->controller->obterTodosProdutos();

    // Verifique que o retorno é vazio
    expect($response)->toBeArray()->toBeEmpty();
});

//buscaCategoriaPorId
it('retorna a categoria correta quando um ID válido é passado', function () {
    // Criar uma categoria no banco de dados
    $categoria = Categoria::factory()->create();

    // Chame o método do controller com um ID válido
    $response = $this->controller->buscarCategoriaPorId($categoria->id);

    // Verifique se a categoria retornada está correta
    expect($response)->not->toBeNull()
        ->and($response->id)->toBe($categoria->id);
});

it('retorna null quando um ID de categoria inválido é passado', function () {
    // Chame o método do controller com um ID que não existe
    $response = $this->controller->buscarCategoriaPorId(9999);

    // Verifique se o retorno é null
    expect($response)->toBeNull();
});

it('retorna null quando o ID de categoria é null', function () {
    // Chame o método do controller com null como ID
    $response = $this->controller->buscarCategoriaPorId(null);

    // Verifique se o retorno é null
    expect($response)->toBeNull();
});

it('retorna null quando uma string inválida é passada como ID da categoria', function () {
    // Chame o método do controller com uma string inválida
    $response = $this->controller->buscarCategoriaPorId('string-invalida');

    // Verifique se o retorno é null
    expect($response)->toBeNull();
});

//CriarConjunto
it('cria um conjunto com categoria e usuário válidos', function () {
    // Criar um usuário e uma categoria no banco de dados
    $user = User::factory()->create();
    $categoria = Categoria::factory()->create(['nome' => 'categoria']);

    // Chame o método do controller para criar o conjunto
    $conjunto = $this->controller->criarConjunto($categoria, $user);

    // Verifique se o conjunto foi criado corretamente no banco de dados
    expect($conjunto)->not->toBeNull()
        ->and($conjunto->nome)->toBe('Conjunto Categoria')
        ->and($conjunto->categoria_id)->toBe($categoria->id)
        ->and($conjunto->user_id)->toBe($user->id);
});

it('cria o conjunto com o nome formatado corretamente', function () {
    // Criar um usuário e uma categoria no banco de dados
    $user = User::factory()->create();
    $categoria = Categoria::factory()->create(['nome' => 'Bronze']);

    // Chame o método do controller para criar o conjunto
    $conjunto = $this->controller->criarConjunto($categoria, $user);

    // Verifique se o nome do conjunto foi capitalizado corretamente
    expect($conjunto->nome)->toBe('Conjunto Bronze');
});

it('associa corretamente o conjunto à categoria', function () {
    // Criar um usuário e uma categoria no banco de dados
    $user = User::factory()->create();
    $categoria = Categoria::factory()->create();

    // Chame o método do controller para criar o conjunto
    $conjunto = $this->controller->criarConjunto($categoria, $user);

    // Verifique se a categoria está associada corretamente ao conjunto
    expect($conjunto->categoria_id)->toBe($categoria->id);
});

it('lança um erro se o usuário não tiver a relação conjuntos', function () {
    // Criar um usuário falso que não tem a relação conjuntos()
    $user = Mockery::mock(User::class);
    $user->shouldReceive('conjuntos')->andThrow(new \Exception('Relacionamento conjuntos() não existe'));

    $categoria = Categoria::factory()->create();

    // Verifique se uma exceção é lançada ao tentar criar um conjunto sem a relação conjuntos()
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Relacionamento conjuntos() não existe');

    $this->controller->criarConjunto($categoria, $user);
});

//AssociarProdutosAoConjunto




//AssociarSoftwaresAoConjunto

//HistoricoConjunto

//SalvarConjuntoHistorico
