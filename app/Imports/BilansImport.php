<?php

namespace App\Imports;
use App\Models\Classe;
use Maatwebsite\Excel\Concerns\ToModel;

class BilansImport implements ToModel
{
    public function model(array $row)
    {
        return new Classe([
            'idClasse'=> $row[0],
            'nomClasse'=> $row[1],
            'nature'=> $row[2],
            'codeN'=> $row[3],
            'code'=> $row[4],
        ]);
    }
}
