<?php
// tests/Unit/ProdutoFinalTest.php

use App\Models\Conjunto;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('possível create Conjunto', function () {
    $produtoFinal = Conjunto::factory()->create();

    expect($produtoFinal)->toBeInstanceOf(Conjunto::class);
    expect($produtoFinal->nome)->not->toBeEmpty();
    expect(['Categoria1', 'Categoria2', 'Categoria3'])->toContain($produtoFinal->categoria);
});

it('possível update Conjunto', function () {
    $produtoFinal = Conjunto::factory()->create();

    $newNome = 'Updated Name';
    $produtoFinal->nome = $newNome;
    $produtoFinal->save();

    $updatedProdutoFinal = Conjunto::find($produtoFinal->id);
    expect($updatedProdutoFinal->nome)->toBe($newNome);
});

it('possível delete Conjunto', function () {
    $produtoFinal = Conjunto::factory()->create();

    $produtoFinal->delete();

    $deletedProdutoFinal = Conjunto::find($produtoFinal->id);
    expect($deletedProdutoFinal)->toBeNull();
});
