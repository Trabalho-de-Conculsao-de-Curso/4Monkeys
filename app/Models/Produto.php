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
        'especificacoes_id',
        'preco_id',
        'lojasOnline',
        'created_at',
        'updated_at',
    ];

    public function marca(){
        return $this->belongsTo(Marca::class);
    }

    public function especificacoes(){
        return $this->belongsTo(Especificacoes::class);
    }

    public function preco(){
        return $this->belongsTo(Preco::class);
    }

    public static function search($term)
    {
        return self::where('nome', 'like', '%' . $term . '%');
    }
}
