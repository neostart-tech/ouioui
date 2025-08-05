<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgenceController extends Controller
{
    // création des classes
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'quartier' => 'required|string|max:255',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->getMessageBag()
            ], 500);
        }

        $agences = new Agence();
        $agences->nom = $request->nom;
        $agences->ville = $request->ville;
        $agences->pays = $request->pays;
        $agences->quartier = $request->quartier;
        $agences->user_id = $request->user_id;
        $res = $agences->save();

        if($res){
            return response()->json([
                'message'=>'Agence a été ajouter avec succès',
                'status_code' => 200,
                'agences'=> $agences
                ]);
        }else{
            return response()->json([
                'message'=>'erreur',
                'status_code' => 500
                ]);
        }
    }

    // recuperation de tous les agences
    public function index(){
        $agences = Agence::with(['user'])
        ->orderBy('id', 'DESC')
        ->get();
        return response()->json([
            "error" => false,
            "message" => "La liste des agences",
            "agences" =>$agences
        ]);
    }

    // afficher une agences avec son id
    public function show($id){
        $agences = Agence::with(['user'])->find($id);
        if (is_null($agences)) {
            return response()->json([
                "error" => true,
                "message" => "agences non retrouvé",
                404
            ]);
        }
        return response()->json([
            "error" => false,
            "agences" => $agences,
            "message" => "Une agences a été trouvé"
        ]);
    }

    // modifier un agence
    public function update(Request $request){

        $agences = Agence::whereId($request->agence_id)->first();
        $agences->nom = $request->nom;
        $agences->ville = $request->ville;
        $agences->pays = $request->pays;
        $agences->quartier = $request->quartier;
        $agences->user_id = $request->user_id;

        $agences->save();

        return response()->json([
            "error" => false,
            "message" => "mise à jour avec success",
            "data" =>$agences,
            "code" => 200,
        ]);
    }

    // supprimer un agence
    public function destroy($id){
        $agences = Agence::find($id);
        if($agences != null){
            $agences->delete();
            return response()->json([
                "error" => false,
                "message" => "un agence supprimé avec succès",
                "code" => 200,
            ]);
        }
        else{
            return response()->json([
                "error" => true,
                "message" => " un agence non trouvé",
                "code" =>500,
            ]);
        }
    }
}
