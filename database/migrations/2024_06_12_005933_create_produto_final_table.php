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
                CREATE TABLE produto_final (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    nome VARCHAR(255),
                    categoria VARCHAR(255),
                    preco_total REAL,
                    cpu VARCHAR(255),
                    gpu VARCHAR(255),
                    ram VARCHAR(255),
                    hdd VARCHAR(255),
                    fonte VARCHAR(255),
                    placa_mae VARCHAR(255),
                    cooler VARCHAR(255),
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP
                )
            ');
        } else {
            // Usando o método Schema para outros bancos de dados
            Schema::create('produto_final', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('categoria');
                $table->float('preco_total');
                $table->string('cpu');
                $table->string('gpu');
                $table->string('ram');
                $table->string('hdd');
                $table->string('fonte');
                $table->string('placa_mae');
                $table->string('cooler');
                $table->timestamps();
            });
        }
    }

       /* Schema::create('produto_final_produto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_final_id')->constrained()->onDelete('cascade');
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });*/

        /*Schema::create('produto_final_software', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_final_id')->constrained()->onDelete('cascade');
            $table->foreignId('software_id')->constrained('softwares')->onDelete('cascade');
            $table->timestamps();
        });*/


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto_final_software');
        //Schema::dropIfExists('produto_final_produto');
        Schema::dropIfExists('produto_final');
    }
};
