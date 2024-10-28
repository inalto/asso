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

        //$this->addCss('css/main.1e43358e.css');
        //$this->addJs('js/main.1e43358e.js', ['defer' => 'defer']);
        $this->addCss('css/app.92647ec0.css');
        $this->addCss('css/chunk-vendors.eec78b6e.css');

        $this->addJs('js/app.fa4b1159.js', ['defer' => 'defer']);
        $this->addJs('js/chunk-vendors.0d33c527.js', ['defer' => 'defer']);

        return $this->makePartial('scheduler');
    }
}
?>
