<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\agenceeleve;
use App\Models\Eleve;
use Illuminate\Http\Request;

class AgenceeleveController extends Controller
{
    // ajouter l'agence à l'élève
    public function saveAgenceEleve(Request $request)
    {
        $eleve = $request->input('eleve');
        $agence = $request->input('agence');

         // Recherche une entrée existante avec le même élève
        $agenceEleve = agenceeleve::where('eleve_id', $eleve)->firstOrNew();
        $agenceEleve->eleve_id = $eleve;
        $agenceEleve->agence_id = $agence;

        // Si l'entrée existe déjà, met à jour l'agence associée à l'élève
        if (!$agenceEleve->exists) {
            $agenceEleve->save();
        } else {
            $agenceEleve->update(['agence_id' => $agence]);
        }

        // Retourne une réponse JSON pour indiquer que l'opération s'est bien déroulée
        return response()->json([
            "error" => false,
            "message" => "mise à jour avec success",
            "data" =>$agenceEleve,
            "code" => 200,
        ]);
    }
    
}
