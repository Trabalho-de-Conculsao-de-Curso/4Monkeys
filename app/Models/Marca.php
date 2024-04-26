<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marcas';
    protected $fillable = [
        'nome',
        'qualidade',
        'garantia',
        'created_at',
        'updated_at',
    ];
    public static function boot()
    {
        parent::boot();

        // Deleta os produtos associados quando uma marca é excluída
        static::deleting(function($marca) {
            $marca->produto()->delete();
        });
    }
    public function produto(){
        return $this->hasMany(Produto::class);
    }
}
