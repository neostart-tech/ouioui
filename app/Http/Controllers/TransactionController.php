<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(){
        $transactions = Transaction::with(['programme', 'programme.cour', 'programme.eleve','programme.eleve.user'])
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json([
            "error" => false,
            "message" => "La liste des programmes",
            "transactions" =>$transactions
        ]);
    }
}
