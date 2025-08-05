<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Programme;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProgrammeController extends Controller
{
    // recuperation de tous les programmes
       public function index(){
        $programmes = Programme::with(['user', 'cour', 'eleve'])
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json([
            "error" => false,
            "message" => "La liste des programmes",
            "programmes" =>$programmes
        ]);
    }
    // création des programmes

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cour_id' => 'required',
            'eleve_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->getMessageBag()
            ], 500);
        }

        $programmes = new Programme();
        // $programmes->user_id = $request->user_id;
        $programmes->cour_id = $request->cour_id;
        $programmes->eleve_id = $request->eleve_id;
        $programmes->status = 0;
        $res = $programmes->save();

        if($res){
            return response()->json([
                'message'=>'le programme a été ajouter avec succès',
                'status_code' => 200,
                'programmes'=> $programmes
                ]);
        }else{
            return response()->json([
                'message'=>'erreur',
                'status_code' => 500
                ]);
        }
    }

    // afficher un Programme avec son id
    public function show($id){
        $programmes = Programme::find($id);
        if (is_null($programmes)) {
            return response()->json([
                "error" => true,
                "message" => "Element non retrouvé', 404"
            ]);
        }
        return response()->json([
            "error" => false,
            "programmes" => $programmes,
            "message" => "Le programmes a été trouvé"
        ]);
    }

    // modifier un programme
    public function updateProgramme(Request $request){
        $programme = Programme::find($request->programme_id);
        $programme->eleve_id = $request->eleve_id;
        $programme->cour_id = $request->cour_id;
        $programme->status = 0;

        $programme->save();

        return response()->json([
            "error" => false,
            "msg" => "Programme mis à jour avec succès"
        ]);
    }

    // supprimer un Programme
    public function destroy($id)
    {
        $programmes = Programme::find($id);

        if ($programmes != null) {
            $programmes->delete();
            return response()->json([
                "error" => false,
                "message" => "Programme supprimé avec succès"
            ]);
        } else {
            return response()->json([
                "error" => true,
                "message" => "Programme non trouvé"
            ]);
        }
    }

    // annulé un programme
    public function cancel($id)
    {
        $programmes = Programme::find($id);
        $programmes->status = 3;

        $programmes->save();

        return response()->json([
            "error" => false,
            "message" => "Programme annulé avec succès"
        ]);
    }

    public function addProfesseur(Request $request)
    {
        $programme = Programme::find($request->programme_id);
        $programme->user_id = $request->user_id;

        $programme->save();
        return response()->json([
            "error" => false,
            "msg" => "Professeur attribué avec succès"
        ]);
    }

    // programme de l'eleve du parent connecté
    public function programmeUser($userId){
        $programmes = Programme::with(['user', 'cour', 'eleve','transactions'])
            ->orderBy('id', 'DESC')
            ->get();

            $filteredProgrammes = $programmes->filter(function ($programme) use ($userId) {
                return $programme->eleve->user_id == $userId;
            });
            
    
        return response()->json([
            "error" => false,
            "message" => "La liste des eleves de l'utilisateur connecté",
            "programmes" => $filteredProgrammes
        ]);
    }

}
