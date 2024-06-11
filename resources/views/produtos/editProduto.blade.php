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
        <h3>Marca campos</h3>
        <div>
            <label for="marca_nome">Nome</label>
            <input type="text" name="marca_nome" id="marca_nome" value="{{$produto->marca->nome}}" required>
        </div>
        <div>
            <label for="marca_qualidade">Qualidade</label>
            <input type="text" name="marca_qualidade" id="marca_qualidade" value="{{$produto->marca->qualidade}}" required>
        </div>
        <div>
            <label for="marca_garantia">Garantia</label>
            <input type="text" name="marca_garantia" id="marca_garantia" value="{{$produto->marca->garantia}}" required>
        </div>

        <h3>Especificações campos</h3>
        <div>
            <label for="especificacoes_detalhes">Especificações</label>
            <input type="text" name="especificacoes_detalhes" id="especificacoes_detalhes" value="{{$produto->especificacoes->detalhes}}" required>
        </div>
        <h3>Preço campos</h3>
        <div>
            <label for="preco">Preço</label>
            <input type="text" name="preco" id="preco" value="{{$produto->preco}}" required>
        </div>
        <h3>Lojas Online campos</h3>
        <div>
            <label for="nomeLojaOnline">lojas Online</label>
            <input type="text" name="nomeLojaOnline" id="nomeLojaOnline" value="{{$produto->lojaOnline}}" required>
        </div>
        <div>
            <label for="urlLojaOnline">URL Loja Online</label>
            <input type="text" name="urlLojaOnline" id="urlLojaOnline" value="{{$produto->lojaOnline}}" required>
        </div>
        <div>
            <input type="submit" value="Editar">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        </div>

    </form>
</div>

</body>
</html>
