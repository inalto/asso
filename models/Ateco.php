<?php namespace MartiniMultimedia\Asso\Models;

use Model;

/**
 * Model
 */
class Ateco extends Model
{
    use \October\Rain\Database\Traits\Validation;
//    use \October\Rain\Database\Traits\NestedTree;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_asso_atecos';

    public $fillable = ['id','code','name','description'];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

   
    public $belongsToMany = [
        'companies' => [
            'MartiniMultimedia\Asso\Models\Company',
            'table' => 'martinimultimedia_asso_ateco_company'
            ]
    ];


}
