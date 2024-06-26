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


        @foreach($produtoFinals as $produtoFinal)
            <div class="bg-white rounded-lg shadow p-6 mb-6 ml-2">
                <h2 class="text-2xl font-semibold mb-4">{{ $produtoFinal->nome }}</h2>
                <p class="text-lg mb-2"><span class="font-semibold">Preço Total:</span> R$ {{ number_format($produtoFinal->preco_total, 2, ',', '.') }}</p>
                <p class="text-lg mb-2"><span class="font-semibold">CPU:</span> {{ $produtoFinal->cpu }}</p>
                <p class="text-lg mb-2"><span class="font-semibold">GPU:</span> {{ $produtoFinal->gpu }}</p>
                <p class="text-lg mb-2"><span class="font-semibold">RAM:</span> {{ $produtoFinal->ram }}</p>
                <p class="text-lg mb-2"><span class="font-semibold">Armazenamento:</span> {{ $produtoFinal->hdd }}</p>
                <p class="text-lg mb-2"><span class="font-semibold">Fonte:</span> {{ $produtoFinal->fonte }}</p>
                <p class="text-lg mb-2"><span class="font-semibold">Placa Mãe:</span> {{ $produtoFinal->placa_mae }}</p>
                <p class="text-lg mb-2"><span class="font-semibold">Cooler:</span> {{ $produtoFinal->cooler }}</p>

            </div>
        @endforeach

    </div>

</body>
</html>
