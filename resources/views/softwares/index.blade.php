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
<h1 class="text-2xl font-bold mb-4 text-center">Lista de Softwares</h1>
    <div class="mb-4 flex justify-between items-center">
        <a href="/softwares/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">Cadastrar Novo Software</a>
    </div>
    <div class="mb-4 flex justify-between items-center">
        <form action="{{ url('softwares/search') }}" method="GET" class="flex items-center">
            <input type="text" name="search" placeholder="Procurar Software" class="border rounded-1 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">Search</button>
        </form>
    </div>
    <table border="1">
        <thead class="bg-gray-200">
        <th class="py-2 px-3">ID</th>
        <th class="py-2 px-3">Tipo</th>
        <th class="py-2 px-3">Nome</th>
        <th class="py-2 px-3">Descrição</th>

        <th class="py-2 px-3">Imagem</th>
        <th class="py-2 px-3">Criado Em</th>
        <th class="py-2 px-3">Ações</th>
        </thead>
        <tbod>
            @foreach($softwares as $software)
                <tr>
                    <td class="py-2 px-3">{{$software->id}}</td>
                    <td class="py-2 px-3">{{$software->tipo}}</td>
                    <td class="py-2 px-3">{{$software->nome}}</td>
                    <td class="py-2 px-3">{{$software->descricao}}</td>

                    <td class="py-2 px-3">
                        @if($software->imagem)
                            <img src="{{ asset('storage/' . $software->imagem) }}" alt="Imagem do Software" width="100">
                        @else
                            <span>Sem imagem</span>
                        @endif
                    </td>
                    <td class="py-2 px-3">{{$software->created_at}}</td>
                    <td>
                        <a href="{{url("softwares/$software->id/edit")}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">Editar</a>
                        <br>
                        <form method="POST" action="{{url("softwares/$software->id")}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white mt-2
                font-bold py-2 px-3.5 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                    onclick="return confirm('Tem certeza que deseja excluir? {{$software->nome}} ?')"> Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </tbod>
    </table>
</div>


</body>
</html>
