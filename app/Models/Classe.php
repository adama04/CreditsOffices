<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $idClasse
 * @property string $nomClasse
 * @property string $nature
 * @property int $codeN
 * @property string $code
 */
class Classe extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'classe';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'idClasse';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['nomClasse', 'nature', 'codeN', 'code'];

}
