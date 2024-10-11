<?php

use App\Models\LojaOnline;
use App\Models\Produto;


use App\Models\RequisitoSoftware;
use App\Models\Software;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


uses(RefreshDatabase::class);



it('Rota index retorna softwares com requisitos e responde com 200', function () {
    // Cria 3 softwares com requisitos associados
    $softwares = Software::factory()->withRequisitos()->count(3)->create();

    // Faz uma requisição GET para a rota index
    $response = $this->get('/softwares');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a view correta foi carregada
    $response->assertViewIs('softwares.index');

    // Verifica se os softwares e os requisitos estão presentes na view
    $response->assertViewHas('softwares', function ($viewSoftwares) use ($softwares) {
        return $viewSoftwares->count() === $softwares->count() &&
            $viewSoftwares->first()->requisitos->isNotEmpty();
    });
});

it('Rota index retorna a segunda página de softwares com paginação', function () {
    // Criação de 20 softwares (mais do que o limite de uma página)
    Software::factory()->withRequisitos()->count(20)->create();

    // Faz uma requisição para a segunda página
    $response = $this->get('/softwares?page=2');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a segunda página de softwares foi carregada corretamente
    $softwaresPaginados = Software::with('requisitos')->paginate(10, ['*'], 'page', 2);
    foreach ($softwaresPaginados->items() as $software) {
        $response->assertSee($software->nome);
    }
});

it('Rota create responde com 200', function () {
    // Envia uma requisição GET para a rota create
    $response = $this->get('/softwares/create');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);
});

it('Rota store cria um software com imagem e requisitos e responde com 302', function () {
    // Simulação de upload de um arquivo genérico em vez de imagem (sem usar GD)
    Storage::fake('public');
    $imagem = UploadedFile::fake()->create('software.jpg', 100); // Cria um arquivo de 100 KB sem precisar da GD

    // Dados de criação do software e requisitos
    $softwareData = [
        'tipo' => 'Utilitário',
        'nome' => 'Software Teste',
        'descricao' => 'Descrição do software de teste',
        'peso' => '500MB', // Campo peso
        'software_imagem' => $imagem,
        // Requisitos mínimos
        'cpu_min' => 'Intel i3',
        'gpu_min' => 'GTX 1050',
        'ram_min' => '8GB',
        'placa_mae_min' => 'Asus B350',
        'ssd_min' => '256GB',
        'cooler_min' => 'Cooler X',
        'fonte_min' => '500W',
        // Requisitos médios
        'cpu_med' => 'Intel i5',
        'gpu_med' => 'GTX 1060',
        'ram_med' => '16GB',
        'placa_mae_med' => 'Asus B450',
        'ssd_med' => '512GB',
        'cooler_med' => 'Cooler Y',
        'fonte_med' => '600W',
        // Requisitos recomendados
        'cpu_rec' => 'Intel i7',
        'gpu_rec' => 'GTX 1070',
        'ram_rec' => '32GB',
        'placa_mae_rec' => 'Asus B550',
        'ssd_rec' => '1TB',
        'cooler_rec' => 'Cooler Z',
        'fonte_rec' => '750W',
    ];

    // Envia a requisição POST para criar o software
    $response = $this->post('/softwares', array_merge($softwareData, [
        '_token' => csrf_token(),
    ]));

    // Verifica se o software foi inserido no banco de dados
    $this->assertDatabaseHas('softwares', ['nome' => 'Software Teste']);

    // Verifica se os requisitos mínimos foram inseridos no banco de dados
    $this->assertDatabaseHas('requisitos_software', ['cpu' => 'Intel i3']);

    // Verifica se os requisitos médios foram inseridos no banco de dados
    $this->assertDatabaseHas('requisitos_software', ['cpu' => 'Intel i5']);

    // Verifica se os requisitos recomendados foram inseridos no banco de dados
    $this->assertDatabaseHas('requisitos_software', ['cpu' => 'Intel i7']);

    // Verifica se o arquivo foi armazenado
    Storage::disk('public')->assertExists('images/' . $imagem->hashName());

    // Verifica o redirecionamento
    $response->assertStatus(302);
});

