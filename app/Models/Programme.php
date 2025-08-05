<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cour_id',
        'eleve_id',
        'status',

    ];

    public function user(){
        return $this->belongsTo(User::class ,'user_id');
    }

    public function cour(){
        return $this->belongsTo(Cour::class ,'cour_id');
    }

    public function eleve(){
        return $this->belongsTo(Eleve::class ,'eleve_id')->with('user');
    }

    public function calendriers()
    {
        return $this->hasMany(Calendrier::class, 'prog_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'prog_id');
    }
}
