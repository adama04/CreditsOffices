<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $idRubrique
 * @property int $idSousclasse
 * @property string $code1
 * @property string $code2
 * @property string $codeD
 * @property string $nomRubrique
 * @property string $nomRubriqueOh
 */
class Rubrique extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'rubrique';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'idRubrique';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['idSousclasse', 'code1', 'code2', 'codeD', 'nomRubrique', 'nomRubriqueOh'];

}
