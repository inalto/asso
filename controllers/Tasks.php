<?php namespace MartiniMultimedia\Asso\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Backend\Models\User;
use MartiniMultimedia\Asso\Models\Task;
use Flash;

class Tasks extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];

    public $listConfig = [
        'list' => 'config_list.yaml',
        'history' => 'config_history.yaml'
    ];
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'martinimultimedia.asso.access_tasks' 
    ];

    public function __construct()
    {
        parent::__construct();
        
        // Initialize both list definitions
        $this->makeLists();
    }

    /**
     * Extend list query for different views
     */
    public function listExtendQuery($query, $definition = null)
    {
        // For dashboard, show recent activities ordered by updated_at
        if ($this->action == 'dashboard') {
            return $query->orderBy('updated_at', 'desc')->limit(10);
        }
        
        // For history page, only show completed/cancelled tasks
        if ($this->action == 'history' || $definition == 'history') {
            $query->whereIn('status', ['completed', 'cancelled']);
            return $query;
        }
        
        // For main tasks page, show only active tasks by default
        if ($this->action == 'index' || $definition == 'list') {
            // Show only active tasks (not completed/cancelled)
            $query->whereNotIn('status', ['completed', 'cancelled']);
            
            // If user doesn't have manage_all_tasks permission, default to their tasks
            if (!$this->user->hasAccess('martinimultimedia.asso.manage_all_tasks')) {
                $query->where('assigned_to_user_id', $this->user->id);
            }
            
            return $query;
        }
        
        // For mytasks page, always show only current user's tasks
        if ($this->action == 'mytasks') {
            $query->where('assigned_to_user_id', $this->user->id);
            return $query;
        }

        return $query;
    }

    /**
     * Extend form to auto-assign current user as creator
     */
    public function formExtendModel($model)
    {
        if (!$model->exists) {
            $model->created_by_user_id = $this->user->id;
            $model->created_by = $this->user->id;
            
            // If not admin, assign to self by default
            if (!$this->user->hasAccess('martinimultimedia.asso.manage_all_tasks')) {
                $model->assigned_to_user_id = $this->user->id;
                $model->assigned_to = $this->user->id;
            }
        }

        return $model;
    }

    /**
     * Custom action to mark task as completed
     */
    public function onMarkCompleted()
    {
        $taskIds = post('checked');
        if (empty($taskIds)) {
            Flash::error('Seleziona almeno un\'attività da segnare come completata.');
            return;
        }

        $completedCount = 0;
        $permissionErrors = 0;

        foreach ($taskIds as $taskId) {
            try {
                $task = Task::findOrFail($taskId);
                
                // Check permissions
                if (!$this->user->hasAccess('martinimultimedia.asso.manage_all_tasks') && 
                    $task->assigned_to_user_id != $this->user->id) {
                    $permissionErrors++;
                    continue;
                }

                $task->markCompleted();
                $completedCount++;
            } catch (\Exception $e) {
                // Task not found, skip it
                continue;
            }
        }

        if ($completedCount > 0) {
            $message = $completedCount == 1 ? 
                'Attività segnata come completata.' : 
                $completedCount . ' attività segnate come completate.';
            Flash::success($message);
        }
        
        if ($permissionErrors > 0) {
            $message = $permissionErrors == 1 ? 
                'Un\'attività è stata saltata per restrizioni di permessi.' : 
                $permissionErrors . ' attività sono state saltate per restrizioni di permessi.';
            Flash::warning($message);
        }
        
        if ($completedCount == 0 && $permissionErrors == 0) {
            Flash::error('Nessuna attività trovata da segnare come completata.');
        }
        
        return $this->listRefresh();
    }

    /**
     * Custom action to unmark completed tasks
     */
    public function onUnmarkCompleted()
    {
        $taskIds = post('checked');
        if (empty($taskIds)) {
            Flash::error('Seleziona almeno un\'attività da riaprire.');
            return;
        }

        $reopenedCount = 0;
        $permissionErrors = 0;

        foreach ($taskIds as $taskId) {
            try {
                $task = Task::findOrFail($taskId);
                
                // Check permissions
                if (!$this->user->hasAccess('martinimultimedia.asso.manage_all_tasks') && 
                    $task->assigned_to_user_id != $this->user->id) {
                    $permissionErrors++;
                    continue;
                }

                // Only unmark if task is completed
                if ($task->status === 'completed') {
                    $task->status = 'pending';
                    $task->completed_at = null;
                    $task->save();
                    $reopenedCount++;
                }
            } catch (\Exception $e) {
                // Task not found, skip it
                continue;
            }
        }

        if ($reopenedCount > 0) {
            $message = $reopenedCount == 1 ? 
                'Attività riaperta.' : 
                $reopenedCount . ' attività riaperte.';
            Flash::success($message);
        }
        
        if ($permissionErrors > 0) {
            $message = $permissionErrors == 1 ? 
                'Un\'attività è stata saltata per restrizioni di permessi.' : 
                $permissionErrors . ' attività sono state saltate per restrizioni di permessi.';
            Flash::warning($message);
        }
        
        if ($reopenedCount == 0 && $permissionErrors == 0) {
            Flash::error('Nessuna attività completata trovata da riaprire.');
        }
        
        return $this->listRefresh();
    }

    /**
     * Override index to set default filter for user
     */
    public function index()
    {
        BackendMenu::setContext('MartiniMultimedia.Asso', 'main-menu-item', 'side-menu-tasks');

        $this->addCss('/plugins/martinimultimedia/asso/assets/css/dashboard.css');

        return $this->asExtension('ListController')->index();
    }

    /**
     * Task History page - shows completed/cancelled tasks
     */
    public function history()
    {
        BackendMenu::setContext('MartiniMultimedia.Asso', 'main-menu-item', 'side-menu-task-history');

        $this->pageTitle = 'Storico Attività';
        
        return $this->listRender('history');
    }

}