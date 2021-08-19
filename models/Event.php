<?php namespace MartiniMultimedia\Asso\Models;

use Model;

/**
 * Calendar Model
 */
class Event extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_asso_events';

    /**
     * @var array Guarded fields
     
    protected $guarded = ['*'];
*/
    /**
     * @var array Fillable fields
     
    protected $fillable = [];
    */

    /**
     * @var array Relations
     
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
    */
}
