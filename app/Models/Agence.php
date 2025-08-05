<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom', 'ville', 'pays','quartier','user_id'
    ];

    public function cour_agences()
    {
        return $this->hasMany(CourAgence::class, 'agence_id');
    }

    public function prof_agences()
    {
        return $this->hasMany(ProfAgence::class, 'agence_id');
    }
    public function agenceeleve(){
        return $this->belongsTo(agencecour::class, 'agence_id');
    }
    public function user(){
        return $this->belongsTo(User::class ,'user_id');
    }
}
