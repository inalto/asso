<?php namespace MartiniMultimedia\Asso\Console;

use Illuminate\Console\Command;
use MartiniMultimedia\Asso\Classes\TaskNotificationHandler;

/**
 * Task notifications console command
 */
class TaskNotifications extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'asso:task-notifications';

    /**
     * @var string The console command description.
     */
    protected $description = 'Check for overdue and upcoming tasks and send notifications';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $this->info('Checking for overdue tasks...');
        TaskNotificationHandler::checkOverdueTasks();
        
        $this->info('Checking for upcoming due dates...');
        TaskNotificationHandler::checkUpcomingTasks();
        
        $this->info('Task notifications check completed.');
    }
}