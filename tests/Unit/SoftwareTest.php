<?php

use App\Models\Software;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('possível create a Software', function () {
    $software = Software::factory()->create();

    expect($software)->toBeInstanceOf(Software::class);
    expect($software->nome)->not->toBeEmpty();
    expect(['XXX', 'YYY', 'ZZZ'])->toContain($software->descricao);
});

it('possível update a Software', function () {
    $software = Software::factory()->create();

    $newNome = 'Updated Name';
    $software->nome = $newNome;
    $software->save();

    $updatedSoftware = Software::find($software->id);
    expect($updatedSoftware->nome)->toBe($newNome);
});

it('possível delete a Software', function () {
    $software = Software::factory()->create();

    $software->delete();

    $deletedSoftware = Software::find($software->id);
    expect($deletedSoftware)->toBeNull();
});