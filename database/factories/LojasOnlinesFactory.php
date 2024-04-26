<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LojasOnlines>
 */

class LojasOnlinesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = LojasOnline::class;
    public function definition(): array
    {
        return [
            'nome' => $this->faker->word(),
            'url' => $this->faker->sentence(),
          
        ];
    }
}
