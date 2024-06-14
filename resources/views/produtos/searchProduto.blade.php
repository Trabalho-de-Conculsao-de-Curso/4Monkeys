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
        <a href="/produtos/create">Cadastrar Novo produto</a>
        <br>
        <br>
        <button><a href="/produtos">Home</a></button>
    </div>
    <br>
    <form action="{{ url('produtos/search') }}" method="GET">
        <input type="text" name="search" placeholder="Procurar produto">
        <button type="submit">Search</button>
    </form>

    @if ($results->count() > 0)
        <table border="1">
            <thead>
            <th>ID</th>
            <th>Nome</th>
            <th>Marca</th>
            <th>Especificações</th>
            <th>Valor</th>
            <th>Moeda</th>
            <th>Lojas Online</th>
            <th>URL Loja Online</th>
            <th>Criado em</th>
            <th>Editado em</th>
            </thead>
            <tbody>
            @foreach ($results as $produto)
                <tr>
                    <td>{{ $produto->id }}</td>
                    <td>{{ $produto->nome }}</td>
                    <td>{{ $produto->marca->nome }}</td>
                    <td>{{ $produto->especificacoes->detalhes}}</td>
                    <td>{{$produto->preco->valor}}</td>
                    <td>{{$produto->preco->moeda}}</td>
                    <td>{{$produto->lojaOnline->nome}}</td>
                    <td>{{$produto->lojaOnline->urlLoja}}</td>
                    <td>{{ $produto->created_at}}</td>
                    <td>{{ $produto->updated_at}}</td>
                    <td>
                        <a href="{{ url("produtos/$produto->id/edit") }}">Editar</a>
                        <br>
                        <form method="POST" action="{{url("produtos/$produto->id")}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Tem certeza que deseja excluir? {{$produto->nome}} ?')"> Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Nenhum produto encontrado.</p>
    @endif
</div>



</body>
</html>
