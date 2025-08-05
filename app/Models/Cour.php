<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cour extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'photo',
        'description',
        'duree',
        'prix',
        'type_cours',
        'matiere_id',
        'status',
        'classe_id',

    ];



    public function classe(){
        return $this->belongsTo(classe::class ,'classe_id');
    }

    public function matiere(){
        return $this->belongsTo(Matiere::class ,'matiere_id');
    }

    public function programmes()
    {
        return $this->hasMany(Programme::class, 'cour_id');
    }

    public function cour_agences()
    {
        return $this->hasMany(CourAgence::class, 'cour_id');
    }
    public function prof_cours()
    {
        return $this->hasMany(Profcour::class, 'cour_id');
    }
    public function user(){
        return $this->belongsTo(User::class ,'user_id');
    }
}
