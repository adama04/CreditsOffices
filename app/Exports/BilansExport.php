<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\LigneBilan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class BilansExport  implements FromView  {
    /**
    * @return \Illuminate\Support\Collection
    */
   /* public function collection( Request $request)
    {
        return Classe::all();
    }*/
    private $input,$collectclassesA,$collectclassesB,$collectclassesAGlobal,$collectclassesBGlobal,$collecttotalclassesA,$collecttotalclassesB
    ,$collecttotalclassesAGlobal,$collecttotalclassesBGlobal,$classesA,$classesB,$exercices,$infoEntreprises;
    public function view():View
    {

        return view('pages.pdf',  [
                'input' => $this->input,
                'collectclassesA' => $this->collectclassesA,
                'collectclassesB' => $this->collectclassesB,
                'collectclassesAGlobal' => $this->collectclassesAGlobal,
                'collectclassesBGlobal' => $this->collectclassesBGlobal,
                'collecttotalclassesA' => $this->collecttotalclassesA,
                'collecttotalclassesB' => $this->collecttotalclassesB,
                'collecttotalclassesAGlobal' => $this->collecttotalclassesAGlobal,
                'collecttotalclassesBGlobal' => $this->collecttotalclassesBGlobal,
                'classesA' => $this->classesA,
                'classesB' => $this->classesB,
                'exercices' => $this->exercices,
                'infoEntreprises' => $this->infoEntreprises
            ]

        );
    }


    public function __construct($input,$collectclassesA,$collectclassesB,$collectclassesAGlobal,$collectclassesBGlobal,$collecttotalclassesA,$collecttotalclassesB
        ,$collecttotalclassesAGlobal,$collecttotalclassesBGlobal,$classesA,$classesB,$exercices,$infoEntreprises)
    {
        $this->input = $input;
        $this->collectclassesA = $collectclassesA;
        $this->collectclassesB = $collectclassesB;
        $this->collectclassesAGlobal = $collectclassesAGlobal;
        $this->collectclassesBGlobal = $collectclassesBGlobal;
        $this->collecttotalclassesA = $collecttotalclassesA;
        $this->collecttotalclassesB = $collecttotalclassesB;
        $this->collecttotalclassesAGlobal = $collecttotalclassesAGlobal;
        $this->collecttotalclassesBGlobal = $collecttotalclassesBGlobal;
        $this->classesA = $classesA;
        $this->classesB = $classesB;
        $this->exercices = $exercices;
        $this->infoEntreprises = $infoEntreprises;
    }
}
