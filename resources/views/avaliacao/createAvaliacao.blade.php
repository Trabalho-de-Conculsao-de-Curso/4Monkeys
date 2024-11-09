@extends('layouts.userpremium')

@section('content premium')
<div class="bg-white  rounded p-2 mt-3">
<div class="min-h-screen flex flex-col mt-4">
    <!-- Conteúdo Principal -->
    <div class="flex-grow">
        <h5 class="text-2xl font-semibold mb-4">Avaliar</h5>

<form action="{{ route('avaliar.store') }}" method="POST" class="space-y-4">
    @csrf

    <!-- Rating Section -->
    <div class="flex items-center space-x-2">
        <div id="rating" class="flex">
            @for($i = 1; $i <= 5; $i++)
                <!-- Radio buttons (hidden, but still functional) -->
                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="hidden star-radio">
                
                <!-- Labels styled as stars -->
                <label for="star{{ $i }}" title="{{ $i }} estrela{{ $i > 1 ? 's' : '' }}" 
                       class="cursor-pointer text-4xl text-gray-400 hover:text-yellow-400 transition-colors star-label" 
                       data-index="{{ $i }}">
                    &#9733;
                </label>
            @endfor
        </div>
    </div>

    <!-- Comentário Section -->
    <div>
        <label for="comentario" class="block text-lg font-medium text-gray-700">Comentário (opcional)</label>
        <textarea name="comentario" id="comentario" rows="4" class="mt-2 p-3 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 transition-colors" placeholder="Escreva aqui seu comentário..."></textarea>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">Enviar Avaliação</button>
</form>
    </div>
</div>
</div>

<script>
    const stars = document.querySelectorAll('.star-label');
    const radioButtons = document.querySelectorAll('.star-radio');

    let selectedRating = 0;

    // Update the star colors based on the selected rating
    function updateStars() {
        stars.forEach((star, index) => {
            if (index < selectedRating) {
                star.classList.remove('text-gray-400');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-400');
            }
        });
    }

    stars.forEach((star, index) => {
        star.addEventListener('mouseover', () => {
            // Show hover effect (highlight up to the hovered star)
            if (selectedRating === 0 || index >= selectedRating) {
                stars.forEach((s, i) => {
                    if (i <= index) {
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                    }
                });
            }
        });

        star.addEventListener('mouseout', () => {
            // Reset hover effect when mouse leaves
            if (selectedRating === 0) {
                updateStars();
            } else {
                updateStars();
            }
        });

        star.addEventListener('click', () => {
            // Toggle selection
            selectedRating = selectedRating === index + 1 ? 0 : index + 1;
            updateStars();
        });
    });

    // Initialize the stars to reflect the current selected rating
    updateStars();
</script>



@endsection
