<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preco extends Model
{
    use HasFactory;

    protected $table = 'precos';
    protected $fillable = [
        'valor',
        'moeda',
        'created_at',
        'updated_at',
    ];

    public function preco(){
        return $this->hasMany(Preco::class);
    }
}
