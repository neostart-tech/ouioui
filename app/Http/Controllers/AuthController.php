<?php

namespace App\Http\Controllers;

use App\Mail\sendMail;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    // methode d'inscription
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'phone_number' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
//            'password' => 'required|string|min:8|confirmed',
//            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->getMessageBag(),
                'status_code' => 500
            ]);
        }

        $utilisateur = new User;
        $utilisateur->firstname = $request->firstname;
        $utilisateur->lastname = $request->lastname;
        $utilisateur->phone_number = $request->phone_number;
        $utilisateur->sex = $request->sex;
        $utilisateur->type_user = $request->type_user;
        $utilisateur->email = $request->email;
        $utilisateur->photo = "images/profile-photo.jpg";

        $password = Str::random(10);
        $utilisateur->password = Hash::make($password);
        $utilisateur->type_user = $request->type_user;
        $utilisateur->email_verified_at = now();
        $res = $utilisateur->save();

        $token = Auth::login($utilisateur);

        $data = [
            'subject' => 'oui-oui Academy',
            'body' => $password
        ];

        if ($res) {
            try {
                Mail::to($request->email)->send(new sendMail($data));
                return response()->json([
                    'error' => false,
                    'message' => 'User save successfuly',
                    'user' => $utilisateur
                ], 200);
            } catch (Exception $th) {
                return response()->json([
                    'error' => false,
                    'message' => 'user save successfuly',
                    'user' => $utilisateur,
                    'status_code' => 200
                ]);
            }
        }

//        if($res){
//            return response()->json([
//                'message'=>'Utilisateur creation reussi',
//                'status_code' => 200,
//                'utilisateur'=> $utilisateur
//                ]);
//        }else{
//            return response()->json([
//                'message'=>'erreur',
//                'status_code' => 500
//                ]);
//        }
    }

    // methode de connexion
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password,$user->password)){
            return response([
                'error'=> true,
                'status'=> 400,
                'message' => 'Utilisateur introuvable !'
            ],401);
        }

        $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'authoriser',
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);

        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
    }

}
