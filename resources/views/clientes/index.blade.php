@extends('padrao')

@section('titulo', 'Clientes')

@section('content')
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Lista de clientes</h4>
                            <button class="btn btn-primary btn-round ml-auto" data-toggle="modal"
                                    data-target="#addRowModal">
                                <i class="fa fa-plus"></i>
                                Cadastrar
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal -->
                        <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header no-bd">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold">Cliente</span>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div><br/>
                                        @endif
                                        <p class="small">Cadastre um novo cliente</p>
                                        <form method="post" action="{{ route('clientes.store') }}">
                                            @csrf
                                            <div class="col-md-10">
                                                <div class="form-group col-md-10">
                                                    <label for="nome">Nome</label>
                                                    <input type="text" required class="form-control" name="nome"/>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="cpf_cnpj">CPF/CNPJ</label>
                                                    <input type="text" required class="form-control" name="cpf_cnpj"/>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="contato">Contato</label>
                                                    <input type="text" required class="form-control" name="contato"/>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="celular">Celular</label>
                                                    <input type="text" required class="form-control" name="celular"/>
                                                </div>

                                                <div class="form-group col-md-10">
                                                    <label for="email">Email</label>
                                                    <input type="email" required class="form-control" name="email"/>
                                                </div>
                                                <div class="modal-footer no-bd">
                                                    <button type="submit" id="addRowButton" class="btn btn-primary">
                                                        Enviar
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                        Fechar
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- fim modal add -->
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nome</th>
                                    <th>CPF/CNPJ</th>
                                    <th>Contato</th>
                                    <th>Celular</th>
                                    <th>E-mail</th>
                                    <th class="text-center" style="width: 10%">Ações</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Nome</th>
                                    <th>CPF/CNPJ</th>
                                    <th>Contato</th>
                                    <th>Celular</th>
                                    <th>E-mail</th>
                                    <th class="text-center" style="width: 10%">Ações</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach($clientes as $cliente)
                                    <tr>
                                        <td>{{$cliente->id}}</td>
                                        <td>{{$cliente->nome}}</td>
                                        <td>{{$cliente->cpf_cnpj}}</td>
                                        <td>{{$cliente->contato}}</td>
                                        <td>{{$cliente->celular}}</td>
                                        <td>{{$cliente->email}}</td>
                                        <td>
                                            <div class="form-button-action">
                                                <a href="{{ route('clientes.edit', $cliente->id)}}"
                                                   data-toggle="tooltip"
                                                   title=""
                                                   class="btn btn-link btn-primary btn-lg"
                                                   data-original-title="Editar">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <form action="{{ route('clientes.destroy', $cliente->id)}}"
                                                      method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-link btn-danger btn-lg"
                                                            data-toggle="tooltip"
                                                            title=""
                                                            data-original-title="Excluir">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
