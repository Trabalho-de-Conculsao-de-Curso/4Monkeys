<?php

namespace Tests\Unit;

use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('Possivel criar Produto com atributos validos', function () {
    $marca = Marca::factory()->create();

    $produto = Produto::factory()->create([
        'nome' => 'Test Produto',
        'especificacoes' => 'Test Especificacoes',
        'preco' => 100,
        'lojasOnline' => json_encode(['Loja A', 'Loja B']),
        'marca_id' => $marca->id,
    ]);

    $this->assertDatabaseHas('produtos', [
        'nome' => 'Test Produto',
        'especificacoes' => 'Test Especificacoes',
        'preco' => 100,
        'lojasOnline' => json_encode(['Loja A', 'Loja B']),
        'marca_id' => $marca->id,
    ]);
});

test('Possivel atualizar Produto', function () {
    $marca = Marca::factory()->create();
    $produto = Produto::factory()->create(['marca_id' => $marca->id]);

    $produto->update([
        'nome' => 'Updated Name',
        'especificacoes' => 'Updated Especificacoes',
        'preco' => 200,
        'lojasOnline' => json_encode(['Loja C', 'Loja D']),
    ]);

    $this->assertDatabaseHas('produtos', [
        'id' => $produto->id,
        'nome' => 'Updated Name',
        'especificacoes' => 'Updated Especificacoes',
        'preco' => 200,
        'lojasOnline' => json_encode(['Loja C', 'Loja D']),
        'marca_id' => $marca->id,
    ]);
});

test('Possivel deletar Produto sem deletar Marca', function () {
    $marca = Marca::factory()->create();
    $produto = Produto::factory()->create(['marca_id' => $marca->id]);

    $produto->delete();

    $this->assertDatabaseMissing('produtos', ['id' => $produto->id]);
    $this->assertDatabaseHas('marcas', ['id' => $marca->id]);
});

test('it can access the associated Marca from a Produto and vice versa', function () {
    $marca = Marca::class;
    $produto = Produto::factory()->create(['marca_id' => $marca->id]);

    $this->assertEquals($marca->id, $produto->marca-->id);
    $this->assertTrue($marca->produtos->contains($produto));
});
