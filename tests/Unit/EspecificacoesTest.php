<?php

// tests/Unit/EspecificacoesTest.php

use App\Models\Especificacoes;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('possivel create Especificacoes', function () {
    $especificacoes = Especificacoes::factory()->create();

    expect($especificacoes)->toBeInstanceOf(Especificacoes::class);
    expect(['XXXX', 'YYYY', 'ZZZZ'])->toContain($especificacoes->detalhes);
});

it('possivel update Especificacoes', function () {
    $especificacoes = Especificacoes::factory()->create();

    $newDetalhes = 'Updated Details';
    $especificacoes->detalhes = $newDetalhes;
    $especificacoes->save();

    $updatedEspecificacoes = Especificacoes::find($especificacoes->id);
    expect($updatedEspecificacoes->detalhes)->toBe($newDetalhes);
});

it('possivel delete Especificacoes', function () {
    $especificacoes = Especificacoes::factory()->create();

    $especificacoes->delete();

    $deletedEspecificacoes = Especificacoes::find($especificacoes->id);
    expect($deletedEspecificacoes)->toBeNull();
});
