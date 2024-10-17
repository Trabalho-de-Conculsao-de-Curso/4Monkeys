@extends('padrao')

@section('titulo', 'Clientes')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <br/>
    @endif

    <div class="panel-header bg-dark-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Clientes</h2>
                    <h5 class="text-white op-7 mb-2">Gerenciamento de clientes</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Atualização de cadastro de clientes</h4>
                        </div>
                    </div>
                    <form method="post" action="{{ route('clientes.update', $cliente->id) }}">
                        @method('PATCH')
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id">Id</label>
                                <input type="text" readonly class="form-control" name="id" value={{ $cliente->id }} />
                            </div>
                            <div class="form-group col-md-10">
                                <label for="nome">Nome</label>
                                <input type="text" required class="form-control" name="nome" value="{{ $cliente->nome }}"/>
                            </div>
                            <div class="form-group">
                                <label for="cpf_cnpj">CPF/CNPJ</label>
                                <input type="text" required class="form-control" name="cpf_cnpj"
                                       value={{ $cliente->cpf_cnpj }} />
                            </div>
                            <div class="form-group">
                                <label for="contato">Contato</label>
                                <input type="text" required class="form-control" name="contato"
                                       value={{ $cliente->contato }} />
                            </div>
                            <div class="form-group">
                                <label for="celular">Celular</label>
                                <input type="text" required class="form-control" name="celular"
                                       value={{ $cliente->celular }} />
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" required class="form-control" name="email"
                                       value={{ $cliente->email }} />
                                </br>
                                <button type="submit" class="btn btn-primary">Atualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
