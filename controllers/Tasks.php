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

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'martinimultimedia.asso.access_tasks' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('MartiniMultimedia.Asso', 'main-menu-item', 'side-menu-tasks');
    }

    /**
     * Extend list query to show user's tasks by default
     */
    public function listExtendQuery($query)
    {
        // Show only tasks assigned to current user unless they have admin permission
        if (!$this->user->hasAccess('martinimultimedia.asso.manage_all_tasks')) {
            $query->where('assigned_to_user_id', $this->user->id);
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
            
            // If not admin, assign to self by default
            if (!$this->user->hasAccess('martinimultimedia.asso.manage_all_tasks')) {
                $model->assigned_to_user_id = $this->user->id;
            }
        }

        return $model;
    }

    /**
     * Custom action to mark task as completed
     */
    public function onMarkCompleted()
    {
        $taskId = post('task_id');
        $task = Task::findOrFail($taskId);
        
        // Check permissions
        if (!$this->user->hasAccess('martinimultimedia.asso.manage_all_tasks') && 
            $task->assigned_to_user_id != $this->user->id) {
            Flash::error('You can only complete your own tasks.');
            return;
        }

        $task->markCompleted();
        Flash::success('Task marked as completed.');
        
        return $this->listRefresh();
    }

    /**
     * My Tasks page - shows only current user's tasks
     */
    public function mytasks()
    {
        $this->pageTitle = 'My Tasks';
        
        // Override list query for this page
        $this->listConfig = 'config_mytasks.yaml';
        
        return $this->asExtension('ListController')->index();
    }

    /**
     * Dashboard view for administrators
     */
    public function dashboard()
    {
        if (!$this->user->hasAccess('martinimultimedia.asso.manage_all_tasks')) {
            return redirect('backend/martinimultimedia/asso/tasks/mytasks');
        }

        $this->pageTitle = 'Tasks Dashboard';
        
        $this->vars['totalTasks'] = Task::count();
        $this->vars['pendingTasks'] = Task::where('status', 'pending')->count();
        $this->vars['inProgressTasks'] = Task::where('status', 'in_progress')->count();
        $this->vars['completedTasks'] = Task::where('status', 'completed')->count();
        $this->vars['overdueTasks'] = Task::overdue()->count();
        $this->vars['upcomingTasks'] = Task::upcoming()->count();
        
        $this->vars['recentTasks'] = Task::with(['assigned_to', 'created_by'])
                                         ->orderBy('created_at', 'desc')
                                         ->limit(10)
                                         ->get();
                                         
        $this->vars['overdueTasksList'] = Task::overdue()
                                              ->with(['assigned_to', 'created_by'])
                                              ->orderBy('due_date', 'asc')
                                              ->limit(10)
                                              ->get();
    }

    /**
     * Filter tasks by user
     */
    public function onFilterByUser()
    {
        $userId = post('user_id');
        
        if ($userId) {
            $this->vars['filterUserId'] = $userId;
            return $this->listRefresh();
        }
    }
}