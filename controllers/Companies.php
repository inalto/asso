<?php namespace MartiniMultimedia\Asso\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use System\Classes\CombineAssets;

use MartiniMultimedia\Asso\Models\Company;
use MartiniMultimedia\Asso\Models\Person;
use Backend\Widgets\Search;
use Input;

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

        BackendMenu::setContext('MartiniMultimedia.Asso', 'main-menu-item', 'side-menu-dashboard');

        $this->addCss('/plugins/martinimultimedia/asso/assets/css/dashboard.css');
        $this->pageTitle="Cruscotto";


        $aziende=Company::count();
        $this->vars['aziende']=$aziende;
        $people=Person::count();
        $this->vars['people']=$people;
        $people_cna=Person::where('cna','=','1')->count();
        $this->vars['people_cna']=$people_cna;

        $this->vars['search']=Input::get('search');

        $this->vars['companies']=[];
        $this->vars['peoples']=[];
        if (Input::get('search')) {
           // $this->vars['people_cna']=Person::where('cna','=','1')->where('name','like','%'.Input::get('search').'%')->count();
           $this->vars['companies']=Company::where('name','like','%'.Input::get('search').'%')->get();
           $this->vars['peoples']=Person::where('first_name','like','%'.Input::get('search').'%')->orWhere('last_name','like','%'.Input::get('search').'%')->get();
        }

        ray($this->vars);

    }

    public function onSearch(){
        ray(Input::get('search'));
        if (Input::get('search')) {
            // $this->vars['people_cna']=Person::where('cna','=','1')->where('name','like','%'.Input::get('search').'%')->count();
            $this->vars['companies']=Company::where('name','like','%'.Input::get('search').'%')->get();
            $this->vars['peoples']=Person::where('first_name','like','%'.Input::get('search').'%')->orWhere('last_name','like','%'.Input::get('search').'%')->get();
         }
 
        return [
            '#searchList' => $this->makePartial('searchList')
        ];

    }

}
