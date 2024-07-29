<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
    use HasFactory;

    protected $table = 'softwares';
    protected $fillable = [
        'nome',
        'tipo',
        'descricao',
        'requisitos'
    ];

    public function produtoFinais()
    {
        return $this->belongsToMany(ProdutoFinals::class, 'produto_final_software', 'software_id', 'produto_final_id');
    }
}
