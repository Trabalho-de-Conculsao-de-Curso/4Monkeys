<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'marca_id',
        'especificacoes',
        'preco',
        'lojasOnline',
        'created_at',
        'updated_at',
    ];

    public function marca(){
        return $this->belongsTo(Marca::class, 'marca_id');
    }
}