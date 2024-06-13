<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resultados</title>
</head>
<body>
<h1>Resultados</h1>

@foreach($produtoFinals as $categoria => $produtoFinal)
    <h2>Produto Final {{ ucfirst($categoria) }}</h2>
    <h3>Peças:</h3>
    <ul>
        @foreach($produtoFinal->produtos as $produto)
            <li>{{ $produto->nome }} - {{ $produto->preco }}</li>
        @endforeach
    </ul>
    <h3>Softwares:</h3>
    <ul>
        @foreach($produtoFinal->softwares as $software)
            <li>{{ $software->nome }}</li>
        @endforeach
    </ul>
@endforeach
</body>
</html>
