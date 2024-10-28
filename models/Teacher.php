<?php namespace MartiniMultimedia\Asso\Models;

use Model;

/**
 * Model
 */
class Teacher extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * @var array dates to cast from the database.
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'martinimultimedia_asso_teachers';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];
    
    public $attachMany = [
        'letters' => 'System\Models\File'
    ];
}
