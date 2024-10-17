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
                    <h2 class="text-white pb-2 fw-bold">Softwares</h2>
                    <h5 class="text-white op-7 mb-2">Gerenciamento de Softwares</h5>
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
                            <h4 class="card-title">Atualização de cadastro de Softwares</h4>
                        </div>
                    </div>
                    <form action="{{ url("/softwares/$software->id") }}" method="POST" enctype="multipart/form-data">
                        @method("PUT")
                        @csrf
                        <h1 class="text-xl font-bold mb-4">Editar Software</h1>
        <div class="form-group">
            <label for="tipo">Tipo do Software: 1 - Jogo, 2 - Trabalho, 3 - Utilitários</label>
            <br/>
            <input class="form-control" type="text" class="form-control" name="tipo" id="tipo" value="{{$software->tipo}}" required>
        </div>

        <div class="form-group">
            <label for="nome">Nome</label>
            <br/>
            <input class="form-control" type="text" name="nome" id="nome" value="{{$software->nome}}" required>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <br/>
            <input class="form-control" type="text" name="descricao" id="descricao" value="{{$software->descricao}}">
        </div>

        <div class="form-group">
            <label for="peso">Peso</label>
            <br/>
            <input class="form-control" type="number" name="peso" id="peso" value="{{$software->peso}}">
        </div>

        <div class="form-group">
            <label for="software_imagem">Upload da Nova Imagem (opcional)</label>
            <br/>
            <input class="form-control" type="file" name="software_imagem" id="software_imagem">
        </div>

        <div class="form-group">
            @if($software->imagem)
                <input type="checkbox" name="remover_imagem" id="remover_imagem">
                <label for="remover_imagem">Remover imagem atual</label>
            @endif
        </div>

        <h3>Requisitos Mínimos</h3>
        <div class="form-group">
            <label for="cpu_min">CPU</label>
            <br/>
            <input class="form-control" type="text" name="cpu_min" id="cpu_min" value="{{ $requisitos['Minimo']['cpu'] }}" required>
        </div>
        <div class="form-group">
            <label for="gpu_min">GPU</label>
            <br/>
            <input class="form-control" type="text" name="gpu_min" id="gpu_min" value="{{ $requisitos['Minimo']['gpu'] }}" required>
        </div>
        <div class="form-group">
            <label for="ram_min">RAM</label>
            <br/>
            <input class="form-control" type="text" name="ram_min" id="ram_min" value="{{ $requisitos['Minimo']['ram'] }}" required>
        </div>
        <div class="form-group">
            <label for="placa_mae_min">Placa Mãe</label>
            <br/>
            <input class="form-control" type="text" name="placa_mae_min" id="placa_mae_min" value="{{ $requisitos['Minimo']['placa_mae'] }}">
        </div>
        <div class="form-group">
            <label for="ssd_min">SSD</label>
            <br/>
            <input class="form-control" type="text" name="ssd_min" id="ssd_min" value="{{ $requisitos['Minimo']['ssd'] }}">
        </div>
        <div class="form-group">
            <label for="cooler_min">Cooler</label>
            <br/>
            <input class="form-control" type="text" name="cooler_min" id="cooler_min" value="{{ $requisitos['Minimo']['cooler'] }}">
        </div>
        <div class="form-group">
            <label for="fonte_min">Fonte</label>
            <br/>
            <input class="form-control" type="text" name="fonte_min" id="fonte_min" value="{{ $requisitos['Minimo']['fonte'] }}">
        </div>

        <h3>Requisitos Médios</h3>
        <div class="form-group">
            <label for="cpu_med">CPU</label>
            <br/>
            <input class="form-control" type="text" name="cpu_med" id="cpu_med" value="{{ $requisitos['Medio']['cpu'] }}" required>
        </div>
        <div class="form-group">
            <label for="gpu_med">GPU</label>
            <br/>
            <input class="form-control" type="text" name="gpu_med" id="gpu_med" value="{{ $requisitos['Medio']['gpu'] }}" required>
        </div>
        <div class="form-group">
            <label for="ram_med">RAM</label>
            <br/>
            <input class="form-control" type="text" name="ram_med" id="ram_med" value="{{ $requisitos['Medio']['ram'] }}" required>
        </div>
        <div class="form-group">
            <label for="placa_mae_med">Placa Mãe</label>
            <br/>
            <input class="form-control" type="text" name="placa_mae_med" id="placa_mae_med" value="{{ $requisitos['Medio']['placa_mae'] }}">
        </div>
        <div class="form-group">
            <label for="ssd_med">SSD</label>
            <br/>
            <input class="form-control" type="text" name="ssd_med" id="ssd_med" value="{{ $requisitos['Medio']['ssd'] }}">
        </div>
        <div class="form-group">
            <label for="cooler_med">Cooler</label>
            <br/>
            <input class="form-control" type="text" name="cooler_med" id="cooler_med" value="{{ $requisitos['Medio']['cooler'] }}">
        </div>
        <div class="form-group">
            <label for="fonte_med">Fonte</label>
            <br/>
            <input class="form-control" type="text" name="fonte_med" id="fonte_med" value="{{ $requisitos['Medio']['fonte'] }}">
        </div>

        <h3>Requisitos Recomendados</h3>
        <div class="form-group">
            <label for="cpu_rec">CPU</label>
            <br/>
            <input class="form-control" type="text" name="cpu_rec" id="cpu_rec" value="{{ $requisitos['Recomendado']['cpu'] }}" required>
        </div>
        <div class="form-group">
            <label for="gpu_rec">GPU</label>
            <br/>
            <input class="form-control" type="text" name="gpu_rec" id="gpu_rec" value="{{ $requisitos['Recomendado']['gpu'] }}" required>
        </div>
        <div class="form-group">
            <label for="ram_rec">RAM</label>
            <br/>
            <input class="form-control" type="text" name="ram_rec" id="ram_rec" value="{{ $requisitos['Recomendado']['ram'] }}" required>
        </div>
        <div class="form-group">
            <label for="placa_mae_rec">Placa Mãe</label>
            <br/>
            <input class="form-control" type="text" name="placa_mae_rec" id="placa_mae_rec" value="{{ $requisitos['Recomendado']['placa_mae'] }}">
        </div>
        <div class="form-group">
            <label for="ssd_rec">SSD</label>
            <br/>
            <input class="form-control" type="text" name="ssd_rec" id="ssd_rec" value="{{ $requisitos['Recomendado']['ssd'] }}">
        </div>
        <div class="form-group">
            <label for="cooler_rec">Cooler</label>
            <br/>
            <input class="form-control" type="text" name="cooler_rec" id="cooler_rec" value="{{ $requisitos['Recomendado']['cooler'] }}">
        </div>
        <div class="form-group">
            <label for="fonte_rec">Fonte</label>
            <br/>
            <input class="form-control" type="text" name="fonte_rec" id="fonte_rec" value="{{ $requisitos['Recomendado']['fonte'] }}">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Editar">
        </div>
    </form>
</div>
@endsection
