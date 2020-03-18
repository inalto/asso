<?php namespace MartiniMultimedia\Asso\Models;

use Model;

/**
 * Model
 */
class Person extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_asso_people';
  
    public $fillable = ['first_name','last_name','birth_date','birth_city','cf','cna','emails','phones'];
  
    public $jsonable = ['phones','emails'];
    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'company' => 'MartiniMultimedia\Asso\Models\Company'
    ];
    public $attachOne = [
        'image' => 'System\Models\File'
    ];

    public $attachMany = [
        'documents' => 'System\Models\File',
        'achievements' => 'System\Models\File'
    ];
    
}
