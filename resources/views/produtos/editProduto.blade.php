<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Editar Produto</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50 bg-gray-200">

<div class="container mx-auto p-4">
    <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        <a href="/produtos">Voltar</a>
    </button>

    <form action="{{ url("/produtos/$produto->id") }}" method="POST" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        @method("PUT")
        <h1 class="text-xl font-bold mb-4 text-center">Editar Produto</h1>

        <div class="mb-4">
            <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" name="nome" id="nome" value="{{$produto->nome}}" required class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 border-2">
        </div>


        <h3 class="text-lg font-semibold mb-2">Preço</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="preco_valor" class="block text-sm font-medium text-gray-700">Valor</label>
                <input type="text" name="preco_valor" id="preco_valor" value="{{$produto->lojaOnline->valor}}" required class="border-2 form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>
            <div>
                <label for="preco_moeda" class="block text-sm font-medium text-gray-700">Moeda</label>
                <input type="text" name="preco_moeda" id="preco_moeda" value="{{$produto->lojaOnline->moeda}}" required class="border-2 form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>
        </div>

        <div class="mb-6">
            <label for="urlLojaOnline" class="block text-sm font-medium text-gray-700">URL da Loja Online</label>
            <input type="text" name="urlLojaOnline" id="urlLojaOnline" value="{{$produto->lojaOnline->urlLoja}}" required class="border-2 form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
        </div>

        <div class="flex justify-center">
            <input type="submit" value="Editar" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
    </form>
</div>

</body>
</html>
