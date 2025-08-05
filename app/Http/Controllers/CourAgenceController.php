<?php

namespace App\Http\Controllers;

use App\Models\CourAgence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CourAgenceController extends Controller
{
    // création du cour_agence
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'cour_id' => 'required',
            'agence_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->getMessageBag()
            ], 500);
        }

        $cour_agences = new CourAgence();
        $cour_agences->libelle = $request->libelle;
        $cour_agences->cour_id = $request->cour_id;
        $cour_agences->agence_id = $request->agence_id;
        $res = $cour_agences->save();

        if($res){
            return response()->json([
                'message'=>'cour_agence a été ajouter avec succès',
                'code' => 200,
                'cour_agences'=> $cour_agences
                ]);
        }else{
            return response()->json([
                'message'=>'erreur',
                'code' => 500
                ]);
        }

    }

    // recuperation de tous les cour_agences
    public function index(){
        $cour_agences = DB::table('cour_agences')
            ->join('cours', 'cour_agences.cour_id', '=', 'cours.id')
            ->join('agences', 'cour_agences.agence_id', '=', 'agences.id')
            ->select('cour_agences.*','cours.titre as cour','agences.nom as agence')
            ->orderBy('cour_agences.id', 'DESC')
            ->get();
        return response()->json([
            "error" => false,
            "message" => "La liste des cour_agences",
            'status_code' => 200,
            "cour_agences" => $cour_agences
        ]);
    }



    // afficher un cour_agence avec son id
    public function show($id){
        $cour_agences = DB::table('cour_agences')
                    ->join('cours', 'cour_agences.cour_id', '=', 'cours.id')
                    ->join('agences', 'cour_agences.agence_id', '=', 'agences.id')
                    ->select('cour_agences.*','cours.titre as cour','agences.nom as agence')
                    ->where('cour_agences.id', $id)
                    ->orderBy('cour_agences.id', 'DESC')
                    ->get();
        if (is_null($cour_agences)) {
        return response()->json([
            "error" => true,
            "message" => "Element non retrouvé",
            "code" => 404
        ]);
        }

        return response()->json([
            "error" => false,
            "cour_agences" => $cour_agences,
            "message" => "Un cour_agences a été trouvé",
            "code" => 200
        ]);
    }

    // modifier un cours_agences
     public function update(Request $request , $id){
        $cour_agences = CourAgence::find($id);
        if($cour_agences){
            $validator = Validator::make($request->all(), [
                'cour_id' => 'required|exists:cours,id',
                'agence_id' => 'required|exists:agences,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->getMessageBag()
                ], 500);
            }

             $cour_agences->libelle = $request->libelle;
             $cour_agences->cour_id = $request->cour_id;
            $cour_agences->agence_id = $request->agence_id;

            $cour_agences ->update();
            $cour_agences = CourAgence::find($id);
            return response()->json([

                "error" => false,
                "message" => "mise à jour avec success",
                "data" =>$cour_agences,
                "code" => 200
            ]);
        }else{
            return  $this->resp('Donnée introuvable',200,null);
        }

        $cour_agences->libelle = $request->libelle;

        $cour_agences ->update();
        $cour_agences = CourAgence::find($id);
        return response()->json([
            "error" => false,
            "message" => "mise à jour avec success",
            "data" =>$cour_agences,
            "code" => 200,

        ]);
    }

    // supprimer un cour_agence
    public function destroy($id){
        $cour_agences = CourAgence::find($id);
        if($cour_agences){
            if($cour_agences != null){
                $cour_agences->delete();
                return response()->json([
                    "error" => false,
                    "message" => "un cour_agences supprimé avec succès",
                    "code" => 200,
                ]);
            }
            else{
                return response()->json([
                    "error" => true,
                    "message" => " un cour_agences non trouvé",
                    "code" => 500,
                ]);
            }
        }else{
            return  $this->resp('Donnée introuvable',200,null);

        }
    }
}
