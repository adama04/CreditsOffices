<?php

namespace App\Http\Controllers;

use App\Imports\BilansImport;
use App\Models\Classe;
use App\Models\Entreprises;
use App\Models\SousClasse;
use App\Models\Rubrique;
use App\Models\LigneBilan;
use App\Models\Pays;
use App\Exports\BilansExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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
                    'secteur.nomSecteur')->get();
        }
            return view('pages.bilan')
                ->with('periode',$periode)
                ->with('infoEntreprises', $infoEntreprises);
        }

    function bilan(Request $request){
        $collectclassesBGlobal=$collectclassesAGlobal =
        $collectclassesA = $collectclassesB =
        $collecttotalclassesAGlobal = $collecttotalclassesBGlobal =
        $collecttotalclassesA = $collecttotalclassesB =
            collect();
        $input = $request->all();
        if ($request->get('exercice1') > $request->get('exercice2')){
            $exercice1 = $request->get('exercice2');
            $exercice2 = $request->get('exercice1');
        }else
        {
            $exercice1 = $request->get('exercice1');
            $exercice2 = $request->get('exercice2');
        }

        #########################################################################
       ################### La verification doit etre dynamique #################
      #########################################################################
        if ($exercice1 > 2000):
            $exercice1 -= 1;
        endif;
        if ($exercice2 < 2017):
            $exercice2 += 1;
        endif;

        $idE = explode('-',$request->get('idEntreprise'))[0];
        $dbs = $this->getDB($request);
        $idsousecteur = DB::connection($dbs)->table('ligneservices')
            ->where('idEntreprise','=',$idE)
            ->get('idsouSecteur');
        foreach ($idsousecteur as $item):
            $idsousecteur = $item->idsouSecteur;
        endforeach;

        if ($request->get('document')=='bilan'):

            // Selectionner les classe a afficher en fonction des données recuperées apres post ddu formulaire
                        ########## Actifs ###############
            $classesA = DB::connection($this->getDB($request))->table('classe')
                ->where('nature','=','actif')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');
            ########## Passifs ###############
            $classesB = DB::connection($this->getDB($request))->table('classe')
                ->where('nature','=','passif')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');

            // En fonction de la classe recupéré la somme des rubriques dans lignebilan en passant par sous classe et rubrique
            foreach ($classesA as $classeA):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    // Global de chaque classe (somme rubrique) pour le secteur d'activité
                    $SommeAGlobal = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                        ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                        ->where('exercice','=',$i)
                        ->where('idsousecteur','=',$idsousecteur)
                        ->where('nomClasse','=',$classeA->nomClasse)
                        ->where('nature','=','actif')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesAGlobal =$collectclassesAGlobal->concat($SommeAGlobal);

                    // Global de chaque classe (somme rubrique) pour l'entreprise
                    $SommeA = DB::connection($this->getDB($request))->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeA->nomClasse)
                        ->where('nature','=','actif')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesA =$collectclassesA->concat($SommeA);

                endfor;
            endforeach;
            foreach ($classesB as $classeB):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    // Global de chaque classe (somme rubrique) pour le secteur d'activité
                    $SommeBGlobal = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                        ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                        ->where('exercice','=',$i)
                        ->where('idsousecteur','=',$idsousecteur)
                        ->where('nomClasse','=',$classeB->nomClasse)
                        ->where('nature','=','passif')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesBGlobal =$collectclassesBGlobal->concat($SommeBGlobal);

                    // Global de chaque classe (somme rubrique) pour l'entreprise
                    $SommeB = DB::connection($this->getDB($request))->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeB->nomClasse)
                        ->where('nature','=','passif')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesB =$collectclassesB->concat($SommeB);

                endfor;
            endforeach;
            // Total Actif, GlobalActif, Passifs et GlobalPassif si Bilan choisi
            for($i = $exercice1; $i <= $exercice2; $i++):
                $totalclassesA = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'actif')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->groupBy('exercice')
                    ->get();
                $totalclassesAGlobal = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                    ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                    ->where('exercice','=',$i)
                    ->where('idsousecteur','=',$idsousecteur)
                    ->where('nature','=','actif')
                    ->groupBy('exercice')
                    ->get();
                $totalclassesB = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'passif')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->groupBy('exercice')
                    ->get();
                $totalclassesBGlobal = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                    ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                    ->where('exercice','=',$i)
                    ->where('idsousecteur','=',$idsousecteur)
                    ->where('nature','=','passif')
                    ->groupBy('exercice')
                    ->get();
                // Concataine collection to return resBilan blade
                $collecttotalclassesA = $collecttotalclassesA->concat($totalclassesA);
                $collecttotalclassesB = $collecttotalclassesB->concat($totalclassesB);
                $collecttotalclassesAGlobal = $collecttotalclassesAGlobal->concat($totalclassesAGlobal);
                $collecttotalclassesBGlobal = $collecttotalclassesBGlobal->concat($totalclassesBGlobal);
            endfor;
        endif;
        if ($request->get('document')=='compres'):
            // Selectionner les classe a afficher en fonction des données recuperées apres post ddu formulaire
            ########## Actifs ###############
            $classesA = DB::connection($this->getDB($request))->table('classe')
                ->where('nature','=','charge')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');
            ########## Passifs ###############
            $classesB = DB::connection($this->getDB($request))->table('classe')
                ->where('nature','=','produit')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');

            // En fonction de la classe recupéré la somme des rubriques dans lignebilan en passant par sous classe et rubrique
            foreach ($classesA as $classeA):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    // Global de chaque classe (somme rubrique) pour le secteur d'activité
                    $SommeAGlobal = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                        ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                        ->where('exercice','=',$i)
                        ->where('idsousecteur','=',$idsousecteur)
                        ->where('nomClasse','=',$classeA->nomClasse)
                        ->where('nature','=','charge')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesAGlobal =$collectclassesAGlobal->concat($SommeAGlobal);

                    // Global de chaque classe (somme rubrique) pour l'entreprise
                    $SommeA = DB::connection($this->getDB($request))->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeA->nomClasse)
                        ->where('nature','=','charge')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesA =$collectclassesA->concat($SommeA);

                endfor;
            endforeach;
            foreach ($classesB as $classeB):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    // Global de chaque classe (somme rubrique) pour le secteur d'activité
                    $SommeBGlobal = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                        ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                        ->where('exercice','=',$i)
                        ->where('idsousecteur','=',$idsousecteur)
                        ->where('nomClasse','=',$classeB->nomClasse)
                        ->where('nature','=','produit')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesBGlobal =$collectclassesBGlobal->concat($SommeBGlobal);

                    // Global de chaque classe (somme rubrique) pour l'entreprise
                    $SommeB = DB::connection($this->getDB($request))->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeB->nomClasse)
                        ->where('nature','=','produit')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesB =$collectclassesB->concat($SommeB);
                endfor;
            endforeach;
            // Total Actif, GlobalActif, Passifs et GlobalPassif si Bilan choisi
            for($i = $exercice1; $i <= $exercice2; $i++):
                $totalclassesA = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'charge')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->groupBy('exercice')
                    ->get();
                $totalclassesAGlobal = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                    ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                    ->where('exercice','=',$i)
                    ->where('idsousecteur','=',$idsousecteur)
                    ->where('nature','=','charge')
                    ->groupBy('exercice')
                    ->get();
                $totalclassesB = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'produit')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->groupBy('exercice')
                    ->get();
                $totalclassesBGlobal = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                    ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                    ->where('exercice','=',$i)
                    ->where('idsousecteur','=',$idsousecteur)
                    ->where('nature','=','produit')
                    ->groupBy('exercice')
                    ->get();
                // Concataine collection to return resBilan blade
                $collecttotalclassesA = $collecttotalclassesA->concat($totalclassesA);
                $collecttotalclassesB = $collecttotalclassesB->concat($totalclassesB);
                $collecttotalclassesAGlobal = $collecttotalclassesAGlobal->concat($totalclassesAGlobal);
                $collecttotalclassesBGlobal = $collecttotalclassesBGlobal->concat($totalclassesBGlobal);
            endfor;
        endif;
        $nomEntreprise=explode("-",$input['idEntreprise'])[1];
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
        $exercices = DB::connection($dbs)->table('lignebilan')
            ->where('exercice','>=',$exercice1)
            ->where('exercice','<=',$exercice2)
            ->groupBy('exercice')
            ->get('exercice');
        return view('pages.resBilan')
            ->with('input',$input)
            ->with('collectclassesA',$collectclassesA)
            ->with('collectclassesB',$collectclassesB)
            ->with('collectclassesAGlobal',$collectclassesAGlobal)
            ->with('collectclassesBGlobal',$collectclassesBGlobal)
            ->with('collecttotalclassesA',$collecttotalclassesA)
            ->with('collecttotalclassesB',$collecttotalclassesB)
            ->with('collecttotalclassesAGlobal',$collecttotalclassesAGlobal)
            ->with('collecttotalclassesBGlobal',$collecttotalclassesBGlobal)
            ->with('classesA',$classesA)
            ->with('classesB',$classesB)
            ->with('exercices',$exercices)
            ->with('infoEntreprises',$infoEntreprises);
    }
    public function export(Request $request)
    {
        $collectclassesBGlobal=$collectclassesAGlobal =
        $collectclassesA = $collectclassesB =
        $collecttotalclassesAGlobal = $collecttotalclassesBGlobal =
        $collecttotalclassesA = $collecttotalclassesB =
            collect();
        $input = $request->all();
        if ($request->get('exercice1') > $request->get('exercice2')){
            $exercice1 = $request->get('exercice2');
            $exercice2 = $request->get('exercice1');
        }else
        {
            $exercice1 = $request->get('exercice1');
            $exercice2 = $request->get('exercice2');
            //dump($exercice1);
        }

        #########################################################################
        ################### La verification doit etre dynamique #################
        #########################################################################
        if ($exercice1 > 2000):
            $exercice1 -= 1;
        endif;
        if ($exercice2 < 2017):
            $exercice2 += 1;
        endif;

        $idE = explode('-',$request->get('idEntreprise'))[0];
        $nomEntreprise = explode("-",$request->get('idEntreprise'))[1];
       // dd($nomEntreprise,$exercice1,$idE);
        $dbs = $this->getDB($request);
        $idsousecteur = DB::connection($dbs)->table('ligneservices')
            ->where('idEntreprise','=',$idE)
            ->get('idsouSecteur');
        foreach ($idsousecteur as $item):
            $idsousecteur = $item->idsouSecteur;
        endforeach;

        if ($request->get('document')=='bilan'):

            // Selectionner les classe a afficher en fonction des données recuperées apres post ddu formulaire
            ########## Actifs ###############
            $classesA = DB::connection($this->getDB($request))->table('classe')
                ->where('nature','=','actif')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');
        //dd($classesA->nomClasse);
            ########## Passifs ###############
            $classesB = DB::connection($this->getDB($request))->table('classe')
                ->where('nature','=','passif')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');

            // En fonction de la classe recupéré la somme des rubriques dans lignebilan en passant par sous classe et rubrique
            foreach ($classesA as $classeA):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    // Global de chaque classe (somme rubrique) pour le secteur d'activité
                    $SommeAGlobal = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                        ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                        ->where('exercice','=',$i)
                        ->where('idsousecteur','=',$idsousecteur)
                        ->where('nomClasse','=',$classeA->nomClasse)
                        ->where('nature','=','actif')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesAGlobal =$collectclassesAGlobal->concat($SommeAGlobal);

                    // Global de chaque classe (somme rubrique) pour l'entreprise
                    $SommeA = DB::connection($this->getDB($request))->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeA->nomClasse)
                        ->where('nature','=','actif')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesA =$collectclassesA->concat($SommeA);

                endfor;
            endforeach;
            foreach ($classesB as $classeB):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    // Global de chaque classe (somme rubrique) pour le secteur d'activité
                    $SommeBGlobal = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                        ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                        ->where('exercice','=',$i)
                        ->where('idsousecteur','=',$idsousecteur)
                        ->where('nomClasse','=',$classeB->nomClasse)
                        ->where('nature','=','passif')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesBGlobal =$collectclassesBGlobal->concat($SommeBGlobal);

                    // Global de chaque classe (somme rubrique) pour l'entreprise
                    $SommeB = DB::connection($this->getDB($request))->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeB->nomClasse)
                        ->where('nature','=','passif')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesB =$collectclassesB->concat($SommeB);

                endfor;
            endforeach;
            // Total Actif, GlobalActif, Passifs et GlobalPassif si Bilan choisi
            for($i = $exercice1; $i <= $exercice2; $i++):
                $totalclassesA = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'actif')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->groupBy('exercice')
                    ->get();
                $totalclassesAGlobal = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                    ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                    ->where('exercice','=',$i)
                    ->where('idsousecteur','=',$idsousecteur)
                    ->where('nature','=','actif')
                    ->groupBy('exercice')
                    ->get();
                $totalclassesB = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'passif')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->groupBy('exercice')
                    ->get();
                $totalclassesBGlobal = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                    ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                    ->where('exercice','=',$i)
                    ->where('idsousecteur','=',$idsousecteur)
                    ->where('nature','=','passif')
                    ->groupBy('exercice')
                    ->get();
                // Concataine collection to return resBilan blade
                $collecttotalclassesA = $collecttotalclassesA->concat($totalclassesA);
                $collecttotalclassesB = $collecttotalclassesB->concat($totalclassesB);
                $collecttotalclassesAGlobal = $collecttotalclassesAGlobal->concat($totalclassesAGlobal);
                $collecttotalclassesBGlobal = $collecttotalclassesBGlobal->concat($totalclassesBGlobal);
            endfor;
        endif;
        if ($request->get('document')=='compres'):
            // Selectionner les classe a afficher en fonction des données recuperées apres post ddu formulaire
            ########## Actifs ###############
            $classesA = DB::connection($this->getDB($request))->table('classe')
                ->where('nature','=','charge')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');
            ########## Passifs ###############
            $classesB = DB::connection($this->getDB($request))->table('classe')
                ->where('nature','=','produit')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');

            // En fonction de la classe recupéré la somme des rubriques dans lignebilan en passant par sous classe et rubrique
            foreach ($classesA as $classeA):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    // Global de chaque classe (somme rubrique) pour le secteur d'activité
                    $SommeAGlobal = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                        ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                        ->where('exercice','=',$i)
                        ->where('idsousecteur','=',$idsousecteur)
                        ->where('nomClasse','=',$classeA->nomClasse)
                        ->where('nature','=','charge')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesAGlobal =$collectclassesAGlobal->concat($SommeAGlobal);

                    // Global de chaque classe (somme rubrique) pour l'entreprise
                    $SommeA = DB::connection($this->getDB($request))->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeA->nomClasse)
                        ->where('nature','=','charge')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesA =$collectclassesA->concat($SommeA);

                endfor;
            endforeach;
            foreach ($classesB as $classeB):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    // Global de chaque classe (somme rubrique) pour le secteur d'activité
                    $SommeBGlobal = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                        ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                        ->where('exercice','=',$i)
                        ->where('idsousecteur','=',$idsousecteur)
                        ->where('nomClasse','=',$classeB->nomClasse)
                        ->where('nature','=','produit')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesBGlobal =$collectclassesBGlobal->concat($SommeBGlobal);

                    // Global de chaque classe (somme rubrique) pour l'entreprise
                    $SommeB = DB::connection($this->getDB($request))->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeB->nomClasse)
                        ->where('nature','=','produit')
                        ->groupby('nomClasse','nature','exercice')
                        ->get();
                    $collectclassesB =$collectclassesB->concat($SommeB);
                endfor;
            endforeach;
            // Total Actif, GlobalActif, Passifs et GlobalPassif si Bilan choisi
            for($i = $exercice1; $i <= $exercice2; $i++):
                $totalclassesA = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'charge')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->groupBy('exercice')
                    ->get();
                $totalclassesAGlobal = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                    ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                    ->where('exercice','=',$i)
                    ->where('idsousecteur','=',$idsousecteur)
                    ->where('nature','=','charge')
                    ->groupBy('exercice')
                    ->get();
                $totalclassesB = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'produit')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->groupBy('exercice')
                    ->get();
                $totalclassesBGlobal = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                    ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                    ->where('exercice','=',$i)
                    ->where('idsousecteur','=',$idsousecteur)
                    ->where('nature','=','produit')
                    ->groupBy('exercice')
                    ->get();
                // Concataine collection to return resBilan blade
                $collecttotalclassesA = $collecttotalclassesA->concat($totalclassesA);
                $collecttotalclassesB = $collecttotalclassesB->concat($totalclassesB);
                $collecttotalclassesAGlobal = $collecttotalclassesAGlobal->concat($totalclassesAGlobal);
                $collecttotalclassesBGlobal = $collecttotalclassesBGlobal->concat($totalclassesBGlobal);
            endfor;
        endif;
      //$nomEntreprise=explode("-",$input['idEntreprise'])[1];

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
       // dd($infoEntreprises);
        $exercices = DB::connection($dbs)->table('lignebilan')
            ->where('exercice','>=',$exercice1)
            ->where('exercice','<=',$exercice2)
            ->groupBy('exercice')
            ->get('exercice');
        return Excel::download(new BilansExport($input,$collectclassesA,$collectclassesB,$collectclassesAGlobal,$collectclassesBGlobal,
            $collecttotalclassesA,$collecttotalclassesB,$collecttotalclassesAGlobal,$collecttotalclassesBGlobal,
            $classesA,$classesB,$exercices,$infoEntreprises), 'bilans.xlsx',\Maatwebsite\Excel\Excel::XLSX);
    }
    public function import(Request $request)
    {
        $dbs=$this->getDB($request);
        $this->validate($request, [
            'select_file' => 'required|mimes:xls,xlsx'
        ]);
        $rubriques = DB::connection($dbs)->table('rubrique')
            ->get(['idRubrique','nomRubrique','idSousclasse']);
        $souclasses = DB::connection($dbs)->table('sousclasse')
            ->get(['idSousclasse','nomSousclasse','idClasse']);
        $classes = DB::connection($dbs)->table('classe')
            ->get(['idClasse','nomClasse','nature']);
        /*->join('classe as c2','c1.idClasse','c2.idClasse')
            ->where('c1.nomClasse','!=','c2.nomClasse')
            ->where('c1.nature','!=','c2.nature')*/
          $classes1 = DB::connection($dbs)->table('classe')
            ->get(['idClasse','nomClasse','nature']);
      /*foreach ($classes as $classe):
            echo $classe->nomClasse;
        endforeach;*/
        $extension = $request->select_file->extension();
        $path = $request->select_file->storeAs(date('Y'),'upload'.time().'.'.$extension);
        $path = Storage::disk('local')->path($path);
        $reader = new Xlsx();
        $data = array();
        $spreadsheet = $reader->load($path);
        $entreprise=$spreadsheet->getSheet(0)
            ->getCellByColumnAndRow(1,3)->getValue();
        $identreprises=DB::connection($dbs)->table('entreprises')
            ->where('nomEntreprise','=',$entreprise)
        ->get('idEntreprise');
        foreach ($identreprises as $identreprise):
          // echo $identreprise->idEntreprise;
        endforeach;
        $rows  = $spreadsheet->getActiveSheet()->getHighestRow();
        /*$highestColumn = $spreadsheet->getActiveSheet()->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        dd($highestColumnIndex);*/
        for ($year = 2; $year <3; $year++):
            foreach ($classes as $classe):
                for ($row0 = 6; $row0 <=$rows ; $row0++):
                    if (htmlentities($classe->nomClasse)!= htmlentities($spreadsheet->getSheet(0)
                            ->getCellByColumnAndRow(1,$row0)->getValue())):
                        continue;
                    else:
                        foreach ($souclasses as $souclasse):
                            if ($souclasse->idClasse != $classe->idClasse):
                                continue;
                            else:
                                for ($row = $row0; $row <=$rows; $row++):
                                    if (htmlentities($souclasse->nomSousclasse) != htmlentities($spreadsheet->getSheet(0)
                                            ->getCellByColumnAndRow(1,$row)->getValue())):
                                        continue;
                                    else:
                                        foreach ($rubriques as $rubrique):
                                            if ($rubrique->idSousclasse != $souclasse->idSousclasse):
                                                continue;
                                            else:
                                                for($row1 = $row; $row1 <=$rows; $row1++):
                                                    if (htmlentities($rubrique->nomRubrique) != htmlentities($spreadsheet->getSheet(0)
                                                                ->getCellByColumnAndRow(1,$row1)->getValue())):
                                                        continue;
                                                    else:
                                                        $data [] = [
                                                            'nomEntreprise'=>$spreadsheet->getSheet(0)
                                                                ->getCellByColumnAndRow(1, $row1)->getValue(),
                                                            'idRubrique' => $rubrique->idRubrique,
                                                            'idEntreprise' => $identreprise->idEntreprise,
                                                            'exercice' => $spreadsheet->getSheet(0)
                                                                ->getCellByColumnAndRow($year, 5)->getValue(),
                                                            'brut' => $spreadsheet->getSheet(0)
                                                                ->getCellByColumnAndRow(2, $row1)->getValue(),
                                                            'provision' => 0
                                                        ];
                                                    endif;
                                                    break;
                                                endfor;
                                            endif;
                                        endforeach;
                                    endif;
                                    break;
                                endfor;
                            endif;
                        endforeach;
                    endif;
                    continue;
                endfor;
            endforeach;
        endfor;
        dd($data);
        if (!empty($data)):
            DB::connection($dbs)->table('lignebilan')->insert($data);
            return back()->with('success', 'Les données ont été importées avec succès');
        else:
            return back()->with('failled', 'Excel Data Imported failed.');
        endif;
    }
    /**
     * @return mixed
     */
    public function export_pdf(Request $request)
   {
       $collectclassesBGlobal=$collectclassesAGlobal =
       $collectclassesA = $collectclassesB =
       $collecttotalclassesAGlobal = $collecttotalclassesBGlobal =
       $collecttotalclassesA = $collecttotalclassesB =
           collect();
       $input = $request->all();
       if ($request->get('exercice1') > $request->get('exercice2')){
           $exercice1 = $request->get('exercice2');
           $exercice2 = $request->get('exercice1');
       }else
       {
           $exercice1 = $request->get('exercice1');
           $exercice2 = $request->get('exercice2');
           //dump($exercice1);
       }

       #########################################################################
       ################### La verification doit etre dynamique #################
       #########################################################################
       if ($exercice1 > 2000):
           $exercice1 -= 1;
       endif;
       if ($exercice2 < 2017):
           $exercice2 += 1;
       endif;

       $idE = explode('-',$request->get('idEntreprise'))[0];
       $nomEntreprise = explode("-",$request->get('idEntreprise'))[1];
       // dd($nomEntreprise,$exercice1,$idE);
       $dbs = $this->getDB($request);
       $idsousecteur = DB::connection($dbs)->table('ligneservices')
           ->where('idEntreprise','=',$idE)
           ->get('idsouSecteur');
       foreach ($idsousecteur as $item):
           $idsousecteur = $item->idsouSecteur;
       endforeach;

       if ($request->get('document')=='bilan'):

           // Selectionner les classe a afficher en fonction des données recuperées apres post du formulaire
           ########## Actifs ###############
           $classesA = DB::connection($this->getDB($request))->table('classe')
               ->where('nature','=','actif')
               ->orderBy('classe.idClasse','asc')
               ->get('nomClasse');
           //dd($classesA->nomClasse);
           ########## Passifs ###############
           $classesB = DB::connection($this->getDB($request))->table('classe')
               ->where('nature','=','passif')
               ->orderBy('classe.idClasse','asc')
               ->get('nomClasse');

           // En fonction de la classe recupéré la somme des rubriques dans lignebilan en passant par sous classe et rubrique
           foreach ($classesA as $classeA):
               // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
               for($i = $exercice1; $i <= $exercice2; $i++):
                   // Global de chaque classe (somme rubrique) pour le secteur d'activité
                   $SommeAGlobal = DB::connection($dbs)->table('classe')
                       ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                       ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                       ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                       ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                       ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                       ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                       ->where('exercice','=',$i)
                       ->where('idsousecteur','=',$idsousecteur)
                       ->where('nomClasse','=',$classeA->nomClasse)
                       ->where('nature','=','actif')
                       ->groupby('nomClasse','nature','exercice')
                       ->get();
                   $collectclassesAGlobal =$collectclassesAGlobal->concat($SommeAGlobal);

                   // Global de chaque classe (somme rubrique) pour l'entreprise
                   $SommeA = DB::connection($this->getDB($request))->table('classe')
                       ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                       ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                       ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                       ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                       ->where('exercice','=',$i)
                       ->where('lignebilan.idEntreprise' , '=' , $idE)
                       ->where('classe.nomClasse','=',$classeA->nomClasse)
                       ->where('nature','=','actif')
                       ->groupby('nomClasse','nature','exercice')
                       ->get();
                   $collectclassesA =$collectclassesA->concat($SommeA);

               endfor;
           endforeach;
           foreach ($classesB as $classeB):
               // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
               for($i = $exercice1; $i <= $exercice2; $i++):
                   // Global de chaque classe (somme rubrique) pour le secteur d'activité
                   $SommeBGlobal = DB::connection($dbs)->table('classe')
                       ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                       ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                       ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                       ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                       ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                       ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                       ->where('exercice','=',$i)
                       ->where('idsousecteur','=',$idsousecteur)
                       ->where('nomClasse','=',$classeB->nomClasse)
                       ->where('nature','=','passif')
                       ->groupby('nomClasse','nature','exercice')
                       ->get();
                   $collectclassesBGlobal =$collectclassesBGlobal->concat($SommeBGlobal);

                   // Global de chaque classe (somme rubrique) pour l'entreprise
                   $SommeB = DB::connection($this->getDB($request))->table('classe')
                       ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                       ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                       ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                       ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                       ->where('exercice','=',$i)
                       ->where('lignebilan.idEntreprise' , '=' , $idE)
                       ->where('classe.nomClasse','=',$classeB->nomClasse)
                       ->where('nature','=','passif')
                       ->groupby('nomClasse','nature','exercice')
                       ->get();
                   $collectclassesB =$collectclassesB->concat($SommeB);

               endfor;
           endforeach;
           // Total Actif, GlobalActif, Passifs et GlobalPassif si Bilan choisi
           for($i = $exercice1; $i <= $exercice2; $i++):
               $totalclassesA = DB::connection($dbs)->table('classe')
                   ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                   ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                   ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                   ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                   ->where('nature' , '=' , 'actif')
                   ->where('exercice' , '=' , $i)
                   ->where('lignebilan.idEntreprise' , '=' , $idE)
                   ->groupBy('exercice')
                   ->get();
               $totalclassesAGlobal = DB::connection($dbs)->table('classe')
                   ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                   ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                   ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                   ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                   ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                   ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                   ->where('exercice','=',$i)
                   ->where('idsousecteur','=',$idsousecteur)
                   ->where('nature','=','actif')
                   ->groupBy('exercice')
                   ->get();
               $totalclassesB = DB::connection($dbs)->table('classe')
                   ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                   ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                   ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                   ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                   ->where('nature' , '=' , 'passif')
                   ->where('exercice' , '=' , $i)
                   ->where('lignebilan.idEntreprise' , '=' , $idE)
                   ->groupBy('exercice')
                   ->get();
               $totalclassesBGlobal = DB::connection($dbs)->table('classe')
                   ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                   ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                   ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                   ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                   ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                   ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                   ->where('exercice','=',$i)
                   ->where('idsousecteur','=',$idsousecteur)
                   ->where('nature','=','passif')
                   ->groupBy('exercice')
                   ->get();
               // Concataine collection to return resBilan blade
               $collecttotalclassesA = $collecttotalclassesA->concat($totalclassesA);
               $collecttotalclassesB = $collecttotalclassesB->concat($totalclassesB);
               $collecttotalclassesAGlobal = $collecttotalclassesAGlobal->concat($totalclassesAGlobal);
               $collecttotalclassesBGlobal = $collecttotalclassesBGlobal->concat($totalclassesBGlobal);
           endfor;
       endif;
       if ($request->get('document')=='compres'):
           // Selectionner les classe a afficher en fonction des données recuperées apres post ddu formulaire
           ########## Actifs ###############
           $classesA = DB::connection($this->getDB($request))->table('classe')
               ->where('nature','=','charge')
               ->orderBy('classe.idClasse','asc')
               ->get('nomClasse');
           ########## Passifs ###############
           $classesB = DB::connection($this->getDB($request))->table('classe')
               ->where('nature','=','produit')
               ->orderBy('classe.idClasse','asc')
               ->get('nomClasse');

           // En fonction de la classe recupéré la somme des rubriques dans lignebilan en passant par sous classe et rubrique
           foreach ($classesA as $classeA):
               // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
               for($i = $exercice1; $i <= $exercice2; $i++):
                   // Global de chaque classe (somme rubrique) pour le secteur d'activité
                   $SommeAGlobal = DB::connection($dbs)->table('classe')
                       ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                       ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                       ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                       ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                       ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                       ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                       ->where('exercice','=',$i)
                       ->where('idsousecteur','=',$idsousecteur)
                       ->where('nomClasse','=',$classeA->nomClasse)
                       ->where('nature','=','charge')
                       ->groupby('nomClasse','nature','exercice')
                       ->get();
                   $collectclassesAGlobal =$collectclassesAGlobal->concat($SommeAGlobal);

                   // Global de chaque classe (somme rubrique) pour l'entreprise
                   $SommeA = DB::connection($this->getDB($request))->table('classe')
                       ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                       ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                       ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                       ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                       ->where('exercice','=',$i)
                       ->where('lignebilan.idEntreprise' , '=' , $idE)
                       ->where('classe.nomClasse','=',$classeA->nomClasse)
                       ->where('nature','=','charge')
                       ->groupby('nomClasse','nature','exercice')
                       ->get();
                   $collectclassesA =$collectclassesA->concat($SommeA);

               endfor;
           endforeach;
           foreach ($classesB as $classeB):
               // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
               for($i = $exercice1; $i <= $exercice2; $i++):
                   // Global de chaque classe (somme rubrique) pour le secteur d'activité
                   $SommeBGlobal = DB::connection($dbs)->table('classe')
                       ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                       ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                       ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                       ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                       ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                       ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                       ->where('exercice','=',$i)
                       ->where('idsousecteur','=',$idsousecteur)
                       ->where('nomClasse','=',$classeB->nomClasse)
                       ->where('nature','=','produit')
                       ->groupby('nomClasse','nature','exercice')
                       ->get();
                   $collectclassesBGlobal =$collectclassesBGlobal->concat($SommeBGlobal);

                   // Global de chaque classe (somme rubrique) pour l'entreprise
                   $SommeB = DB::connection($this->getDB($request))->table('classe')
                       ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                       ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                       ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                       ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                       ->where('exercice','=',$i)
                       ->where('lignebilan.idEntreprise' , '=' , $idE)
                       ->where('classe.nomClasse','=',$classeB->nomClasse)
                       ->where('nature','=','produit')
                       ->groupby('nomClasse','nature','exercice')
                       ->get();
                   $collectclassesB =$collectclassesB->concat($SommeB);
               endfor;
           endforeach;
           // Total Actif, GlobalActif, Passifs et GlobalPassif si Bilan choisi
           for($i = $exercice1; $i <= $exercice2; $i++):
               $totalclassesA = DB::connection($dbs)->table('classe')
                   ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                   ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                   ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                   ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                   ->where('nature' , '=' , 'charge')
                   ->where('exercice' , '=' , $i)
                   ->where('lignebilan.idEntreprise' , '=' , $idE)
                   ->groupBy('exercice')
                   ->get();
               $totalclassesAGlobal = DB::connection($dbs)->table('classe')
                   ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                   ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                   ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                   ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                   ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                   ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                   ->where('exercice','=',$i)
                   ->where('idsousecteur','=',$idsousecteur)
                   ->where('nature','=','charge')
                   ->groupBy('exercice')
                   ->get();
               $totalclassesB = DB::connection($dbs)->table('classe')
                   ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                   ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                   ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                   ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                   ->where('nature' , '=' , 'produit')
                   ->where('exercice' , '=' , $i)
                   ->where('lignebilan.idEntreprise' , '=' , $idE)
                   ->groupBy('exercice')
                   ->get();
               $totalclassesBGlobal = DB::connection($dbs)->table('classe')
                   ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                   ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                   ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                   ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                   ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                   ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                   ->where('exercice','=',$i)
                   ->where('idsousecteur','=',$idsousecteur)
                   ->where('nature','=','produit')
                   ->groupBy('exercice')
                   ->get();
               // Concataine collection to return resBilan blade
               $collecttotalclassesA = $collecttotalclassesA->concat($totalclassesA);
               $collecttotalclassesB = $collecttotalclassesB->concat($totalclassesB);
               $collecttotalclassesAGlobal = $collecttotalclassesAGlobal->concat($totalclassesAGlobal);
               $collecttotalclassesBGlobal = $collecttotalclassesBGlobal->concat($totalclassesBGlobal);
           endfor;
       endif;
       //$nomEntreprise=explode("-",$input['idEntreprise'])[1];

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
       // dd($infoEntreprises);
       $exercices = DB::connection($dbs)->table('lignebilan')
           ->where('exercice','>=',$exercice1)
           ->where('exercice','<=',$exercice2)
           ->groupBy('exercice')
           ->get('exercice');
       return Excel::download(new BilansExport($input,$collectclassesA,$collectclassesB,$collectclassesAGlobal,$collectclassesBGlobal,
           $collecttotalclassesA,$collecttotalclassesB,$collecttotalclassesAGlobal,$collecttotalclassesBGlobal,
           $classesA,$classesB,$exercices,$infoEntreprises), 'bilans.pdf',\Maatwebsite\Excel\Excel::DOMPDF);
   }

   public function index_import(Request $pays){
       $dbs = $this->getDB($pays);
       $input = $pays->all();
        return view('pages.import')
            ->with('dbs',$dbs)
            ->with('input',$input);
   }
}
