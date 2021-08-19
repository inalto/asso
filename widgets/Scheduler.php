<?php
namespace MartiniMultimedia\Asso\Widgets;

use Backend\Classes\WidgetBase;

class Scheduler extends WidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'scheduler';

    public function render()
    {
        
        $this->addCss('css/main.1e43358e.css');
        //$this->addJs('js/main.1e43358e.js');
        
        return $this->makePartial('scheduler');
    }
}
?>