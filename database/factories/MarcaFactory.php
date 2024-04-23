<?php

namespace Database\Factories;

use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marca>
 */
class MarcaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Marca::class;
    public function definition(): array
    {

        return [
            'nome' => $this->faker->word(),
            'qualidade' => $this->faker->randomElement(['Alta', 'Média', 'Baixa']),
            'garantia' => $this->faker->randomElement(['1 ano', '2 anos', 'Sem garantia']),
        ];
    }
}
