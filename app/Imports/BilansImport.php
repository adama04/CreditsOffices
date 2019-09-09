<?php

namespace App\Imports;
use App\Models\Classe;
use App\Models\LigneBilan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BilansImport implements ToModel
{
   // private $idRubrique,$idEntreprise;
   /* public function model(array $row)
    {
        return new Classe([
            'idClasse'=> $row[0],
            'nomClasse'=> $row[1],
            'nature'=> $row[2],
            'codeN'=> $row[3],
            'code'=> $row[4],
        ]);
    }*/
   /* public function headingRow(): int
    {
        return ;
    }*/

   /* public function collection(Collection $rows)
    {
       foreach($rows as $row){
            $cell=$row->count();
            dd($cell);
       }
       return $rows;

    }
    public function collection(Collection $rows)
    {
        dump($rows);
    }*/
    /*public function __construct($idRubrique,$idEntreprise)
    {
        $this->idRubrique = $idRubrique;
        $this->idEntreprise = $idEntreprise;
    }*/
    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;

        return new LigneBilan([
            'brut' => $row[3]
        ]);
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
