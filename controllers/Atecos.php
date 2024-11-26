<?php namespace MartiniMultimedia\Asso\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Atecos extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController',
   //     'Backend\Behaviors\FilterController'
        ];
    
    public $listConfig = 'config_list.yaml';
  //  public $filterConfig = 'config_filter.yaml';

    public $formConfig = 'config_form.yaml';
    public $relationConfig ='config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('MartiniMultimedia.Asso', 'main-menu-item', 'side-menu-item5');

    }

    public function index()
    {
      //  parent::__construct();

        $this->addCss('/plugins/martinimultimedia/asso/assets/css/dashboard.css');
      $this->makeLists();
    }
    

}
