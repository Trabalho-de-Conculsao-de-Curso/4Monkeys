<?php

use App\Models\LojaOnline;
use App\Models\Produto;


use App\Models\Software;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


uses(RefreshDatabase::class);


it('Rota create responde com 200', function () {
    // Envia uma requisição GET para a rota create
    $response = $this->get('/softwares/create');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);
});




