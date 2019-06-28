<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Entreprises;
use App\Models\SousClasse;
use App\Models\Rubrique;
use App\Models\Entreprise;
use App\Models\LigneBilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class BilanController extends Controller
{
    function index(){
       $classes = DB::table('classe')->paginate(3);
        $entreprises=DB::table('entreprises')->get();
        $lignebilans=DB::table('lignebilan')->distinct()->get();
        return view('pages.bilan')
            ->with('classes',$classes)
            ->with('entreprises',$entreprises)
            ->with('lignebilans',$lignebilans);
}

}
