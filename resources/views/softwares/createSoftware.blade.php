@extends('layouts.appSoftware')

@section('content')

<!-- Main Content -->
<div class="p-4">
    <div class="border border-gray-300 rounded-lg p-6 bg-white shadow-md"> <!-- Adicionando a borda e o fundo branco -->
        <form action="/softwares" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="tipo" class="block font-semibold">Tipo do Software: 1 - Jogo, 2 - Trabalho, 3 - Utilitários</label>
                <input type="text" name="tipo" id="tipo" required class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="nome" class="block font-semibold">Nome</label>
                <input type="text" name="nome" id="nome" required class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="descricao" class="block font-semibold">Descrição</label>
                <input type="text" name="descricao" id="descricao" class="border border-gray-300 p-2 w-full rounded">
            </div>

            <div>
                <label for="peso" class="block font-semibold">Peso</label>
                <input type="number" name="peso" id="peso" class="border border-gray-300 p-2 w-full rounded">
            </div>

            <div>
                <label for="software_imagem" class="block font-semibold">Upload de Imagem</label>
                <input id="software_imagem" type="file" name="software_imagem" required class="border border-gray-300 p-2 w-full rounded">
                @error('software_imagem')
                <div class="text-sm text-red-400">{{ $message }}</div>
                @enderror
            </div>

            <h3 class="font-semibold">Requisitos Mínimos</h3>
            <div>
                <label for="cpu_min" class="block font-semibold">CPU</label>
                <input type="text" name="cpu_min" id="cpu_min" required class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="gpu_min" class="block font-semibold">GPU</label>
                <input type="text" name="gpu_min" id="gpu_min" required class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="ram_min" class="block font-semibold">RAM</label>
                <input type="text" name="ram_min" id="ram_min" required class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="placa_mae_min" class="block font-semibold">Placa Mãe</label>
                <input type="text" name="placa_mae_min" id="placa_mae_min" class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="ssd_min" class="block font-semibold">SSD</label>
                <input type="text" name="ssd_min" id="ssd_min" class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="cooler_min" class="block font-semibold">Cooler</label>
                <input type="text" name="cooler_min" id="cooler_min" class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="fonte_min" class="block font-semibold">Fonte</label>
                <input type="text" name="fonte_min" id="fonte_min" class="border border-gray-300 p-2 w-full rounded">
            </div>

            <h3 class="font-semibold">Requisitos Médios</h3>
            <div>
                <label for="cpu_med" class="block font-semibold">CPU</label>
                <input type="text" name="cpu_med" id="cpu_med" required class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="gpu_med" class="block font-semibold">GPU</label>
                <input type="text" name="gpu_med" id="gpu_med" required class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="ram_med" class="block font-semibold">RAM</label>
                <input type="text" name="ram_med" id="ram_med" required class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="placa_mae_med" class="block font-semibold">Placa Mãe</label>
                <input type="text" name="placa_mae_med" id="placa_mae_med" class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="ssd_med" class="block font-semibold">SSD</label>
                <input type="text" name="ssd_med" id="ssd_med" class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="cooler_med" class="block font-semibold">Cooler</label>
                <input type="text" name="cooler_med" id="cooler_med" class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="fonte_med" class="block font-semibold">Fonte</label>
                <input type="text" name="fonte_med" id="fonte_med" class="border border-gray-300 p-2 w-full rounded">
            </div>

            <h3 class="font-semibold">Requisitos Recomendados</h3>
            <div>
                <label for="cpu_rec" class="block font-semibold">CPU</label>
                <input type="text" name="cpu_rec" id="cpu_rec" required class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="gpu_rec" class="block font-semibold">GPU</label>
                <input type="text" name="gpu_rec" id="gpu_rec" required class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="ram_rec" class="block font-semibold">RAM</label>
                <input type="text" name="ram_rec" id="ram_rec" required class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="placa_mae_rec" class="block font-semibold">Placa Mãe</label>
                <input type="text" name="placa_mae_rec" id="placa_mae_rec" class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="ssd_rec" class="block font-semibold">SSD</label>
                <input type="text" name="ssd_rec" id="ssd_rec" class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="cooler_rec" class="block font-semibold">Cooler</label>
                <input type="text" name="cooler_rec" id="cooler_rec" class="border border-gray-300 p-2 w-full rounded">
            </div>
            <div>
                <label for="fonte_rec" class="block font-semibold">Fonte</label>
                <input type="text" name="fonte_rec" id="fonte_rec" class="border border-gray-300 p-2 w-full rounded">
            </div>

            <div>
                <input type="submit" value="Enviar" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
            </div>
        </form>
    </div>
</div>
@endsection
