<?php

namespace Database\Factories;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LojaOnline extends Migration
{
    public function up(): void
    {
        Schema::create('loja_online', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('urlLoja');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loja_online');
    }
}
