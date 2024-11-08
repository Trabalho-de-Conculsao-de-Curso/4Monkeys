@extends('layouts.userpremium')

@section('content premium')

<div class="mt-10">
    <form id="software-selection-form" action="{{ auth()->check() ? route('home.selecionar') : route('home.selecionar') }}" method="POST" class="bg-white relative rounded-lg shadow p-6 border border-gray-800">
        @csrf
        <h3 class="concert-one-regular text-center text-4xl font-bold mb-6 bg-gradient-to-r from-orange-500 via-pink-400 to-blue-300 text-transparent bg-clip-text animate-fade-in">
            Selecionar Softwares
        </h3>


        <div class="flex justify-between space-x-4">

            <div class="flex-1">
                <h3 class="concert-one-regular text-center text-4xl font-bold mb-6 bg-gradient-to-r from-purple-800 via-purple-400 to-blue-400 text-transparent bg-clip-text animate-fade-in">
                    Jogos
                </h3>
                @foreach($softwares as $software)
                    @if($software->tipo == 1)
                        <div id="checkboxDiv{{ $software->id }}"
                            class="flex justify-start items-center border-2 rounded-lg border-purple-700 mb-4 p-3 bg-transparent shadow-none hover:shadow-lg transition-shadow duration-300 transform hover:scale-105 cursor-pointer"
                            onclick="toggleFillAndDetails({{ $software->id }}, '{{ addslashes($software->descricao) }}')">

                            <img src="{{ asset('storage/' . $software->imagem) }}" alt="Imagem de {{ $software->nome }}" class="w-16 h-12 object-cover rounded-md transition-transform duration-300 hover:scale-110">

                            <div class="relative ml-4">

                                <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="hidden">


                                <label for="software{{ $software->id }}" class="block text-lg font-semibold flex items-center space-x-2">
                                    <span>{{ $software->nome }}</span>
                                </label>
                            </div>
                        </div>

                        <div id="details-{{ $software->id }}" class="details mt-2" style="display: none;">

                            <p id="description-{{ $software->id }}" class="text-1xl description"></p>
                        </div>
                    @endif
                @endforeach
            </div>


            <div class="flex-1">
                <h3 class="concert-one-regular text-center text-4xl font-bold mb-6 bg-gradient-to-r from-orange-500 via-pink-400 to-blue-300 text-transparent bg-clip-text animate-fade-in">
                    Trabalho
                </h3>
                @foreach($softwares as $software)
                    @if($software->tipo == 2)
                        <div id="checkboxDiv{{ $software->id }}"
                            class="flex justify-start items-center border-2 rounded-lg border-purple-700 mb-4 p-3 bg-transparent shadow-none hover:shadow-lg transition-shadow duration-300 transform hover:scale-105 cursor-pointer"
                            onclick="toggleFillAndDetails({{ $software->id }}, '{{ addslashes($software->descricao) }}')">

                            <img src="{{ asset('storage/' . $software->imagem) }}" alt="Imagem de {{ $software->nome }}" class="w-16 h-12 object-cover rounded-md transition-transform duration-300 hover:scale-110">

                            <div class="relative ml-4">

                                <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="hidden">


                                <label for="software{{ $software->id }}" class="block text-lg font-semibold flex items-center space-x-2">
                                    <span>{{ $software->nome }}</span>
                                </label>
                            </div>
                        </div>

                        <div id="details-{{ $software->id }}" class="details mt-2" style="display: none;">

                            <p id="description-{{ $software->id }}" class="text-1xl description"></p>
                        </div>
                    @endif
                @endforeach
            </div>


            <div class="flex-1">
                <h3 class="concert-one-regular text-center text-4xl font-bold mb-6 bg-gradient-to-r from-orange-500 via-pink-400 to-blue-300 text-transparent bg-clip-text animate-fade-in">
                    Dispositivos
                </h3>
                @foreach($softwares as $software)
                    @if($software->tipo == 3)
                        <div id="checkboxDiv{{ $software->id }}"
                            class="flex justify-start items-center border-2 rounded-lg border-purple-700 mb-4 p-3 bg-transparent shadow-none hover:shadow-lg transition-shadow duration-300 transform hover:scale-105 cursor-pointer"
                            onclick="toggleFillAndDetails({{ $software->id }}, '{{ addslashes($software->descricao) }}')">

                            <img src="{{ asset('storage/' . $software->imagem) }}" alt="Imagem de {{ $software->nome }}" class="w-16 h-12 object-cover rounded-md transition-transform duration-300 hover:scale-110">

                            <div class="relative ml-4">

                                <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="hidden">


                                <label for="software{{ $software->id }}" class="block text-lg font-semibold flex items-center space-x-2">
                                    <span>{{ $software->nome }}</span>
                                </label>
                            </div>
                        </div>

                        <div id="details-{{ $software->id }}" class="details mt-2" style="display: none;">

                            <p id="description-{{ $software->id }}" class="text-1xl description"></p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>


        <div class="flex justify-center mt-6">
            <button type="submit" class="concert-one-regular bg-gradient-to-r from-purple-700 via-pink-600 to-rose-600 text-white py-4 px-8 text-2xl rounded-lg shadow-lg hover:bg-gradient-to-r hover:from-purple-600 hover:via-pink-500 hover:to-rose-500 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-purple-300">
                Selecionar Softwares
            </button>
        </div>


        <div id="loading-spinner" class="absolute inset-0 bg-zinc-800 flex flex-col items-center justify-center z-10 hidden rounded-sm">
            <div class="inline-block w-42 h-36 mb-4">
                <img src="{{ asset('images/gif-minecraft.gif') }}" alt="Descrição da imagem" class="object-cover w-42 h-36 rounded-sm">
            </div>
            <p class="text-white text-lg">Aguarde, seu setup está sendo montado...</p>
        </div>
    </form>
</div>
@endsection
