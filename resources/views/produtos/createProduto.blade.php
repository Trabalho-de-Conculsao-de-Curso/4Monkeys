<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud das Entidades</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans antialiased dark:bg-black dark:text-gray-200 h-screen flex items-center justify-center">

<div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-lg">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6">
        <a href="/produtos">Home</a>
    </button>

    <form action="/produtos/" method="POST" class="space-y-6">
        <div>
            <label for="nome" class="block text-sm font-medium text-gray-300">Nome</label>
            <input type="text" name="nome" id="nome" class="mt-1 block w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-gray-300" required>
        </div>

        <h3 class="text-lg font-medium text-gray-100">Marca campos</h3>
        <div>
            <label for="marca_nome" class="block text-sm font-medium text-gray-300">Nome</label>
            <input type="text" name="marca_nome" id="marca_nome" class="mt-1 block w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-gray-300" required>
        </div>

        <div>
            <label for="marca_qualidade" class="block text-sm font-medium text-gray-300">Qualidade</label>
            <input type="text" name="marca_qualidade" id="marca_qualidade" class="mt-1 block w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-gray-300" required>
        </div>

        <div>
            <label for="marca_garantia" class="block text-sm font-medium text-gray-300">Garantia</label>
            <input type="text" name="marca_garantia" id="marca_garantia" class="mt-1 block w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-gray-300" required>
        </div>

        <h3 class="text-lg font-medium text-gray-100">Especificações campos</h3>
        <div>
            <label for="especificacoes_detalhes" class="block text-sm font-medium text-gray-300">Detalhes</label>
            <input type="text" name="especificacoes_detalhes" id="especificacoes_detalhes" class="mt-1 block w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-gray-300" required>
        </div>

        <h3 class="text-lg font-medium text-gray-100">Preço campos</h3>
        <div>
            <label for="preco" class="block text-sm font-medium text-gray-300">Preço</label>
            <input type="number" name="preco" id="preco" class="mt-1 block w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-gray-300" required>
        </div>

        <h3 class="text-lg font-medium text-gray-100">Lojas Online campos</h3>
        <div>
            <label for="nomeLojaOnline" class="block text-sm font-medium text-gray-300">Lojas Online</label>
            <input type="text" name="nomeLojaOnline" id="nomeLojaOnline" class="mt-1 block w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-gray-300" required>
        </div>

        <div>
            <label for="urlLojaOnline" class="block text-sm font-medium text-gray-300">URL Da Loja</label>
            <input type="text" name="urlLojaOnline" id="urlLojaOnline" class="mt-1 block w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-gray-300" required>
        </div>

        <div>
            <input type="submit" value="Enviar" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded cursor-pointer w-full">
        </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>

</body>
</html>
