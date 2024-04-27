<?php

use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('Possivel criar Marca', function () {
    $marca = Marca::factory()->create([
        'nome' => 'Test Marca',
        'qualidade' => 'Test Qualidade',
        'garantia' => 'Test Garantia',
    ]);

    $this->assertDatabaseHas('marcas', [
        'nome' => 'Test Marca',
        'qualidade' => 'Test Qualidade',
        'garantia' => 'Test Garantia',
    ]);
});

test('Possivel criar Produto', function () {
    $marca = Marca::factory()->create();
    $lojasOnline = ['Loja A','Loja B','Loja C'];

    $produto = Produto::factory()->create([
        'nome' => 'Test Produto',
        'marca_id' => $marca->id,
        'especificacoes' => 'Test Especificacoes',
        'preco' => 100,
        'lojasOnline' => json_encode($lojasOnline),
    ]);

    $this->assertDatabaseHas('produtos', [
        'nome' => 'Test Produto',
        'marca_id' => $marca->id,
        'especificacoes' => 'Test Especificacoes',
        'preco' => 100,
        'lojasOnline' => json_encode($lojasOnline),
    ]);
});
