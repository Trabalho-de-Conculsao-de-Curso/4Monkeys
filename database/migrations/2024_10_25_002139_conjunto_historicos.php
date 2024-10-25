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
    { if(config('database.default') == 'sqlite'){
        DB::statement('
        CREATE TABLE conjunto_historicos(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        produto_id INTEGER,
        valor REAL,
        created_at TIMESTAMP,
        FOREIGN KEY (produto_id) REFERENCES produtos(id)
    )
');

    } else{
        Schema::create('conjunto_historicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos');
            $table->float('valor');
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conjunto_historicos');
    }
};
