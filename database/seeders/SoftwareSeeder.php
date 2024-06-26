<?php

namespace Database\Seeders;

use App\Models\Software;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoftwareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Software::create([
            'nome' => 'Valorant',
            'descricao' => 'Requisitos minimos para configurações bronze: CPU: Intel Core 2 Duo E8400, GPU: Intel HD 3000, RAM: 4 GB. Requisitos minimos para para configurações prata: CPU: Intel i3-4150, GPU: GeForce GT 730, RAM: 4 GB. Requisitos minimos para para configurações ouro: CPU: Intel i5-9400F 2.90GHz (ou equivalente), GPU: GTX, 1050 Ti (ou equivalente), RAM: 8 GB ',
        ]);

        Software::create([
            'nome' => 'PUBG: BATTLEGROUNDS',
            'descricao' => 'Requisitos minimos para 30 FPS:  CPU: Intel Core i5-4430 / AMD FX-6300, GPU: NVIDIA GeForce GTX 960 2GB / AMD Radeon R7 370 2GB, RAM: 8 GB. Requisitos minimos para 60 FPS: CPU: Intel Core i5-6600K / AMD Ryzen 5 1600, GPU: NVIDIA GeForce GTX 1060 3GB / AMD Radeon RX 580 4GB, RAM: 16 GB. Requisitos minimos para 120 FPS: CPU: Intel Core i7-8700K / AMD Ryzen 7 2700X, GPU: NVIDIA GeForce GTX 1080 Ti 4GB / AMD Radeon RX Vega 64 4GB ,RAM: 16 GB.',
        ]);

        Software::create([
            'nome' => 'ELDEN RING',
            'descricao' => 'Requisitos minimos para 30 FPS:  CPU: Intel Core i5-4430 / AMD FX-6300, GPU: NVIDIA GeForce GTX 960 2GB / AMD Radeon R7 370 2GB, RAM: 8 GB. Requisitos minimos para 60 FPS: CPU: Intel Core i5-6600K / AMD Ryzen 5 1600, GPU: NVIDIA GeForce GTX 1060 3GB / AMD Radeon RX 580 4GB, RAM: 16 GB. Requisitos minimos para 120 FPS: CPU: Intel Core i7-8700K / AMD Ryzen 7 2700X, GPU: NVIDIA GeForce GTX 1080 Ti 4GB / AMD Radeon RX Vega 64 4GB, RAM: 16 GB.',
        ]);

        Software::create([
            'nome' => 'Grand Theft Auto V',
            'descricao' => 'Requisitos minimos para 30 FPS:  CPU: Intel Core i5-4430 / AMD FX-6300, GPU: NVIDIA GeForce GTX 960 2GB / AMD Radeon R7 370 2GB, RAM: 8 GB. Requisitos minimos para 60 FPS: CPU: Intel Core i5-6600K / AMD Ryzen 5 1600, GPU: NVIDIA GeForce GTX 1060 3GB / AMD Radeon RX 580 4GB, RAM: 16 GB. Requisitos minimos para 120 FPS: CPU: Intel Core i7-8700K / AMD Ryzen 7 2700X, GPU: NVIDIA GeForce GTX 1080 Ti 4GB / AMD Radeon RX Vega 64 4GB, RAM: 16 GB',
        ]);

        Software::create([
            'nome' => 'Pacote Office',
            'descricao' => 'Requisitos minimos para 30 FPS:  CPU: Intel Core i3-2100 / AMD A6-3620, GPU: Intel HD Graphics 3000 / AMD Radeon HD 6450, RAM: 4 GB. Requisitos minimos para 60 FPS: CPU: Intel Core i5-2500K / AMD FX-6300, GPU: Intel HD Graphics 4000 / AMD Radeon R7 250, RAM: 8 GB. Requisitos minimos para 120 FPS: CPU: Intel Core i7-3770 / AMD Ryzen 5 1600, GPU: NVIDIA GeForce GTX 1050 Ti / AMD Radeon RX 560, RAM: 16 GB',
        ]);

        Software::create([
            'nome' => 'OBS',
            'descricao' => 'Requisitos minimos para 30 FPS: CPU: Intel Core i5-2500K / AMD FX-4300, GPU: NVIDIA GeForce GTX 960 / AMD Radeon R7 370, RAM: 8 GB. Requisitos minimos para 60 FPS: CPU: Intel Core i5-6600K / AMD Ryzen 5 1600, GPU: NVIDIA GeForce GTX 1060 / AMD Radeon RX 580, RAM: 16 GB. Requisitos minimos para 120 FPS: CPU: Intel Core i7-8700K / AMD Ryzen 7 2700X, GPU: NVIDIA GeForce GTX 1080 Ti / AMD Radeon RX Vega 64, RAM: 16 GB',
        ]);

        Software::create([
            'nome' => 'Adobe Photoshop',
            'descricao' => 'Requisitos minimos para 30 FPS: CPU: Intel Core i5-4460 / AMD FX-8120, GPU: NVIDIA GeForce GTX 1050 / AMD Radeon R7 250X, RAM: 8 GB. Requisitos minimos para 60 FPS: CPU: Intel Core i7-8700 / AMD Ryzen 5 3600, GPU: NVIDIA GeForce GTX 1660 / AMD Radeon RX 580, RAM: 16 GB. Requisitos minimos para 120 FPS: CPU: Intel Core i9-9900K / AMD Ryzen 7 3700X, GPU: NVIDIA GeForce RTX 2070 / AMD Radeon RX 5700 XT, RAM: 32 GB.',
        ]);

        Software::create([
            'nome' => 'Minecraft',
            'descricao' => 'Requisitos minimos para 30 FPS: CPU: Intel Core i3-3210 / AMD A8-7600 APU, GPU: Intel HD Graphics 4000 / AMD Radeon R5, RAM: 4 GB. Requisitos minimos para 60 FPS: CPU: Intel Core i5-4690 / AMD A10-7800 APU, GPU: NVIDIA GeForce 700 Series / AMD Radeon Rx 200 Series, RAM: 8 GB. Requisitos minimos para 120 FPS: CPU: Intel Core i7-8700K / AMD Ryzen 7 2700X, GPU: NVIDIA GeForce GTX 1070 / AMD Radeon RX Vega 56, RAM: 16 GB.',
        ]);

        Software::create([
            'nome' => 'PhpStorm',
            'descricao' => 'Requisitos minimos para 30 FPS: CPU: Intel Core i3-2100 / AMD FX-4100, GPU: Integrada (Intel HD Graphics 4000 / AMD Radeon HD 6450), RAM: 4 GB. Requisitos minimos para 60 FPS: CPU: Intel Core i5-6500 / AMD Ryzen 5 1500X, GPU: Integrada (Intel HD Graphics 530 / AMD Radeon R7), RAM: 8 GB. Requisitos minimos para 120 FPS:CPU: Intel Core i7-8700 / AMD  Ryzen 7 2700X, GPU: NVIDIA GeForce GTX 1050 / AMD Radeon RX 560, RAM: 16 GB.',
        ]);

        Software::create([
            'nome' => 'Visual Studio Code',
            'descricao' => 'Requisitos minimos para 30 FPS: CPU: Intel Core i3-2100 / AMD FX-4100, GPU: Integrada (Intel HD Graphics 4000 / AMD Radeon HD 6450), RAM: 4 GB. Requisitos minimos para 60 FPS: CPU: Intel Core i5-6500 / AMD Ryzen 5 1500X, GPU: Integrada (Intel HD Graphics 530 / AMD Radeon R7), RAM: 8 GB. Requisitos minimos para 120 FPS: CPU: Intel Core i7-8700 / AMD Ryzen 7 2700X, GPU: NVIDIA GeForce GTX 1050 / AMD Radeon RX 560, RAM: 16 GB.',
        ]);
    }
}
