<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LojasOnlines;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'marca',
        'especificacoes',
        'preco',
        'lojasOnline_id',
        'created_at',
        'updated_at',
    ];

    public function LojasOnline(){
        return $this->belongsTo(LojasOnlines::class, 'lojasOnline_id');
    }
}