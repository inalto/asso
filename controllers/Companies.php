<?php namespace MartiniMultimedia\Asso\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use System\Classes\CombineAssets;

use MartiniMultimedia\Asso\Models\Company;
use MartiniMultimedia\Asso\Models\Person;


class Companies extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
        'Backend\Behaviors\RelationController' 
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
	public $relationConfig = 'config_relation.yaml';


    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('MartiniMultimedia.Asso', 'main-menu-item', 'side-menu-item');
    }
    
    public function dashboard() {
        $styles = [
            '/martinimultimedia/asso/assets/scss/dashboard.scss'
          ];
        $this->addCss(CombineAssets::combine($styles, plugins_path()));
        $this->pageTitle="Cruscotto";


        $aziende=Company::count();
        $this->vars['aziende']=$aziende;
        $people=Person::count();
        $this->vars['people']=$people;
        $people_cna=Person::where('cna','=','1')->count();
        $this->vars['people_cna']=$people_cna;
    }


}
