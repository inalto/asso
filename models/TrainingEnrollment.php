<?php namespace MartiniMultimedia\Asso\Models;

use Model;

/**
 * Model
 */
class TrainingEnrollment extends Model
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
    public $table = 'martinimultimedia_asso_training_enrollments';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
