<?php

namespace Database\Seeders;

use App\Models\Marca;
use App\Models\Preco;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrecoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $precos = [
            ['valor' => 332400, 'moeda' => 'BRL'], // AMD Threadripper 1900X 1
            ['valor' => 58220, 'moeda' => 'BRL'], // AMD Ryzen 5 5500 2
            ['valor' => 83160, 'moeda' => 'BRL'], // AMD Ryzen 5 5600G 3
            ['valor' => 112000, 'moeda' => 'BRL'], // AMD Ryzen 7 5700X 4
            ['valor' => 78000, 'moeda' => 'BRL'], // AMD Ryzen 5 5600 5
            ['valor' => 16452, 'moeda' => 'BRL'], // Intel Core i5-3470 6
            ['valor' => 179999, 'moeda' => 'BRL'], // Placa-mãe ASUS PRIME X399-A E-ATX 1
            ['valor' => 35999, 'moeda' => 'BRL'], // Placa Mãe MSI B450M-A Pro Max 2
            ['valor' => 74970, 'moeda' => 'BRL'], // Placa Mãe Gigabyte B550M AORUS Elite, Chipset B550 3
            ['valor' => 599,99, 'moeda' => 'BRL'], // Placa Mãe SuperFrame B450M Legendary Series Chipset B450 4
            ['valor' => 44999, 'moeda' => 'BRL'], // PLACA MAE GIGABYTE A520M DS3H V2 5
            ['valor' => 34125, 'moeda' => 'BRL'], //Gigabyte GA-B75M-D3H 6
            ['valor' => 332400, 'moeda' => 'BRL'], //
            ['valor' => 332400, 'moeda' => 'BRL'], //
            ['valor' => 332400, 'moeda' => 'BRL'], //
            ['valor' => 332400, 'moeda' => 'BRL'], //
            ['valor' => 332400, 'moeda' => 'BRL'], //
            ['valor' => 332400, 'moeda' => 'BRL'], //
            ['valor' => 332400, 'moeda' => 'BRL'], //
            ['valor' => 332400, 'moeda' => 'BRL'], //
        ];

        foreach ($precos as $preco)
            Preco::create($preco);
    }
}
