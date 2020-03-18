namespace Backend\Widgets;

use Backend\Classes\WidgetBase;

class Scheduler extends WidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'scheduler';

    public function render()
    {
        return $this->makePartial('scheduler');
    }
}