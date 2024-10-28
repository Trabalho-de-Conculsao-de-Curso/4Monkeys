
@extends('layouts.admin')

@section('titulo', 'Softwares')

@section('content')

<div class="panel-header bg-dark-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-dark pb-2 fw-bold">Administradores</h2>
                <h5 class="text-dark op-7 mb-2">Gerenciamento de Admnistradores</h5>
            </div>
        </div>
    </div>
</div>
<div class="card mb-4">
</div>  
        <div class="page-inner mt--5">
            <div class="row">
                <div class="col-md-5">
                    <div class="card">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Criar Conta de Administrador</h4>
                        </div>
                        <form method="POST" action="/create-admin" class="user">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="name" name="name" required placeholder="Nome">
                            </div>
                            <hr>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="email" name="email" required placeholder="Email">
                            </div>
                            <hr>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" id="password" name="password" required placeholder="Senha">
                            </div>
                            <hr>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" id="password_confirmation" name="password_confirmation" required placeholder="Confirme a Senha">
                            </div>
                            <hr>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Registrar
                                </button>
                            </div>                            
                            <hr>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection


