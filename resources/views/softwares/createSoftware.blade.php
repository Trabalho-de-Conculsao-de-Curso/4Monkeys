<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Software</title>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

<div>
    <form action="/softwares" method="POST" enctype="multipart/form-data">
        @csrf
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
            <label for="peso">Peso</label>
            <br/>
            <input type="number" name="peso" id="peso">
        </div>

        <div>
            <label for="software_imagem">Upload de Imagem</label>
            <br/>
            <input id="software_imagem" type="file" name="software_imagem" required>
            @error('software_imagem')
            <div class="text-sm text-red-400">{{ $message }}</div>
            @enderror
        </div>

        <h3>Requisitos Mínimos</h3>
        <div>
            <label for="cpu_min">CPU</label>
            <br/>
            <input type="text" name="cpu_min" id="cpu_min" required>
        </div>
        <div>
            <label for="gpu_min">GPU</label>
            <br/>
            <input type="text" name="gpu_min" id="gpu_min" required>
        </div>
        <div>
            <label for="ram_min">RAM</label>
            <br/>
            <input type="text" name="ram_min" id="ram_min" required>
        </div>
        <div>
            <label for="placa_mae_min">Placa Mãe</label>
            <br/>
            <input type="text" name="placa_mae_min" id="placa_mae_min">
        </div>
        <div>
            <label for="ssd_min">SSD</label>
            <br/>
            <input type="text" name="ssd_min" id="ssd_min">
        </div>
        <div>
            <label for="cooler_min">Cooler</label>
            <br/>
            <input type="text" name="cooler_min" id="cooler_min">
        </div>
        <div>
            <label for="fonte_min">Fonte</label>
            <br/>
            <input type="text" name="fonte_min" id="fonte_min">
        </div>

        <h3>Requisitos Médios</h3>
        <div>
            <label for="cpu_med">CPU</label>
            <br/>
            <input type="text" name="cpu_med" id="cpu_med" required>
        </div>
        <div>
            <label for="gpu_med">GPU</label>
            <br/>
            <input type="text" name="gpu_med" id="gpu_med" required>
        </div>
        <div>
            <label for="ram_med">RAM</label>
            <br/>
            <input type="text" name="ram_med" id="ram_med" required>
        </div>
        <div>
            <label for="placa_mae_med">Placa Mãe</label>
            <br/>
            <input type="text" name="placa_mae_med" id="placa_mae_med">
        </div>
        <div>
            <label for="ssd_med">SSD</label>
            <br/>
            <input type="text" name="ssd_med" id="ssd_med">
        </div>
        <div>
            <label for="cooler_med">Cooler</label>
            <br/>
            <input type="text" name="cooler_med" id="cooler_med">
        </div>
        <div>
            <label for="fonte_med">Fonte</label>
            <br/>
            <input type="text" name="fonte_med" id="fonte_med">
        </div>

        <h3>Requisitos Recomendados</h3>
        <div>
            <label for="cpu_rec">CPU</label>
            <br/>
            <input type="text" name="cpu_rec" id="cpu_rec" required>
        </div>
        <div>
            <label for="gpu_rec">GPU</label>
            <br/>
            <input type="text" name="gpu_rec" id="gpu_rec" required>
        </div>
        <div>
            <label for="ram_rec">RAM</label>
            <br/>
            <input type="text" name="ram_rec" id="ram_rec" required>
        </div>
        <div>
            <label for="placa_mae_rec">Placa Mãe</label>
            <br/>
            <input type="text" name="placa_mae_rec" id="placa_mae_rec">
        </div>
        <div>
            <label for="ssd_rec">SSD</label>
            <br/>
            <input type="text" name="ssd_rec" id="ssd_rec">
        </div>
        <div>
            <label for="cooler_rec">Cooler</label>
            <br/>
            <input type="text" name="cooler_rec" id="cooler_rec">
        </div>
        <div>
            <label for="fonte_rec">Fonte</label>
            <br/>
            <input type="text" name="fonte_rec" id="fonte_rec">
        </div>

        <div>
            <input type="submit" value="Enviar">
        </div>
    </form>
</div>

</body>
</html>
