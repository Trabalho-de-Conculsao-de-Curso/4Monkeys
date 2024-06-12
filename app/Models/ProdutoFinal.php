<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoFinal extends Model
{
    use HasFactory;

    protected $table = 'produto_final';

    protected $fillable = [
        'nome',
        'categoria'

    ];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'produto_final_produto');
    }

    public function softwares()
    {
        return $this->belongsToMany(Software::class, 'produto_final_software');
    }
}
