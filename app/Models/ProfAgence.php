<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfAgence extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'user_id',
        'agence_id',
    ];

    public function user(){
        return $this->belongsTo(User::class ,'user_id');
    }

    public function agence(){
        return $this->belongsTo(Agence::class ,'agence_id');
    }
}
