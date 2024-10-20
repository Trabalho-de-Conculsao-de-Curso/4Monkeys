<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Softwares</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="softwares[]"]');

            // Desmarcar todos os checkboxes ao carregar a página
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });

            // Adicionar um listener para impedir a seleção de mais de 3
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedCount = document.querySelectorAll('input[name="softwares[]"]:checked').length;

                    if (checkedCount > 3) {
                        alert('Você só pode selecionar no máximo 3 softwares.');
                        checkbox.checked = false;  // Desmarca o checkbox que ultrapassou o limite
                    }
                });
            });
        });

        // Função para alternar o estado do card e o checkbox, mas impedindo ação se já houver 3 selecionados
        function toggleFillAndDetails(id, description) {
            const checkedCount = document.querySelectorAll('input[name="softwares[]"]:checked').length;

            // Verifica se já existem 3 selecionados antes de permitir preencher o card
            const checkbox = document.getElementById(`software${id}`);
            if (!checkbox.checked && checkedCount >= 3) {
                alert('Você só pode selecionar no máximo 3 softwares.');
                return;  // Sai da função sem preencher o card ou exibir detalhes
            }

            // Lógica de preenchimento do card
            toggleFillCard(id);

            // Lógica para exibir/ocultar detalhes do software com efeito de digitação
            toggleDetails(id, description);

            // Alterna o checkbox para marcado/desmarcado
            toggleCheckbox(id);
        }

        function toggleFillCard(id) {
            const card = document.getElementById(`checkboxDiv${id}`);
            card.classList.toggle('filled-card');
        }

        function toggleDetails(id, description) {
            const details = document.getElementById('details-' + id);
            if (details.style.display === 'none') {
                details.style.display = 'block';
                typeWriterEffect(`description-${id}`, description);
            } else {
                details.style.display = 'none';
            }
        }

        // Função para alternar o estado do checkbox
        function toggleCheckbox(id) {
            const checkbox = document.getElementById(`software${id}`);
            checkbox.checked = !checkbox.checked;  // Inverte o estado do checkbox
        }

        // Função para o efeito de digitação
        function typeWriterEffect(elementId, text, speed = 50) {
            const element = document.getElementById(elementId);
            element.innerHTML = '';  // Limpa o conteúdo existente antes de iniciar o efeito
            let i = 0;

            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);  // Define a velocidade de digitação
                }
            }

            type();  // Inicia a função de digitação
        }
    </script>

</head>

