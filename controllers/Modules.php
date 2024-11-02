<?php namespace MartiniMultimedia\Asso\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Http\Request;
use MartiniMultimedia\Asso\Models\Module;
use Carbon\Carbon;
class Modules extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('MartiniMultimedia.Asso', 'main-menu-item', 'side-menu-item8');
    }


    public function dates(Request $request)
    {
        // Extract dates from FullCalendar's request
        $startDate = $request->query('start');
        $endDate = $request->query('end');

        // Query scheduled course modules within this date range
        $modules = Module::whereBetween('date', [$startDate, $endDate])->with('training')->get();

        // Format the data for FullCalendar
        $events = $modules->map(function ($module) {
            $startDateTime = Carbon::parse($module->date . ' ' . $module->start_time);
            $endDateTime = $startDateTime->copy()->addHours($module->hours);

            return [
                'title' => $module->training->name,
                'start' => $startDateTime->toIso8601String(),
                'end' => $endDateTime->toIso8601String(),
                'description' => $module->name,
           //     'color' => $module->color,
            ];
        });

        // Return the response in JSON format
        return response()->json($events);
    }
}
