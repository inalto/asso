<?php namespace MartiniMultimedia\Asso\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Response;
use Str;
use Log;

use MartiniMultimedia\Asso\Models\Event;

class Events extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
    }

    public function events()
    {
        Log::info('events call');
        $evt = Event::all();


        $events = $evt->map(function ($item) {
            return collect($item)->keyBy(function ($value, $key) {
                return ucfirst(Str::camel($key));
    
            });
        });
/*
        $events=$evt->keyBy(function ($value, $key) {
            return Str::camel($key);

        });


        $evt->map(function ($item, $key) {
            return $item * 2;
        });
*/
        /*
        $filtered = $unfiltered->keyBy(function ($value, $key) {
            if ($key == 'data') {
                return 'parties';
            } else {
                return $key;
            }
       });
*/
        return Response::json($events);
    } 
}