it('Rota show realiza busca e retorna softwares correspondentes', function () {
    // Criar softwares para busca
    $software1 = Software::factory()->create(['nome' => 'Software A']);
    $software2 = Software::factory()->create(['nome' => 'Software B']);

    // Envia uma requisição GET para a rota show com parâmetro de busca
    $response = $this->get('/softwares?search=Software A');

    // Verifica se o software correspondente aparece nos resultados
    $response->assertSee('Software A');
    $response->assertStatus(200);
});

it('Rota edit responde com 200 e retorna software e seus requisitos', function () {
    // Criação de um software
    $software = Software::factory()->create();

    // Criação de requisitos associados
    RequisitoSoftware::factory()->create([
        'software_id' => $software->id,
        'requisito_nivel' => 'Minimo',
        'cpu' => 'Intel i3',
        'gpu' => 'GTX 1050',
        'ram' => '8GB',
    ]);

    RequisitoSoftware::factory()->create([
        'software_id' => $software->id,
        'requisito_nivel' => 'Medio',
        'cpu' => 'Intel i5',
        'gpu' => 'GTX 1060',
        'ram' => '16GB',
    ]);

    RequisitoSoftware::factory()->create([
        'software_id' => $software->id,
        'requisito_nivel' => 'Recomendado',
        'cpu' => 'Intel i7',
        'gpu' => 'GTX 1070',
        'ram' => '32GB',
    ]);

    // Faz uma requisição GET para a rota edit
    $response = $this->get("/softwares/{$software->id}/edit");

    // Verifica se o nome do software está presente na view
    $response->assertSee($software->nome);

    // Verifica se os requisitos mínimos estão presentes na view
    $response->assertSee('Intel i3');
    $response->assertSee('GTX 1050');
    $response->assertSee('8GB');

    // Verifica se os requisitos médios estão presentes na view
    $response->assertSee('Intel i5');
    $response->assertSee('GTX 1060');
    $response->assertSee('16GB');

    // Verifica se os requisitos recomendados estão presentes na view
    $response->assertSee('Intel i7');
    $response->assertSee('GTX 1070');
    $response->assertSee('32GB');

    // Verifica o status da resposta
    $response->assertStatus(200);
});


it('Rota update atualiza software e seus requisitos e responde com 302', function () {
    // Criação de um software com requisitos associados
    $software = Software::factory()->withRequisitos()->create();

    // Simulação de upload de um arquivo genérico em vez de uma imagem
    Storage::fake('public');
    $novaImagem = UploadedFile::fake()->create('novo_software.jpg', 100); // Cria um arquivo genérico de 100 KB

    // Dados atualizados do software e requisitos
    $updatedData = [
        'tipo' => 'Jogo',
        'nome' => 'Software Atualizado',
        'descricao' => 'Descrição atualizada',
        'peso' => '1.5',  // Adicionando o campo "peso"
        'software_imagem' => $novaImagem,
        // Atualizando os requisitos mínimos
        'cpu_min' => 'Intel i5',
        'gpu_min' => 'GTX 1060',
        'ram_min' => '16GB',
        'placa_mae_min' => 'Asus B450',
        'ssd_min' => '512GB',
        'cooler_min' => 'Cooler Y',
        'fonte_min' => '600W',
        // Atualizando os requisitos médios
        'cpu_med' => 'Intel i7',
        'gpu_med' => 'GTX 1070',
        'ram_med' => '32GB',
        'placa_mae_med' => 'Asus B550',
        'ssd_med' => '1TB',
        'cooler_med' => 'Cooler Z',
        'fonte_med' => '750W',
        // Atualizando os requisitos recomendados
        'cpu_rec' => 'Intel i9',
        'gpu_rec' => 'GTX 1080',
        'ram_rec' => '64GB',
        'placa_mae_rec' => 'Asus B570',
        'ssd_rec' => '2TB',
        'cooler_rec' => 'Cooler X',
        'fonte_rec' => '850W',
    ];

    // Envia a requisição PUT para atualizar o software
    $response = $this->put("/softwares/{$software->id}", array_merge($updatedData, [
        '_token' => csrf_token(),
    ]));

    // Verifica se o software foi atualizado no banco de dados
    $this->assertDatabaseHas('softwares', [
        'id' => $software->id,
        'nome' => 'Software Atualizado',
        'peso' => '1.5',  // Verifica também o campo "peso"
    ]);

    // Verifica se os requisitos mínimos foram atualizados no banco de dados
    $this->assertDatabaseHas('requisitos_software', [
        'software_id' => $software->id,
        'requisito_nivel' => 'Minimo',
        'cpu' => 'Intel i5',
        'gpu' => 'GTX 1060',
        'ram' => '16GB',
        'placa_mae' => 'Asus B450',
        'ssd' => '512GB',
        'cooler' => 'Cooler Y',
        'fonte' => '600W'
    ]);

    // Verifica se os requisitos médios foram atualizados no banco de dados
    $this->assertDatabaseHas('requisitos_software', [
        'software_id' => $software->id,
        'requisito_nivel' => 'Medio',
        'cpu' => 'Intel i7',
        'gpu' => 'GTX 1070',
        'ram' => '32GB',
        'placa_mae' => 'Asus B550',
        'ssd' => '1TB',
        'cooler' => 'Cooler Z',
        'fonte' => '750W'
    ]);

    // Verifica se os requisitos recomendados foram atualizados no banco de dados
    $this->assertDatabaseHas('requisitos_software', [
        'software_id' => $software->id,
        'requisito_nivel' => 'Recomendado',
        'cpu' => 'Intel i9',
        'gpu' => 'GTX 1080',
        'ram' => '64GB',
        'placa_mae' => 'Asus B570',
        'ssd' => '2TB',
        'cooler' => 'Cooler X',
        'fonte' => '850W'
    ]);

    // Verifica se o arquivo (genérico) foi armazenado
    Storage::disk('public')->assertExists('images/' . $novaImagem->hashName());

    // Verifica o redirecionamento
    $response->assertStatus(302);
});

