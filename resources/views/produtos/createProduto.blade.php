<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Crud das Entidades</title>
</head>
<body class="bg-gray-200 font-sans antialiased dark:bg-gray-900 dark:text-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

<div class="container mx-auto p-4">
    <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        <a href="/produtos">Home</a>
    </button>
    
    <form action="/produtos/" method="POST" class="max-w-lg mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h1 class="text-xl font-bold mb-4 text-center dark:text-white">Criar Produto</h1>

        <div class="mb-4">
            <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
            <input type="text" name="nome" id="nome" required class="border-2 form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
        </div>

        <h3 class="text-lg font-semibold mb-2 dark:text-white">Marca</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="marca_nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                <input type="text" name="marca_nome" id="marca_nome" required class="border-2 form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
            </div>
            <div>
                <label for="marca_qualidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Qualidade</label>
                <input type="text" name="marca_qualidade" id="marca_qualidade" required class="border-2 form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
            </div>
            <div>
                <label for="marca_garantia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Garantia</label>
                <input type="text" name="marca_garantia" id="marca_garantia" required class="border-2 form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
            </div>
        </div>

        <h3 class="text-lg font-semibold mt-6 mb-2 dark:text-white">Especificações</h3>
        <div class="mb-4">
            <label for="especificacoes_detalhes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Detalhes</label>
            <input type="text" name="especificacoes_detalhes" id="especificacoes_detalhes" required class="border-2 form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
        </div>

        <h3 class="text-lg font-semibold mb-2 dark:text-white">Preço</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="preco_valor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor</label>
                <input type="number" name="preco_valor" id="preco_valor" required class="border-2 form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
            </div>
            <div>
                <label for="preco_moeda" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Moeda</label>
                <input type="text" name="preco_moeda" id="preco_moeda" required class="border-2 form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
            </div>
        </div>

        <h3 class="text-lg font-semibold mt-6 mb-2 dark:text-white">Lojas Online</h3>
        <div class="mb-4">
            <label for="lojasOnline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome da Loja</label>
            <input type="text" name="lojasOnline" id="lojasOnline" required class="border-2 form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
        </div>

        <div class="mb-6">
            <label for="urlLojaOnline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL da Loja Online</label>
            <input type="text" name="urlLojaOnline" id="urlLojaOnline" required class="border-2 form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
        </div>

        <div class="flex justify-center">
            <input type="submit" value="Enviar" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
    </form>
</div>

</body>
</html>
