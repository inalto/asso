<?php namespace MartiniMultimedia\Asso\Models;

use Model;

/**
 * Model
 */
class AtecoCompany extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_asso_ateco_company';

    public $fillable = ['company_id','ateco_id'];
    public $timestamps = false;

    
}
