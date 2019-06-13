<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $idSousclasse
 * @property int $idClasse
 * @property string $nomSousclasse
 * @property string $codeDsc
 * @property string $name
 */
class SousClasse extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sousclasse';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'idSousclasse';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['idClasse', 'nomSousclasse', 'codeDsc', 'name'];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function rubrique()
    {
        return $this->hasMany(Rubrique::class);
    }
}
