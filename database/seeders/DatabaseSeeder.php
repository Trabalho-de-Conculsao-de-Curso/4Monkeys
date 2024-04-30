<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Produto;
use App\Models\LojasOnlines;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Produto::factory()->create();
    }
}
