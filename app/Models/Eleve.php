<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'photo',
        'dateNais',
        'sex',
        'classe_id',
        'user_id',

    ];

    public function classe(){
        return $this->belongsTo(Classe::class ,'classe_id');
    }
    public function agenceeleve(){
        return $this->belongsTo(agencecour::class, 'eleve_id');
    }
    
    public function user(){
        return $this->belongsTo(User::class ,'user_id');
    }
    public function programmes()
    {
        return $this->hasMany(Programme::class, 'eleve_id');
    }
}
