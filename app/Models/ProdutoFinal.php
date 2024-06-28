<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoFinal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'categoria'
    ];
    protected $table = 'produto_final';
    
    /*public function produtos() -- Mateus 28/06/2024 desvinculando de produtos
    {
        return $this->belongsToMany(Produto::class, 'produto_final_produto', 'produto_final_id', 'produto_id');
    }*/

    public function softwares()
    {
        return $this->belongsToMany(Software::class, 'produto_final_software', 'produto_final_id', 'software_id');
    }
}
