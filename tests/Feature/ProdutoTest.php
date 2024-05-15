<?php

use App\Models\Produto;
use App\Models\Marca;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Possivel criar Produto associado a Marc', function () {

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
    expect($produto->id)->toBe($produto->marca->id);
});

it('Possivel atualizar Produto e campos de Marca', function () {

    $produto = Produto::factory()->create();
    $marca = $produto->marca;

    $produto->update([
        'nome' => 'Novo Nome do Produto',
        'preco' => 200,
    ]);
    $marca->update([
        'nome' => 'Novo Nome da Marca',
        'qualidade'=>'Nova Qualidade de Marca',
        'garantia'=>'Nova Garantia de Marca',
    ]);

    $produto->refresh();

    expect($produto->nome)->toBe('Novo Nome do Produto');
    expect($produto->preco)->toBe(200);
    expect($produto->marca->nome)->toBe('Novo Nome da Marca');
    expect($produto->marca->qualidade)->toBe('Nova Qualidade de Marca');
    expect($produto->marca->garantia)->toBe('Nova Garantia de Marca');
});

it('Possivel deletar Produto e Marca associada', function () {

    $produto = Produto::factory()->create();
    $marca = $produto->marca;

    $produto->delete();

    expect(Produto::find($produto->id))->toBeNull();
    expect(Produto::find($marca->id))->toBeNull();
});

it('Possivel realizar busca ', function () {

    Produto::factory()->create(['nome' => 'Produto A']);
    Produto::factory()->create(['nome' => 'Produto B']);
    Produto::factory()->create(['nome' => 'Produto C']);

    $results = Produto::search('Produto A')->get();

    $this->assertCount(1, $results);
    $this->assertEquals('Produto A', $results->first()->nome);
});
