<?php
// tests/Unit/ProdutoFinalTest.php

use App\Models\ProdutoFinals;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('possível create ProdutoFinals', function () {
    $produtoFinal = ProdutoFinals::factory()->create();

    expect($produtoFinal)->toBeInstanceOf(ProdutoFinals::class);
    expect($produtoFinal->nome)->not->toBeEmpty();
    expect(['Categoria1', 'Categoria2', 'Categoria3'])->toContain($produtoFinal->categoria);
});

it('possível update ProdutoFinals', function () {
    $produtoFinal = ProdutoFinals::factory()->create();

    $newNome = 'Updated Name';
    $produtoFinal->nome = $newNome;
    $produtoFinal->save();

    $updatedProdutoFinal = ProdutoFinals::find($produtoFinal->id);
    expect($updatedProdutoFinal->nome)->toBe($newNome);
});

it('possível delete ProdutoFinals', function () {
    $produtoFinal = ProdutoFinals::factory()->create();

    $produtoFinal->delete();

    $deletedProdutoFinal = ProdutoFinals::find($produtoFinal->id);
    expect($deletedProdutoFinal)->toBeNull();
});
