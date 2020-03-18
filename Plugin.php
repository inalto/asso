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
            ]
        ];
    }

}
