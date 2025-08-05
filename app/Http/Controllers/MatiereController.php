<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatiereController extends Controller
{
    // recuperation de tous les matieres
    public function index(){
        $matieres = Matiere::orderBy('id', 'DESC')->get();
        return response()->json([
            "error" => false,
            "message" => "La liste des matieres",
            "matieres" =>$matieres
        ]);
    }

    // création des matires
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'libelle' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->getMessageBag()
            ], 500);
        }

        $matiere = new Matiere();
        $matiere->libelle = $request->libelle;
        $res = $matiere->save();

        if($res){
            return response()->json([
                'message'=>'la matiere a été ajouter avec succès',
                'status_code' => 200,
                'matiere'=> $matiere
                ]);
        }else{
            return response()->json([
                'message'=>'erreur',
                'status_code' => 500
                ]);
        }
    }

    // afficher une matiere avec son id
    public function show($id){
        $matieres = Matiere::find($id);
        if (is_null($matieres)) {
            return response()->json([
                "error" => true,
                "message" => "matieres non retrouvé",
                "code" => 400
            ]);
        }
        return response()->json([
            "error" => false,
            "matieres" => $matieres,
            "message" => "Une matieres a été trouvé",
            "code" => 200
        ]);
    }

    // modifier une matiere

    public function update(Request $request){

        $matiere = Matiere::whereId($request->matiere_id)->first();
        $matiere->libelle = $request->libelle;

        $matiere->save();

        return response()->json([
            "error" => false,
            "message" => "mise à jour avec success",
            "data" =>$matiere,
            "code" => 200,
        ]);
    }

    // supprimer une matiere
    public function destroy($id){
        $matieres = Matiere::find($id);
        if($matieres){
            if($matieres != null){
                $matieres->delete();
                return response()->json([
                    "error" => false,
                    "message" => "une matieres supprimé avec succès",
                    "code" => 200,
                ]);
            }
            else{
                return response()->json([
                    "error" => true,
                    "message" => " une matieres non trouvé",
                    "code" => 500,
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
