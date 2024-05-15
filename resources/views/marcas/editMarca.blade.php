<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud das Entidades</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

<div>

        <form action="{{ url("/marcas/$marca->id") }}" method="POST">
            @method("PUT")
            <h1 class="text-xl font-bold mb-4">Editar Usuario</h1>
            <div>
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" value="{{$marca->nome}}" required>
            </div>
            <div>
                <label for="qualidade">Qualidade</label>
                <input type="text" name="qualidade" id="qualidade" value="{{$marca->qualidade}}" required>
            </div>
            <div>
                <label for="garantia">Garantia</label>
                <input type="text" name="garantia" id="garantia" value="{{$marca->garantia}}" required>
            </div>
            <div>
                <input type="submit" value="Editar">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            </div>
        </form>
</div>

</body>
</html>
