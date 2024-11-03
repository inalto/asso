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

    public $fillable = ['name', 'slug', 'hours','date','credits','training_id', 'description'];

    /**
     * Belongs to Training
     */

     public $belongsTo = [
        'training' => [
            'MartiniMultimedia\Asso\Models\Training',
            'key' => 'training_id',
            'otherKey' => 'id',
            'order' => 'name'
        ],
        'venue' => [
            'MartiniMultimedia\Asso\Models\Venue',
            'key' => 'venue_id',
            'otherKey' => 'id',
            'order' => 'name'
        ],
    ];
    public $belongsToMany = [
        'attendees' => [
            'MartiniMultimedia\Asso\Models\Person',
            'table' => 'martinimultimedia_asso_module_attendances',
            'pivot' => ['attended'],
            'pivotModel' => 'MartiniMultimedia\Asso\Models\ModuleAttendance',
            'order' => 'last_name'
        ]
    ];
    public $attachMany = [
        'attachments' => 'System\Models\File'
    ];
}
