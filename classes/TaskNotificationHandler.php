<?php namespace MartiniMultimedia\Asso\Classes;

use Event;
use Mail;
use MartiniMultimedia\Asso\Models\Task;
use Backend\Models\User;

/**
 * Task Notification Handler
 */
class TaskNotificationHandler
{
    /**
     * Subscribe to task events
     */
    public function subscribe($events)
    {
        $events->listen('model.afterCreate', [$this, 'handleTaskCreated']);
        $events->listen('model.afterUpdate', [$this, 'handleTaskUpdated']);
    }

    /**
     * Handle task created event
     */
    public function handleTaskCreated($model, $data)
    {
        if (!$model instanceof Task) {
            return;
        }

        $this->sendTaskAssignedNotification($model);
    }

    /**
     * Handle task updated event
     */
    public function handleTaskUpdated($model, $data)
    {
        if (!$model instanceof Task) {
            return;
        }

        // Check if assignment changed
        if ($model->isDirty('assigned_to_user_id')) {
            $this->sendTaskReassignedNotification($model);
        }

        // Check if status changed to completed
        if ($model->isDirty('status') && $model->status === 'completed') {
            $this->sendTaskCompletedNotification($model);
        }
    }

    /**
     * Send task assigned notification
     */
    protected function sendTaskAssignedNotification(Task $task)
    {
        if (!$task->assigned_to || !$task->assigned_to->email) {
            return;
        }

        $data = [
            'task' => $task,
            'assigned_user' => $task->assigned_to,
            'created_by' => $task->created_by
        ];

        try {
            Mail::send('martinimultimedia.asso::mail.task_assigned', $data, function($message) use ($task) {
                $message->to($task->assigned_to->email, $task->assigned_to->full_name);
                $message->subject('New Task Assigned: ' . $task->title);
            });
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::error('Failed to send task assignment email: ' . $e->getMessage());
        }
    }

    /**
     * Send task reassigned notification
     */
    protected function sendTaskReassignedNotification(Task $task)
    {
        if (!$task->assigned_to || !$task->assigned_to->email) {
            return;
        }

        $data = [
            'task' => $task,
            'assigned_user' => $task->assigned_to,
            'created_by' => $task->created_by
        ];

        try {
            Mail::send('martinimultimedia.asso::mail.task_reassigned', $data, function($message) use ($task) {
                $message->to($task->assigned_to->email, $task->assigned_to->full_name);
                $message->subject('Task Reassigned: ' . $task->title);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send task reassignment email: ' . $e->getMessage());
        }
    }

    /**
     * Send task completed notification
     */
    protected function sendTaskCompletedNotification(Task $task)
    {
        if (!$task->created_by || !$task->created_by->email || 
            $task->created_by->id === $task->assigned_to->id) {
            return;
        }

        $data = [
            'task' => $task,
            'assigned_user' => $task->assigned_to,
            'created_by' => $task->created_by
        ];

        try {
            Mail::send('martinimultimedia.asso::mail.task_completed', $data, function($message) use ($task) {
                $message->to($task->created_by->email, $task->created_by->full_name);
                $message->subject('Task Completed: ' . $task->title);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send task completion email: ' . $e->getMessage());
        }
    }

    /**
     * Check for overdue tasks and send notifications
     */
    public static function checkOverdueTasks()
    {
        $overdueTasks = Task::overdue()
                           ->with(['assigned_to', 'created_by'])
                           ->get();

        foreach ($overdueTasks as $task) {
            static::sendOverdueNotification($task);
        }
    }

    /**
     * Check for upcoming due dates and send notifications
     */
    public static function checkUpcomingTasks()
    {
        $upcomingTasks = Task::upcoming()
                            ->with(['assigned_to', 'created_by'])
                            ->get();

        foreach ($upcomingTasks as $task) {
            static::sendUpcomingDueNotification($task);
        }
    }

    /**
     * Send overdue notification
     */
    protected static function sendOverdueNotification(Task $task)
    {
        if (!$task->assigned_to || !$task->assigned_to->email) {
            return;
        }

        $data = [
            'task' => $task,
            'assigned_user' => $task->assigned_to,
            'created_by' => $task->created_by
        ];

        try {
            Mail::send('martinimultimedia.asso::mail.task_overdue', $data, function($message) use ($task) {
                $message->to($task->assigned_to->email, $task->assigned_to->full_name);
                $message->subject('Overdue Task: ' . $task->title);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send overdue task email: ' . $e->getMessage());
        }
    }

    /**
     * Send upcoming due notification
     */
    protected static function sendUpcomingDueNotification(Task $task)
    {
        if (!$task->assigned_to || !$task->assigned_to->email) {
            return;
        }

        $data = [
            'task' => $task,
            'assigned_user' => $task->assigned_to,
            'created_by' => $task->created_by
        ];

        try {
            Mail::send('martinimultimedia.asso::mail.task_due_soon', $data, function($message) use ($task) {
                $message->to($task->assigned_to->email, $task->assigned_to->full_name);
                $message->subject('Task Due Soon: ' . $task->title);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send due soon task email: ' . $e->getMessage());
        }
    }
}