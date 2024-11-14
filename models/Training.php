<?php namespace MartiniMultimedia\Asso\Models;

use Model;
use Carbon\Carbon;
/**
 * Model
 */
class Training extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name','description','duration','start_date'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_asso_trainings';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'description' => 'required',
        'duration' => 'required',
        'start_date' => 'required'
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

    public function getToAttribute($value)
    {
        return Carbon::parse($this->start_date)->format('m/Y');
    }

    public function getFromAttribute($value)
    {
        return Carbon::parse($this->start_date)->addYears($this->duration)->format('m/Y');
    }


}
