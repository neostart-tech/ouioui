<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EleveController extends Controller
{
    // création des élèves
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'dateNais' => 'required',
            'sex' => 'required',
            'classe_id' => 'required|exists:classes,id',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->getMessageBag()
            ], 500);
        }
        $eleve = new Eleve();
        $eleve->nom = $request->nom;
        $eleve->prenom = $request->prenom;
        $eleve->dateNais = $request->dateNais;
        $eleve->sex = $request->sex;
        $eleve->classe_id = $request->classe_id;
        $eleve->user_id = $request->user_id;
        $eleve->photo = "images/profile-photo.jpg";

        if($request->file('photo')){
            $pathTofile = $request->file('photo');
            $file_name_image= "images".time().'_'.$pathTofile->getClientOriginalName();
            $pathTofile->move(public_path('images'), $file_name_image);
            $eleve->photo = $file_name_image;
        }
        else{
            $eleve->photo = "images/profile-photo.jpg";
        }
        $res = $eleve->save();

        if($res){
            return response()->json([
                'message'=>'eleve a été ajouter avec succès',
                'status_code' => 200,
                'student'=> $eleve
                ]);
        }else{
            return response()->json([
                'message'=>'erreur',
                'status_code' => 500
                ]);
        }
    }

    // recuperation de tous les élèves
    public function index(){
        $eleves = Eleve::join('users', 'eleves.user_id', '=', 'users.id')
          ->join('classes', 'eleves.classe_id', '=', 'classes.id')
          ->orderBy('eleves.id', 'DESC')
          ->select('eleves.*', 'users.lastname', 'users.firstname', 'classes.libelle')
          ->get();
        return response()->json([
            "error" => false,
            "message" => "La liste des eleves",
            "eleves" =>$eleves
        ]);
    }

    // les eleves qui ont pour user_id , l'id de l'utilisateur connecté
    public function eleveUser($userId){
        $eleves = Eleve::join('users', 'eleves.user_id', '=', 'users.id')
            ->join('classes', 'eleves.classe_id', '=', 'classes.id')
            ->where('eleves.user_id', $userId)
            ->orderBy('eleves.id', 'DESC')
            ->select('eleves.*','users.lastname', 'users.firstname', 'classes.libelle')
            ->get();
    
        return response()->json([
            "error" => false,
            "message" => "La liste des eleves de l'utilisateur connecté",
            "eleves" => $eleves
        ]);
    }

    // afficher un élève avec son id
     public function show($id){
        $eleves = Eleve::join('users', 'eleves.user_id', '=', 'users.id')
            ->join('classes', 'eleves.classe_id', '=', 'classes.id')
            ->orderBy('eleves.id', 'DESC')
            ->select('eleves.*', 'users.lastname', 'users.firstname', 'classes.libelle')
            ->find($id);
        if (is_null($eleves)) {
            return response()->json([
                "error" => true,
                "message" => "élève non retrouvé",
                404
            ]);
        }
        return response()->json([
            "error" => false,
            "eleves" => $eleves,
            "message" => "L'élèe a été trouvé"
        ]);
    }


    // modifier un élève
    public function update(Request $request){
        $eleve = Eleve::find($request->eleve_id);
        $eleve->nom = $request->nom;
        $eleve->prenom = $request->prenom;
        $eleve->dateNais = $request->dateNais;
        $eleve->sex = $request->sex;
        $eleve->classe_id  = $request->classe_id ;
        $eleve->user_id = $request->user_id;

        if($request->file('medias')){
            $pathTofile = $request->file('medias');
             $file_name_image= "images/".time().'_'.$pathTofile->getClientOriginalName();
             $pathTofile->move(('images'), $file_name_image);
             $eleve->photo = $file_name_image;
         }
        $eleve->save();

        return response()->json([
            "error" => false,
            "msg" => "eleve mis à jour avec succès"
        ]);
    }

    // supprimer un eleve
    public function destroy($id){
        $eleves = Eleve::find($id);
        if($eleves != null){
            $eleves->delete();
            return response()->json([
                "error" => false,
                "message" => "eleves supprimé avec succès",
                "code" => 200,
            ]);
        }
        else{
            return response()->json([
                "error" => true,
                "message" => "eleves non trouvé",
                "code" => 500,
            ]);
        }
    }

    // count eleves for dashboard
    public function elevesCount(){
        $enfants = Eleve::count();
        return response()->json([
            "error" => false,
            "msg" => "L'ensemble des eleves",
            "enfants" => $enfants,
        ]);
    }
}
