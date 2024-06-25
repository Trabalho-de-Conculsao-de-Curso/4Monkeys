<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados dos Produtos Finais</title>
</head>
<body>
<h1>Resultados dos Produtos Finais</h1>


    @foreach($produtoFinals as $produtoFinal)
        <div>
            <h2>{{ $produtoFinal->nome }} - Categoria: {{ $produtoFinal->categoria }}</h2>
            <p>Preço Total: R$ {{ number_format($produtoFinal->preco_total, 2, ',', '.') }}</p>
            <p>CPU: {{ $produtoFinal->cpu }}</p>
            <p>GPU: {{ $produtoFinal->gpu }}</p>
            <p>RAM: {{ $produtoFinal->ram }}</p>
            <p>HDD: {{ $produtoFinal->hdd }}</p>
            <p>Fonte: {{ $produtoFinal->fonte }}</p>
            <p>Placa Mãe: {{ $produtoFinal->placa_mae }}</p>
            <p>Cooler: {{ $produtoFinal->cooler }}</p>
        </div>
        <hr>
    @endforeach

</body>
</html>
