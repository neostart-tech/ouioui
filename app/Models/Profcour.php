<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profcour extends Model
{
    use HasFactory;
    protected $fillable = [
        'libelle',
        'user_id',
        'cour_id',
    ];

    public function user(){
        return $this->belongsTo(User::class ,'user_id');
    }

    public function cour(){
        return $this->belongsTo(Cour::class ,'cour_id');
    }
}
