<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    function listeEntreprises(Request $request)
    {
        $dbs = getDB($request);
        $entreprises = DB::connection($dbs)->table('entreprises')
            ->where('nomEntreprise', 'LIKE', "%{$request->input('query')}%")
            ->orWhere('Sigle', 'LIKE', "%{$request->input('query')}%")
            ->get(['nomEntreprise', 'idEntreprise']);
        $dataModified = array();
        foreach ($entreprises as $entreprise) {
            $dataModified[] = $entreprise->idEntreprise . '-' . $entreprise->nomEntreprise;
        }
        return response()->json($dataModified);
    }

    function listeSecteurs(Request $request){
        $dbs = getDB($request);
        $soussecteurs = DB::connection($dbs)->table('sousecteur')
            ->where('nomsouSecteur', 'LIKE', "%{$request->input('query')}%")
            ->get('nomsouSecteur');
        $dataModified = array();
        foreach ($soussecteurs as $soussecteur) {
            $dataModified[] = $soussecteur->nomsouSecteur;
        }
        return response()->json($dataModified);
    }
}
