<?php namespace MartiniMultimedia\Asso\Models;

use Model;

/**
 * Model
 */
class Training extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_asso_trainings';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $hasMany = [
        'modules' => 'MartiniMultimedia\Asso\Models\Module'
    ];

    public $belongsToMany = [
        
        'teachers' => [
            'MartiniMultimedia\Asso\Models\Teacher',
            'table' => 'martinimultimedia_asso_teacher_training',
            'order' => 'last_name'
        ],
        'enrollments' => [
            'MartiniMultimedia\Asso\Models\Person',
            'table' => 'martinimultimedia_asso_training_enrollments',
            'order' => 'last_name'
        ]
    ];
    public $attachOne = [
        'template' => 'System\Models\File'
    ];
}
