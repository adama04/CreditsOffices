<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $idEntreprise
 * @property string $numRegistre
 * @property string $codePays
 * @property string $codeRegion
 * @property string $type
 * @property int $numEnregistre
 * @property string $nomEntreprise
 * @property string $Adresse
 * @property string $Tel1
 * @property string $Fax
 * @property string $persressouMail
 * @property string $webSite
 * @property string $boitePostal
 * @property string $dateCreation
 * @property int $moisCreation
 * @property int $jourCreation
 * @property string $Categorie
 * @property int $idLocalite
 * @property string $Pays
 * @property string $Sigle
 * @property string $Logo
 * @property string $geoLocali
 * @property string $tailleEntreprise
 * @property string $statutEtreprise
 * @property string $dateFin
 */
class Entreprise extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'idEntreprise';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['numRegistre', 'codePays', 'codeRegion', 'type', 'numEnregistre', 'nomEntreprise', 'Adresse', 'Tel1', 'Fax', 'persressouMail', 'webSite', 'boitePostal', 'dateCreation', 'moisCreation', 'jourCreation', 'Categorie', 'idLocalite', 'Pays', 'Sigle', 'Logo', 'geoLocali', 'tailleEntreprise', 'statutEtreprise', 'dateFin'];

}
