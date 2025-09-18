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
  
    public $fillable = ['first_name','last_name','birth_date','birth_city','cf','cna','emails','phones','task','company_id'];
  
    public $jsonable = ['phones','emails'];
    /**
     * @var array Validation rules
     */
    public $rules = [
    ];



    public $belongsTo = [
        'company' => 'MartiniMultimedia\Asso\Models\Company'
    ];

    public $belongsToMany = [

        'trainings' => [
            'MartiniMultimedia\Asso\Models\Training',
            'table' => 'martinimultimedia_asso_training_enrollments',
            'order' => 'name'
        ],

        'enrollments' => [
            'MartiniMultimedia\Asso\Models\Training',
            'table' => 'martinimultimedia_asso_training_enrollments',
            'order' => 'name'
        ],
        'modules' => [
            'MartiniMultimedia\Asso\Models\Module',
            'table' => 'martinimultimedia_asso_module_attendance',
 //           'pivot' => ['attended'],
  //          'pivotModel' => 'MartiniMultimedia\Asso\Models\ModuleAttendance',
            'order' => 'last_name' 
        ]
    ];

    public $attachOne = [
        'image' => 'System\Models\File'
    ];

    public $attachMany = [
        'documents' => 'System\Models\File',
        'achievements' => 'System\Models\File'
    ];
    
}
