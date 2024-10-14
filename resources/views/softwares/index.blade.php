<!-- resources/views/softwares/index.blade.php -->
@extends('layouts.appSoftware')

@section('title', 'Lista de Softwares')

@section('content')

<h1 class="text-1xl mb-4 text-left">Administrar > Lista de Softwares</h1> <!-- Alinhado à esquerda -->
<div class="w-full h-1 bg-gray-300 mb-4"></div>

<div class="mb-4 flex justify-between items-center">
    <form action="{{ url('softwares/search') }}" method="GET" class="flex items-center">
        <input type="text" name="search" placeholder="Procurar Software" 
               class="border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        <button type="submit" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                Search
        </button>
    </form>
    <a href="{{ route('softwares.create') }}" 
       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
       Cadastrar Novo Software
    </a>
</div>

<table class="table-auto w-full text-left border-collapse border border-gray-500 bg-white rounded-md">
    <thead class="bg-blue-500">
        <tr>
            <th class="py-2 px-3 border">ID</th>
            <th class="py-2 px-3 border">Tipo</th>
            <th class="py-2 px-3 border">Nome</th>
            <th class="py-2 px-3 border">Descrição</th>
            <th class="py-2 px-3 border">Peso</th>
            <th class="py-2 px-3 border">Imagem</th>
            <th class="py-2 px-3 border">Criado Em</th>
            <th class="py-2 px-3 border">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($softwares as $software)
            <tr>
                <td class="py-2 px-3 border">{{ $software->id }}</td>
                <td class="py-2 px-3 border">{{ $software->tipo }}</td>
                <td class="py-2 px-3 border">{{ $software->nome }}</td>
                <td class="py-2 px-3 border">{{ $software->descricao }}</td>
                <td class="py-2 px-3 border">{{ $software->peso }}</td>
                <td class="py-2 px-3 border">
                    @if($software->imagem)
                        <img src="{{ asset('storage/' . $software->imagem) }}" alt="Imagem do Software" width="100">
                    @else
                        <span>Sem imagem</span>
                    @endif
                </td>
                <td class="py-2 px-3 border">{{ $software->created_at->format('d/m/Y H:i') }}</td>
                <td class="py-2 px-3 border">
                    <a href="{{ route('softwares.edit', $software->id) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                       Editar
                    </a>
                    <form method="POST" action="{{ route('softwares.destroy', $software->id) }}" class="inline-block mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3.5 rounded focus:outline-none focus:ring-2 focus:ring-red-500 text-sm"
                                onclick="return confirm('Tem certeza que deseja excluir {{ $software->nome }}?')">
                                Excluir
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
