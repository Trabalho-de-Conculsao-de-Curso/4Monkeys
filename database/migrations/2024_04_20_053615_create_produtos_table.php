<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('marca_id')->nullable()->constrained()->unique('marcas')->onDelete('cascade');
            $table->foreignId('especificacoes_id')->nullable()->constrained('especificacoes')->unique()->onDelete('cascade');
            $table->foreignId('preco_id')->nullable()->constrained('precos')->unique()->onDelete('cascade');
            $table->foreignId('loja_online_id')->nullable()->constrained('loja_online')->unique()->onDelete('cascade');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
