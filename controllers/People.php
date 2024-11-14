<?php namespace MartiniMultimedia\Asso\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class People extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig ='config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('MartiniMultimedia.Asso', 'main-menu-item', 'side-menu-item4');
    }

    public function relationExtendPivotQuery($query, $field, $model)
    {
        if ($field === 'attendees') {
            $training = $model->training;
            if ($training) {
                $enrolledPersonIds = $training->enrollments->pluck('id')->toArray();
                $query->whereIn('id', $enrolledPersonIds);
            }
        }
    }
}
