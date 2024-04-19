<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud das Entidades</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <h1>Lista de Marcas</h1>

    <div>
        <div>
            <a href="/marcas/create">Cadastrar Nova Marca</a>
        </div>
        <div>
            <form action="{{ url('marcas/search') }}" method="GET">
                <input type="text" name="search" placeholder="Procurar usuário">
                <button type="submit">Search</button>
            </form>
        </div>
        <table border="1">
            <thead>
            <th>ID</th>
            <th>Nome</th>
            <th>Criado Em</th>
            <th>Ações</th>
            </thead>
            <tbod>
                @foreach($marcas as $marca)
                    <tr>
                        <td>{{$marca->id}}</td>
                        <td>{{$marca->nome}}</td>
                        <td>{{$marca->created_at}}</td>
                        <td>
                            <a href="{{url("marcas/$marca->id/edit")}}">Editar</a>
                            <br>
                            <form method="POST" action="{{url("marcas/$marca->id")}}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Tem certeza que deseja excluir? {{$marca->nome}} ?')"> Excluir</button>

                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbod>
        </table>
    </div>


</body>
</html>
