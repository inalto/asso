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

    public function registerPermissions()
    {
        return [
            'martinimultimedia.asso.access_tasks' => [
                'tab' => 'CNA',
                'label' => 'Access Tasks',
            ],
            'martinimultimedia.asso.manage_all_tasks' => [
                'tab' => 'CNA', 
                'label' => 'Manage All Tasks',
            ],
        ];
    }
    public function register()
    {
        $this->registerConsoleCommand('asso.import', 'MartiniMultimedia\Asso\Console\Import');
        $this->registerConsoleCommand('asso.ateco', 'MartiniMultimedia\Asso\Console\Ateco');
        $this->registerConsoleCommand('asso.comuni', 'MartiniMultimedia\Asso\Console\Comuni');
        $this->registerConsoleCommand('asso.sync-ateco', 'MartiniMultimedia\Asso\Console\SyncCompanyAteco');
        $this->registerConsoleCommand('asso.task-notifications', 'MartiniMultimedia\Asso\Console\TaskNotifications');

        \Route::group(['prefix' => 'api', 'middleware' => ['web']], function () {
       
            \Route::get('dates', 'MartiniMultimedia\Asso\Controllers\Modules@dates');
        });
    }

    public function boot()
    {
        // Register task notification event listeners
        \Event::subscribe('MartiniMultimedia\Asso\Classes\TaskNotificationHandler');
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
