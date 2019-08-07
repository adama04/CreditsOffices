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
    function show(Request $request){
        $collectclassesA = $collectclassesB = $collecttotalclassesA = $collecttotalclassesB= $collection= collect();
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
        $idE = explode('-',$request->get('idEntreprise'))[0];
        $dbs = $this->getDB($request);
        // Selectionner en fonction du requet
       // ->select(DB::raw("SUM(users_address.id) as total_address"))


        if ($request->get('document')=='bilan'):

            // Selectionner les classe a afficher en fonction des données recuperées apres post ddu formulaire
                        ########## Actifs ###############
            $classesA = DB::connection($dbs)->table('classe')
                ->where('nature','=','actif')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');
            ########## Passifs ###############
            $classesB = DB::connection($dbs)->table('classe')
                ->where('nature','=','passif')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');

            // En fonction de la classe recupéré la somme des rubriques dans lignebilan en passant par sous classe et rubrique
            foreach ($classesA as $classeA):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    $SommeA = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeA->nomClasse)
                        ->where('nature','=','actif')
                        ->groupby('nomClasse','nature')
                        ->get();
                // Collection pour les actifs
                    $collectclassesA =$collectclassesA->concat($SommeA);
                endfor;
            endforeach;
            foreach ($classesB as $classeB):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    $SommeB = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeB->nomClasse)
                        ->where('nature','=','passif')
                        ->groupby('nomClasse','nature')
                        ->get();

                    // Collection pour les Passifs
                    $collectclassesB =$collectclassesB->concat($SommeB);
                endfor;
            endforeach;
            // Total Actif et Passif si Bilan choisi
            for($i = $exercice1; $i <= $exercice2; $i++):
                $totalclassesA = DB::connection($dbs)->table('classe')
                    ->selectRaw('SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'actif')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->get();
                $totalclassesB = DB::connection($dbs)->table('classe')
                    ->selectRaw('SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'passif')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->get();
                // Concataine collection to return resBilan blade
                $collecttotalclassesA = $collecttotalclassesA->concat($totalclassesA);
                $collecttotalclassesB = $collecttotalclassesB->concat($totalclassesB);
            endfor;
        endif;
        if ($request->get('document')=='compres'):
            // Selectionner les classe a afficher en fonction des données recuperées apres post ddu formulaire
            ########## Charges ###############
            $classesA = DB::connection($dbs)->table('classe')
                ->where('nature','=','charge')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');

            ########## Produits ###############
            $classesB = DB::connection($dbs)->table('classe')
                ->where('nature','=','produit')
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');

            // En fonction de la classe recupéré la somme des rubriques dans lignebilan en passant par sous classe et rubrique
            foreach ($classesA as $classeA):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    $SommeA = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeA->nomClasse)
                        ->where('nature','=','charge')
                        ->groupby('nomClasse','nature')
                        ->get();
                    // Collection pour les Charges
                    $collectclassesA = $collectclassesA ->concat($SommeA) ;
                endfor;
            endforeach;
            foreach ($classesB as $classeB):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for($i = $exercice1; $i <= $exercice2; $i++):
                    $SommeB = DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,SUM(lignebilan.brut) as total')
                        ->join('sousclasse','classe.idClasse','=','sousclasse.idClasse')
                        ->join('rubrique','sousclasse.idSousclasse','=','rubrique.idSousclasse')
                        ->join('lignebilan','rubrique.idRubrique','=','lignebilan.idRubrique')
                        ->where('exercice','=',$i)
                        ->where('lignebilan.idEntreprise' , '=' , $idE)
                        ->where('classe.nomClasse','=',$classeB->nomClasse)
                        ->where('nature','=','produit')
                        ->groupby('nomClasse','nature')
                        ->get();

                    // Collection pour les Produits
                    $collectclassesB =$collectclassesB->concat($SommeB);
                endfor;
            endforeach;
            // Total Charge et Produit si compres choisi
            for($i = $exercice1; $i <= $exercice2; $i++):
                $totalclassesA = DB::connection($dbs)->table('classe')
                    ->selectRaw('SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'charge')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->get();
                $totalclassesB = DB::connection($dbs)->table('classe')
                    ->selectRaw('SUM(lignebilan.brut) as total')
                    ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                    ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                    ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                    ->where('nature' , '=' , 'produit')
                    ->where('exercice' , '=' , $i)
                    ->where('lignebilan.idEntreprise' , '=' , $idE)
                    ->get();
                // Concataine collection to return resBilan blade
                $collecttotalclassesA = $collecttotalclassesA->concat($totalclassesA);
                $collecttotalclassesB = $collecttotalclassesB->concat($totalclassesB);
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
        return view('pages.resBilan')
            ->with('input',$input)
            ->with('collectclassesA',$collectclassesA)
            ->with('collectclassesB',$collectclassesB)
            ->with('collecttotalclassesA',$collecttotalclassesA)
            ->with('collecttotalclassesB',$collecttotalclassesB)
            ->with('classesA',$classesA)
            ->with('classesB',$classesB)
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
