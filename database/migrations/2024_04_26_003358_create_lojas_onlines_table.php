<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Produto;

return new class extends Migration
{
    /**
     * Executa as migrações.
     */
    public function up(): void
    {
        Schema::create('lojas_onlines', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('url');
            $table->timestamps();



        });
    }

    /**
     * Reverter as migrações.
     */
    public function down(): void
    {
        Schema::dropIfExists('lojas_onlines');
    }
};
