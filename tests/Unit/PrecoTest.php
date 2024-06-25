<?php

// tests/Unit/PrecoTest.php

use App\Models\Preco;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('possível create Preco', function () {
    $preco = Preco::factory()->create();

    expect($preco)->toBeInstanceOf(Preco::class);
    expect($preco->valor)->toBeFloat();
    expect($preco->moeda)->toBe('BRL');
});

it('possível update Preco', function () {
    $preco = Preco::factory()->create();

    $newValor = 199.99;
    $preco->valor = $newValor;
    $preco->save();

    $updatedPreco = Preco::find($preco->id);
    expect($updatedPreco->valor)->toBe($newValor);
});

it('possível delete Preco', function () {
    $preco = Preco::factory()->create();

    $preco->delete();

    $deletedPreco = Preco::find($preco->id);
    expect($deletedPreco)->toBeNull();
});
