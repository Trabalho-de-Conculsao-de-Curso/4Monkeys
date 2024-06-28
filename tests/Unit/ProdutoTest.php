<?php

namespace Tests\Unit;

use App\Models\Marca;
use App\Models\Produto;
use App\Models\Preco;
use App\Models\Especificacoes;
use App\Models\LojaOnline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);


test('Possivel criar Produto', function () {
    $produto = Produto::factory()->create();
    expect($produto->exists)->toBeTrue();
});

test('Possivel criar Produto com atributos validos', function () {
    $marca = Marca::factory()->create();
    $especificacoes = Especificacoes::factory()->create();
    $preco = Preco::factory()->create();
    $lojaOnline = LojaOnline::factory()->create();

    $produto = Produto::factory()->create([
        'nome' => 'Test Produto',
        'marca_id' => $marca->id,
        'especificacoes_id' => $especificacoes->id,
        'preco_id' => $preco->id,
        'loja_online_id' => $lojaOnline->id,
    ]);

    $this->assertDatabaseHas('produtos', [
        'nome' => 'Test Produto',
        'marca_id' => $marca->id,
        'especificacoes_id' => $especificacoes->id,
        'preco_id' => $preco->id,
        'loja_online_id' => $lojaOnline->id,
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

test('Possivel delete Produto e suas relações', function () {

    $marca = Marca::factory()->create();
    $especificacoes = Especificacoes::factory()->create();
    $preco = Preco::factory()->create();
    $lojaOnline = LojaOnline::factory()->create();

    $produto = Produto::factory()->create([
        'marca_id' => $marca->id,
        'especificacoes_id' => $especificacoes->id,
        'preco_id' => $preco->id,
        'loja_online_id' => $lojaOnline->id,
    ]);

    $this->assertDatabaseHas('produtos', ['id' => $produto->id]);
    $this->assertDatabaseHas('marcas', ['id' => $marca->id]);
    $this->assertDatabaseHas('especificacoes', ['id' => $especificacoes->id]);
    $this->assertDatabaseHas('precos', ['id' => $preco->id]);
    $this->assertDatabaseHas('loja_online', ['id' => $lojaOnline->id]);

    $produto->delete();

    $this->assertDatabaseMissing('produtos', ['id' => $produto->id]);
    $this->assertDatabaseMissing('marcas', ['id' => $marca->id]);
    $this->assertDatabaseMissing('especificacoes', ['id' => $especificacoes->id]);
    $this->assertDatabaseMissing('precos', ['id' => $preco->id]);
    $this->assertDatabaseMissing('loja_online', ['id' => $lojaOnline->id]);    
});