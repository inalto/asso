<?php namespace MartiniMultimedia\Asso\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Http\Request;
use MartiniMultimedia\Asso\Models\Module;
use Carbon\Carbon;
class Modules extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController'

    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

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
                'id' => $module->id,
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

    public function onSync()
{
    ray('onsync');
    $model = $this->formGetModel();
    $trainingId = $model->training_id;
    
    if ($trainingId) {
        $enrolledPeople = \MartiniMultimedia\Asso\Models\Training::find($trainingId)
            ->enrollments()
            ->get();
            
        // Sync with pivot data, setting attended to false by default
        $syncData = $enrolledPeople->pluck('id')
            ->mapWithKeys(function ($id) {
                return [$id => ['attended' => false]];
            })
            ->toArray();
            
        $model->attendees()->sync($syncData);
       return; 
     //   return $this->relationRefreshManageList('attendees');
    }
}
/*
    public function relationExtendManageListWidget($widget, $field, $model)
    {
        if ($field === 'attendees') {
            ray('List Widget binding triggered for attendees');
            
            $widget->bindEvent('list.extendQueryBefore', function ($query) use ($model) {
                ray('Query extension executing');
                
                $trainingId = $model->training_id;
                if ($trainingId) {
                    $enrolledPersonIds = \MartiniMultimedia\Asso\Models\Training::find($trainingId)
                        ->enrollments()
                        ->pluck('id')
                        ->toArray();
                        
                    ray('Enrolled IDs:', $enrolledPersonIds);
                    
                    $query->whereIn('id', $enrolledPersonIds);
                }
            });
        }
    }

    public function relationExtendQuery($query, $relationName)
    {
        ray($relationName);
        if ($relationName === 'attendees') {
            $model = $this->formGetModel();
            $trainingId = $model->training_id;
            if ($trainingId) {
                $enrolledPersonIds = \MartiniMultimedia\Asso\Models\Training::find($trainingId)
                    ->enrollments()->pluck('id')->toArray();
                $query->whereIn('id', $enrolledPersonIds);
            }
        }
    }

    public function relationExtendManageWidget($widget, $field)
    {
        if ($field === 'attendees') {
            // Get the model being edited
            $model = $this->formGetModel();

            // Access the list widget
            $listWidget = $widget->list;

            if ($listWidget) {
                // Bind to the list's extendQuery event
                $listWidget->bindEvent('list.extendQuery', function ($query) use ($model) {
                    // Get the training ID associated with the module
                    $trainingId = $model->training_id;
                    if ($trainingId) {
                        // Get IDs of enrolled persons in the training
                        $enrolledPersonIds = \MartiniMultimedia\Asso\Models\Training::find($trainingId)
                            ->enrollments()->pluck('id')->toArray();
                        // Filter the query to include only enrolled persons
                        $query->whereIn('id', $enrolledPersonIds);
                    }
                });
            } else {
                \Log::info('List widget is null for field ' . $field);
            }
        }
    }
        */
}