<body class="bg-zinc-900 text-gray-200 flex flex-col h-screen">
<!-- Cabeçalho -->
<header class="bg-zinc-900 text-gray-200 p-4 border-b-2 border-gray-600 flex items-center justify-between">
    <div class="flex items-center">
        <img src="{{ asset('images/macacoOculos.png') }}" alt="Descrição da imagem" class="w-18 h-16 rounded-full border-2 mr-4">
        <h1 class="text-2xl font-bold">4 Monkeys Setup</h1>
    </div>
    <button class="flex items-center bg-gray-600 font-bold text-white py-2 px-4 rounded hover:bg-purple-700 transition duration-300 border-2 border-purple-800">
        <span class="mr-2">ASSINAR AGORA</span>
        <img src="{{ asset('images/seta-direita.png') }}" alt="Descrição da imagem" class="w-10 h-8 object-cover ml-2">
    </button>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Concert+One&display=swap');

        .concert-one-regular {
            font-family: "Concert One", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
        .filled-card {
            background: rgb(249,69,226);
            background: linear-gradient(65deg, rgba(249,69,226,0.5606617647058824) 0%, rgba(148,187,233,0.0032387955182072714) 100%);
            transition: background-color 0.3s ease;
        }


    </style>
</header>

<!-- Conteúdo Principal -->
<div class="flex flex-1">
    <!-- Navbar -->
    <div class="bg-zinc-900 text-gray-200 w-64 p-4 border-r-2 border-gray-600 hidden md:block">
        <h2 class="text-2xl font-bold mb-6">Menu</h2>
        <ul>
            <li class="mb-4"><a href="#" class="text-lg hover:underline">Home</a></li>
            <li class="mb-4"><a href="#" class="text-lg hover:underline">Seleção de Softwares</a></li>
            <li class="mb-4"><a href="#" class="text-lg hover:underline">Configurações</a></li>
            <li class="mb-4"><a href="#" class="text-lg hover:underline">Ajuda</a></li>
        </ul>
    </div>

    <!-- Conteúdo Principal com ajuste para responsividade -->
    <div class="flex-1 container mx-auto py-8">
        <div class="block md:hidden mb-4">
            <button id="menu-btn" class="text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="bg-zinc-900 text-gray-200 p-4 border-r-2 border-gray-600 fixed top-0 left-0 w-64 h-full transform -translate-x-full md:hidden">
            <h2 class="text-2xl font-bold mb-6">Menu</h2>
            <ul>
                <li class="mb-4"><a href="#" class="text-lg hover:underline">Home</a></li>
                <li class="mb-4"><a href="#" class="text-lg hover:underline">Seleção de Softwares</a></li>
                <li class="mb-4"><a href="#" class="text-lg hover:underline">Configurações</a></li>
                <li class="mb-4"><a href="#" class="text-lg hover:underline">Ajuda</a></li>
            </ul>
        </div>

        <div class="flex flex-col md:flex-row">
            <div class="md:flex-1 ml-20 mr-20 md:ml-55 p-3">
                <h1 class="text-4xl font-bold mb-0">Encontre o Hardware Ideal para Seus Softwares</h1>
                <h1 class="concert-one-regular text-3xl mb-10 font-bold bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 text-transparent bg-clip-text">
                    Conectando Tecnologia com Precisão
                </h1>

                <div>
                    <form id="software-selection-form" action="{{ auth()->check() ? route('free.selecionar') : route('free.selecionar') }}" method="POST" class="bg-zinc-800 relative rounded-lg shadow p-6 border border-gray-600">
                        @csrf
                        <h2 class="concert-one-regular text-4xl font-semibold mb-4">
                            Selecione os softwares desejados:
                        </h2>

                        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Coluna de Jogos -->
                            <div>
                                <h3 class="concert-one-regular text-center text-4xl font-bold mb-6 bg-gradient-to-r from-purple-200 via-pink-200 to-blue-100 text-transparent bg-clip-text animate-fade-in">
                                    Jogos
                                </h3>
                                @foreach($softwares as $software)
                                    @if($software->tipo == 1)
                                        <div id="checkboxDiv{{ $software->id }}"
                                             class="flex justify-start items-center border-2 rounded-lg border-purple-700 mb-4 p-3 bg-transparent shadow-none hover:shadow-lg transition-shadow duration-300 transform hover:scale-105 cursor-pointer"
                                             onclick="toggleFillAndDetails({{ $software->id }}, '{{ addslashes($software->descricao) }}')">

                                            <img src="{{ asset('storage/' . $software->imagem) }}" alt="Imagem de {{ $software->nome }}" class="w-16 h-12 object-cover rounded-md transition-transform duration-300 hover:scale-110">

                                            <div class="relative ml-4">
                                                <!-- Checkbox oculto, mas funcional para seleção -->
                                                <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="hidden">

                                                <!-- Exibindo o nome do software -->
                                                <label for="software{{ $software->id }}" class="block text-lg font-semibold flex items-center space-x-2">
                                                    <span>{{ $software->nome }}</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div id="details-{{ $software->id }}" class="details mt-2" style="display: none;">
                                            <!-- Parágrafo com efeito de digitação -->
                                            <p id="description-{{ $software->id }}" class="text-1xl description"></p>
                                        </div>

                                    @endif
                                @endforeach
                            </div>
                            <!-- Coluna de Trabalho -->
                            <div>
                                <h3 class="concert-one-regular text-center text-4xl font-bold mb-6 bg-gradient-to-r from-purple-200 via-pink-200 to-blue-100 text-transparent bg-clip-text animate-fade-in">
                                    Trabalho
                                </h3>
                                @foreach($softwares as $software)
                                    @if($software->tipo == 2)
                                        <div id="checkboxDiv{{ $software->id }}"
                                             class="flex justify-start items-center border-2 rounded-lg border-purple-700 mb-4 p-3 bg-transparent shadow-none hover:shadow-lg transition-shadow duration-300 transform hover:scale-105 cursor-pointer"
                                             onclick="toggleFillAndDetails({{ $software->id }}, '{{ addslashes($software->descricao) }}')">

                                            <img src="{{ asset('storage/' . $software->imagem) }}" alt="Imagem de {{ $software->nome }}" class="w-16 h-12 object-cover rounded-md transition-transform duration-300 hover:scale-110">

                                            <div class="relative ml-4">
                                                <!-- Checkbox oculto, mas funcional para seleção -->
                                                <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="hidden">

                                                <!-- Exibindo o nome do software -->
                                                <label for="software{{ $software->id }}" class="block text-lg font-semibold flex items-center space-x-2">
                                                    <span>{{ $software->nome }}</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div id="details-{{ $software->id }}" class="details mt-2" style="display: none;">
                                            <!-- Parágrafo com efeito de digitação -->
                                            <p id="description-{{ $software->id }}" class="text-1xl description"></p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <!-- Coluna de Dispositivos -->
                            <div>
                                <h3 class="concert-one-regular text-center text-4xl font-bold mb-6 bg-gradient-to-r from-purple-200 via-pink-200 to-blue-100 text-transparent bg-clip-text animate-fade-in">
                                    Dispositivos
                                </h3>
                                @foreach($softwares as $software)
                                    @if($software->tipo == 3)
                                        <div id="checkboxDiv{{ $software->id }}"
                                             class="flex justify-start items-center border-2 rounded-lg border-purple-700 mb-4 p-3 bg-transparent shadow-none hover:shadow-lg transition-shadow duration-300 transform hover:scale-105 cursor-pointer"
                                             onclick="toggleFillAndDetails({{ $software->id }}, '{{ addslashes($software->descricao) }}')">

                                            <img src="{{ asset('storage/' . $software->imagem) }}" alt="Imagem de {{ $software->nome }}" class="w-16 h-12 object-cover rounded-md transition-transform duration-300 hover:scale-110">

                                            <div class="relative ml-4">
                                                <!-- Checkbox oculto, mas funcional para seleção -->
                                                <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="hidden">

                                                <!-- Exibindo o nome do software -->
                                                <label for="software{{ $software->id }}" class="block text-lg font-semibold flex items-center space-x-2">
                                                    <span>{{ $software->nome }}</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div id="details-{{ $software->id }}" class="details mt-2" style="display: none;">
                                            <!-- Parágrafo com efeito de digitação -->
                                            <p id="description-{{ $software->id }}" class="text-1xl description"></p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <!-- Botão de Submissão -->
                        <div class="flex justify-center mt-6">
                            <button type="submit" class="concert-one-regular bg-gradient-to-r from-purple-700 via-pink-600 to-rose-600 text-white py-4 px-8 text-2xl rounded-lg shadow-lg hover:bg-gradient-to-r hover:from-purple-600 hover:via-pink-500 hover:to-rose-500 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-purple-300">
                                Selecionar Softwares
                            </button>
                        </div>

                        <!-- Animação de Carregamento -->
                        <div id="loading-spinner" class="absolute inset-0 bg-zinc-800 flex flex-col items-center justify-center z-10 hidden rounded-sm">
                            <div class="inline-block w-42 h-36 mb-4">
                                <img src="{{ asset('images/gif-minecraft.gif') }}" alt="Descrição da imagem" class="object-cover w-42 h-36 rounded-sm">
                            </div>
                            <p class="text-white text-lg">Aguarde, seu setup está sendo montado...</p>
                        </div>
                    </form>
                    <div id="desktops-container"></div>
                </div>
            </div>
        </div>
    </div>

</div>

{{--<script>
        // Toggle mobile menu
        document.getElementById('menu-btn').addEventListener('click', function() {
            var menu = document.getElementById('mobile-menu');
            menu.classList.toggle('-translate-x-full');
        });

        document.getElementById('software-selection-form').addEventListener('submit', function(event) {
        event.preventDefault();

        var loadingSpinner = document.getElementById('loading-spinner');
        loadingSpinner.classList.remove('hidden');

        var formData = new FormData(this);

        fetch('{{ route("free.selecionar") }}', {
            method: 'POST',
            body: formData,
            /*headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Inclua o token CSRF se necessário
            }*/
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            loadingSpinner.classList.add('hidden');

            console.log('Dados recebidos:', data);
            alert("Softwares selecionados com sucesso!");

            if (data.desktops && Array.isArray(data.desktops)) {
                console.log('Desktops recebidos:', data.desktops);

                // Esconde o formulário
                var formContainer = document.getElementById('software-selection-form');
                formContainer.style.display = 'none';

                const desktopsContainer = document.getElementById('desktops-container');

                desktopsContainer.innerHTML = ''; // Limpa o conteúdo anterior

                data.desktops.forEach(desktop => {
                    const desktopItem = document.createElement('div');

                    desktopItem.classList.add('desktop-item', 'bg-zinc-800', 'p-4', 'rounded-lg', 'shadow', 'mb-4');

                    desktopItem.innerHTML = `
                        <h3 class="text-xl font-semibold mb-2">Categoria: ${desktop.categoria}</h3>
                        <p><strong>CPU:</strong> ${desktop.componentes.CPU}</p>
                        <p><strong>Cooler:</strong> ${desktop.componentes.Cooler}</p>
                        <p><strong>Fonte:</strong> ${desktop.componentes.Fonte}</p>
                        <p><strong>GPU:</strong> ${desktop.componentes.GPU}</p>
                        <p><strong>HD:</strong> ${desktop.componentes.HD}</p>
                        <p><strong>MOTHERBOARD:</strong> ${desktop.componentes.MOTHERBOARD}</p>
                        <p><strong>RAM:</strong> ${desktop.componentes.RAM}</p>
                        <div class="border-1">
                            <button type="submit" class="bg-purple-800 text-white py-2 px-4 rounded hover:bg-purple-600 transition duration-300">Assine o Premium</button>
                        </div>
                    `;

                    desktopsContainer.appendChild(desktopItem);

                });

                desktopsContainer.style.display = 'block'; // Assegura que o container esteja visível
                console.log('Desktops exibidos com sucesso.');
            } else {
                console.error('Dados recebidos não estão no formato esperado:', data);
            }
        })
        .catch(error => {
            loadingSpinner.classList.add('hidden');
            console.error('Erro:', error);
            alert("Ocorreu um erro ao selecionar os softwares.");
        });
    });




    </script>--}}
</body>
</html>
