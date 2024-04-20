<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud das Entidades</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

<div>
    <button><a href="/produtos">Home</a></button>

    <form action="{{ url("/produtos/$produto->id") }}" method="POST">
        @method("PUT")
        <h1 class="text-xl font-bold mb-4">Editar Produto</h1>
        <div>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="{{$produto->nome}}" required>
        </div>
        <div>
            <label for="marca">Marca</label>
            <input type="text" name="marca" id="marca" value="{{$produto->marca}}" required>
        </div>
        <div>
            <label for="especificacoes">Especificações</label>
            <input type="text" name="especificacoes" id="especificacoes" value="{{$produto->especificacoes}}" required>
        </div>
        <div>
            <label for="preco">Preço</label>
            <input type="text" name="preco" id="preco" value="{{$produto->preco}}" required>
        </div>
        <div>
            <label for="lojasOnline">lojas Online</label>
            <input type="text" name="lojasOnline" id="lojasOnline" value="{{$produto->lojasOnline}}" required>
        </div>
        <div>
            <input type="submit" value="Editar">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        </div>

    </form>
</div>

</body>
</html>
