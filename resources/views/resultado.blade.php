@php use App\Helpers\NumberHelper; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados dos Produtos Finais</title>
    <!-- Adicione o CSS do Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function toggleDetails(id) {
            var details = document.getElementById('details-' + id);
            if (details.style.display === 'none') {
                details.style.display = 'block';
            } else {
                details.style.display = 'none';
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-900">

<h1 class="text-3xl font-bold mb-8 text-center">Resultados dos Produtos Finais</h1>
<div class="container mx-auto py-8 grid grid-cols-3">


    @foreach ($produtoFinals as $produtoFinal)
        <div>
            <h2>{{ $produtoFinal->nome }}</h2>
            <p>Categoria: {{ ucfirst($produtoFinal->categoria) }}</p>
            <p>Preço Total: R$ {{ number_format($produtoFinal->preco_total, 2, ',', '.') }}</p>

            <h3>Componentes</h3>
            <ul>
                @foreach ($produtoFinal->produtos as $produto)
                    <li>{{ $produto->nome }} - {{ $produto->especificacoes->detalhes }}</li>
                @endforeach
            </ul>

        </div>
    @endforeach

</div>

</body>
</html>
