<h5>Avaliar</h5>
<form action="{{ route('avaliar.store') }}" method="POST">
    @csrf
    <div>
        <label for="rating">Avaliação</label>
        <div id="rating" class="star-rating">
            @for($i = 1; $i <= 5; $i++)
                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                <label for="star{{ $i }}" title="{{ $i }} estrela{{ $i > 1 ? 's' : '' }}">&#9733;</label>
            @endfor
        </div>
    </div>
    <div>
        <label for="comentario">Comentário (opcional)</label>
        <textarea name="comentario" id="comentario" rows="3" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Enviar Avaliação</button>
</form>
