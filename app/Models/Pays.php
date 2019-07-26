<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $idPays
 * @property int $idContinent
 * @property string $ohada
 * @property string $cedeao
 * @property string $codesN
 * @property string $codes2
 * @property string $codes3
 * @property string $nomPays
 * @property string $bdpays
 */
class Pays extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'idPays';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['idContinent', 'ohada', 'cedeao', 'codesN', 'codes2', 'codes3', 'nomPays', 'bdpays'];

}
