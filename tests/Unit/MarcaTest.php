<?php
namespace Tests\Unit;

use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
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



test('Uma Marca pertence a um Produto', function (){
    $produto = Produto::factory()->create();
    $marca = Marca::factory()->create(['produto_id' => $produto->id]);

    $this->assertInstanceOf(Produto::class, $marca->produto);

});

test('uma Marca pertence a um único Produto', function () {
    $produto = Produto::factory()->create();
    $marca = Marca::factory()->create(['produto_id' => $produto->id]);

    $this->assertEquals($produto->id, $marca->produto->id);
});


test('Possivel acessar Produto associada a Marca', function () {
    $produto = Produto::factory()->create();
    $marca = Marca::factory()->create(['produto_id' => $produto->id]);
    expect($marca->produto->id)->toBe($produto->id);
});
