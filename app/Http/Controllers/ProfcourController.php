<?php

namespace App\Http\Controllers;

use App\Models\Profcour;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfcourController extends Controller
{
    public function index(){
        $profcours = DB::table('profcours')
                    ->join('users', 'profcours.user_id', '=', 'users.id')
                    ->join('cours', 'profcours.cour_id', '=', 'cours.id')
                    ->select('profcours.*','users.type_user as user','cours.titre','cours.type_cours','users.lastname','users.firstname','users.photo')
                    ->orderBy('profcours.id', 'DESC')
                    ->get();
        return response()->json([
            "error" => false,
            "message" => "La liste des prof_agences",
            'status_code' => 200,
            "profcours" => $profcours
        ]);
    }

     // création du prof_cour
     public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'cour_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->getMessageBag()
            ], 500);
        }

        // Vérifier si une ligne existe déjà avec les mêmes user_id et agence_id
        $existingRecord = Profcour::where('user_id', $request->user_id)
                            ->where('cour_id', $request->cour_id)
                            ->first();
    
        if ($existingRecord) {
            return response()->json([
                'message' => 'Une ligne existe déjà avec le même user_id et cour_id.',
                'code' => 500
            ]);
        }

        $prof_cours = new Profcour();
        $prof_cours->libelle = $request->libelle;
        $prof_cours->user_id = $request->user_id;
        $prof_cours->cour_id = $request->cour_id;
        $res = $prof_cours->save();

        if($res){
            return response()->json([
                'message'=>'prof_agences a été ajouter avec succès',
                'code' => 200,
                'prof_agences'=> $prof_cours
                ]);
        }else{
            return response()->json([
                'message'=>'erreur',
                'code' => 500
                ]);
        }

    }

     // modifier un prof_cours
     public function update(Request $request){
        $profcour = Profcour::find($request->profcour_id);
        $profcour->libelle = $request->libelle;
        $profcour->cour_id  = $request->cour_id ;
        $profcour->user_id = $request->user_id;

        $profcour->save();

        return response()->json([
            "error" => false,
            "msg" => "prof et agence mis à jour avec succès"
        ]);
    }

    // voir un prof_cours
    public function show($id){
        $prof_cours = DB::table('profcours')
                    ->join('users', 'profcours.user_id', '=', 'users.id')
                    ->join('cours', 'profcours.cour_id', '=', 'cours.id')
                    ->select('profcours.*','users.type_user as user','cours.titre','cours.type_cours','users.lastname','users.firstname','users.photo')
                    ->where('profcours.id', $id)
                    ->orderBy('profcours.id', 'DESC')
                    ->get();
        if (is_null($prof_cours )) {
        return response()->json([
            "error" => true,
            "message" => "Element non retrouvé",
            "code" => 404
        ]);
        }

        return response()->json([
            "error" => false,
            "prof_cours" => $prof_cours ,
            "message" => "Un prof_cours a été trouvé",
            "code" => 200
        ]);
    }

     // supprimer un prof_agence
     public function destroy($id){
        $profcour = Profcour::find($id);
        if($profcour){
            if($profcour != null){
                $profcour->delete();
                return response()->json([
                    "error" => false,
                    "message" => "un prof_cours supprimé avec succès",
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
