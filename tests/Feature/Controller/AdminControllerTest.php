<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Rota index retorna a lista de administradores e responde com 200', function () {
    // Cria e autentica um administrador
    $admin = Admin::factory()->create();
    $this->actingAs($admin, 'admin'); // Autentica o administrador no guard 'admin'

    // Cria 15 administradores adicionais (mais do que o limite de 10 por página)
    Admin::factory()->count(15)->create();

    // Envia uma requisição GET para a rota de index de administradores
    $response = $this->get('/create-admin');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a view correta foi carregada
    $response->assertViewIs('auth.admin.index');

    // Verifica se os administradores paginados estão presentes na view
    $response->assertViewHas('admins', function ($viewAdmins) {
        return $viewAdmins->count() === 10; // Verifica se a paginação está correta (10 por página)
    });

    // Verifica se os primeiros 10 administradores aparecem na view
    $adminsPaginados = Admin::paginate(10);
    foreach ($adminsPaginados as $admin) {
        $response->assertSee($admin->name); // Verifica se o nome dos admins aparece na view
    }
});

it('Rota create carrega o formulário de criação de administradores e responde com 200', function () {
    // Cria e autentica um administrador
    $admin = Admin::factory()->create();
    $this->actingAs($admin, 'admin'); // Autentica o administrador no guard 'admin'

    // Envia uma requisição GET para a rota de criação de administradores
    $response = $this->get('/create-admin/create');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a view correta foi carregada
    $response->assertViewIs('auth.admin.createAdmin');

    // Verifica a presença dos elementos do formulário na view
    $response->assertSee('<form', false);
    $response->assertSee('name="name"', false);
    $response->assertSee('name="email"', false);
    $response->assertSee('name="password"', false);
});

it('Rota store cria um novo administrador e responde com 302', function () {
    // Autentica um administrador para acessar a rota
    $admin = Admin::factory()->create();
    $this->actingAs($admin, 'admin'); // Autentica o administrador no guard 'admin'

    // Fake de eventos para capturar o evento Registered
    Event::fake();

    // Simula os dados do novo administrador
    $adminData = [
        'name' => 'Admin Teste',
        'email' => 'adminteste@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123', // Confirmação da senha
    ];

    // Envia uma requisição POST para a rota de criação de administradores
    $response = $this->post('/create-admin', $adminData);

    // Verifica se o administrador foi criado no banco de dados
    $this->assertDatabaseHas('admins', [
        'name' => 'Admin Teste',
        'email' => 'adminteste@example.com',
    ]);

    // Verifica se o evento Registered foi disparado
    Event::assertDispatched(Registered::class);

    // Verifica se o redirecionamento para /create-admin foi feito corretamente
    $response->assertStatus(302);
    $response->assertRedirect('/create-admin');
});

it('Rota store falha ao criar um administrador com dados inválidos', function () {
    // Simula os dados inválidos do administrador
    $adminData = [
        'name' => '', // Nome vazio
        'email' => 'invalid-email', // Email inválido
        'password' => 'short', // Senha muito curta
        'password_confirmation' => 'short', // Confirmação da senha
    ];

    // Envia uma requisição POST com os dados inválidos
    $response = $this->postJson('/create-admin', array_merge($adminData, [
        '_token' => csrf_token(),
    ]));

    $response->assertStatus(302);

    // Verifica se os erros de validação estão presentes
    $response->assertValid(['name', 'email', 'password']);
});

