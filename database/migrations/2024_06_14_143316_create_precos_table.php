<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('database.default') === 'sqlite') {
            // Usando SQL bruto para criar a tabela no SQLite
            DB::statement('
                CREATE TABLE precos (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    valor REAL,
                    moeda VARCHAR(20),
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP
                )
            ');
        } else {
            // Usando o método Schema para outros bancos de dados
            Schema::create('precos', function (Blueprint $table) {
                $table->id();
                $table->float('valor');
                $table->string('moeda', 20);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precos');
    }
};
