<?php

use App\Models\Produto;
use App\Models\LojaOnline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;



uses(RefreshDatabase::class);


it('Rota index responde com 302', function () {
    $response = $this->get('/produtos');
    $response->assertStatus(302);
});

it('Rota create responde com 302', function () {
    $response = $this->get('/produtos/create');
    $response->assertStatus(302);
});

it('Rota show responde com 302', function () {
    $produto = Produto::factory()->create();
    $response = $this->get("/produtos/{$produto->id}");
    $response->assertStatus(302);
});

it('Rota edit responde com 302', function () {
    $produto = Produto::factory()->create();
    $response = $this->get("/produtos/{$produto->id}/edit");

    $response->assertStatus(302);
});



/*it('Rota store cria um produto, estoque, log e responde com 302', function () {
    // Mock para o CustomLog
    $this->mock(Log::class, function ($mock) {
        $mock->shouldReceive('create')
            ->once()
            ->with([
                'descricao' => "Produto criado: Produto Teste",
                'operacao' => 'create',
                'user_id' => auth()->id() ?? 1,
            ]);
    });

    // Dados completos necessários para a criação do produto
    $produtoData = [
        'nome' => 'Produto Teste',
        'preco_valor' => 99.99,
        'preco_moeda' => 'BRL',
        'urlLojaOnline' => 'http://lojavirtual.com/produto-teste',
        'disponibilidade' => 1,  // Produto disponível
    ];

    // Simular a requisição POST para criar o produto
    $response = $this->post('/produtos', array_merge($produtoData, [
        '_token' => csrf_token(),
    ]));

    // Verificar se o produto foi inserido na tabela 'produtos'
    $this->assertDatabaseHas('produtos', ['nome' => $produtoData['nome']]);

    // Verificar se o registro da loja online foi criado
    $this->assertDatabaseHas('loja_online', [
        'urlLoja' => $produtoData['urlLojaOnline'],
        'valor' => $produtoData['preco_valor'],
        'moeda' => $produtoData['preco_moeda']
    ]);

    // Verificar se o produto está disponível e o estoque foi criado
    $produto = Produto::where('nome', $produtoData['nome'])->first();
    if ($produto->disponibilidade == 1) {
        $this->assertDatabaseHas('estoque', ['produto_id' => $produto->id]);
    }

    // Verificar se houve redirecionamento para a página de index
    $response->assertStatus(302);
    $response->assertRedirect(route('produtos.index'));
});*/


/*it('Rota show realiza busca e retorna produtos correspondentes', function () {
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
});*/

beforeEach(function () {
    $this->produto = Produto::factory()->create([
        'nome' => 'Produto Antigo',
        'disponibilidade' => 1
    ]);

    $this->lojaOnline = LojaOnline::find($this->produto->loja_online_id);
});
/*
it('atualiza um produto e cria logs corretamente', function () {
    // Mock para garantir que o log está sendo criado
    $this->mock(Log::class, function ($mock) {
        $mock->shouldReceive('create')->twice(); // Uma vez para o produto, outra para a loja online
    });

    // Novos dados para atualizar
    $novosDados = [
        'nome' => 'Produto Atualizado',
        'preco_valor' => 199.99,
        'preco_moeda' => 'USD',
        'urlLojaOnline' => 'http://nova-loja.com/produto-atualizado',
    ];

    // Simular a requisição de atualização
    $response = $this->put("/produtos/{$this->produto->id}", array_merge($novosDados, [
        '_token' => csrf_token(),
    ]));

    // Verificar se o produto foi atualizado
    $this->assertDatabaseHas('produtos', ['id' => $this->produto->id, 'nome' => 'Produto Atualizado']);

    // Verificar se a loja online foi atualizada
    $this->assertDatabaseHas('loja_online', [
        'id' => $this->lojaOnline->id,
        'urlLoja' => $novosDados['urlLojaOnline'],
        'valor' => $novosDados['preco_valor'],
        'moeda' => $novosDados['preco_moeda']
    ]);

    // Verificar se houve redirecionamento para a página de index
    $response->assertStatus(302);
    $response->assertRedirect(route('produtos.index'));
});


it('exclui um produto e cria log de exclusão corretamente', function () {
    // Mock para garantir que o log de exclusão é criado
    $this->mock(Log::class, function ($mock) {
        $mock->shouldReceive('create')->once()->with([
            'descricao' => "Produto excluído: {$this->produto->nome}",
            'operacao' => 'destroy',
            'user_id' => auth()->id() ?? 1,
        ]);
    });

    // Simular a requisição DELETE para excluir o produto
    $response = $this->delete("/produtos/{$this->produto->id}", [
        '_token' => csrf_token(),
    ]);

    // Verificar se o produto foi excluído do banco de dados
    $this->assertDatabaseMissing('produtos', ['id' => $this->produto->id]);

    // Verificar se a loja online foi excluída do banco de dados
    $this->assertDatabaseMissing('loja_online', ['id' => $this->lojaOnline->id]);

    // Verificar se houve redirecionamento para a página de index
    $response->assertStatus(302);
    $response->assertRedirect(route('produtos.index'));
});
*/










