<?php

namespace Database\Factories;
use App\Models\LojasOnlines;
use App\Models\Produto;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LojasOnlines>
 */

class LojasOnlinesFactory extends Factory
{
    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>
     */

    protected $model = LojasOnlines::class;
    public function definition(): array
    {
        return [
            'nome' => $this->faker->word(),
            'url' => $this->faker->sentence(),
          
        ];
    }
}
