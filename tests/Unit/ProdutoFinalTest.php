<?php
// tests/Unit/ProdutoFinalTest.php

use App\Models\ProdutoFinal;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('possível create ProdutoFinal', function () {
    $produtoFinal = ProdutoFinal::factory()->create();

    expect($produtoFinal)->toBeInstanceOf(ProdutoFinal::class);
    expect($produtoFinal->nome)->not->toBeEmpty();
    expect(['Categoria1', 'Categoria2', 'Categoria3'])->toContain($produtoFinal->categoria);
});

it('possível update ProdutoFinal', function () {
    $produtoFinal = ProdutoFinal::factory()->create();

    $newNome = 'Updated Name';
    $produtoFinal->nome = $newNome;
    $produtoFinal->save();

    $updatedProdutoFinal = ProdutoFinal::find($produtoFinal->id);
    expect($updatedProdutoFinal->nome)->toBe($newNome);
});

it('possível delete ProdutoFinal', function () {
    $produtoFinal = ProdutoFinal::factory()->create();

    $produtoFinal->delete();

    $deletedProdutoFinal = ProdutoFinal::find($produtoFinal->id);
    expect($deletedProdutoFinal)->toBeNull();
});
