<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud das Entidades</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

<div>
    <form action="/softwares" method="POST" enctype="multipart/form-data">
        <div>
            <label for="tipo">Tipo do Software: 1 - Jogo, 2 - Trabalho, 3 - Utilitários</label>
            <br/>
            <input type="text" name="tipo" id="tipo" required>
        </div>
        <div>
            <label for="nome">Nome</label>
            <br/>
            <input type="text" name="nome" id="nome" required>
        </div>
        <div>
            <label for="descricao">Descrição</label>
            <br/>
            <input type="text" name="descricao" id="descricao">
        </div>
        <div>
            <label for="requisitos">Requisitos</label>
            <br/>
            <input type="text" name="requisitos" id="requisitos">
        </div>
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                   for="software_imagem">Upload</label>
            <input
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                id="software_imagem" type="file" name="software_imagem">
            @error('software_imagem')
            <div class="text-sm text-red-400">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <input type="submit" value="Enviar">
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </form>
</div>

</body>
</html>
