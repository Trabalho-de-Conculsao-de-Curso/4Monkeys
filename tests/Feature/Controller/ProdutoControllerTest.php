<?php

use App\Models\LojaOnline;
use App\Models\Produto;


use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('Rota index responde com 200', function () {
    $response = $this->get('/produtos');
    $response->assertStatus(200);
});

it('Rota create responde com 200', function () {
    $response = $this->get('/produtos/create');
    $response->assertStatus(200);
});

it('Rota show responde com 200', function () {
    $produto = Produto::factory()->create();
    $response = $this->get("/produtos/{$produto->id}");
    $response->assertStatus(200);
});

it('Rota edit responde com 200', function () {
    $produto = Produto::factory()->create();
    $response = $this->get("/produtos/{$produto->id}/edit");

    $response->assertStatus(200);
});



it('Rota index retorna produtos da segunda página com paginação e responde com 200', function () {
    // Criação de 15 produtos, mais do que o limite de 10 por página
    Produto::factory()->count(15)->hasLojaOnline()->create();

    // Faz uma requisição para a segunda página
    $response = $this->get('/produtos?page=2', [
        '_token' => csrf_token(),
    ]);

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se os produtos da segunda página aparecem na view
    $produtosPaginados = Produto::with('lojaOnline')->paginate(10, ['*'], 'page', 2);
    foreach ($produtosPaginados->items() as $produto) {
        $response->assertSee($produto->nome);
    }
});

it('Rota store cria um produto e responde com 302', function () {
    // Dados completos necessários para a criação do produto
    $produtoData = [
        'nome' => 'Produto Teste',
        'preco_valor' => 99.99,
        'preco_moeda' => 'BRL',
        'urlLojaOnline' => 'http://lojavirtual.com/produto-teste',
        'disponibilidade' => 1,  // Produto disponível
    ];

    $response = $this->post('/produtos', array_merge($produtoData, [
        '_token' => csrf_token(),
    ]));

    $this->assertDatabaseHas('produtos', ['nome' => $produtoData['nome']]);

    $response->assertStatus(302);
});

it('Rota show realiza busca e retorna produtos correspondentes', function () {
    // Criar produtos e lojas online
    $produto1 = Produto::factory()->create(['nome' => 'Produto A']);
    $produto2 = Produto::factory()->create(['nome' => 'Produto B']);
    $lojaOnline = LojaOnline::factory()->create([
        'urlLoja' => 'http://lojavirtual.com/produto-a'
    ]);

    // Fazer uma busca pelo nome do produto
    $response = $this->get('/produtos?search=Produto A');

    // Verifica se o produto correspondente aparece nos resultados
    $response->assertSee('Produto A');
    $response->assertStatus(200);
});

it('Rota update atualiza um produto e responde com 302', function () {
    // Criação de uma loja online associada
    $lojaOnline = LojaOnline::factory()->create([
        'valor' => 50.00,
        'moeda' => 'BRL',
        'urlLoja' => 'http://lojavirtual.com/produto-antigo',
    ]);

    // Criação de um produto associado a essa loja online
    $produto = Produto::factory()->create([
        'nome' => 'Produto Antigo',
        'disponibilidade' => 0, // Inicialmente indisponível
        'loja_online_id' => $lojaOnline->id,
    ]);

    // Dados atualizados
    $updatedData = [
        'nome' => 'Produto Atualizado',
        'disponibilidade' => 1, // Tornar o produto disponível
        'preco_valor' => 99.99,
        'preco_moeda' => 'USD',
        'urlLojaOnline' => 'http://lojavirtual.com/produto-atualizado',
        '_token' => csrf_token(),
    ];

    // Envia a requisição de atualização
    $response = $this->put("/produtos/{$produto->id}", $updatedData);

    // Verifica se o produto foi atualizado no banco de dados
    $this->assertDatabaseHas('produtos', [
        'id' => $produto->id,
        'nome' => 'Produto Atualizado',
        'disponibilidade' => 1,
        'loja_online_id' => $lojaOnline->id,
    ]);

    // Verifica se a loja online foi atualizada corretamente
    $this->assertDatabaseHas('loja_online', [
        'id' => $lojaOnline->id,
        'valor' => 99.99,
        'moeda' => 'USD',
        'urlLoja' => 'http://lojavirtual.com/produto-atualizado',
    ]);

    // Verifica se o estoque foi criado, já que a disponibilidade foi definida como 1
    $this->assertDatabaseHas('estoque', ['produto_id' => $produto->id]);

    // Verifica o redirecionamento
    $response->assertStatus(302);
});

it('Rota update remove estoque quando o produto fica indisponível', function () {
    // Criar produto com estoque
    $produto = Produto::factory()->create(['disponibilidade' => 1]);

    // Tornar o produto indisponível
    $updatedData = [
        'nome' => 'Produto Atualizado',
        'disponibilidade' => 0, // Indisponível
        '_token' => csrf_token(),
    ];

    // Enviar requisição de atualização
    $response = $this->put("/produtos/{$produto->id}", $updatedData);

    // Verifica se o estoque foi removido
    $this->assertDatabaseMissing('estoque', ['produto_id' => $produto->id]);

    // Verifica o redirecionamento
    $response->assertStatus(302);
});

it('Rota delete responde com 302', function () {
    $produto = Produto::factory()->create();

    // Enviando o token na requisição de exclusão
    $response = $this->delete("/produtos/{$produto->id}", [
        '_token' => csrf_token(),
    ]);

    $this->assertDatabaseMissing('produtos', ['id' => $produto->id]);
    $response->assertStatus(302);
});

it('Rota delete exclui o produto e seus relacionamentos e responde com 302', function () {
    $produto = Produto::factory()->hasLojaOnline()->create(); // Certifique-se de que a factory de Produto cria a relação

    $response = $this->delete("/produtos/{$produto->id}", [
        '_token' => csrf_token(),
    ]);

    $this->assertDatabaseMissing('produtos', ['id' => $produto->id]);
    $this->assertDatabaseMissing('loja_online', ['produto_id' => $produto->id]); // Verifica se o relacionamento também foi deletado
    $response->assertStatus(302);
});

it('Rota store falha ao criar um produto sem dados obrigatórios e responde com 422', function () {
    $response = $this->postJson('/produtos', []); // Enviando dados vazios

    $response->assertStatus(422);

    $response->assertJsonValidationErrors(['nome', 'preco_valor', 'preco_moeda', 'urlLojaOnline', 'disponibilidade']);
});









