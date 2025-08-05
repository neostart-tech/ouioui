<?php

namespace App\Http\Controllers;

use App\Models\ProfAgence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfAgenceController extends Controller
{
    // création du prof_agence
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'agence_id' => 'required',
            'libelle' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->getMessageBag()
            ], 500);
        }
    
        // Vérifier si une ligne existe déjà avec les mêmes user_id et agence_id
        $existingRecord = ProfAgence::where('user_id', $request->user_id)
                            ->where('agence_id', $request->agence_id)
                            ->first();
    
        if ($existingRecord) {
            return response()->json([
                'message' => 'Une ligne existe déjà avec le même user_id et agence_id.',
                'code' => 500
            ]);
        }
    
        $prof_agences = new ProfAgence();
        $prof_agences->libelle = $request->libelle;
        $prof_agences->user_id = $request->user_id;
        $prof_agences->agence_id = $request->agence_id;
        $res = $prof_agences->save();
    
        if ($res) {
            return response()->json([
                'message' => 'prof_agences a été ajouté avec succès',
                'code' => 200,
                'prof_agences' => $prof_agences
            ]);
        } else {
            return response()->json([
                'message' => 'erreur',
                'code' => 500
            ]);
        }
    }


    // recuperation de tous les prod_agences
    public function index(){
        $profagences = DB::table('prof_agences')
                    ->join('users', 'prof_agences.user_id', '=', 'users.id')
                    ->join('agences', 'prof_agences.agence_id', '=', 'agences.id')
                    ->select('prof_agences.*','users.type_user as user','agences.nom','users.lastname','users.firstname','users.photo')
                    ->orderBy('prof_agences.id', 'DESC')
                    ->get();
        return response()->json([
            "error" => false,
            "message" => "La liste des prof_agences",
            'status_code' => 200,
            "profagences" => $profagences
        ]);
    }


    // afficher un prof_agences avec son id
    public function show($id){
        $prof_agences = DB::table('prof_agences')
                    ->join('users', 'prof_agences.user_id', '=', 'users.id')
                    ->join('agences', 'prof_agences.agence_id', '=', 'agences.id')
                    ->select('prof_agences.*','users.type_user as user','agences.nom','users.lastname','users.firstname','users.photo')
                    ->where('prof_agences.id', $id)
                    ->orderBy('prof_agences.id', 'DESC')
                    ->get();
        if (is_null($prof_agences)) {
        return response()->json([
            "error" => true,
            "message" => "Element non retrouvé",
            "code" => 404
        ]);
        }

        return response()->json([
            "error" => false,
            "prof_agences" => $prof_agences,
            "message" => "Un prof_agences a été trouvé",
            "code" => 200
        ]);
    }

    // modifier un prof_agences
    public function update(Request $request){
        $profagence = ProfAgence::find($request->profagence_id);
        $profagence->libelle = $request->libelle;
        $profagence->agence_id  = $request->agence_id ;
        $profagence->user_id = $request->user_id;

        // vérifier si la ligne existe déjà , si oui ne plus la créer
        $existingRecord = ProfAgence::where('user_id', $request->user_id)
                            ->where('agence_id', $request->agence_id)
                            ->first();
    
        if ($existingRecord) {
            return response()->json([
                'message' => 'Une ligne existe déjà avec le même user_id et agence_id.',
                'code' => 500
            ]);
        }

        $profagence->save();

        return response()->json([
            "error" => false,
            "msg" => "eleve mis à jour avec succès"
        ]);
    }

     // supprimer un prof_agence
    public function destroy($id){
        $profagence = ProfAgence::find($id);
        if($profagence){
            if($profagence != null){
                $profagence->delete();
                return response()->json([
                    "error" => false,
                    "message" => "un prof_agences supprimé avec succès",
                    "code" => 200,
                ]);
            }
            else{
                return response()->json([
                    "error" => true,
                    "message" => " un prof_agences non trouvé",
                    "code" => 500,
                ]);
            }
        }else{
            return  $this->resp('Donnée introuvable',200,null);

        }
    }
}
