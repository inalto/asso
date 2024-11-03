<?php namespace MartiniMultimedia\Asso\Models;

use October\Rain\Database\Pivot;
/**
 * Model
 */
class ModuleAttendance extends Pivot
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'martinimultimedia_asso_module_attendance';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];
    public $belongsTo = [
        'module' => 'MartiniMultimedia\Asso\Models\Module',
        'person' => 'MartiniMultimedia\Asso\Models\Person'
    ];
}
