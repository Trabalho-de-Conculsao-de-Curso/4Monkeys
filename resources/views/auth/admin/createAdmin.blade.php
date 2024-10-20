@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-200 dark:bg-gray-900">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
        <form method="POST" action="{{ url('/login') }}" class="space-y-6">
            {{ csrf_field() }}
            <div class="flex justify-center">
                <img src="{{ url('/resources/assets/img/icon_avatar.png') }}" alt="Avatar" class="w-24 h-24 mb-4">
            </div>

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="border-2 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" placeholder="Email">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                </div>
                @if ($errors->has('email'))
                    <span class="text-red-600 text-sm mt-1">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <!-- Password Field -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Senha</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="password" id="password" name="password" required class="border-2 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" placeholder="Senha">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>
                @if ($errors->has('password'))
                    <span class="text-red-600 text-sm mt-1">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Entrar
                </button>
            </div>

            <!-- Register Button -->
            <div>
                <a href="{{ url('/register') }}" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded text-center block mt-2">
                    Registrar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
