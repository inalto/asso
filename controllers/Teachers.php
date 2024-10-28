<?php namespace MartiniMultimedia\Asso\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class Teachers extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = [
        'martinimultimedia.asso' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('MartiniMultimedia.Asso', 'main-menu-item', 'side-menu-item7');
    }

}
