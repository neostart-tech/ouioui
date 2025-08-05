<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgetMail;
use Exception;
use Illuminate\Support\Str;


class UserController extends Controller
{
    // recuperation de tous les utilisateur
    public function index(){
        $users = User::withCount('eleves')->orderBy('id', 'DESC')->get();

        return response()->json([
            "error" => false,
            "message" => "Liste des utilisateurs",
            "users" => $users
        ]);
    }

    // modifier un user
    public function update(Request $request){
        $utilisateur = User::find($request->user_id);
    
        if($request->email !== $utilisateur->email) { // Vérifie si l'email a été modifié
            $userWithSameEmail = User::where('email', $request->email)->first();
            if ($userWithSameEmail && $userWithSameEmail->id !== $utilisateur->id) {
                return \response()->json([
                    "error" => true,
                    "message" => "The email address is already taken by another user."
                ], 500);
            } else {
                $utilisateur->email = $request->email;
            }
        }
        if ($request->has('password')) { // Vérifie si un mot de passe a été fourni
            $validator = Validator::make($request->all(), [
                'password' => 'required|confirmed|min:6',
            ]);
            if ($validator->fails()) {
                return \response()->json([
                    "error" => true,
                    "message" => $validator->getMessageBag()
                ], 500);
            }
            $utilisateur->password = Hash::make($request->password);
        }

        if($request->file('medias')){
            $pathTofile = $request->file('medias');
             $file_name_image= "images/".time().'_'.$pathTofile->getClientOriginalName();
             $pathTofile->move(('images'), $file_name_image);
             $utilisateur->photo = $file_name_image;
         }

        $utilisateur->firstname = $request->firstname;
        $utilisateur->lastname = $request->lastname;
        $utilisateur->phone_number = $request->phone_number;
        $utilisateur->sex = $request->sex;
        $utilisateur->type_user = $request->type_user;
        $utilisateur->save();

        return response()->json([
            "error" => false,
            "msg" => "Utilisateur mis à jour avec succès",
            "user" => $utilisateur
        ]);
    }

    // supprimer un utilisateur
    public function destroy($id){
        $users = User::find($id);
        if($users != null){
            $users->delete();
            return response()->json([
                "error" => false,
                "message" => "users supprimé avec succès"
            ]);
        }
        else{
            return response()->json([
                "error" => true,
                "message" => "users non trouvé"
            ]);
        }
    }

    public function getAuthUser($id){
        $user = User::find($id);
        
        if ($user) {
            return response()->json([
                "error" => false,
                "user" => $user
            ]);
        } else {
            return response()->json([
                "error" => true,
                "message" => "L'utilisateur n'est pas connecté."
            ]);
        }
    }

    // afficher un cours avec son id
    public function show($id){
        $users = User::find($id);
        if (is_null($users)) {
            return response()->json([
                "error" => true,
                "message" => "Element non retrouvé",
                404
            ]);
        }
        return response()->json([
            "error" => false,
            "users" => $users,
            "message" => "Le users a été trouvé"
        ]);
    }


    //Modification de mot de passe
    public function changePassword(Request $request,$id){
        $users = User::find($id);
        if($users){
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return  $this->resp($validator->getMessageBag(),500,null); 
            }
            $users->password = Hash::make($request->password);
            $users->update();
            return  $this->resp('Mot de passe modifié avec succes !',200,null);
        }else{
            return  $this->resp('Donnée introuvable',400,null);
        }

    }


    public function resp($message,$code,$data){
        return response()->json([
            'message' => $message,
            'status' => $code,
            'data' => $data
        ]);
    }

    public function professeurCount(){
        $professeursCount = User::where('type_user', 'professeur')->count();
        return response()->json([
            "error" => false,
            "msg" => "L'ensemble des professeurs",
            "professeurs" => $professeursCount,
        ]);
    }

    public function tuteursCount(){
        $tuteursCount = User::where('type_user', 'tuteur')->count();
        return response()->json([
            "error" => false,
            "msg" => "L'ensemble des tuteurs",
            "tuteurs" => $tuteursCount,
        ]);
    }

    // mot de passe oublié
    public function motDePasseOublier(Request $request)
    {
        $user = User::whereEmail($request->email)->first();
        if($user){
            $password = Str::random(8);
            $this->sendPasswordByMail($user->email, $password);
            return $this->resp(false, 200, $password, "Mot de passe crée avec succes");
        }else{
            
            return $this->resp(true, 400, null, "Utilisateur introuvable");
        }
        
    }

    public function sendPasswordByMail($email, $password)
    {
        $data = [
            'subject' => 'Demande de changement de mot de passe',
            'body' => $password
        ];

        try {
            Mail::to($email)->send(new ForgetMail($data));
        } catch (Exception $th) {
            return response()->json([
                'error' => false,
                'message' => 'Mail envoyer avec succes !',
                'status_code' => 200
            ]);
        }
    }

    public function resetPassword(Request $request){
        $user = User::whereEmail($request->email)->first();
        if($user){
            
            $user->password = Hash::make($request->password);
            $user->save();
            
            return $this->resp(false, 200, null, "Mot de passe modifié avec succes",null);
        }else{
            
            return $this->resp(true, 400, null, "Utilisateur introuvable",null);
        }
        
    }
}
