<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seleção de Softwares</title>
</head>
<body>
<h1>Seleção de Softwares</h1>

<form action="{{ route('home.selecionar') }}" method="POST">
    @csrf
    <h2>Selecione os Softwares Desejados:</h2>
    @foreach($softwares as $software)
        <input type="checkbox" id="software{{ $software->id }}" name="softwares[]" value="{{ $software->id }}">
        <label for="software{{ $software->id }}">{{ $software->nome }}</label>
        <br>
    @endforeach
    <button type="submit">Selecionar Softwares</button>
</form>
</body>
</html>
