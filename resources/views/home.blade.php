<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seleção de Softwares</title>
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

        function validateForm(event) {
            var checkboxes = document.querySelectorAll('input[name="softwares[]"]');
            var isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

            if (!isChecked) {
                event.preventDefault();
                alert("Por favor, selecione pelo menos um software.");
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">Seleção de Softwares</h1>

        <form action="{{ route('home.selecionar') }}" method="POST" class="bg-white rounded-lg shadow p-6" onsubmit="validateForm(event)">
            @csrf
            <h2 class="text-2xl font-semibold mb-4">Selecione os Softwares Desejados:</h2>
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="">
                    <h3 class="text-xl font-semibold mb-4 ">Jogos</h3>
                    @foreach($softwares as $software)
                        @if($software->tipo == 1)
                            <div class="flex items-center mb-2">
                                <div class="">
                                    <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                                    <label for="software{{ $software->id }}" class="ml-2 text-lg">{{ $software->nome }}</label>
                                    <button type="button" onclick="toggleDetails({{ $software->id }})" class="text-blue-500 hover:underline">Ler Mais</button>
                                    <div id="details-{{ $software->id }}" class="text-sm text-gray-600 mt-2" style="display: none;">
                                        {{ $software->descricao }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Trabalho</h3>
                    @foreach($softwares as $software)
                        @if($software->tipo == 2)
                            <div class="flex items-center mb-2">
                                <div class="">
                                    <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                                    <label for="software{{ $software->id }}" class="ml-2 text-lg">{{ $software->nome }}</label>
                                    <button type="button" onclick="toggleDetails({{ $software->id }})" class="text-blue-500 hover:underline">Ler Mais</button>
                                    <div id="details-{{ $software->id }}" class="text-sm text-gray-600 mt-2" style="display: none;">
                                            {{ $software->descricao }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Dispositivos</h3>
                    @foreach($softwares as $software)
                        @if($software->tipo == 3)
                        <div class="flex items-center mb-2">
                            <div>
                                <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                                <label for="software{{ $software->id }}" class="ml-2 text-lg">{{ $software->nome }}</label>
                                <button type="button" onclick="toggleDetails({{ $software->id }})" class="text-blue-500 hover:underline">Ler Mais</button>
                                <div id="details-{{ $software->id }}" class="text-sm text-gray-600 mt-2" style="display: none;">
                                        {{ $software->descricao }}
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-300">Selecionar Softwares</button>
        </form>
    </div>
</body>
</html>
