<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourAgence extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'cour_id',
        'agence_id',
    ];

    public function cour(){
        return $this->belongsTo(Cour::class ,'cour_id');
    }

    public function agence(){
        return $this->belongsTo(Agence::class ,'agence_id');
    }
}
