<?php

// tests/Unit/LojaOnlineTest.php

use App\Models\LojaOnline;
use App\Http\Controllers\ProdutoController;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('possível criar Loja', function () {
    $lojaOnline = LojaOnline::factory()->create();

    expect($lojaOnline)->toBeInstanceOf(LojaOnline::class);
    expect($lojaOnline->nome)->not->toBeEmpty();
    expect(['ZZZ', 'XXXX', 'YYYY'])->toContain($lojaOnline->urlLoja);
});

it('possível update Loja', function () {
    $lojaOnline = LojaOnline::factory()->create();

    $newName = 'Updated Name';
    $lojaOnline->nome = $newName;
    $lojaOnline->save();

    $updatedLojaOnline = LojaOnline::find($lojaOnline->id);
    expect($updatedLojaOnline->nome)->toBe($newName);
});

it('possível delet Loja através do controlador Produto', function () {
    // Cria uma loja online
    $lojaOnline = LojaOnline::factory()->create();

    // Cria um produto associado à loja online
    $produto = Produto::factory()->create(['loja_online_id' => $lojaOnline->id]);

    // Simula uma requisição ao controlador de Produto para deletar a loja online associada
    $request = new Request();
    $produtoController = new ProdutoController();
    $produtoController->destroy($request, $produto->id);

    // Verifica se a loja online foi deletada
    $deletedLojaOnline = LojaOnline::find($lojaOnline->id);
    expect($deletedLojaOnline)->toBeNull();
});
