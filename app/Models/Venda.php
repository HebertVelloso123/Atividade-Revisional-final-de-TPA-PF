<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    public function produtos() {
        return $this->belongsToMany(Produto::class)
                    ->withPivot('quantidade', 'preco_unitario')
                    ->withTimestamps();
    }
    
}


