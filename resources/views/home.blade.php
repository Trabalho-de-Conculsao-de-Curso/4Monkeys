<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seleção de Softwares</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">Seleção de Softwares</h1>

        <form action="{{ route('home.selecionar') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            <h2 class="text-2xl font-semibold mb-4">Selecione os Softwares Desejados:</h2>
            <div class="mb-6">
                @foreach($softwares as $software)
                    <div class="flex items-center mb-2">
                        <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                        <label for="software{{ $software->id }}" class="ml-2 text-lg">{{ $software->nome }}</label>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-300">Selecionar Softwares</button>
        </form>
    </div>
</body>
</html>
