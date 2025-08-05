<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendrier extends Model
{
    use HasFactory;
    protected $fillable = [
        'jour','heure_debut','heure_fin','status','prog_id'

    ];
    public function programme(){
        return $this->belongsTo(Programme::class ,'prog_id');
    }
}
