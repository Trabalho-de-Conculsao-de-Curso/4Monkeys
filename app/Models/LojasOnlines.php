<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produto;

class LojasOnlines extends Model
{
    use HasFactory;
// abaixo campos q serao preenchidos no banco 
    protected $table = 'lojasonlines';
    protected $fillable = [
        'id',
        'nome',
        'url',
        'created_at',
        'updated_at',
    ];

    public function produto(){
        return $this->hasMany(Produto::class);
    }
}