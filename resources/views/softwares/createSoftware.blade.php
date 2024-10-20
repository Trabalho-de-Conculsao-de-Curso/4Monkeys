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
                <h2 class="text-dark pb-2 fw-bold">Softwares</h2>
                <h5 class="text-dark op-7 mb-2">Gerenciamento de Softwares</h5>
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
                        <h4 class="card-title ">Cadastro de Softwares</h4>
                    </div>
                </div>
                <form action="/softwares" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <h1 class="text-xl font-bold mb-4 ml-4">Adicionar Software</h1>

                    <div class="form-group">
                        <label for="tipo">Tipo do Software: 1 - Jogo, 2 - Trabalho, 3 - Utilitários</label>
                        <br/>
                        <input class="form-control" type="text" name="tipo" id="tipo" required>
                    </div>

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <br/>
                        <input class="form-control" type="text" name="nome" id="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <br/>
                        <input class="form-control" type="text" name="descricao" id="descricao">
                    </div>

                    <div class="form-group">
                        <label for="peso">Peso</label>
                        <br/>
                        <input class="form-control" type="number" name="peso" id="peso">
                    </div>

                    <div class="form-group">
                        <label for="software_imagem">Upload de Imagem</label>
                        <br/>
                        <input class="form-control" type="file" name="software_imagem" id="software_imagem" required>
                        @error('software_imagem')
                            <div class="text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <h3>Requisitos Mínimos</h3>
                    <div class="form-group">
                        <label for="cpu_min">CPU</label>
                        <br/>
                        <input class="form-control" type="text" name="cpu_min" id="cpu_min" required>
                    </div>
                    <div class="form-group">
                        <label for="gpu_min">GPU</label>
                        <br/>
                        <input class="form-control" type="text" name="gpu_min" id="gpu_min" required>
                    </div>
                    <div class="form-group">
                        <label for="ram_min">RAM</label>
                        <br/>
                        <input class="form-control" type="text" name="ram_min" id="ram_min" required>
                    </div>
                    <div class="form-group">
                        <label for="placa_mae_min">Placa Mãe</label>
                        <br/>
                        <input class="form-control" type="text" name="placa_mae_min" id="placa_mae_min">
                    </div>
                    <div class="form-group">
                        <label for="ssd_min">SSD</label>
                        <br/>
                        <input class="form-control" type="text" name="ssd_min" id="ssd_min">
                    </div>
                    <div class="form-group">
                        <label for="cooler_min">Cooler</label>
                        <br/>
                        <input class="form-control" type="text" name="cooler_min" id="cooler_min">
                    </div>
                    <div class="form-group">
                        <label for="fonte_min">Fonte</label>
                        <br/>
                        <input class="form-control" type="text" name="fonte_min" id="fonte_min">
                    </div>

                    <h3>Requisitos Médios</h3>
                    <div class="form-group">
                        <label for="cpu_med">CPU</label>
                        <br/>
                        <input class="form-control" type="text" name="cpu_med" id="cpu_med" required>
                    </div>
                    <div class="form-group">
                        <label for="gpu_med">GPU</label>
                        <br/>
                        <input class="form-control" type="text" name="gpu_med" id="gpu_med" required>
                    </div>
                    <div class="form-group">
                        <label for="ram_med">RAM</label>
                        <br/>
                        <input class="form-control" type="text" name="ram_med" id="ram_med" required>
                    </div>
                    <div class="form-group">
                        <label for="placa_mae_med">Placa Mãe</label>
                        <br/>
                        <input class="form-control" type="text" name="placa_mae_med" id="placa_mae_med">
                    </div>
                    <div class="form-group">
                        <label for="ssd_med">SSD</label>
                        <br/>
                        <input class="form-control" type="text" name="ssd_med" id="ssd_med">
                    </div>
                    <div class="form-group">
                        <label for="cooler_med">Cooler</label>
                        <br/>
                        <input class="form-control" type="text" name="cooler_med" id="cooler_med">
                    </div>
                    <div class="form-group">
                        <label for="fonte_med">Fonte</label>
                        <br/>
                        <input class="form-control" type="text" name="fonte_med" id="fonte_med">
                    </div>

                    <h3>Requisitos Recomendados</h3>
                    <div class="form-group">
                        <label for="cpu_rec">CPU</label>
                        <br/>
                        <input class="form-control" type="text" name="cpu_rec" id="cpu_rec" required>
                    </div>
                    <div class="form-group">
                        <label for="gpu_rec">GPU</label>
                        <br/>
                        <input class="form-control" type="text" name="gpu_rec" id="gpu_rec" required>
                    </div>
                    <div class="form-group">
                        <label for="ram_rec">RAM</label>
                        <br/>
                        <input class="form-control" type="text" name="ram_rec" id="ram_rec" required>
                    </div>
                    <div class="form-group">
                        <label for="placa_mae_rec">Placa Mãe</label>
                        <br/>
                        <input class="form-control" type="text" name="placa_mae_rec" id="placa_mae_rec">
                    </div>
                    <div class="form-group">
                        <label for="ssd_rec">SSD</label>
                        <br/>
                        <input class="form-control" type="text" name="ssd_rec" id="ssd_rec">
                    </div>
                    <div class="form-group">
                        <label for="cooler_rec">Cooler</label>
                        <br/>
                        <input class="form-control" type="text" name="cooler_rec" id="cooler_rec">
                    </div>
                    <div class="form-group">
                        <label for="fonte_rec">Fonte</label>
                        <br/>
                        <input class="form-control" type="text" name="fonte_rec" id="fonte_rec">
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