it('Rota destroy exclui um software e seus requisitos e responde com 302', function () {
    // Criação de um software com requisitos associados
    $software = Software::factory()->withRequisitos()->create();

    // Envia a requisição DELETE para remover o software
    $response = $this->delete("/softwares/{$software->id}", [
        '_token' => csrf_token(),
    ]);

    // Verifica se o software foi removido do banco de dados
    $this->assertDatabaseMissing('softwares', ['id' => $software->id]);

    // Verifica se os requisitos associados foram removidos do banco de dados
    $this->assertDatabaseMissing('requisitos_software', ['software_id' => $software->id]);

    // Verifica o redirecionamento
    $response->assertStatus(302);
});


it('Rota store falha ao criar software sem dados obrigatórios e responde com 422', function () {
    // Tenta criar um software sem passar dados obrigatórios
    $response = $this->postJson('/softwares', []);

    // Verifica se a resposta contém o código 422 (Unprocessable Entity)
    $response->assertStatus(422);

    // Verifica se a sessão contém erros de validação para campos obrigatórios
    $response->assertJsonValidationErrors(['nome', 'software_imagem', 'cpu_min', 'ram_min', 'gpu_min']);
});

it('Rota update falha ao atualizar software sem dados obrigatórios e responde com 422', function () {
    // Cria um software com dados válidos
    $software = Software::factory()->withRequisitos()->create();

    // Tenta atualizar o software sem passar dados obrigatórios
    $response = $this->putJson("/softwares/{$software->id}", [
    ]);

    // Verifica se a resposta contém o código 422
    $response->assertStatus(422);

    // Verifica se a sessão contém erros de validação para campos obrigatórios
    $response->assertJsonValidationErrors(['nome', 'cpu_min', 'ram_min', 'gpu_min']);
});

it('Rota store falha ao criar software com imagem inválida e responde com 422', function () {
    // Simula o upload de um arquivo inválido (não imagem)
    Storage::fake('public');
    $arquivoInvalido = UploadedFile::fake()->create('documento.pdf', 100); // Cria um arquivo PDF

    // Tenta criar um software com um arquivo inválido
    $response = $this->postJson('/softwares', [
        'nome' => 'Software Teste',
        'software_imagem' => $arquivoInvalido,
        '_token' => csrf_token(),
    ]);

    // Verifica se a resposta contém o código 422 (Unprocessable Entity)
    $response->assertStatus(422);

    // Verifica se há um erro de validação para o campo software_imagem
    $response->assertJsonValidationErrors(['software_imagem']);
});

