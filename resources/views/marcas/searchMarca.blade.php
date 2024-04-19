<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud das Entidades</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

<div>
    <h1>Busca</h1>
    <div>
        <a href="/marcas/create">Cadastrar Novo marcas</a>
        <br>
        <br>
        <button><a href="/marcas">Home</a></button>
    </div>
    <br>
    <form action="{{ url('marcas/search') }}" method="GET">
        <input type="text" name="search" placeholder="Procurar marca">
        <button type="submit">Search</button>
    </form>

    @if ($results->count() > 0)
        <table border="1">
            <thead>
            <th>ID</th>
            <th>Nome</th>
            <th>Criado em</th>
            <th>Editado em</th>
            </thead>
            <tbody>
            @foreach ($results as $marca)
                <tr>
                    <td>{{ $marca->id }}</td>
                    <td>{{ $marca->nome }}</td>
                    <td>{{ $marca->created_at}}</td>
                    <td>{{ $marca->updated_at}}</td>
                    <td>
                        <a href="{{ url("marcas/$marca->id/edit") }}">Editar</a>
                        <br>
                        <form method="POST" action="{{url("marcas/$marca->id")}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Tem certeza que deseja excluir? {{$marca->nome}} ?')"> Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Nenhuma marca encontrada.</p>
    @endif
</div>



</body>
</html>
