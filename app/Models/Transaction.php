<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'status',
        'id_charge_stripe',
        'prog_id',

    ];

    public function programme(){
        return $this->belongsTo(Programme::class ,'prog_id');
    }
}
