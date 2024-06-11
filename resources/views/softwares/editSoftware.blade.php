<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud das Entidades</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

<div>

    <form action="{{ url("/softwares/$software->id") }}" method="POST">
        @method("PUT")
        <h1 class="text-xl font-bold mb-4">Editar Software</h1>
        <div>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="{{$software->nome}}" required>
        </div>
        <div>
            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" id="descricao" value="{{$software->descricao}}">
        </div>

        <div>
            <input type="submit" value="Editar">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        </div>
    </form>
</div>

</body>
</html>
