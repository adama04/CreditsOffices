<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\SousClasse;
use App\Models\Rubrique;
use App\Models\Entreprise;
use App\Models\LigneBilan;
use Illuminate\Http\Request;

class BilanController extends Controller
{
    function index(){
        $classes = Classe::paginate(8);

        return view('pages.bilan',compact('classes'));

    }
}
