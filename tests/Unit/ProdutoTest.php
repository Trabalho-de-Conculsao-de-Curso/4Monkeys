<?php

namespace Tests\Unit;

use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);


test('Possivel criar Produto', function () {
    $produto = Produto::factory()->create();
    expect($produto->exists)->toBeTrue();
});

test('Possivel criar Produto com atributos validos', function () {

    $produto = Produto::factory()->create([
        'nome' => 'Test Produto',
        'especificacoes' => 'Test Especificacoes',
        'preco' => 100,
        'lojasOnline' => json_encode(['Loja A', 'Loja B']),
    ]);

    $this->assertDatabaseHas('produtos', [
        'nome' => 'Test Produto',
        'especificacoes' => 'Test Especificacoes',
        'preco' => 100,
        'lojasOnline' => json_encode(['Loja A', 'Loja B']),
        'marca_id' => $produto->marca->id,
    ]);
});

test('Possivel acessar Marca associada a Produto', function () {

    $produto = Produto::factory()->create();
    expect($produto->marca->id)->toBe($produto->marca->id);
});

test('um Produto pertence a uma Marca', function () {

    $produto = Produto::factory()->create();

    $this->assertInstanceOf(Marca::class, $produto->marca);
});

test('um Produto tem apenas uma Marca', function () {
    $produto = Produto::factory()->create();
    $marca = Marca::factory()->create(['produto_id' => $produto->id]);

    $this->assertEquals($produto->id, $marca->produto->id);
});


