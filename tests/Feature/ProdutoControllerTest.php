<?php

use App\Models\Produto;


use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Rota index responde com 200', function () {
    $response = $this->get('/produtos');
    $response->assertStatus(200);
});

it('Rota show responde com 200', function () {
    $produto = Produto::factory()->create();
    $response = $this->get("/produtos/{$produto->id}");
    $response->assertStatus(200);
});

it('Rota create responde com 200', function () {
    $response = $this->get('/produtos/create');
    $response->assertStatus(200);
});

it('Rota delete responde com 200', function () {
    $produto = Produto::factory()->create();

    $response = $this->delete("/produtos/{$produto->id}");

    $response->assertStatus(302);
    $this->assertDatabaseMissing('produtos', ['id' => $produto->id]);
});
