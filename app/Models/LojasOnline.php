<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LojasOnline extends Model
{
    use HasFactory;

    protected $table = 'LojasOnline';
    protected $fillable = [
        'id',
        'loja',
        'url',
        'disponibilidade',
        'created_at',
        'updated_at',
    ];

    public function produto(){
        return $this->hasMany(Produto::class);
    }
}