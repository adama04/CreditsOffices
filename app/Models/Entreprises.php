<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprises extends Model  
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'entreprises';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['idEntreprise', 'numRegistre', 'codePays', 'codeRegion', 'type', 'numEnregistre', 'nomEntreprise', 'Adresse', 'Tel1', 'Fax', 'persressouMail', 'webSite', 'boitePostal', 'dateCreation', 'moisCreation', 'jourCreation', 'Categorie', 'idLocalite', 'Pays', 'Sigle', 'Logo', 'geoLocali', 'tailleEntreprise', 'statutEtreprise', 'dateFin'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];
    public function rubriques()
    {
        return $this->belongsToMany(Rubrique::class);
    }

}
