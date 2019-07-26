<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Entreprises;
use App\Models\SousClasse;
use App\Models\Rubrique;
use App\Models\Entreprise;
use App\Models\LigneBilan;
use App\Models\Pays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class BilanController extends Controller
{
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
        return view('pages.bilan')
            ->with('classes',$classes)
            ->with('entreprises',$entreprises)
            ->with('lignebilans',$lignebilans);
}

    function periode(Request $request){
        $exercice1=$request->get('exercice1');
        $exercice2=$request->get('exercice2');
        $taux=$exercice2/$exercice1;
        if($taux<1){
            $periode=$exercice2-$exercice1;
        }
    }
    function show(Request $request){
        $input = $request->all();
        //$bilan = DB::connection(getDB())->get()
        return view('pages.resBilan',compact('input'));
    }
}