it('Rota store falha ao criar um administrador com um e-mail já existente', function () {
    // Cria um administrador existente
    Admin::factory()->create(['email' => 'adminteste@example.com']);

    // Simula os dados de um novo administrador com o mesmo e-mail
    $adminData = [
        'name' => 'Admin Teste',
        'email' => 'adminteste@example.com', // E-mail já existente
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    // Envia uma requisição POST com o e-mail duplicado
    $response = $this->postJson('/create-admin', array_merge($adminData, [
        '_token' => csrf_token(),
    ]));

    $response->assertStatus(302);

    // Verifica se o erro de validação para o campo 'email' está presente
    $response->assertValid(['email']);
});

it('Rota show retorna administradores correspondentes ao critério de busca', function () {
    // Autentica um administrador para acessar a rota
    $admin = Admin::factory()->create();
    $this->actingAs($admin, 'admin'); // Autentica o administrador no guard 'admin'

    // Criação de alguns administradores para teste
    $admin1 = Admin::factory()->create(['name' => 'Admin Teste', 'email' => 'admin1@example.com']);
    $admin2 = Admin::factory()->create(['name' => 'Admin Demo', 'email' => 'admin2@example.com']);

    // Envia uma requisição GET para a rota de busca com o critério 'Admin Teste'
    $response = $this->get('/create-admin/search?search=Admin Teste');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a view correta foi carregada
    $response->assertViewIs('auth.admin.searchAdmin');

    // Verifica se o administrador correspondente à busca está presente na view
    $response->assertSee('Admin Teste');
    $response->assertSee('admin1@example.com');

    // Verifica se o administrador que não corresponde à busca NÃO está presente na view
    $response->assertDontSee('Admin Demo');
    $response->assertDontSee('admin2@example.com');
});

it('Rota show retorna nenhum administrador quando a busca não encontra correspondências', function () {
    // Autentica um administrador para acessar a rota
    $admin = Admin::factory()->create();
    $this->actingAs($admin, 'admin'); // Autentica o administrador no guard 'admin'

    // Criação de alguns administradores para teste
    Admin::factory()->create(['name' => 'Admin Teste', 'email' => 'admin1@example.com']);
    Admin::factory()->create(['name' => 'Admin Demo', 'email' => 'admin2@example.com']);

    // Envia uma requisição GET para a rota de busca com um critério que não corresponde a nenhum administrador
    $response = $this->get('/create-admin/search?search=Inexistente');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a view correta foi carregada
    $response->assertViewIs('auth.admin.searchAdmin');

    // Verifica se a view NÃO contém os administradores criados (nenhuma correspondência)
    $response->assertDontSee('Admin Teste');
    $response->assertDontSee('admin1@example.com');
    $response->assertDontSee('Admin Demo');
    $response->assertDontSee('admin2@example.com');
});

it('Rota show retorna resultados paginados de administradores', function () {
    // Autentica um administrador para acessar a rota
    $admin = Admin::factory()->create();
    $this->actingAs($admin, 'admin'); // Autentica o administrador no guard 'admin'

    // Criação de 15 administradores (mais do que o limite de 10 por página)
    Admin::factory()->count(15)->create();

    // Envia uma requisição GET para a rota de busca com paginação, na segunda página
    $response = $this->get('/create-admin/search?search=Admin&page=2');

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a view correta foi carregada
    $response->assertViewIs('auth.admin.searchAdmin');
});

it('Rota edit carrega a página de edição de administradores', function () {
    // Criação e autenticação de um administrador para acessar a rota
    $authenticatedAdmin = Admin::factory()->create();
    $this->actingAs($authenticatedAdmin, 'admin'); // Autentica o administrador no guard 'admin'

    // Criação de um administrador para edição
    $admin = Admin::factory()->create();

    // Envia uma requisição GET para a rota de edição do administrador
    $response = $this->get("/create-admin/{$admin->id}/edit");

    // Verifica se a resposta está correta (200 OK)
    $response->assertStatus(200);

    // Verifica se a view correta foi carregada
    $response->assertViewIs('auth.admin.editAdmin');

    // Verifica se o administrador aparece na view com os dados corretos
    $response->assertViewHas('admin', function ($viewAdmin) use ($admin) {
        return $viewAdmin->id === $admin->id;
    });
    $response->assertSee($admin->name);
    $response->assertSee($admin->email);
});

it('Rota update atualiza um administrador com sucesso e responde com 302', function () {
    // Criação e autenticação de um administrador para acessar a rota
    $authenticatedAdmin = Admin::factory()->create();
    $this->actingAs($authenticatedAdmin, 'admin'); // Autentica o administrador no guard 'admin'

    // Criação de um administrador para atualização
    $admin = Admin::factory()->create([
        'name' => 'Admin Antigo',
        'email' => 'adminantigo@example.com',
        'password' => Hash::make('oldpassword123'),
    ]);

    // Dados atualizados para o administrador
    $updatedData = [
        'name' => 'Admin Atualizado',
        'email' => 'adminatualizado@example.com',
        'password' => 'newpassword123',
    ];

    // Envia a requisição PUT para a rota de atualização do administrador
    $response = $this->put("/create-admin/{$admin->id}", $updatedData);

    // Verifica se o administrador foi atualizado no banco de dados
    $this->assertDatabaseHas('admins', [
        'id' => $admin->id,
        'name' => 'Admin Atualizado',
        'email' => 'adminatualizado@example.com',
    ]);

    // Verifica se a senha foi atualizada corretamente (hash)
    $this->assertTrue(Hash::check('newpassword123', Admin::find($admin->id)->password));

    // Verifica se o redirecionamento para a rota correta foi feito
    $response->assertStatus(302);
    $response->assertRedirect(route('create-admin.index'));

    // Verifica se a sessão contém a mensagem de sucesso
    $response->assertSessionHas('success', 'Admin atualizado com sucesso!');
});

it('Rota update falha ao atualizar com dados inválidos e retorna erros de validação', function () {
    // Criação e autenticação de um administrador para acessar a rota
    $authenticatedAdmin = Admin::factory()->create();
    $this->actingAs($authenticatedAdmin, 'admin'); // Autentica o administrador no guard 'admin'

    // Criação de um administrador para atualização
    $admin = Admin::factory()->create([
        'name' => 'Admin Antigo',
        'email' => 'adminantigo@example.com',
    ]);

    // Dados inválidos para a atualização
    $invalidData = [
        'name' => '',               // Nome vazio
        'email' => 'invalid-email',  // Email inválido
        'password' => 'short',       // Senha muito curta
    ];

    // Envia a requisição PUT para a rota de atualização do administrador
    $response = $this->from("/create-admin/{$admin->id}/edit") // Define a URL de origem para redirecionamento
    ->put("/create-admin/{$admin->id}", $invalidData);

    // Verifica se o redirecionamento para a página de edição ocorreu
    $response->assertStatus(302);
    $response->assertRedirect("/create-admin/{$admin->id}/edit");

    // Verifica se os erros de validação estão presentes na sessão
    $response->assertSessionHasErrors(['name', 'email', 'password']);
});

it('Rota update falha ao atualizar com um e-mail já existente e responde com 302 e erro de validação', function () {
    // Criação e autenticação de um administrador para acessar a rota
    $authenticatedAdmin = Admin::factory()->create();
    $this->actingAs($authenticatedAdmin, 'admin'); // Autentica o administrador no guard 'admin'

    // Criação de dois administradores com emails distintos
    $admin1 = Admin::factory()->create(['email' => 'admin1@example.com']);
    $admin2 = Admin::factory()->create(['email' => 'admin2@example.com']);

    // Dados atualizados para admin2 usando o e-mail de admin1
    $updatedData = [
        'name' => 'Admin Atualizado',
        'email' => 'admin1@example.com', // E-mail já em uso por admin1
        'password' => 'newpassword123',
    ];

    // Envia a requisição PUT para a rota de atualização de admin2
    $response = $this->from("/create-admin/{$admin2->id}/edit") // Define de onde veio a requisição
    ->put("/create-admin/{$admin2->id}", $updatedData);

    // Verifica se o redirecionamento ocorreu (código 302) de volta para a página de edição
    $response->assertStatus(302);
    $response->assertRedirect("/create-admin/{$admin2->id}/edit");

    // Verifica se há erro de validação na sessão relacionado ao campo 'email'
    $response->assertSessionHasErrors(['email']);
});

it('Rota destroy exclui um administrador com sucesso e responde com 302', function () {
    // Criação e autenticação de um administrador para acessar a rota
    $authenticatedAdmin = Admin::factory()->create();
    $this->actingAs($authenticatedAdmin, 'admin'); // Autentica o administrador no guard 'admin'

    // Criação de um administrador para exclusão
    $admin = Admin::factory()->create();

    // Envia a requisição DELETE para a rota de exclusão do administrador, incluindo o token
    $response = $this->delete("/create-admin/{$admin->id}", [
        '_token' => csrf_token(),
    ]);

    // Verifica se o administrador foi removido do banco de dados
    $this->assertDatabaseMissing('admins', ['id' => $admin->id]);

    // Verifica o redirecionamento para a rota correta
    $response->assertStatus(302);
    $response->assertRedirect(route('create-admin.index'));

    // Verifica se a sessão contém a mensagem de sucesso
    $response->assertSessionHas('success', 'Admin apagado com sucesso!');
});

it('Rota destroy retorna 302 se o administrador não for encontrado', function () {
    // ID de administrador inexistente
    $nonExistentId = 999;

    // Envia a requisição DELETE para a rota de exclusão com um ID inexistente
    $response = $this->delete("/create-admin/{$nonExistentId}", [
        '_token' => csrf_token(),
    ]);

    $response->assertStatus(302);
});

it('Rota destroy redireciona corretamente quando o _token não está presente', function () {
    // Criação e autenticação de um administrador para acessar a rota
    $authenticatedAdmin = Admin::factory()->create();
    $this->actingAs($authenticatedAdmin, 'admin'); // Autentica o administrador no guard 'admin'

    // Criação de um administrador para teste de exclusão
    $admin = Admin::factory()->create();

    // Envia a requisição DELETE sem o _token
    $response = $this->delete("/create-admin/{$admin->id}");

    // Verifica se o redirecionamento ocorreu para a rota correta (sem exclusão)
    $response->assertStatus(302);
    $response->assertRedirect(route('create-admin.index'));

    // Verifica se o administrador ainda está no banco de dados (não foi excluído)
    $this->assertDatabaseHas('admins', ['id' => $admin->id]);
});

