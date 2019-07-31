<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Entreprises;
use App\Models\SousClasse;
use App\Models\Rubrique;
use App\Models\LigneBilan;
use App\Models\Pays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class BilanController extends Controller
{

    function getDB(Request $request)
    {
        if ($request['pays'])
            $idPays = $request['pays'];
        else
            $idPays = 201;

        $db = DB::table('pays')->where('idPays', $idPays)->get('bdPays');
        foreach ($db as $d) {
            $db = $d->bdPays;
        }
        return $db;
    }

    function index(Request $pays)
    {
        $dbs = $this->getDB($pays);
        $lignebilans = DB::connection($dbs)->table('lignebilan')->groupBy('exercice')->get('exercice');
        return view('pages.bilan')
            ->with('dbs', $dbs)
            ->with('lignebilans', $lignebilans);
    }

    function periode(Request $request)
    {

    }

    function listeEntreprises(Request $request)
    {

        $dbs = $this->getDB($request);
        $entreprises = DB::connection($dbs)->table('entreprises')
            ->where('nomEntreprise', 'LIKE', "%{$request->input('query')}%")
            ->orWhere('Sigle', 'LIKE', "%{strtoupper($request->input('query'))}%")
            ->get(['nomEntreprise', 'idEntreprise']);
        $dataModified = array();
        foreach ($entreprises as $entreprise) {
            $dataModified[] = $entreprise->idEntreprise . '-' . $entreprise->nomEntreprise;
        }
        return response()->json($dataModified);
    }

    function recupererinfo(Request $request)
    {
        $dbs = $this->getDB($request);
        $exercice1 = $request->get('exercice1');
        $exercice2 = $request->get('exercice2');
        $taux = $exercice2 / $exercice1;
        if ($taux < 1) {
            $periode = $exercice2 - $exercice1;
            $infoEntreprises = DB::connection($dbs)->table('entreprises')
                ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                ->join('service', 'ligneservices.idService', '=', 'service.idService')
                ->join('sousecteur', 'sousecteur.idSousecteur', '=', 'ligneservices.idSousecteur')
                ->join('secteur', 'secteur.idSecteur', '=', 'sousecteur.idSecteur')
                ->select('sousecteur.idsouSecteur', 'secteur.idSecteur', 'service.idService', 'entreprises.numRegistre',
                    'entreprises.idEntreprise', 'entreprises.Adresse', 'entreprises.Sigle', 'entreprises.codePays', 'entreprises.codeRegion',
                    'entreprises.Pays', 'entreprises.type', 'entreprises.dateCreation', 'entreprises.numEnregistre', 'sousecteur.nomsouSecteur', 'service.nomService',
                    'secteur.nomSecteur')->where(ligneservices . type, '=', 'P')
                ->get('numRegistre', 'Adresse', 'Sigle', 'idsouSecteur', 'nomsouSecteur', 'idSecteur', 'nomSecteur', 'idService', 'nomService', 'codePays', 'type', 'dateCreation', 'numEnregistre');

        }

    }

    function show(Request $request)
    {
        $dbs = $this->getDB($request);
        $input = $request->all();
        $exercice1 = $request->get('exercice1');
        $exercice2 = $request->get('exercice2');
        $taux = $exercice2 / $exercice1;
        $nomEntreprise=explode("-",$input['idEntreprise'])[1];
        if ($taux < 1) {
            $periode = $exercice2 - $exercice1;
        }
        $infoEntreprises = DB::connection($dbs)->table('entreprises')
            ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
            ->join('service', 'ligneservices.idService', '=', 'service.idService')
            ->join('sousecteur', 'sousecteur.idSousecteur', '=', 'ligneservices.idSousecteur')
            ->join('secteur', 'secteur.idSecteur', '=', 'sousecteur.idSecteur')
            ->select('sousecteur.idsouSecteur', 'secteur.idSecteur', 'service.idService', 'entreprises.numRegistre', 'entreprises.nomEntreprise',
                'entreprises.idEntreprise', 'entreprises.Adresse', 'entreprises.Sigle', 'entreprises.codePays', 'entreprises.codeRegion',
                'entreprises.Pays', 'entreprises.type', 'entreprises.dateCreation', 'entreprises.numEnregistre', 'sousecteur.nomsouSecteur', 'service.nomService',
                'secteur.nomSecteur')->where('nomEntreprise','=',$nomEntreprise)
            ->get();
        return view('pages.resBilan')
            ->with('input',$input)
            ->with('infoEntreprises',$infoEntreprises);
    }
}
