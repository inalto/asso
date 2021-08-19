<?php namespace Martinimultimedia\Asso\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use MartiniMultimedia\Asso\Widgets\Scheduler;

/**
 * Calendars Back-end Controller
 */
class Calendars extends Controller
{
    /*
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
*/
    public function __construct()
    {
        parent::__construct();

        $scheduler = new Scheduler($this);
        $scheduler->alias = 'scheduler';
        $scheduler->bindToController();

        BackendMenu::setContext('MartiniMultimedia.Asso', 'main-menu-item', 'side-menu-item');
    }

    public function index()
    {
    }
}
