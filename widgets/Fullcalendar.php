<?php namespace MartiniMultimedia\Asso\Widgets;

use Backend\Classes\WidgetBase;
use Backend\Classes\Controller;
use System\Classes\CombineAssets;

class Fullcalendar extends WidgetBase
{


    public function render()
    {
        $this->assetPath = '/plugins/martinimultimedia/asso/widgets/fullcalendar/assets';
        //$this->addCss($this->assetPath . '/css/fullcalendar.css');
        $this->addJs($this->assetPath . '/js/fullcalendar.js');
        $this->addJs($this->assetPath . '/js/init.js');
        $this->vars['events'] = json_encode($this->getEvents());
        return $this->makePartial('widget_calendar');
    }

    protected function getEvents()
    {
        return [
            ['title' => 'Event 1', 'start' => '2024-11-01'],
            ['title' => 'Event 2', 'start' => '2024-11-05']
            // Add more events as needed
        ];
    }
}
