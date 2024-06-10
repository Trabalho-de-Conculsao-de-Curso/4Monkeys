<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especificacoes extends Model
{
    use HasFactory;

    protected $table = 'especificacoes';

    protected $fillable = [
        'detalhes',
        'created_at',
        'updated_at',
    ];

    public function produto(){
        return $this->hasMany(Produto::class);
    }
}


