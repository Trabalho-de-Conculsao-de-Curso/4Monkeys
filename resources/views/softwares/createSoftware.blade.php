<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud das Entidades</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

<div>
    <form action="/softwares" method="POST">
        <div>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" required>
        </div>
        <div>
            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" id="descricao">
        </div>
        <div>
            <input type="submit"value="Enviar">
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </form>
</div>

</body>
</html>
