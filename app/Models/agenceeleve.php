<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class agenceeleve extends Model
{
    use HasFactory;
    protected $fillable = [
        'agence_id','eleve_id'
    ];

    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'classe_id');
    }

    public function agences(){
        return $this->hasMany(Agence::class, 'agence_id');
    }
}
