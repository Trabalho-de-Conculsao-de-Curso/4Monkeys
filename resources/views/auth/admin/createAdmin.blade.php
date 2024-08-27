<!-- resources/views/auth/register.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Laravel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-zinc-900 text-gray-200">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6 text-center">Cadastro</h1>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium">Nome</label>
                    <input type="text" id="name" name="name" required class="mt-1 block w-full p-2 bg-zinc-700 text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" id="email" name="email" required class="mt-1 block w-full p-2 bg-zinc-700 text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium">Senha</label>
                    <input type="password" id="password" name="password" required class="mt-1 block w-full p-2 bg-zinc-700 text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium">Confirme a Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="mt-1 block w-full p-2 bg-zinc-700 text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>

                <button type="submit" class="w-full bg-zinc-600 text-white font-bold py-2 px-4 rounded-md hover:bg-zinc-500 transition">
                    Registrar
                </button>
            </form>
        </div>
    </div>
</body>
</html>
