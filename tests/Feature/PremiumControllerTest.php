<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Rota index retorna usuários paginados e responde com 200', function () {
    // Criação de 15 usuários, mais do que o limite de uma página
    User::factory()->count(15)->create();

    // Faz uma requisição GET para a rota index
    $response = $this->get('/usuario-premium');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a view correta foi carregada
    $response->assertViewIs('premium.index');

    // Verifica se os usuários foram passados para a view e estão paginados
    $response->assertViewHas('usuarios', function ($viewUsuarios) {
        return $viewUsuarios->count() === 10; // Verifica se a paginação está correta (10 por página)
    });
});

it('Rota index retorna a segunda página de usuários paginados e responde com 200', function () {
    // Criação de 15 usuários (mais do que o limite de 10 por página)
    User::factory()->count(15)->create();

    // Faz uma requisição para a segunda página
    $response = $this->get('/usuario-premium?page=2');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a view correta foi carregada
    $response->assertViewIs('premium.index');

    // Verifica se os usuários da segunda página foram passados para a view
    $response->assertViewHas('usuarios', function ($viewUsuarios) {
        return $viewUsuarios->count() === 5; // A segunda página deve conter 5 usuários (pois foram criados 15 no total)
    });
});

it('Rota create responde com 200 e carrega a view correta', function () {
    // Faz uma requisição GET para a rota create
    $response = $this->get('/usuario-premium/create');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a view correta foi carregada
    $response->assertViewIs('premium.createPremium');
});

it('Rota store cria um novo usuário e responde com 302', function () {
    // Dados de exemplo para criar um novo usuário
    $usuarioData = [
        'nome' => 'Usuário Teste',
        'email' => 'usuario@teste.com',
        'situacao' => 'ativo',
        'password' => 'senha123', // Envia a senha em texto simples
    ];

    // Envia a requisição POST para criar o usuário
    $response = $this->post('/usuario-premium', array_merge($usuarioData, [
        '_token' => csrf_token(),
    ]));

    // Verifica se o usuário foi inserido no banco de dados
    $this->assertDatabaseHas('users', [
        'name' => 'Usuário Teste',  // O campo é 'name' no banco de dados
        'email' => 'usuario@teste.com',
        'situacao' => 'ativo',
    ]);

    // Verifica o redirecionamento para a rota usuario-premium.index
    $response->assertStatus(302);
    $response->assertRedirect(route('usuario-premium.index'));
});

it('Rota show retorna usuários correspondentes à busca e responde com 200', function () {
    // Criação de 3 usuários no banco de dados
    $usuario1 = User::factory()->create(['name' => 'Usuário Teste 1', 'email' => 'teste1@exemplo.com', 'situacao' => 'ativo']);
    $usuario2 = User::factory()->create(['name' => 'Usuário Teste 2', 'email' => 'teste2@exemplo.com', 'situacao' => 'ativo']);
    $usuario3 = User::factory()->create(['name' => 'Outro Usuário', 'email' => 'outro@exemplo.com', 'situacao' => 'inativo']);

    // Faz uma requisição GET para a rota show com um termo de busca
    $response = $this->get('/usuario-premium/show?search=Usuário');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se os usuários correspondentes à busca estão presentes na view
    $response->assertSee('Usuário Teste 1');
    $response->assertSee('Usuário Teste 2');
});

it('Rota edit responde com 200 e carrega o usuário correto para edição', function () {
    // Criação de um usuário no banco de dados
    $usuario = User::factory()->create([
        'name' => 'Usuário Teste',
        'email' => 'usuario@teste.com',
        'situacao' => 'ativo',
    ]);

    // Faz uma requisição GET para a rota edit
    $response = $this->get("/usuario-premium/{$usuario->id}/edit");

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a view correta foi carregada
    $response->assertViewIs('premium.editPremium');

    // Verifica se o nome e o email do usuário estão presentes na view
    $response->assertSee('Usuário Teste');
    $response->assertSee('usuario@teste.com');
});

it('Rota update atualiza um usuário existente e responde com 302', function () {
    // Criação de um usuário no banco de dados
    $usuario = User::factory()->create([
        'name' => 'Usuário Antigo',
        'email' => 'usuario@antigo.com',
        'situacao' => 'inativo',
        'password' => bcrypt('senhaAntiga123'),
    ]);

    // Dados atualizados
    $updatedData = [
        'name' => 'Usuário Atualizado',
        'email' => 'usuario@atualizado.com',
        'situacao' => 'ativo',
        'password' => 'novaSenha123',  // Nova senha fornecida
    ];

    // Envia a requisição PUT para atualizar o usuário
    $response = $this->put("/usuario-premium/{$usuario->id}", array_merge($updatedData,
    ));

    // Atualiza o modelo do usuário para garantir que os dados sejam carregados corretamente
    $usuario->refresh();

    // Verifica se o usuário foi atualizado no banco de dados
    $this->assertDatabaseHas('users', [
        'id' => $usuario->id,
        'name' => 'Usuário Atualizado',
        'email' => 'usuario@atualizado.com',
        'situacao' => 'ativo',
    ]);

    // Verifica o redirecionamento após a atualização
    $response->assertStatus(302);
    $response->assertRedirect(route('usuario-premium.index'));
});


it('Rota destroy exclui um usuário existente e responde com 302', function () {
    // Criação de um usuário no banco de dados
    $usuario = User::factory()->create([
        'name' => 'Usuário a ser excluído',
        'email' => 'usuario@excluir.com',
        'situacao' => 'ativo',
    ]);

    // Envia a requisição DELETE para excluir o usuário
    $response = $this->delete("/premium/{$usuario->id}", [
        '_token' => csrf_token(),
    ]);

    // Verifica se o usuário foi removido do banco de dados
    $this->assertDatabaseMissing('users', ['id' => $usuario->id]);

    // Verifica o redirecionamento após a exclusão
    $response->assertStatus(302);
    $response->assertRedirect(route('usuario-premium.index'));
});




it('Rota store falha ao criar usuário sem dados obrigatórios e responde com 422', function () {
    // Envia uma requisição POST sem dados para a rota /premium
    $response = $this->postJson('/usuario-premium', [
        '_token' => csrf_token(),
    ]);

    // Verifica se a resposta contém o código 422 (Unprocessable Entity)
    $response->assertStatus(422);

    // Verifica se os erros de validação foram retornados para os campos obrigatórios
    $response->assertJsonValidationErrors(['nome', 'email', 'situacao']);
});

it('Rota edit retorna erro 404 se o usuário não existir', function () {
    // ID de usuário inexistente
    $nonExistentId = 999;

    // Faz uma requisição GET para a rota edit com um ID inexistente
    $response = $this->get("/premium/{$nonExistentId}/edit");

    // Verifica se a resposta é 404 (não encontrado)
    $response->assertStatus(404);
});

it('Rota update retorna erro 404 se o usuário não existir', function () {
    // ID de usuário inexistente
    $nonExistentId = 999;

    // Dados de exemplo para tentar atualizar o usuário inexistente
    $updatedData = [
        'nome' => 'Usuário Atualizado',
        'email' => 'usuario@atualizado.com',
        'situacao' => 'ativo',
        'password' => 'novaSenha123',
    ];

    // Envia a requisição PUT para atualizar um usuário inexistente
    $response = $this->put("/premium/{$nonExistentId}", array_merge($updatedData, [
        '_token' => csrf_token(),
    ]));

    // Verifica se a resposta é 404 (não encontrado)
    $response->assertStatus(404);
});









