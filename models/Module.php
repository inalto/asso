<?php namespace MartiniMultimedia\Asso\Models;

use Model;

/**
 * Model
 */
class Module extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_asso_modules';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $attachMany = [
        'attachments' => 'System\Models\File'
    ];
}