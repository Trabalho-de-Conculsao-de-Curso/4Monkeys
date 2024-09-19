<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud das Entidades</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

<div>

    <form action="{{ url("/softwares/$software->id") }}" method="POST" enctype="multipart/form-data">
        @method("PUT")
        <h1 class="text-xl font-bold mb-4">Editar Software</h1>
        <div>
            <label for="nome">Tipo do Software: 1 - Jogo, 2 - Trabalho, 3 - Utilitários</label>
            <br/>
            <input type="text" name="tipo" id="tipo" value="{{$software->tipo}}" required>
        </div>
        <div>
            <label for="nome">Nome</label>
            <br/>
            <input type="text" name="nome" id="nome" value="{{$software->nome}}" required>
        </div>
        <div>
            <label for="descricao">Descrição</label>
            <br/>
            <input type="text" name="descricao" id="descricao" value="{{$software->descricao}}">
        </div>
        <div>
            <label for="requisitos">Requisitos</label>
            <br/>
            <input type="text" name="requisitos" id="requisitos" value="{{$software->requisitos}}">
        </div>
        <div>
            <label for="software_imagem">Upload da Nova Imagem (opcional)</label>
            <br/>
            <input type="file" name="software_imagem" id="software_imagem">
        </div>
        <div>
            @if($software->imagem)
                <input type="checkbox" name="remover_imagem" id="remover_imagem">
                <label for="remover_imagem">Remover imagem atual</label>
            @endif
        </div>

        <div>
            <input type="submit" value="Editar">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        </div>
    </form>
</div>

</body>
</html>
