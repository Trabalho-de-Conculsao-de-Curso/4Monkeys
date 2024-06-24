<?php

namespace Tests\Unit;

use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);


test('Possivel criar Marca', function () {
    Marca::factory()->create();

    $this->assertDatabaseCount('marcas', 1);
});

test('Não é possivel criar Marca sem um nome, qualidade e garantia', function () {
    $this->expectException(\Illuminate\Database\QueryException::class);

    Marca::factory()->create([
        'nome' => null,
        'qualidade' => null,
        'garantia' => null,
    ]);
});


test('Um Produto pode ter várias Marcas', function (){

    $produto = Produto::factory()->create();
    $marcas = Marca::factory(count: 3)->create();
    foreach ($marcas as $marca) {
        $produto->marca_id = $marca->id;
        $produto->save();
    }

    //Verifica se tem 4 pois na factory de produto ja cria uma marca associada
    assertDatabaseCount('marcas', 4);
    foreach ($marcas as $marca) {
        assertDatabaseHas('marcas', ['id' => $marca->id]);
    }
});


test('Possivel acessar Produto associada a Marca', function () {

    $produtos = Produto::factory()->count(3)->create();

    foreach ($produtos as $produto) {
        $marca = (new Marca)->find($produto->marca_id);
        expect($marca->produtos)->toContain($produto);
    }
});
