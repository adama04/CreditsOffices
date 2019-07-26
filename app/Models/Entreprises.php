<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprises extends Model  
{

    public function rubriques()
    {
        return $this->belongsToMany(Rubrique::class);
    }

}
