<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produto;
<<<<<<< HEAD
use App\Models\LojasOnlines;

=======
use App\Models\Marca;
>>>>>>> d170fa60fde362a52b7237dafded019a6462d741

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Produto::class;
<<<<<<< HEAD
    
    public function definition(): array
    {

        $lojasOnlines = LojasOnlines::factory()->create();
        return [
            'nome' => $this->faker->word(),
            'marca' => $this->faker->company(),
            'especificacoes' => $this->faker->sentence(),
            'preco' => $this->faker->randomFloat(2, 10, 1000),
            'lojasOnline_id'=>$lojasOnlines->id,
        ];
    }
=======
    public function definition(): array
    {
        $marca = Marca::factory()->create();
        return [
            'nome' => $this->faker->word,
            'marca_id' => $marca->id,
            'especificacoes' => $this->faker->sentence(),
            'preco' => $this->faker->randomFloat(2, 10, 1000),
            'lojasOnline'=> json_encode(['Loja A', 'Loja B', 'Loja C']),
        ];
    }

>>>>>>> d170fa60fde362a52b7237dafded019a6462d741
}
