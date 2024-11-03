@extends('layouts.userpremium')

@section('content premium')

@foreach ($desktops as $desktop)
        <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd;">
            <h2>Categoria: {{ $desktop['categoria'] }}</h2>

            <h3>Componentes:</h3>
            <ul>
                @foreach ($desktop['componentes'] as $componente => $detalhe)
                    <li><strong>{{ $componente }}:</strong> {{ $detalhe }}</li>
                @endforeach
            </ul>

            <p><strong>Total:</strong> R$ {{ $desktop['total'] }}</p>
        </div>
@endforeach






@endsection