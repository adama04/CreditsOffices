<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectorActivityController extends Controller
{
    /**
     * Function for standard bilan in Activity Sector
     */
    function index_bilan(Request $request){
        $dbs = getDB($request);
        $lignebilans = DB::connection($dbs)->table('lignebilan')->groupBy('exercice')->get('exercice');
       $view = view('forms.sectors.bilan_form');
        $view->pays = $request['pays'];
        $view->lignebilans = $lignebilans;
      return $view;
    }

    function bilan(){
        dd('Bilan');

    }

    /**
     *Function For difference bilan of entreprise in same Activity
     */
    function index_bilan_df(Request $request){
        $dbs = getDB($request);
        $lignebilans = DB::connection($dbs)->table('lignebilan')->groupBy('exercice')->get('exercice');
        $view = view('forms.sectors.df_bilan_form');
        $view->pays = $request['pays'];
        $view->lignebilans = $lignebilans;
        return $view;
    }
    function bilan_df(){
        dd('Bilan Df');
    }

    /**
     *Function for Poste Bilan
     */
    function index_bilan_post(Request $request){


        $dbs = getDB($request);
        $lignebilans = DB::connection($dbs)->table('lignebilan')->groupBy('exercice')->get('exercice');
        $natures = DB::connection($dbs)->table('classe')->groupBy('nature')->get('nature');
        $postes = DB::connection($dbs)->table('rubrique')->get(['nomRubrique', 'idRubrique']);
        $view = view('forms.sectors.poste_bilan_form');
        $view->pays = $request['pays'];
        $view->lignebilans = $lignebilans;
        $view->natures = $natures;
        $view->postes = $postes;
        return $view;
    }
    function bilan_post(Request $request){

        $collectCountries = $collectSameNatureCountries =
        $collectSameNatureUEMOA = $collectUEMOA = $entreprises =
           $collectEntreprises = $collectSameNatureEntreprises =  collect();
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
        $dbs = getDB($request);
        $conx = env('DB_DATABASE_0');
        $idSector = DB::connection($dbs)->table('sousecteur')
            ->where('nomsouSecteur',  $request->secteur)
            ->first('idsouSecteur');
        $poste = DB::connection($dbs)->table('rubrique')->where('idRubrique',$request->poste)
            ->first('nomRubrique');
        if ($request->localite == 'pays'):
            $Countries = DB::connection($dbs)->table('pays')->where('idPays',$request->pays)
                ->first(['idPays','nomPays','bdPays']);
            $entreprises = DB::connection($Countries->bdPays)->table('ligneservices')
                ->join('entreprises','ligneservices.idEntreprise','=','entreprises.idEntreprise')
                ->join('lignebilan','entreprises.idEntreprise','=','lignebilan.idEntreprise')
                ->whereIn('exercice',$YEARS)
                ->where('ligneservices.idsouSecteur',$idSector->idsouSecteur)
                ->groupBy('entreprises.idEntreprise','entreprises.nomEntreprise','entreprises.Sigle')
                ->get(['entreprises.idEntreprise','entreprises.nomEntreprise','entreprises.Sigle']);
            for ($i=0; $i<count($YEARS); $i++):
                /*
                 * Select Country Poste
                 */
                $Poste = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                    ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                    ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                    ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                    ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                    ->where('exercice',  $YEARS[$i])
                    ->where('idsousecteur',  $idSector->idsouSecteur)
                    ->where('rubrique.idRubrique',  $request->poste)
                    ->groupby( 'exercice')
                    ->get();
                $collectCountries = $collectCountries->concat($Poste);
                /*
                 * Select Country Same Nature Of Poste
                 */
                $Poste = DB::connection($dbs)->table('classe')
                    ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                    ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                    ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                    ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                    ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                    ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                    ->where('exercice',  $YEARS[$i])
                    ->where('idsousecteur',  $idSector->idsouSecteur)
                    ->where('nature',  $request->nature)
                    ->groupby( 'exercice')
                    ->get();
                $collectSameNatureCountries = $collectSameNatureCountries->concat($Poste);
            endfor;
            foreach ($entreprises as $entreprise):
                for ($i=0; $i<count($YEARS); $i++):
                    /*
                     * Poste for ether entreprise in ether year
                     */
                    $Poste = DB::connection($dbs)->table('classe')
                        ->selectRaw('entreprises.idEntreprise, entreprises.nomEntreprise,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                        ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                        ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                        ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                        ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                        ->where('exercice',  $YEARS[$i])
                        ->where('idsousecteur',  $idSector->idsouSecteur)
                        ->where('rubrique.idRubrique',  $request->poste)
                        ->where('entreprises.idEntreprise',  $entreprise->idEntreprise)
                        ->groupby( 'exercice','entreprises.idEntreprise','entreprises.nomEntreprise')
                        ->get();
                    $collectEntreprises = $collectEntreprises->concat($Poste);
                    /*
                    * Poste same Nature for ether entreprise in ether year
                    */
                    $Poste = DB::connection($dbs)->table('classe')
                        ->selectRaw('entreprises.idEntreprise,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                        ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                        ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                        ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                        ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                        ->where('exercice',  $YEARS[$i])
                        ->where('idsousecteur',  $idSector->idsouSecteur)
                        ->where('nature',  $request->nature)
                        ->where('entreprises.idEntreprise',  $entreprise->idEntreprise)
                        ->groupby( 'exercice','entreprises.idEntreprise')
                        ->get();
                    $collectSameNatureEntreprises = $collectSameNatureEntreprises->concat($Poste);
                endfor;
            endforeach;

        else:

            $Countries = DB::connection($conx)->table('pays')->where('cedeao','ce')
                ->get(['idPays','nomPays','bdPays']);
            foreach ($Countries as $pay):
                for ($i=0; $i<count($YEARS); $i++):
                    /*
                     * Select Country Poste
                     */
                    $Poste = DB::connection($pay->bdPays)->table('classe')
                        ->selectRaw($pay->idPays.' as idPays ,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                        ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                        ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                        ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                        ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                        ->where('exercice',  $YEARS[$i])
                        ->where('idsousecteur',  $idSector->idsouSecteur)
                        ->where('rubrique.idRubrique',  $request->poste)
                        ->groupby( 'exercice')
                        ->get();
                    $collectCountries = $collectCountries->concat($Poste);
                    /*
                     * Select Country Same Nature Of Poste
                     */
                    $Poste = DB::connection($pay->bdPays)->table('classe')
                        ->selectRaw($pay->idPays.' as idPays ,exercice,SUM(lignebilan.brut) as total')
                        ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                        ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                        ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                        ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                        ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                        ->where('exercice',  $YEARS[$i])
                        ->where('idsousecteur',  $idSector->idsouSecteur)
                        ->where('nature',  $request->nature)
                        ->groupby( 'exercice')
                        ->get();
                    $collectSameNatureCountries = $collectSameNatureCountries->concat($Poste);
                endfor;
                /*
                 * Select Entreprise for this Countries
                  */

                $e = DB::connection($pay->bdPays)->table('ligneservices')
                    ->selectRaw($pay->idPays.' as idPays ,entreprises.idEntreprise, entreprises.nomEntreprise, entreprises.Sigle')
                    ->join('entreprises','ligneservices.idEntreprise','=','entreprises.idEntreprise')
                    ->join('lignebilan','entreprises.idEntreprise','=','lignebilan.idEntreprise')
                    ->whereIn('exercice',$YEARS)
                    ->where('ligneservices.idsouSecteur',$idSector->idsouSecteur)
                    ->groupBy('entreprises.idEntreprise','entreprises.nomEntreprise','entreprises.Sigle')
                    ->get();
                $entreprises = $entreprises->concat($e);

                foreach ($e as $entreprise):
                    for ($i=0; $i<count($YEARS); $i++):
                        /*
                         * Poste Poste for ether entreprise in ether year
                         */
                        $Poste = DB::connection($pay->bdPays)->table('classe')
                            ->selectRaw($pay->idPays.' as idPays, entreprises.idEntreprise,exercice,SUM(lignebilan.brut) as total')
                            ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                            ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                            ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                            ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                            ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                            ->where('exercice',  $YEARS[$i])
                            ->where('idsousecteur',  $idSector->idsouSecteur)
                            ->where('rubrique.idRubrique',  $request->poste)
                            ->where('entreprises.idEntreprise',  $entreprise->idEntreprise)
                            ->groupby( 'entreprises.idEntreprise','exercice')
                            ->get();
                        $collectEntreprises = $collectEntreprises->concat($Poste);
                        /*
                        * Poste same Nature for ether entreprise in ether year
                        */
                        $Poste = DB::connection($pay->bdPays)->table('classe')
                            ->selectRaw($pay->idPays.' as idPays,entreprises.idEntreprise,exercice,SUM(lignebilan.brut) as total')
                            ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                            ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                            ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                            ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                            ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                            ->where('exercice',  $YEARS[$i])
                            ->where('idsousecteur',  $idSector->idsouSecteur)
                            ->where('nature',  $request->nature)
                            ->where('entreprises.idEntreprise',  $entreprise->idEntreprise)
                            ->groupby( 'exercice','entreprises.idEntreprise')
                            ->get();
                        $collectSameNatureEntreprises = $collectSameNatureEntreprises->concat($Poste);
                    endfor;
                endforeach;
            endforeach;
        endif;
        for ($i=0; $i<count($YEARS); $i++):
            /*
            * Select UEMOA Poste
             */
            $Poste = DB::connection($conx)->table('classe')
                ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                ->where('exercice',  $YEARS[$i])
                ->where('idsousecteur',  $idSector->idsouSecteur)
                ->where('rubrique.idRubrique',  $request->poste)
                ->groupby( 'exercice')
                ->get();
            $collectUEMOA = $collectUEMOA->concat($Poste);
            /*
             * Select UEMOA Same Nature Of Poste
             */
            $Poste = DB::connection($conx)->table('classe')
                ->selectRaw('exercice,SUM(lignebilan.brut) as total')
                ->join('sousclasse', 'classe.idClasse', '=', 'sousclasse.idClasse')
                ->join('rubrique', 'sousclasse.idSousclasse', '=', 'rubrique.idSousclasse')
                ->join('lignebilan', 'rubrique.idRubrique', '=', 'lignebilan.idRubrique')
                ->join('entreprises', 'lignebilan.idEntreprise', '=', 'entreprises.idEntreprise')
                ->join('ligneservices', 'entreprises.idEntreprise', '=', 'ligneservices.idEntreprise')
                ->where('exercice',  $YEARS[$i])
                ->where('idsousecteur',  $idSector->idsouSecteur)
                ->where('nature',  $request->nature)
                ->groupby( 'exercice')
                ->get();
            $collectSameNatureUEMOA = $collectSameNatureUEMOA->concat($Poste);
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
        $view = view('pages.s_activities.poste');
        $view->collectEntreprises = $collectEntreprises;
        $view->collectSameNatureEntreprises = $collectSameNatureEntreprises;
        $view->collectUEMOA = $collectUEMOA;
        $view->collectSameNatureUEMOA = $collectSameNatureUEMOA;
        $view->collectCountries = $collectCountries;
        $view->collectSameNatureCountries = $collectSameNatureCountries;
        $view->input = $request->all();
        $view->exercices = $exercices;
        $view->poste = $poste;
        $view->Countries = $Countries;
        $view->entreprises = $entreprises;
        return $view;
    }
}
