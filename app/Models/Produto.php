<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use App\Models\LojasOnlines;
=======
>>>>>>> d170fa60fde362a52b7237dafded019a6462d741

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
<<<<<<< HEAD
        'marca',
        'especificacoes',
        'preco',
        'lojasOnline_id',
=======
        'marca_id',
        'especificacoes_id',
        'preco_id',
        'loja_online_id',
>>>>>>> d170fa60fde362a52b7237dafded019a6462d741
        'created_at',
        'updated_at',
    ];

<<<<<<< HEAD
    public function LojasOnline(){
        return $this->belongsTo(LojasOnlines::class, 'lojasOnline_id');
    }
}
=======
    public function marca(){
        return $this->belongsTo(Marca::class);
    }

    public function especificacoes(){
        return $this->belongsTo(Especificacoes::class);
    }

    public function preco(){
        return $this->belongsTo(Preco::class);
    }

    public function lojaOnline(){
        return $this->belongsTo(LojaOnline::class);
    }

    public function produtoFinais()
    {
        return $this->belongsToMany(ProdutoFinal::class, 'produto_final_produto', 'produto_id', 'produto_final_id');
    }

    public static function search($term)
    {
        return self::where('nome', 'like', '%' . $term . '%');
    }
}
>>>>>>> d170fa60fde362a52b7237dafded019a6462d741
