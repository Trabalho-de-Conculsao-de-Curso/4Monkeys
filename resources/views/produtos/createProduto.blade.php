<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud das Entidades</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

<div>
    <form action="/produtos" method="POST">
        <div>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" required>
        </div>
        <div>
            <label for="marca">Marca</label>
            <input type="text" name="marca" id="marca" required>
        </div>
        <div>
            <label for="especificacoes">Especificacoes</label>
            <input type="text" name="especificacoes" id="especificacoes" required>
        </div>
        <div>
            <label for="preco">Preço</label>
            <input type="number" name="preco" id="preco" required>
        </div>
        <div>
            <label for="lojasOnline">Lojas Online</label>
            <input type="text" name="lojasOnline" id="lojasOnline" required>
        </div>
        <div>
            <input type="submit"value="Enviar">
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </form>
</div>

</body>
</html>
