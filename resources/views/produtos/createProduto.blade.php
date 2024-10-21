@extends('layouts.admin')

@section('titulo', 'Softwares')

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
                <h2 class="text-dark pb-2 fw-bold">Lista de Hardwares</h2>
                <h5 class="text-dark op-7 mb-2">Gerenciamento de Hardwares</h5>
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
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Cadastro de Produtos</h4>
                    </div>
                </div>

                <form action="/produtos/" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <h1 class="text-xl font-bold mb-4 ml-4">Criar Produto</h1>

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <br/>
                        <input type="text" name="nome" id="nome" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="disponibilidade">Disponibilidade</label>
                        <br/>
                        <input type="number" name="disponibilidade" id="disponibilidade" required class="form-control">
                    </div>

                    <h3 class="text-lg font-semibold mt-4">Preço</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="preco_valor">Valor</label>
                            <br/>
                            <input type="number" name="preco_valor" id="preco_valor" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="preco_moeda">Moeda</label>
                            <br/>
                            <input type="text" name="preco_moeda" id="preco_moeda" required class="form-control">
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mt-4">Loja Online</h3>
                    <div class="form-group">
                        <label for="urlLojaOnline">URL da Loja Online</label>
                        <br/>
                        <input type="text" name="urlLojaOnline" id="urlLojaOnline" required class="form-control">
                    </div>

                    <div class="form-group">
                        <input type="submit" value="Enviar" class="btn btn-primary" value="enviar">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
