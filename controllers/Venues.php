<?php namespace MartiniMultimedia\Asso\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Venues extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'martinimultimedia.asso',
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('MartiniMultimedia.Asso', 'main-menu-item', 'side-menu-item9');
    }
}
