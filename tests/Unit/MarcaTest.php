<?php
namespace Tests\Unit;

use App\Models\Marca;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);


test('Não é possivel criar Marca sem um nome, qualidade e garantia', function () {
    $this->expectException(\Illuminate\Database\QueryException::class);

    Marca::factory()->create([
        'nome' => null,
        'qualidade' => null,
        'garantia' => null,
    ]);
});

test('Possivel atualizar Marca', function () {
    $marca = Marca::factory()->create();

    $marca->update([
        'nome' => 'Updated Name',
        'qualidade' => 'Updated Qualidade',
        'garantia' => 'Updated Garantia',
    ]);

    $this->assertDatabaseHas('marcas', [
        'id' => $marca->id,
        'nome' => $marca->nome,
        'qualidade' => $marca->qualidade,
        'garantia' => $marca->garantia,
    ]);
});

test('Possivel deletar Marca', function () {
    $marca = Marca::factory()->create();

    $marca->delete();

    $this->assertDatabaseMissing('marcas', ['id' => $marca->id]);
});
