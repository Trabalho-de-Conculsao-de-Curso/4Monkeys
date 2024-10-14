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
                <h1 class="text-3xl font-bold mb-0">Encontre o Hardware Ideal para Seus Softwares</h1>
                <h1 class="text-3xl mb-10 font-bold bg-gradient-to-r from-blue-600 via-indigo-800 via-purple-700 to-purple-900 text-transparent bg-clip-text">
                Conectando Tecnologia com Precisão
                </h1>
                <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-6">
                    <div class="flex justify-start items-center border-2 rounded-md border-purple-700">
                        <img src="{{ asset('images/csgo2.png') }}" alt="Descrição da imagem" class="w-8 h-8 object-cover">
                        <span class="ml-2">Counter Strike</span>
                    </div>
                    <div class="flex justify-start items-center border-2 rounded-md border-purple-700">
                        <img src="{{ asset('images/pubg.jpg') }}" alt="Descrição da imagem" class="w-12 h-8 object-cover">
                        <span class="ml-5">PUBG</span>
                    </div>
                    <div class="flex justify-start items-center border-2 rounded-md border-purple-700">
                        <img src="{{ asset('images/valorant.png') }}" alt="Descrição da imagem" class="w-12 h-8 object-cover">
                        <span class="ml-5">Valorant</span>
                    </div>
                    <div class="flex justify-start items-center border-2 rounded-md border-purple-700">
                        <img src="{{ asset('images/rockstar.jpg') }}" alt="Descrição da imagem" class="w-12 h-8 object-cover rounded-sm">
                        <span class="ml-5">GTA V</span>
                    </div>
                    <div class="flex justify-start items-center border-2 rounded-md border-purple-700">
                        <img src="{{ asset('images/minecraft.jpg') }}" alt="Descrição da imagem" class="w-12 h-8 object-cover">
                        <span class="ml-4">Minecraft</span>
                    </div>
                    <div class="flex justify-start items-center border-2 rounded-md border-purple-700">
                        <img src="{{ asset('images/vs-code-logo.png') }}" alt="Descrição da imagem" class="w-12 h-8 object-cover">
                        <span class="ml-4">VS Code</span>
                    </div>
                </div>
                    <div>
                        <form id="software-selection-form" action="{{ auth()->check() ? route('free.selecionar') : route('free.selecionar') }}" method="POST" class="bg-zinc-800 relative rounded-lg shadow p-6 border border-gray-600" onsubmit="validateForm(event)">
                            @csrf
                            <h2 class="text-2xl font-semibold mb-4">Selecione os Softwares Desejados:</h2>
                            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Coluna de Jogos -->
                                <div>
                                    <h3 class="text-xl font-semibold mb-4">Jogos</h3>
                                    @foreach($softwares as $software)
                                        @if($software->tipo == 1)
                                            <div class="flex items-center mb-2">
                                                <div>
                                                    <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="form-checkbox h-5 w-5 text-blue-400">
                                                    <label for="software{{ $software->id }}" class="ml-2 text-lg">{{ $software->nome }}</label>
                                                    <button type="button" onclick="toggleDetails({{ $software->id }})" class="text-blue-400 hover:underline">Ler Mais</button>
                                                    <div id="details-{{ $software->id }}" class="text-sm text-gray-400 mt-2" style="display: none;">
                                                        {{ $software->descricao }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <!-- Coluna de Trabalho -->
                                <div>
                                    <h3 class="text-xl font-semibold mb-4">Trabalho</h3>
                                    @foreach($softwares as $software)
                                        @if($software->tipo == 2)
                                            <div class="flex items-center mb-2">
                                                <div>
                                                    <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="form-checkbox h-5 w-5 text-blue-400">
                                                    <label for="software{{ $software->id }}" class="ml-2 text-lg">{{ $software->nome }}</label>
                                                    <button type="button" onclick="toggleDetails({{ $software->id }})" class="text-blue-400 hover:underline">Ler Mais</button>
                                                    <div id="details-{{ $software->id }}" class="text-sm text-gray-400 mt-2" style="display: none;">
                                                        {{ $software->descricao }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <!-- Coluna de Dispositivos -->
                                <div>
                                    <h3 class="text-xl font-semibold mb-4">Dispositivos</h3>
                                    @foreach($softwares as $software)
                                        @if($software->tipo == 3)
                                        <div class="flex items-center mb-2">
                                            <div>
                                                <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}" class="form-checkbox h-5 w-5 text-blue-400">
                                                <label for="software{{ $software->id }}" class="ml-2 text-lg">{{ $software->nome }}</label>
                                                <button type="button" onclick="toggleDetails({{ $software->id }})" class="text-blue-400 hover:underline">Ler Mais</button>
                                                <div id="details-{{ $software->id }}" class="text-sm text-gray-400 mt-2" style="display: none;">
                                                    {{ $software->descricao }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <!-- Botão de Submissão -->
                            <button type="submit" class="bg-purple-800 text-white py-2 px-4 rounded hover:bg-purple-600 transition duration-300">Selecionar Softwares</button>

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
{{--
    <script>
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




    </script> --}}
</body>
</html>
