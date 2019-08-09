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
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Null_;

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
        //$document = $request->get('document');
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
    public function export()
    {
        return Excel::download(new BilansExport, 'bilans.xlsx');
    }
    public function import()
    {
        Excel::import(new BilansImport, request()->file('file'));
        return back();
    }
   public function export_pdf(Request $request)
   {
       $dbs = $this->getDB($request);
       $data=DB::connection($dbs)->table('classe')->get();
       $pdf = PDF::loadView('pdf.classes', $data);
       return $pdf->download('classes.pdf');
   }

}
