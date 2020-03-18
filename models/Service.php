<?php namespace MartiniMultimedia\Asso\Models;

use Model;

/**
 * Model
 */
class Service extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\NestedTree;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_asso_services';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
