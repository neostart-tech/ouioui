<?php

namespace App\Http\Controllers;

use App\Models\Cour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourController extends Controller
{
    // création de cours
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|',
            'duree' => 'required',
            'prix' => 'required|string|max:255',
            'type_cours' => 'required|string|max:255',
            'matiere_id' => 'required',
            'classe_id' => 'required'
        ]);

        $image_name = null;
        $image_extension = null;

        $cours = new Cour();
        $cours->titre = $request->titre;
        $cours->description = $request->description;
        $cours->duree = $request->duree;
        $cours->prix = $request->prix;
        $cours->photo = "images/cour3.jpg";
        $cours->type_cours = $request->type_cours;
        $cours->matiere_id = $request->matiere_id;
        $cours->classe_id = $request->classe_id;

        if($request->file('photo')){
            $pathTofile = $request->file('photo');
            $file_name_image= "images".time().'_'.$pathTofile->getClientOriginalName();
            $pathTofile->move(public_path('images'), $file_name_image);
            $cours->photo = $file_name_image;
        }
        else{
            $cours->photo = "images/cour3.jpg";
        }

        $cours->save();

        return response()->json([
            "error" => false,
            "message" => "Le cours a été ajouté avec succès",
            "code" => 200,
        ]);
    }

    // recuperation de tous les cours
    public function index()
    {
        $cours = DB::table('cours')
            ->join('matieres', 'cours.matiere_id', '=', 'matieres.id')
            ->join('classes', 'cours.classe_id', '=', 'classes.id')
            ->select('cours.*', 'matieres.libelle as matiere', 'classes.libelle as classe')
            ->orderBy('cours.id', 'DESC')
            ->get();
        return response()->json([
            "error" => false,
            "message" => "La liste des cours",
            "cours" => $cours
        ]);
    }

    // afficher un cours avec son id
    public function show($id)
    {
        $cours = DB::table('cours')
            ->join('matieres', 'cours.matiere_id', '=', 'matieres.id')
            ->join('classes', 'cours.classe_id', '=', 'classes.id')
            ->select('cours.*', 'matieres.libelle as matiere', 'classes.libelle as classe')
            ->where('cours.id', $id)
            ->orderBy('cours.id', 'DESC')
            ->get();
        if (is_null($cours)) {
            return response()->json([
                "error" => true,
                "message" => "Element non retrouvé",
                "code" => 404
            ]);
        }
        return response()->json([
            "error" => false,
            "cours" => $cours,
            "message" => "Le cour a été trouvé",
            "code" => 200
        ]);
    }

    public function updateCour(Request $request){

        $cour = Cour::find($request->cour_id);
        $cour->titre = $request->titre;
        $cour->description = $request->description;
        $cour->duree = $request->duree;
        $cour->prix = $request->prix;
        $cour->type_cours = $request->type_cours;
        $cour->matiere_id = $request->matiere_id;
        $cour->classe_id = $request->classe_id;

        if($request->file('medias')){
            $pathTofile = $request->file('medias');
             $file_name_image= "images/".time().'_'.$pathTofile->getClientOriginalName();
             $pathTofile->move(('images'), $file_name_image);
             $cour->photo = $file_name_image;
         }

        $cour->save();

        return response()->json([
            "error" => false,
            "msg" => "Cour mis à jour avec succès"
        ]);
    }

    // supprimer un cour
    public function destroy($id)
    {
        $cours = Cour::find($id);

        if ($cours != null) {
            $cours->delete();
            return response()->json([
                "error" => false,
                "message" => "cours supprimé avec succès"
            ]);
        } else {
            return response()->json([
                "error" => true,
                "message" => "cours non trouvé"
            ]);
        }
    }

    // update le statut du cours
    public function updateStatus(Request $request, $id)
    {
        $cour = Cour::find($id);
        $cour->status = $request->input('status');
        $cour->save();
      
        return response()->json([
            "error" => false,
            "msg" => "Cours mis en cours recommandé"
        ]);
    }

    public function resp($message, $code, $data)
    {
        return response()->json([
            'message' => $message,
            'status' => $code,
            'data' => $data
        ]);
    }

    // public function activerCours(Cour $cours)
    // {
    //     $cours->status = 1;

    //     $cours->update();

    //     return redirect()->back()->with('status', 'le cours' . $cours->titre . ' a été activé avec succès');
    // }

    // public function desactivercours(Cour $cours)
    // {

    //     $cours->status = 0;

    //     $cours->update();
    // }
}
