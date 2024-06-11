<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud das Entidades</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">
<h1>Lista de Softwares</h1>

<div>
    <div>
        <a href="/softwares/create">Cadastrar Novo Software</a>
    </div>
    <div>
        <form action="{{ url('softwares/search') }}" method="GET">
            <input type="text" name="search" placeholder="Procurar Software">
            <button type="submit">Search</button>
        </form>
    </div>
    <table border="1">
        <thead>
        <th>ID</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Criado Em</th>
        <th>Ações</th>
        </thead>
        <tbod>
            @foreach($softwares as $software)
                <tr>
                    <td>{{$software->id}}</td>
                    <td>{{$software->nome}}</td>
                    <td>{{$software->descricao}}</td>
                    <td>{{$software->created_at}}</td>
                    <td>
                        <a href="{{url("softwares/$software->id/edit")}}">Editar</a>
                        <br>
                        <form method="POST" action="{{url("softwares/$software->id")}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Tem certeza que deseja excluir? {{$software->nome}} ?')"> Excluir</button>

                        </form>
                    </td>
                </tr>
            @endforeach
        </tbod>
    </table>
</div>


</body>
</html>
