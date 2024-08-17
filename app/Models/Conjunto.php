<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conjunto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'categoria_id',
        'preco_total',
    ];
    protected $table = 'conjunto';

    public function getPrecoTotalFormatado()
    {

        return number_format($this->preco_total, 2, ',', '.');
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'conjunto_produto', 'conjunto_id', 'produto_id');
    }
    public function softwares()
    {
        return $this->belongsToMany(Software::class, 'conjunto_software', 'conjunto_id', 'software_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');  // Relacionamento com Categoria
    }
}
