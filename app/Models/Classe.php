<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];

    public function cours()
    {
        return $this->hasMany(Cour::class, 'classe_id');
    }

    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'classe_id');
    }
}
