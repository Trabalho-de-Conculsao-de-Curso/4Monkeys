<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'preco_id',
        'loja_online_id',
        'created_at',
        'updated_at',
    ];


    public function preco(){
        return $this->belongsTo(Preco::class);
    }

    public function lojaOnline(){
        return $this->belongsTo(LojaOnline::class);
    }

    public function produtoFinais()
    {
        return $this->belongsToMany(ProdutoFinals::class, 'produto_final_produto', 'produto_id', 'produto_final_id');
    }

    public static function search($term)
    {
        return self::where('nome', 'like', '%' . $term . '%');
    }
}
