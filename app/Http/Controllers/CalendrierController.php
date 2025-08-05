<?php

namespace App\Http\Controllers;

use App\Models\Calendrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalendrierController extends Controller
{
     // recuperation de tous les calendriers
     public function index() {
        $calendriers = Calendrier::with(['programme','programme.cour','programme.eleve','programme.user'])
        ->orderBy('id', 'DESC')
        ->get();
        return response()->json([
            "error" => false,
            "message" => "La liste des calendriers",
            "calendriers" =>$calendriers
        ]);
    }

    // programmes selon users
    public function calendrierUser($userId){
        $calendriers = Calendrier::with(['programme','programme.cour','programme.eleve','programme.user'])
            ->orderBy('id', 'DESC')
            ->get();

            $filteredProgrammes = $calendriers->where('status', 'Actif')->filter(function ($calendrier) use ($userId) {
                return $calendrier->programme->eleve->user_id == $userId;
            });
             
        return response()->json([
            "error" => false,
            "message" => "La liste des eleves de l'utilisateur connecté",
            "calendriers" => $filteredProgrammes
        ]);
    }
    // création des calendriers
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'jour' => 'required|string|max:255',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'prog_id' => 'required|exists:programmes,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->getMessageBag()
            ], 500);
        }
        $calendrier = new Calendrier();
        $calendrier->jour = $request->jour;
        $calendrier->heure_debut = $request->heure_debut;
        $calendrier->heure_fin = $request->heure_fin;
        $calendrier->status = "Actif";
        $calendrier->prog_id = $request->prog_id;
        $res = $calendrier->save();

        if($res){
            return response()->json([
                'message'=>'le calendrier a été ajouter avec succès',
                'status_code' => 200,
                'calendrier'=> $calendrier
                ]);
        }else{
            return response()->json([
                'message'=>'erreur',
                'status_code' => 500
                ]);
        }
    }

    // modifier un élève
    public function update(Request $request){
        $calendrier = Calendrier::find($request->calendrier_id);
        $calendrier->jour = $request->jour;
        $calendrier->heure_debut = $request->heure_debut;
        $calendrier->heure_fin = $request->heure_fin;
        $calendrier->prog_id  = $request->prog_id;
        $calendrier->save();

        return response()->json([
            "error" => false,
            "msg" => "Calendrier mis à jour avec succès"
        ]);
    }

    // supprimer un eleve
    public function destroy($id){
        $calendrier = Calendrier::find($id);
        if($calendrier != null){
            $calendrier->delete();
            return response()->json([
                "error" => false,
                "message" => "Calendrier supprimé avec succès",
                "code" => 200,
            ]);
        }
        else{
            return response()->json([
                "error" => true,
                "message" => "Calendrier non trouvé",
                "code" => 500,
            ]);
        }
    }
// show calendrier
    public function show($id){
        $calendriers = Calendrier::with(['programme','programme.cour','programme.eleve','programme.user'])->find($id);
        if (is_null($calendriers)) {
            return response()->json([
                "error" => true,
                "message" => "calendrier non retrouvé",
                404
            ]);
        }
        return response()->json([
            "error" => false,
            "calendriers" => $calendriers,
            "message" => "Une calendrier a été trouvé"
        ]);
    }

    //changer le status du calendrier
    public function changeStatus($id){
        $calendrier = Calendrier::find($id);
        if($calendrier != null){
            $calendrier->status = $calendrier->status == "Actif" ? "Inactif" : "Actif";
            $calendrier->save();
            return response()->json([
                "error" => false,
                "message" => "Status modifié avec succès",
                "code" => 200,
            ]);
        }
        else{
            return response()->json([
                "error" => true,
                "message" => "Calendrier non trouvé",
                "code" => 500,
            ]);
        }
    }

     // les eleves qui ont pour user_id , l'id de l'utilisateur connecté
     public function userCalendrier($userId){
        $calendriers = Calendrier::with(['programme','programme.cour','programme.eleve','programme.user'])
        ->whereHas('programme.user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })
            ->orderBy('id', 'DESC')
            ->get();
    
        return response()->json([
            "error" => false,
            "message" => "La liste de calendrier de l'utilisateur connecté",
            "calendriers" => $calendriers
        ]);
    }
}
