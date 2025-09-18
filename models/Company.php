<?php namespace MartiniMultimedia\Asso\Models;

use Model;

/**
 * Model
 */
class Company extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_asso_companies';

    public $fillable = ['name','address','zip','city','state','vat','cf','cd','ateco','phones','emails','pec'];
    public $jsonable =['phones','emails'];
    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
    public $hasMany = [
        'people' => 'MartiniMultimedia\Asso\Models\Person'
    ];
    public $belongsTo = [
        'ateco' => 'MartiniMultimedia\Asso\Models\Ateco'
    ];
    public $belongsToMany = [
        'atecos' => ['MartiniMultimedia\Asso\Models\Ateco', 'table' => 'martinimultimedia_asso_ateco_company']
    ];
}
