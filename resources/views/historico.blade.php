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
    <h1 class="text-3xl font-bold mb-6"><i class="fas fa-tachometer-alt"></i> Histórico </h1>
    @foreach ($historico as $item)
        <div class="bg-white border border-gray-300 rounded-lg shadow-md p-4 mb-4">
            <h2 class="text-xl font-semibold border-b pb-2">Data: {{ $item['data'] }}</h2>
            
            <div class="flex space-x-4 mt-4"> 
                @foreach ($item['conjuntos'] as $conjunto)
                <div class="border border-gray-300 shadow-md p-6 rounded shadow-gray-700">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold mt-4  grafite">{{ $conjunto['nome'] }} (Total: R$ {{ number_format($conjunto['total'], 2, ',', '.') }})</h3>
                        <h3 class="text-lg font-bold mt-4 
                            @if ($conjunto['nome'] === 'Conjunto Gold') text-yellow-500 @elseif ($conjunto['nome'] === 'Conjunto Silver') text-gray-400 @elseif ($conjunto['nome'] === 'Conjunto Bronze') text-orange-500 @endif">
                            {{ $conjunto['nome'] }}
                        </h3>
                        <h4 class="font-semibold mt-2">Softwares:</h4>
                        <ul class="list-disc ml-2 flex flex-wrap">
                            @foreach ($conjunto['softwares'] as $software)
                                <li class="flex items-center mb-2 mr-5">
                                    <i class="fas fa-gamepad mr-2 text-purple-600"></i> 
                                    <strong>{{ $software['nome'] }}</strong>
                                </li>
                            @endforeach
                        </ul>
                        <h4 class="font-semibold mt-2">Acesse os produtos:</h4>
                        <ul class="list-disc ml-2">
                           
                            @foreach ($conjunto['produtos'] as $index => $produto)
                                <li class="flex items-center mb-2">
                                    <i class="{{ $icones[$index % count($icones)] }} mr-2 text-black"></i> 
                                    <span class="mr-2">{{ $produto['nome'] }}</span>
                                    <a href="{{ $produto['url'] }}" target="_blank" class="bg-purple-600 text-white px-2 py-1 rounded hover:bg-purple-600">Acessar</a>
                                </li>
                            @endforeach
                        
                        </ul>
                    </div>
                </div>
                @endforeach
            
            </div>
        </div>
    @endforeach
</body>

@endsection