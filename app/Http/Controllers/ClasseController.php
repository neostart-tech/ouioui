<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClasseController extends Controller
{
    // recuperation de tous les classes
    public function index(){
        $classes = Classe::orderBy('id', 'DESC')->get();
        return response()->json([
            "error" => false,
            "message" => "La liste des classes",
            "classes" => $classes
        ]);
    }

    // création des classes
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->getMessageBag()
            ], 500);
        }

        $class = new Classe();
        $class->libelle = $request->libelle;
        $res = $class->save();

        if($res){
            return response()->json([
                'message'=>'la classe a été ajouter avec succès',
                'status_code' => 200,
                'class'=> $class
                ]);
        }else{
            return response()->json([
                'message'=>'erreur',
                'status_code' => 500
                ]);
        }
    }


    // afficher une classe avec son id
     public function show($id){
        $classes = Classe::find($id);
        if (is_null($classes)) {
            return response()->json([
                "error" => true,
                "message" => "classe non retrouvé",
                404
            ]);
        }
        return response()->json([
            "error" => false,
            "classes" => $classes,
            "message" => "Une classes a été trouvé"
        ]);
    }

    // modifier une classe

    public function update(Request $request){

        $classes = Classe::whereId($request->classe_id)->first();
        $classes->libelle = $request->libelle;

        $classes->save();

        return response()->json([
            "error" => false,
            "message" => "mise à jour avec success",
            "data" =>$classes,
            "code" => 200,
        ]);
    }

    // supprimer une classe
    public function destroy($id){
        $classes = Classe::find($id);
        if($classes){
            if($classes != null){
                $classes->delete();
                return response()->json([
                    "error" => false,
                    "message" => "une classe supprimé avec succès",
                    "code" => 200,

                ]);
            }
            else{
                return response()->json([
                    "error" => true,
                    "message" => " une classe non trouvé",
                    "code" =>500
                ]);
            }
        }else{
            return  $this->resp('Donnée introuvable',200,null);
        }
    }


    public function resp($message,$code,$data){
        return response()->json([
            'message' => $message,
            'status' => $code,
            'data' => $data
        ]);
    }
}
