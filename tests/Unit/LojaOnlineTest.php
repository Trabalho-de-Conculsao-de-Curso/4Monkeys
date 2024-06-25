<?php

// tests/Unit/LojaOnlineTest.php

use App\Models\LojaOnline;
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

it('possível delet Loja', function () {
    $lojaOnline = LojaOnline::factory()->create();

    $lojaOnline->delete();

    $deletedLojaOnline = LojaOnline::find($lojaOnline->id);
    expect($deletedLojaOnline)->toBeNull();
});
