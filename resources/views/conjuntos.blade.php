@extends('layouts.userpremium')

@section('content premium')

@php
    $icones = [
    'fas fa-microchip',  
    'fas fa-video',       
    'fas fa-memory',     
    'fas fa-thumbs-up',   
    'fas fa-video',       
    'fas fa-hdd',         
    ];
@endphp


<body class="bg-gray-100 p-6">
    <h1 class="text-3xl font-bold mb-6 text-center mt-4">
         Configurações geradas com base nos Softwares selecionados
    </h1>
    <div class="flex space-x-4 mt-4"> <!-- Flex aplicado aqui para os conjuntos -->
        @foreach ($conjuntos as $conjunto)
            <div class="bg-white border border-gray-300 rounded-lg shadow-md p-4 mb-4 w-1/3"> <!-- Cada conjunto ocupa 1/3 da largura -->
                <h2 class="text-xl font-semibold border-b pb-2">{{ ucfirst($conjunto['categoria']) }}</h2>
                <h3 class="text-lg font-bold text-gray-700 mb-4">Total: R$ {{ number_format($conjunto['total'], 2, ',', '.') }}</h3>

                <div class="space-y-4"> <!-- Flex não é necessário aqui, as peças serão empilhadas verticalmente -->
                @foreach ($conjunto['componentes'] as $index => $componente)
                <div class="flex-1">
                    <ul class="list-disc">
                        <li class="flex items-center mb-2">
                            <!-- Usando substr para mostrar apenas os primeiros 30 caracteres -->
                            <span class="mr-2">{{ substr($componente['nome'], 0, 30) }}...</span>
                            <a href="{{ $componente['url'] }}" target="_blank" class="bg-purple-600 text-white px-2 py-1 rounded hover:bg-purple-600 ml-auto">Acessar</a>
                        </li>
                        <p>Preço: R$ {{ number_format($componente['preco'], 2, ',', '.') }}</p>
                    </ul>
                </div>
                @endforeach
                </div>
            </div>
        @endforeach
    </div>
</body>
@endsection