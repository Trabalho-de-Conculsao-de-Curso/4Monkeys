<?php

use App\Models\Produto;
use App\Models\Marca;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Possivel criar Produto', function () {

    $produto = Produto::factory()->create([
        'nome' => 'Produto de Teste',
        'especificacoes' => 'Especificações do Produto de Teste',
        'preco' => 100,
        'lojasOnline' => 'Loja A, Loja B',
    ]);

    expect($produto->nome)->toBe('Produto de Teste');
    expect($produto->especificacoes)->toBe('Especificações do Produto de Teste');
    expect($produto->preco)->toBe(100);
    expect($produto->lojasOnline)->toBe('Loja A, Loja B');
});

it('Possivel atualizar Produto', function () {

    $produto = Produto::factory()->create();
    $produto->update([
        'nome' => 'Novo Nome do Produto',
        'preco' => 200,
    ]);
    $produto->refresh();

    expect($produto->nome)->toBe('Novo Nome do Produto');
    expect($produto->preco)->toBe(200);
});

it('Possivel deletar Produto', function () {

    $produto = Produto::factory()->create();
    $produto->delete();

    expect(Produto::find($produto->id))->toBeNull();
});

it('Possivel criar Produto associado a Marca', function () {
    // Cria uma nova marca
    $marca = Marca::factory()->create();

    // Cria um novo produto associado à marca criada
    $produto = Produto::factory()->create(['marca_id' => $marca->id]);

    // Verifica se o produto foi criado corretamente
    expect($produto->marca_id)->toBe($marca->id);
});

it('Possivel atualizar Produto associado a Marca', function () {

    $marca = Marca::factory()->create();
    $produto = Produto::factory()->create();

    $produto->update(['marca_id' => $marca->id]);
    $produto->refresh();

    expect($produto->marca_id)->toBe($marca->id);
});

it('Possivel deletar Produto e Marca associada', function () {
    // Cria uma nova marca
    $produto = Produto::factory()->create();

    // Cria um novo produto associado à marca criada
    $marca = Marca::factory()->create(['produto_id' => $produto->id]);

    // Deleta o produto
    $produto->delete();

    // Verifica se o produto foi removido do banco de dados
    expect(Produto::find($produto->id))->toBeNull();

    // Verifica se a marca associada foi removida do banco de dados
    expect(Marca::find($marca->id))->toBeNull();
});
