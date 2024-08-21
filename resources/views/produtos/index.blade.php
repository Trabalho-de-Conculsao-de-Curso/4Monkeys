<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Crud das Entidades</title>
</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4 text-center">Lista de Produtos</h1>

        <div class="mb-4 flex justify-between items-center">
            <form action="{{ url('/produtos/search') }}" method="GET" class="flex items-center">
                <input type="text" name="search" placeholder="Procurar produto" class="border-2 border-gray-300 rounded-l-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">Procurar</button>
            </form>

            <a href="/produtos/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">Cadastrar Novo Produto</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-solid text-sm dark:bg-gray-800 dark:border-gray-700">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="py-2 px-3 text-left">ID</th>
                        <th class="py-2 px-3 text-left">Nome</th>
                        <th class="py-2 px-3 text-left">Marca</th>
                        <th class="py-2 px-3 text-left">Especificação</th>
                        <th class="py-2 px-3 text-left">Valor</th>
                        <th class="py-2 px-3 text-left">Moeda</th>
                        <th class="py-2 px-3 text-left">Lojas Online</th>
                        <th class="py-2 px-3 text-left">URL Loja Online</th>
                        <th class="py-2 px-3 text-left">Criado Em</th>
                        <th class="py-2 px-3 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @foreach($produtos as $produto)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="py-2 px-3">{{ $produto->id }}</td>
                        <td class="py-2 px-3">{{ $produto->nome }}</td>
                        <td class="py-2 px-3">{{ $produto->marca->nome }}</td>
                        <td class="py-2 px-3">{{ $produto->especificacoes->detalhes }}</td>
                        <td class="py-2 px-3">{{ $produto->preco->valor }}</td>
                        <td class="py-2 px-3">{{ $produto->preco->moeda }}</td>
                        <td class="py-2 px-3">{{ $produto->lojaOnline->nome }}</td>
                        <td class="py-2 px-3 max-w-xs truncate">
                            <span class="tooltiptext">{{ $produto->lojaOnline->urlLoja }}</span>
                            {{ $produto->lojaOnline->urlLoja }}
                        </td>
                        <td class="py-2 px-3">{{ $produto->created_at }}</td>
                        <td class="py-2 px-3 flex flex-col">
                            <a href="{{ url("produtos/$produto->id/edit") }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">Editar</a>

                            <form method="POST" action="{{ url("produtos/$produto->id") }}" class="flex flex-col items-center">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" onclick="return confirm('Tem certeza que deseja excluir {{ $produto->nome }}?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $produtos->links() }}
        </div>
    </div>
</body>

</html>