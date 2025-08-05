<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use App\Mail\sendMail;
use Exception;
use Illuminate\Support\Facades\Mail as FacadesMail;

class MailController extends Controller
{
    public function index(Request $request){

        $comb = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $shfl = str_shuffle($comb);
        $code = substr($shfl,0,6);

        $data = [
            'subject' => 'oui-oui Academy',
            'body' => $code
        ];

        $email = $request->user_email;

        try {
            FacadesMail::to($email)->send(new sendMail($data));
            return response()->json(['success'=>'true' , 'message'=>'Envoi reussi' , 'code' => $code],200);
        } catch (Exception $th) {
            return response()->json(['success'=>'false' ,'message' => 'Erreur d envoi'], 500);
        }
    }
}
