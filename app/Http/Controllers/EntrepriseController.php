<?php

namespace App\Http\Controllers;
use App\Models\Entreprises;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EntrepriseController extends Controller
{
    /**
     * Function for standard bilan in Activity Sector
     */
    function index_bilan(Request $pays){

        $dbs = getDB($pays);
        $lignebilans = DB::connection($dbs)->table('lignebilan')->groupBy('exercice')->get('exercice');
        return view('forms.rech_bilan')
            ->with('pays', $pays['pays'])
            ->with('lignebilans', $lignebilans);
    }
    function bilan(Request $request){
        $collectclassesBGlobal=$collectclassesAGlobal =
        $collectclassesA = $collectclassesB =
        $collecttotalclassesAGlobal = $collecttotalclassesBGlobal =
        $collecttotalclassesA = $collecttotalclassesB =
        $collectclassesAUEMOA = $collectclassesBUEMOA =
        $collecttotalclassesAUEMOA = $collecttotalclassesBUEMOA =
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
        $exercice01 = $exercice1;
        $exercice02 = $exercice2;
        #########################################################################
        ################### La verification doit etre dynamique #################
        #########################################################################
        $idE = explode('-',$request->get('idEntreprise'))[0];
        $nomEntreprise = explode('-',$request->get('idEntreprise'))[1];

        $dbs = getDB($request);
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
            $YEARS [] = $exercice01; $YEARS [] = $exercice02;
        endif;
        if ($request->get('document')=='bilan'):
            $natureA = 'actif'; $natureB = 'passif';
        else:
            $natureA = 'charge'; $natureB = 'produit';
        endif;
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

        $classesA = DB::connection($dbs)->table('classe')
            ->where('nature','=',$natureA)
            ->orderBy('classe.idClasse','asc')
            ->get('nomClasse');
        ########## Passifs ###############
        $classesB = DB::connection($dbs)->table('classe')
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
                $SommeA=DB::connection($dbs)->table('classe')
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
                $SommeB=DB::connection($dbs)->table('classe')
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
                ->where('exercice','=',$YEARS[$i])
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


            // Concataine collection to return resBilan blade
            $collecttotalclassesA = $collecttotalclassesA->concat($totalclassesA);
            $collecttotalclassesB = $collecttotalclassesB->concat($totalclassesB);
            $collecttotalclassesAGlobal = $collecttotalclassesAGlobal->concat($totalclassesAGlobal);
            $collecttotalclassesBGlobal = $collecttotalclassesBGlobal->concat($totalclassesBGlobal);
        endfor;

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
        $view = view('pages.entreprises.bilan');
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
    }

    /**
     *Function For difference bilan of entreprise in same Activity
     */
    function index_bilan_diff(Request $request){
        $dbs = getDB($request);
        $lignebilans = DB::connection($dbs)->table('lignebilan')->groupBy('exercice')->get('exercice');
        return view('forms.diff_bilan')
            ->with('pays', $request['pays'])
            ->with('lignebilans', $lignebilans);
    }
    function bilan_diff(Request $request){
        $collectclassesA = $collectclassesB =
        $collectclassesAR = $collectclassesBR =
        $collecttotalclassesA = $collecttotalclassesB =
        $collecttotalclassesAR = $collecttotalclassesBR =
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
        $exercice01 = $exercice1;
        $exercice02 = $exercice2;
        #########################################################################
        ################### La verification doit etre dynamique #################
        #########################################################################
        $idER = explode('-',$request->get('idEntrepriser'))[0];
        $nomEntrepriseR = explode('-',$request->get('idEntrepriser'))[1];
        $idE = explode('-',$request->get('idEntreprise'))[0];
        $nomEntreprise = explode('-',$request->get('idEntreprise'))[1];

        $dbs = getDB($request);
        $idsousecteur = DB::connection($dbs)->table('ligneservices')
            ->where('idEntreprise','=',$idER)
            ->get('idsouSecteur');
        foreach ($idsousecteur as $item):
            $idsousecteur = $item->idsouSecteur;
        endforeach;
        if ($request->get('naturep') == 'paran'):
            for ($i = $exercice1; $i <= $exercice2; $i++):
                $YEARS [] = $i;
            endfor;
        else:
            $YEARS [] = $exercice01; $YEARS [] = $exercice02;
        endif;
        if ($request->get('document')=='bilan'):
            $natureA = 'actif'; $natureB = 'passif';
        else:
            $natureA = 'charge';  $natureB = 'produit';
        endif;
        $infoEntrepriseR = DB::connection($dbs)->table('entreprises')
            ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
            ->join('service', 'ligneservices.idService', '=', 'service.idService')
            ->join('sousecteur', 'sousecteur.idSousecteur', '=', 'ligneservices.idSousecteur')
            ->join('secteur', 'secteur.idSecteur', '=', 'sousecteur.idSecteur')
            ->select('sousecteur.idsouSecteur', 'secteur.idSecteur', 'service.idService', 'entreprises.numRegistre', 'entreprises.nomEntreprise',
                'entreprises.idEntreprise', 'entreprises.Adresse', 'entreprises.Sigle', 'entreprises.codePays', 'entreprises.codeRegion',
                'entreprises.Pays', 'entreprises.type', 'entreprises.dateCreation', 'entreprises.numEnregistre', 'sousecteur.nomsouSecteur', 'service.nomService',
                'secteur.nomSecteur')->where('entreprises.idEntreprise','=',$idER)
            ->get();
        $infoEntreprise = DB::connection($dbs)->table('entreprises')
            ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
            ->join('service', 'ligneservices.idService', '=', 'service.idService')
            ->join('sousecteur', 'sousecteur.idSousecteur', '=', 'ligneservices.idSousecteur')
            ->join('secteur', 'secteur.idSecteur', '=', 'sousecteur.idSecteur')
            ->select('sousecteur.idsouSecteur', 'secteur.idSecteur', 'service.idService', 'entreprises.numRegistre', 'entreprises.nomEntreprise',
                'entreprises.idEntreprise', 'entreprises.Adresse', 'entreprises.Sigle', 'entreprises.codePays', 'entreprises.codeRegion',
                'entreprises.Pays', 'entreprises.type', 'entreprises.dateCreation', 'entreprises.numEnregistre', 'sousecteur.nomsouSecteur', 'service.nomService',
                'secteur.nomSecteur')->where('entreprises.idEntreprise','=',$idE)
            ->get();

        $entreprises = DB::connection($dbs)->table('entreprises')
            ->where('idEntreprise',$idE)->orWhere('idEntreprise',$idER)->get('idEntreprise');

        $classesA = DB::connection($dbs)->table('classe')
            ->where('nature','=',$natureA)->orderBy('classe.idClasse','asc')
            ->get('nomClasse');
        ########## Passifs ###############
        $classesB = DB::connection($dbs)->table('classe')
            ->where('nature','=',$natureB)->orderBy('classe.idClasse','asc')
            ->get('nomClasse');

        // Selectionner les classe a afficher en fonction des données recuperées apres post ddu formulaire
        ########## Actifs ###############
        // En fonction de la classe recupéré la somme des rubriques dans lignebilan en passant par sous classe et rubrique
        foreach ($classesA as $classeA):
            // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
            for ($i=0; $i<count($YEARS); $i++):

                // Global de chaque classe (somme rubrique) pour l'entreprise reference
                $SommeAR=DB::connection($dbs)->table('classe')
                    ->selectRaw('idEntreprise,nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                    ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                    ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                    ->where('exercice', '=', $YEARS[$i])
                    ->where('lignebilan.idEntreprise', '=', $idER)
                    ->where('classe.nomClasse', '=', $classeA->nomClasse)
                    ->where('nature', '=', $natureA)
                    ->groupby('nomClasse', 'nature', 'exercice','idEntreprise')
                    ->get();
                // Global de chaque classe (somme rubrique) pour l'entreprise
                $SommeA=DB::connection($dbs)->table('classe')
                    ->selectRaw('idEntreprise,nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                    ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                    ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                    ->where('exercice', '=', $YEARS[$i])
                    ->where('lignebilan.idEntreprise', '=', $idE)
                    ->where('classe.nomClasse', '=', $classeA->nomClasse)
                    ->where('nature', '=', $natureA)
                    ->groupby('nomClasse', 'nature', 'exercice','idEntreprise')
                    ->get();

                $collectclassesAR=$collectclassesAR->concat($SommeAR);
                $collectclassesA=$collectclassesA->concat($SommeA);
            endfor;
        endforeach;
        foreach ($classesB as $classeB):
            // Recuperer pour chaque année d'exercice la somme des rubrique de chaque classe
            for ($i=0; $i<count($YEARS); $i++):
                // Global de chaque classe (somme rubrique) pour l'entreprise
                $SommeB=DB::connection($dbs)->table('classe')
                    ->selectRaw('idEntreprise,nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                    ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                    ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                    ->where('exercice', '=', $YEARS[$i])
                    ->where('lignebilan.idEntreprise', '=', $idE)
                    ->where('classe.nomClasse', '=', $classeB->nomClasse)
                    ->where('nature', '=', $natureB)
                    ->groupby('nomClasse', 'nature', 'exercice','idEntreprise')
                    ->get();
                $SommeBR=DB::connection($dbs)->table('classe')
                    ->selectRaw('idEntreprise,nomClasse,nature,exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                    ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                    ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                    ->where('exercice', '=', $YEARS[$i])
                    ->where('lignebilan.idEntreprise', '=', $idER)
                    ->where('classe.nomClasse', '=', $classeB->nomClasse)
                    ->where('nature', '=', $natureB)
                    ->groupby('nomClasse', 'nature', 'exercice','idEntreprise')
                    ->get();
                $collectclassesBR=$collectclassesBR->concat($SommeBR);
                $collectclassesB=$collectclassesB->concat($SommeB);

            endfor;
        endforeach;
        for ($i=0; $i<count($YEARS); $i++):
            $totalclassesAR = DB::connection($dbs)->table('classe')
                ->selectRaw('idEntreprise,exercice,SUM(lignebilan.brut) as total')
                ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                ->where('nature' , '=' , $natureA)
                ->where('exercice' , '=' , $YEARS[$i])
                ->where('lignebilan.idEntreprise' , '=' , $idER)
                ->groupBy('exercice','idEntreprise')
                ->get();
            $totalclassesA = DB::connection($dbs)->table('classe')
                ->selectRaw('idEntreprise,exercice,SUM(lignebilan.brut) as total')
                ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                ->where('nature' , '=' , $natureA)
                ->where('exercice' , '=' , $YEARS[$i])
                ->where('lignebilan.idEntreprise' , '=' , $idE)
                ->groupBy('exercice','idEntreprise')
                ->get();

            $totalclassesBR = DB::connection($dbs)->table('classe')
                ->selectRaw('idEntreprise,exercice,SUM(lignebilan.brut) as total')
                ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                ->where('nature' , '=' , $natureB)
                ->where('exercice' , '=' , $YEARS[$i])
                ->where('lignebilan.idEntreprise' , '=' , $idER)
                ->groupBy('exercice','idEntreprise')
                ->get();
            $totalclassesB = DB::connection($dbs)->table('classe')
                ->selectRaw('idEntreprise,exercice,SUM(lignebilan.brut) as total')
                ->join('sousclasse' , 'classe.idClasse' , '=' , 'sousclasse.idClasse')
                ->join('rubrique' , 'sousclasse.idSousclasse' , '=' , 'rubrique.idSousclasse')
                ->join('lignebilan' , 'rubrique.idRubrique' , '=' , 'lignebilan.idRubrique')
                ->where('nature' , '=' , $natureB)
                ->where('exercice' , '=' , $YEARS[$i])
                ->where('lignebilan.idEntreprise' , '=' , $idE)
                ->groupBy('exercice','idEntreprise')
                ->get();
            // Concataine collection to return resBilan blade
            $collecttotalclassesAR = $collecttotalclassesAR->concat($totalclassesAR);
            $collecttotalclassesBR = $collecttotalclassesBR->concat($totalclassesBR);
            $collecttotalclassesA = $collecttotalclassesA->concat($totalclassesA);
            $collecttotalclassesB = $collecttotalclassesB->concat($totalclassesB);

        endfor;

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
        $view = view('pages.entreprises.difference');
        $view->input = $input;
        $view->collectclassesAR = $collectclassesAR;
        $view->collectclassesA = $collectclassesA;
        $view->collecttotalclassesAR = $collecttotalclassesAR;
        $view->collecttotalclassesA = $collecttotalclassesA;
        $view->classesA = $classesA;
        $view->exercices = $exercices;
        $view->infoEntreprisesR = $infoEntrepriseR;
        $view->infoEntreprises = $infoEntreprise;
        $view->collectclassesBR = $collectclassesBR;
        $view->collectclassesB = $collectclassesB;
        $view->collecttotalclassesB = $collecttotalclassesB;
        $view->collecttotalclassesBR = $collecttotalclassesBR;
        $view->classesB = $classesB;
        $view->entreprises = $entreprises;
        return $view;
    }

    /**
     *Function for Poste Bilan
     */
    function index_bilan_post(Request $request){
        $dbs = getDB($request);
        $lignebilans = DB::connection($dbs)->table('lignebilan')->groupBy('exercice')->get('exercice');
        $natures = DB::connection($dbs)->table('classe')->groupBy('nature')->get('nature');
        $postes = DB::connection($dbs)->table('rubrique')->get(['nomRubrique', 'idRubrique']);
        $view = view('forms.poste_bilan');
        $view->pays = $request['pays'];
        $view->lignebilans = $lignebilans;
        $view->natures = $natures;
        $view->postes = $postes;
        return $view;
    }
    function bilan_post(Request $request){
        $collectPoste = $collecttotalPoste = $collectSameNature = $collectSameNatureCountry = $collectEntreprise
            = $collectPosteUEMOA = $collectSameNatureUEMOA
            = collect();
        $input = $request->all();

        ################ Uniformisation of parameters ########################
        if ($request->exercice1 > $request->exercice2) {
            $exercice1 = $request->exercice2;
            $exercice2 = $request->exercice1;
        } else {
            $exercice1 = $request->get('exercice1');
            $exercice2 = $request->get('exercice2');
        }

        ################# Recupes values provide to requeste form et customise if not #############
        $exercice01 = $exercice1;
        $poste = $request->poste;
        $nature = $request->nature;
        $dbs = getDB($request);
        $idE = explode('-', $request->idEntreprise)[0];
//        $nomEntreprise = explode('-', $request->idEntreprise)[1];
//        $naturep = $request->naturep;
//        $document = $request->document;
//        $localite = $request->localite;

        if ($exercice1 > 2000) {
            $exercice1 -= 1;
        }
        ############# Take good intervall ############
        if ($request->get('naturep') == 'paran'):
            for ($i = $exercice1; $i <= $exercice2; $i++):
                $YEARS [] = $i;
            endfor;
        else:
            $YEARS [] = $exercice01;
            $YEARS [] = $exercice2;
        endif;
        ####### End of recuperation ##########

        $Poste_ = DB::connection($dbs)->table('rubrique')
            ->where('idRubrique', $poste)
            ->first();
        $Sigle = DB::connection($dbs)->table('entreprises')
            ->where('idEntreprise',$idE)
            ->first();

        ### If Local Post
        ########### Select Poste val for either year ####
        for ($i = 0; $i < count($YEARS); $i++):
            /*
             * Poste for selected Entreprise
             */
            $PostEntreprise = DB::connection($dbs)
                ->table('lignebilan')
                ->where('idEntreprise', $idE)
                ->where('idRubrique', $poste)
                ->where('exercice', $YEARS[$i])
                ->get(['brut', 'idEntreprise', 'exercice']);
            $collectPoste = $collectPoste->concat($PostEntreprise);
            /*
            * Sum All Poste same nature of selected poste in same Entreprise.*
            */
            $PostEntreprise = DB::connection($dbs)->table('lignebilan')
                ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                ->join('rubrique', 'lignebilan.idRubrique', '=', 'rubrique.idRubrique')
                ->join('sousclasse', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                ->join('classe', 'classe.idClasse', '=', 'sousclasse.idClasse')
                ->where('classe.nature', $nature)
                ->where('lignebilan.idEntreprise', $idE)
                ->where('exercice', $YEARS[$i])
                ->groupBy('exercice')
                ->get();
            $collectSameNature = $collectSameNature->concat($PostEntreprise);
            /*
             * Poste for Selected Country
             */
            $PostEntreprise = DB::connection($dbs)->table('lignebilan')
                ->selectRaw('nomRubrique,exercice,SUM(lignebilan.brut) as total')
                ->join('rubrique', 'lignebilan.idRubrique', '=', 'rubrique.idRubrique')
                ->where('rubrique.idRubrique', $poste)
                ->where('exercice', $YEARS[$i])
                ->groupBy('exercice', 'nomRubrique')
                ->get();
            $collecttotalPoste = $collecttotalPoste->concat($PostEntreprise);
            /*
            * Sum All Poste same nature of selected poste in same Country.*
            */
            $PostEntreprise = DB::connection($dbs)->table('lignebilan')
                ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                ->join('rubrique', 'lignebilan.idRubrique', '=', 'rubrique.idRubrique')
                ->join('sousclasse', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                ->join('classe', 'classe.idClasse', '=', 'sousclasse.idClasse')
                ->where('classe.nature', $nature)
                ->where('exercice', $YEARS[$i])
                ->groupBy('exercice')
                ->get();
            $collectSameNatureCountry = $collectSameNatureCountry->concat($PostEntreprise);
            ##### If UEMOA Post
            if ($request->localite == 'uemoa'):
                /*
             * Poste for Selected Poste in UEMOA
             */
                $con = 'sensyyg2_umeoabd';
                $PostEntreprise = DB::connection($con)->table('lignebilan')
                    ->selectRaw('nomRubrique,exercice,SUM(lignebilan.brut) as total')
                    ->join('rubrique', 'lignebilan.idRubrique', '=', 'rubrique.idRubrique')
                    ->where('rubrique.idRubrique', $poste)
                    ->where('exercice', $YEARS[$i])
                    ->groupBy('exercice', 'nomRubrique')
                    ->get();
                $collectPosteUEMOA = $collectPosteUEMOA->concat($PostEntreprise);
                /*
                * Sum All Poste same nature of selected poste in UEMOA.*
                */
                $PostEntreprise = DB::connection($con)->table('lignebilan')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('rubrique', 'lignebilan.idRubrique', '=', 'rubrique.idRubrique')
                    ->join('sousclasse', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                    ->join('classe', 'classe.idClasse', '=', 'sousclasse.idClasse')
                    ->where('classe.nature', $nature)
                    ->where('exercice', $YEARS[$i])
                    ->groupBy('exercice')
                    ->get();
                $collectSameNatureUEMOA = $collectSameNatureUEMOA->concat($PostEntreprise);
            endif;
        endfor;
        if ($request->get('naturep') == 'paran'):
            $exercices = DB::connection($dbs)->table('lignebilan')
                ->where('exercice', '>=', $exercice01)
                ->where('exercice', '<=', $exercice2)
                ->groupBy('exercice')
                ->get('exercice');
        else:
            $exercices = DB::connection($dbs)->table('lignebilan')
                ->where('exercice', '=', $exercice01)
                ->orwhere('exercice', '=', $exercice2)
                ->groupBy('exercice')
                ->get('exercice');
        endif;
        $view = view('pages.entreprises.poste');
        $view->input = $input;
        $view->exercices = $exercices;
        $view->collectPostes = $collectPoste;
        $view->Poste_ = $Poste_;
        $view->Sigle = $Sigle;
        $view->collecttotalPostes = $collecttotalPoste;
        $view->collectSameNatures = $collectSameNature;
        $view->collectSameNatureCountries = $collectSameNatureCountry;
        if ($request->localite == 'uemoa'):
            $view->collectPosteUEMOA = $collectPosteUEMOA;
            $view->collectSameNatureUEMOA = $collectSameNatureUEMOA;
        endif;
        return $view;
    }
}
