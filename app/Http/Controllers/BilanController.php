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
            $collectclassesAUEMOA = $collectclassesBUEMOA =
            $collecttotalclassesAUEMOA = $collecttotalclassesBUEMOA =
            collect();
//        $this->validate($request, [
//            'idEntreprise' => 'required'
//        ]);
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
        $exercice01 = $exercice1;
        $exercice02 = $exercice2;
        #########################################################################
       ################### La verification doit etre dynamique #################
      #########################################################################
        $idE = explode('-',$request->get('idEntreprise'))[0];
        $dbs = $this->getDB($request);
        $idsousecteur = DB::connection($dbs)->table('ligneservices')
            ->where('idEntreprise','=',$idE)
            ->get('idsouSecteur');
        foreach ($idsousecteur as $item):
            $idsousecteur = $item->idsouSecteur;
        endforeach;
        if ($request->get('naturep') == 'paran'):
            if ($exercice1 > 2000):
                $exercice1 -= 1;
            endif;
            for ($i = $exercice1; $i <= $exercice2; $i++):
                $YEARS [] = $i;
            endfor;
        else:
            $YEARS [] = $exercice01;
            $YEARS [] = $exercice02;
        endif;
        if ($request->get('document')=='bilan'):
            $natureA = 'actif';
            $natureB = 'passif';
        else:
            $natureA = 'charge';
            $natureB = 'produit';
        endif;

            $classesA = DB::connection($this->getDB($request))->table('classe')
                ->where('nature','=',$natureA)
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');
            ########## Passifs ###############
            $classesB = DB::connection($this->getDB($request))->table('classe')
                ->where('nature','=',$natureB)
                ->orderBy('classe.idClasse','asc')
                ->get('nomClasse');

                    // Selectionner les classe a afficher en fonction des données recuperées apres post ddu formulaire
                                ########## Actifs ###############
                    // En fonction de la classe recupéré la somme des rubriques dans lignebilan en passant par sous classe et rubrique
                foreach ($classesA as $classeA):
                    // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                    for ($i=0; $i<count($YEARS); $i++):
                        // Global de chaque classe (somme rubrique) pour le secteur d'activité
                        $SommeAGlobal=DB::connection($dbs)->table('classe')
                            ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                            ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                            ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                            ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                            ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                            ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                            ->where('exercice', '=', $YEARS[$i])
                            ->where('idsousecteur', '=', $idsousecteur)
                            ->where('nomClasse', '=', $classeA->nomClasse)
                            ->where('nature', '=', $natureA)
                            ->groupby('nomClasse', 'nature', 'exercice')
                            ->get();
                        $collectclassesAGlobal=$collectclassesAGlobal->concat($SommeAGlobal);

                        // Global de chaque classe (somme rubrique) pour l'entreprise
                        $SommeA=DB::connection($this->getDB($request))->table('classe')
                            ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                            ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                            ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                            ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                            ->where('exercice', '=', $YEARS[$i])
                            ->where('lignebilan.idEntreprise', '=', $idE)
                            ->where('classe.nomClasse', '=', $classeA->nomClasse)
                            ->where('nature', '=', $natureA)
                            ->groupby('nomClasse', 'nature', 'exercice')
                            ->get();

                        $collectclassesA=$collectclassesA->concat($SommeA);
                        if($input['localite'] == 'uemoa'):

                            $SommeAUEMOA=DB::connection('sensyyg2_umeoabd')->table('classe')
                                ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                                ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                                ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                                ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                                ->where('exercice', '=', $YEARS[$i])
                                ->where('nomClasse', '=', $classeA->nomClasse)
                                ->where('nature', '=', $natureA)
                                ->groupby('nomClasse', 'nature', 'exercice')
                                ->get();
                            $collectclassesAUEMOA=$collectclassesAUEMOA->concat($SommeAUEMOA);
                        endif;
                    endfor;
                endforeach;
            foreach ($classesB as $classeB):
                // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
                for ($i=0; $i<count($YEARS); $i++):
                    // Global de chaque classe (somme rubrique) pour le secteur d'activité
                    $SommeBGlobal=DB::connection($dbs)->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                        ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                        ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                        ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                        ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                        ->where('exercice', '=', $YEARS[$i])
                        ->where('idsousecteur', '=', $idsousecteur)
                        ->where('nomClasse', '=', $classeB->nomClasse)
                        ->where('nature', '=', $natureB)
                        ->groupby('nomClasse', 'nature', 'exercice')
                        ->get();
                    $collectclassesBGlobal=$collectclassesBGlobal->concat($SommeBGlobal);

                    // Global de chaque classe (somme rubrique) pour l'entreprise
                    $SommeB=DB::connection($this->getDB($request))->table('classe')
                        ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                        ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                        ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                        ->where('exercice', '=', $YEARS[$i])
                        ->where('lignebilan.idEntreprise', '=', $idE)
                        ->where('classe.nomClasse', '=', $classeB->nomClasse)
                        ->where('nature', '=', $natureB)
                        ->groupby('nomClasse', 'nature', 'exercice')
                        ->get();
                    $collectclassesB=$collectclassesB->concat($SommeB);

                    if($input['localite'] == 'uemoa'):
                        $SommeBUEMOA=DB::connection('sensyyg2_umeoabd')->table('classe')
                            ->selectRaw('nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                            ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                            ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                            ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                            ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                            ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                            ->where('exercice', '=', $YEARS[$i])
                            ->where('idsousecteur', '=', $idsousecteur)
                            ->where('nomClasse', '=', $classeB->nomClasse)
                            ->where('nature', '=', $natureB)
                            ->groupby('nomClasse', 'nature', 'exercice')
                            ->get();
                        $collectclassesBUEMOA=$collectclassesBUEMOA->concat($SommeBUEMOA);
                    endif;
                endfor;
            endforeach;
                    for ($i=0; $i<count($YEARS); $i++):
                        $totalclassesA = DB::connection($dbs)->table('classe')
                            ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                            ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                            ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                            ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                            ->where('nature' , '=' , $natureA)
                            ->where('exercice' , '=' , $YEARS[$i])
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
                            ->where('exercice','=',$YEARS[$i])
                            ->where('idsousecteur','=',$idsousecteur)
                            ->where('nature','=',$natureA)
                            ->groupBy('exercice')
                            ->get();
                        if($input['localite'] == 'uemoa'):
                            $totalclassesAUEMOA = DB::connection('sensyyg2_umeoabd')->table('classe')
                                ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                                ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                                ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                                ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                                ->where('exercice','=',$YEARS[$i])
                                ->where('nature','=',$natureA)
                                ->groupBy('exercice')
                                ->get();

                            $totalclassesBUEMOA = DB::connection('sensyyg2_umeoabd')->table('classe')
                                ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                                ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                                ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                                ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                                ->join('entreprises','lignebilan.idEntreprise','=','entreprises.idEntreprise')
                                ->join('ligneservices','entreprises.idEntreprise','=','ligneservices.idEntreprise')
                                ->where('exercice','=',$YEARS[$i])
                                ->where('idsousecteur','=',$idsousecteur)
                                ->where('nature','=',$natureB)
                                ->groupBy('exercice')
                                ->get();

                            $collecttotalclassesAUEMOA = $collecttotalclassesAUEMOA->concat($totalclassesAUEMOA);
                            $collecttotalclassesBUEMOA = $collecttotalclassesBUEMOA->concat($totalclassesBUEMOA);
                            endif;

                            $totalclassesB = DB::connection($dbs)->table('classe')
                            ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                            ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                            ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                            ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                            ->where('nature' , '=' , $natureB)
                            ->where('exercice' , '=' , $YEARS[$i])
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
                            ->where('exercice','=',$YEARS[$i])
                            ->where('idsousecteur','=',$idsousecteur)
                            ->where('nature','=',$natureB)
                            ->groupBy('exercice')
                            ->get();


                        $collecttotalclassesA = $collecttotalclassesA->concat($totalclassesA);
                        $collecttotalclassesAGlobal = $collecttotalclassesAGlobal->concat($totalclassesAGlobal);
                        $collecttotalclassesB = $collecttotalclassesB->concat($totalclassesB);
                        $collecttotalclassesBGlobal = $collecttotalclassesBGlobal->concat($totalclassesBGlobal);

                    endfor;
        $nomEntreprise=explode("-",$input['idEntreprise'])[0];
        $infoEntreprises = DB::connection($dbs)->table('entreprises')
            ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
            ->join('service', 'ligneservices.idService', '=', 'service.idService')
            ->join('sousecteur', 'sousecteur.idSousecteur', '=', 'ligneservices.idSousecteur')
            ->join('secteur', 'secteur.idSecteur', '=', 'sousecteur.idSecteur')
            ->select('sousecteur.idsouSecteur', 'secteur.idSecteur', 'service.idService', 'entreprises.numRegistre', 'entreprises.nomEntreprise',
                'entreprises.idEntreprise', 'entreprises.Adresse', 'entreprises.Sigle', 'entreprises.codePays', 'entreprises.codeRegion',
                'entreprises.Pays', 'entreprises.type', 'entreprises.dateCreation', 'entreprises.numEnregistre', 'sousecteur.nomsouSecteur', 'service.nomService',
                'secteur.nomSecteur')->where('entreprises.idEntreprise','=',$nomEntreprise)
            ->get();
        if ($request->get('naturep') == 'paran'):
            $exercices = DB::connection($dbs)->table('lignebilan')
                ->where('exercice','>=',$exercice01)
                ->where('exercice','<=',$exercice02)
                ->groupBy('exercice')
                ->get('exercice');
        else:
            $exercices = DB::connection($dbs)->table('lignebilan')
                ->where('exercice','=',$exercice01)
                ->orwhere('exercice','=',$exercice02)
                ->groupBy('exercice')
                ->get('exercice');
        endif;
        $view = view('pages.resBilan');
        $view->input = $input;
        $view->collectclassesA = $collectclassesA;
        $view->collectclassesAGlobal = $collectclassesAGlobal;
        $view->collecttotalclassesA = $collecttotalclassesA;
        $view->collecttotalclassesAGlobal = $collecttotalclassesAGlobal;
        $view->classesA = $classesA;
        $view->exercices = $exercices;
        $view->infoEntreprises = $infoEntreprises;
        $view->collectclassesB = $collectclassesB;
        $view->collectclassesBGlobal = $collectclassesBGlobal;
        $view->collecttotalclassesB = $collecttotalclassesB;
        $view->collecttotalclassesBGlobal = $collecttotalclassesBGlobal;
        $view->classesB = $classesB;
        if($input['localite'] == 'uemoa'):
            $view->collecttotalclassesAUEMOA = $collecttotalclassesAUEMOA;
            $view->collecttotalclassesBUEMOA = $collecttotalclassesBUEMOA;
            $view->collectclassesAUEMOA = $collectclassesAUEMOA;
             $view->collectclassesBUEMOA = $collectclassesBUEMOA;
        endif;
        return $view;
//        return view('pages.resBilan',compact(
//            'input','collectclassesA',
//            'collectclassesAGlobal', 'collecttotalclassesA',
//            'collecttotalclassesAGlobal', 'classesA',
//            'exercices', 'infoEntreprises',
//            'collectclassesB',
//            'collectclassesBGlobal', 'collecttotalclassesB',
//            'collecttotalclassesBGlobal', 'classesB'
//        ));

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
