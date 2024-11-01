<?php namespace MartiniMultimedia\Asso;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }
    public function register()
    {
        $this->registerConsoleCommand('asso.import', 'MartiniMultimedia\Asso\Console\Import');
        $this->registerConsoleCommand('asso.ateco', 'MartiniMultimedia\Asso\Console\Ateco');
        $this->registerConsoleCommand('asso.comuni', 'MartiniMultimedia\Asso\Console\Comuni');

        \Route::group(['prefix' => 'api', 'middleware' => ['web']], function () {
       
            \Route::get('dates', 'MartiniMultimedia\Asso\Controllers\Modules@dates');
        });
    }

    public function registerReportWidgets()
    {
        return [
            'MartiniMultimedia\Asso\Widgets\Scheduler' => [
                'label'   => 'Scheduler',
                'context' => 'dashboard',
                'permissions' => [
                    'rainlab.googleanalytics.widgets.traffic_overview',
                ],
            ],
            'MartiniMultimedia\Asso\Widgets\Fullcalendar' => [
                'label' => 'Calendar Widget',
                'code' => 'calendarwidget',
                'context' => 'dashboard',
                'permissions' => [
                    'rainlab.googleanalytics.widgets.traffic_overview',
                ],
            ]
        ];

    }
/*
    public function registerFormWidgets()
    {
        return [
            'MartiniMultimedia\Asso\Widgets\CalendarWidget' => [
                'label' => 'Calendar Widget',
                'code' => 'calendarwidget'
            ]
        ];
    }
*/
}
