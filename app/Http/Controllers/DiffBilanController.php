<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiffBilanController extends Controller
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
    public function index(Request $request){
        $dbs = $this->getDB($request);
        $lignebilans = DB::connection($dbs)->table('lignebilan')->groupBy('exercice')->get('exercice');
        return view('forms.diff_bilan')
            ->with('pays', $request['pays'])
            ->with('lignebilans', $lignebilans);
    }

    public function local(Request $request){
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

        $dbs = $this->getDB($request);
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
            ->where('idEntreprise',$idE)
            ->orWhere('idEntreprise',$idER)
            ->get('idEntreprise');

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

                // Global de chaque classe (somme rubrique) pour l'entreprise reference
                $SommeAR=DB::connection($this->getDB($request))->table('classe')
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
                $SommeA=DB::connection($this->getDB($request))->table('classe')
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
                $SommeB=DB::connection($this->getDB($request))->table('classe')
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
                $SommeBR=DB::connection($this->getDB($request))->table('classe')
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
        $view = view('pages.resDiffBilan');
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
}
