


    <h1>Usuários</h1>
    <a href="{{ route('usuario-premium.create') }}">Criar Novo Usuário</a>
    <ul>
        @foreach($users as $user)
            <li>{{ $user->name }} - {{ $user->email }}


            </li>
        @endforeach
    </ul>


