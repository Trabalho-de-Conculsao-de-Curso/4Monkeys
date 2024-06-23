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
    <form action="/produtos/" method="POST">
        <div>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" required>
        </div>
            <h3>Marca campos</h3>
        <div>
            <label for="marca_nome">Nome</label>
            <input type="text" name="marca_nome" id="marca_nome" required>
        </div>

        <div>
            <label for="marca_qualidade">Qualidade</label>
            <input type="text" name="marca_qualidade" id="marca_qualidade" required>
        </div>

        <div>
            <label for="marca_garantia">Garantia</label>
            <input type="text" name="marca_garantia" id="marca_garantia" required>
        </div>
        <h3>Especificaçẽs campos</h3>
        <div>
            <label for="especificacoes">Detalhes</label>
            <input type="text" name="especificacoes_detalhes" id="especificacoes_detalhes" required>
        </div>
        <h3>Preço campos</h3>
        <div>
            <label for="preco">Preço</label>
            <input type="number" name="preco_valor" id="preco_valor" required>
        </div>
        <br>
        <div>
            <label for="moeda">Moeda</label>
            <input type="text" name="preco_moeda" id="preco_moeda" required>
        </div>
        <h3>Lojas Online campos</h3>
        <div>
            <label for="lojasOnline">Lojas Online</label>
            <input type="text" name="lojasOnline" id="lojasOnline" required>
        </div>

        <div>
            <label for="urlLojaOnline">URL Da Loja</label>
            <input type="text" name="urlLojaOnline" id="urlLojaOnline" required>
        </div>
        <div>
            <input type="submit"value="Enviar">
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </form>
</div>

</body>
</html>
