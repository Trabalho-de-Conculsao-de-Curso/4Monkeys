@extends('padrao')

@section('titulo', 'Produtos')

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


<div>
    <div class="panel-header bg-dark-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Produtos</h2>
                    <h5 class="text-white op-7 mb-2">Gerenciamento de Produtos</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                <form action="{{ url("/produtos/$produto->id") }}" method="POST">
                    @method("PUT")
                    @csrf
                    <h1 class="text-xl font-bold mb-4 text-center">Editar Produto</h1>

                    <div class="form-group">
                        <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="nome" id="nome" value="{{$produto->nome}}" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="disponibilidade" class="block text-sm font-medium text-gray-700">Disponibilidade</label>
                        <input type="number" name="disponibilidade" id="disponibilidade" value="{{$produto->disponibilidade}}" required class="form-control">
                    </div>

                    <h3 class="text-lg font-semibold mb-2">Preço</h3>
                    <div class="form-group">
                        <div>
                            <label for="preco_valor" class="block text-sm font-medium text-gray-700">Valor</label>
                            <input type="text" name="preco_valor" id="preco_valor" value="{{$produto->lojaOnline->valor}}" required class="form-control">
                        </div>
                        <div>
                            <label for="preco_moeda" class="block text-sm font-medium text-gray-700">Moeda</label>
                            <input type="text" name="preco_moeda" id="preco_moeda" value="{{$produto->lojaOnline->moeda}}" required class="form-control"
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="urlLojaOnline" class="block text-sm font-medium text-gray-700">URL da Loja Online</label>
                        <input type="text" name="urlLojaOnline" id="urlLojaOnline" value="{{$produto->lojaOnline->urlLoja}}" required class="form-control">
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Editar">
                    </div>
                </form>
            </div>
            </div>
            </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
