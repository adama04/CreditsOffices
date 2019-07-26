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
<<<<<<< HEAD
    function getDB($request){
        if($request['pays'])
            $idPays = $request['pays'];
        else
            $idPays=201;

        $db = DB::table('pays')->where('idPays',$idPays)->get('bdPays');
        foreach ($db as $d){
            $db = $d->bdPays;
        }
        return $db;
    }

    function index(Request $pays){
        $dbs = $this->getDB($pays);
       $classes = DB::table('classe')->paginate(3);
        $entreprises=DB::connection($dbs)->table('entreprises')->get();
        $lignebilans=DB::table('lignebilan')->groupBy('exercice')->get('exercice');
=======
    function index()
    {
        $classes = DB::table('classe')->paginate(3);
        $lignebilans = DB::table('lignebilan')->groupBy('exercice')->get('exercice');
>>>>>>> 5aff8f104a6701d39d626f784f7f9aa16baea9eb
        return view('pages.bilan')
            ->with('classes',$classes)
            ->with('lignebilans',$lignebilans);
<<<<<<< HEAD
}

    function periode(Request $request){
=======
    }
    function listeEntreprises(Request $request){
        $entreprises = Entreprises::select("nomEntreprise")
            ->where('nomEntreprise', 'LIKE',"%{$request->input('query')}%")->get();
        $dataModified = array();
        foreach ($entreprises as $entreprise)
        {
            $dataModified[] = $entreprise->nomEntreprise;
        }
        return response()->json($dataModified);
    }

    function recupererinfo(Request $request){
>>>>>>> 5aff8f104a6701d39d626f784f7f9aa16baea9eb
        $exercice1=$request->get('exercice1');
        $exercice2=$request->get('exercice2');
        $taux=$exercice2/$exercice1;
        if($taux<1){
            $periode=$exercice2-$exercice1;
            $infoEntreprises = DB::table('entreprises')
                ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                ->join('service', 'ligneservices.idService', '=', 'service.idService')
                ->join('sousecteur', 'sousecteur.idSousecteur', '=', 'ligneservices.idSousecteur')
                ->join('secteur', 'secteur.idSecteur', '=', 'sousecteur.idSecteur')
                ->select('sousecteur.idsouSecteur', 'secteur.idSecteur', 'service.idService', 'entreprises.numRegistre',
                    'entreprises.idEntreprise', 'entreprises.Adresse', 'entreprises.Sigle', 'entreprises.codePays', 'entreprises.codeRegion',
                    'entreprises.Pays', 'entreprises.type', 'entreprises.dateCreation', 'entreprises.numEnregistre', 'sousecteur.nomsouSecteur', 'service.nomService',
                    'secteur.nomSecteur')->get();
            return view('pages.bilan')
                ->with('periode',$periode)
                ->with('infoEntreprises', $infoEntreprises);
        }

    }
<<<<<<< HEAD
    function show(Request $request){
        $input = $request->all();
        //$bilan = DB::connection(getDB())->get()
        return view('pages.resBilan',compact('input'));
    }
=======

>>>>>>> 5aff8f104a6701d39d626f784f7f9aa16baea9eb
}
