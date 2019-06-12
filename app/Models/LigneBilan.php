<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $exercice
 * @property int $idRubrique
 * @property int $idEntreprise
 * @property float $brut
 * @property float $provision
 */
class LigneBilan extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'lignebilan';

    /**
     * @var array
     */
    protected $fillable = ['brut', 'provision'];

}
