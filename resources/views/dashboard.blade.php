@extends('layouts.userpremium')

@section('content premium')

<div class="mt-10">
    <!-- Formulário Principal -->
    <form id="software-selection-form" action="{{ auth()->check() ? route('home.selecionar') : route('free.selecionar') }}" method="POST" class="relative p-6 bg-white border border-gray-800 rounded-lg shadow">
        @csrf
        <h2 class="mb-6 text-4xl font-bold text-center text-transparent concert-one-regular bg-gradient-to-r from-orange-500 via-pink-400 to-blue-300 bg-clip-text animate-fade-in">
            Selecionar Softwares
        </h2>

        <div class="flex justify-between space-x-4">
            <!-- Coluna de Jogos -->
            <div class="flex-1">
                <h3 class="mb-6 text-4xl font-bold text-center text-transparent concert-one-regular bg-gradient-to-r from-orange-500 via-pink-400 to-blue-300 bg-clip-text animate-fade-in">
                    Jogos
                </h3>
                @foreach($softwares as $software)
                    @if($software->tipo == 1)
                        <div id="checkboxDiv{{ $software->id }}" class="flex items-center p-3 mb-4 transition-shadow duration-300 transform bg-transparent border-2 border-purple-700 rounded-lg shadow-none cursor-pointer hover:shadow-lg hover:scale-105"
                             onclick="toggleFillAndDetails({{ $software->id }}, '{{ addslashes($software->descricao) }}')">
                            <img src="{{ asset('storage/' . $software->imagem) }}" alt="Imagem de {{ $software->nome }}" class="object-cover w-16 h-12 transition-transform duration-300 rounded-md hover:scale-110">
                            <div class="relative ml-4">
                                <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="hidden">
                                <label for="software{{ $software->id }}" class="flex items-center block space-x-2 text-lg font-semibold">
                                    <span>{{ $software->nome }}</span>
                                </label>
                            </div>
                        </div>
                        <div id="details-{{ $software->id }}" class="mt-2 details" style="display: none;">
                            <p id="description-{{ $software->id }}" class="text-1xl description"></p>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Coluna de Trabalho -->
            <div class="flex-1">
                <h3 class="mb-6 text-4xl font-bold text-center text-transparent concert-one-regular bg-gradient-to-r from-orange-500 via-pink-400 to-blue-300 bg-clip-text animate-fade-in">
                    Trabalho
                </h3>
                @foreach($softwares as $software)
                    @if($software->tipo == 2)
                        <div id="checkboxDiv{{ $software->id }}" class="flex items-center p-3 mb-4 transition-shadow duration-300 transform bg-transparent border-2 border-purple-700 rounded-lg shadow-none cursor-pointer hover:shadow-lg hover:scale-105"
                             onclick="toggleFillAndDetails({{ $software->id }}, '{{ addslashes($software->descricao) }}')">
                            <img src="{{ asset('storage/' . $software->imagem) }}" alt="Imagem de {{ $software->nome }}" class="object-cover w-16 h-12 transition-transform duration-300 rounded-md hover:scale-110">
                            <div class="relative ml-4">
                                <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="hidden">
                                <label for="software{{ $software->id }}" class="flex items-center block space-x-2 text-lg font-semibold">
                                    <span>{{ $software->nome }}</span>
                                </label>
                            </div>
                        </div>
                        <div id="details-{{ $software->id }}" class="mt-2 details" style="display: none;">
                            <p id="description-{{ $software->id }}" class="text-1xl description"></p>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Coluna de Dispositivos -->
            <div class="flex-1">
                <h3 class="mb-6 text-4xl font-bold text-center text-transparent concert-one-regular bg-gradient-to-r from-orange-500 via-pink-400 to-blue-300 bg-clip-text animate-fade-in">
                    Dispositivos
                </h3>
                @foreach($softwares as $software)
                    @if($software->tipo == 3)
                        <div id="checkboxDiv{{ $software->id }}" class="flex items-center p-3 mb-4 transition-shadow duration-300 transform bg-transparent border-2 border-purple-700 rounded-lg shadow-none cursor-pointer hover:shadow-lg hover:scale-105"
                             onclick="toggleFillAndDetails({{ $software->id }}, '{{ addslashes($software->descricao) }}')">
                            <img src="{{ asset('storage/' . $software->imagem) }}" alt="Imagem de {{ $software->nome }}" class="object-cover w-16 h-12 transition-transform duration-300 rounded-md hover:scale-110">
                            <div class="relative ml-4">
                                <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="hidden">
                                <label for="software{{ $software->id }}" class="flex items-center block space-x-2 text-lg font-semibold">
                                    <span>{{ $software->nome }}</span>
                                </label>
                            </div>
                        </div>
                        <div id="details-{{ $software->id }}" class="mt-2 details" style="display: none;">
                            <p id="description-{{ $software->id }}" class="text-1xl description"></p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <!-- Botão de Submissão -->
        <div class="flex justify-center mt-6">
            <button type="submit" class="px-8 py-4 text-2xl text-white transition duration-300 ease-in-out transform rounded-lg shadow-lg concert-one-regular bg-gradient-to-r from-purple-700 via-pink-600 to-rose-600 hover:bg-gradient-to-r hover:from-purple-600 hover:via-pink-500 hover:to-rose-500 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-purple-300">
                Selecionar Softwares
            </button>
        </div>
    </form>
</div>

@endsection
