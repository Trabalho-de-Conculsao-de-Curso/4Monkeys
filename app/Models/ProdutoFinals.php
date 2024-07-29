<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoFinals extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'categoria',
        'preco_total'
    ];
    protected $table = 'produto_finals';

    public function getPrecoTotalFormatado()
    {

        return number_format($this->preco_total, 2, ',', '.');
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'produto_final_produto', 'produto_final_id', 'produto_id');
    }
    public function softwares()
    {
        return $this->belongsToMany(Software::class, 'produto_final_software', 'produto_final_id', 'software_id');
    }
}
