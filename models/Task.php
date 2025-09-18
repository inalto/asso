<?php namespace MartiniMultimedia\Asso\Models;

use Model;
use Backend\Models\User;

/**
 * Task Model
 */
class Task extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_asso_tasks';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'title',
        'description', 
        'priority',
        'status',
        'due_date',
        'completed_at',
        'created_by_user_id',
        'assigned_to_user_id',
        'assigned_to',
        'created_by'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'created_by' => [
            'Backend\Models\User',
            'key' => 'created_by_user_id'
        ],
        'assigned_to' => [
            'Backend\Models\User',
            'key' => 'assigned_to_user_id'
        ]
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'title' => 'required|max:255',
        'description' => 'nullable',
        'priority' => 'required|in:low,medium,high,urgent',
        'status' => 'required|in:pending,in_progress,completed,cancelled',
        'due_date' => 'nullable|date',
        'assigned_to_user_id' => 'required|exists:backend_users,id'
    ];

    /**
     * @var array Attributes
     */
    protected $dates = ['deleted_at', 'due_date', 'completed_at'];

    /**
     * @var array Status options
     */
    public static function getStatusOptions()
    {
        return [
            'pending' => 'martinimultimedia.asso::lang.task.status.pending',
            'in_progress' => 'martinimultimedia.asso::lang.task.status.in_progress', 
            'completed' => 'martinimultimedia.asso::lang.task.status.completed',
            'cancelled' => 'martinimultimedia.asso::lang.task.status.cancelled'
        ];
    }

    /**
     * @var array Priority options
     */
    public static function getPriorityOptions()
    {
        return [
            'low' => 'martinimultimedia.asso::lang.task.priority.low',
            'medium' => 'martinimultimedia.asso::lang.task.priority.medium',
            'high' => 'martinimultimedia.asso::lang.task.priority.high',
            'urgent' => 'martinimultimedia.asso::lang.task.priority.urgent'
        ];
    }

    /**
     * Get user options for assignment
     */
    public function getUserOptions()
    {
        return User::orderBy('first_name')->orderBy('last_name')->get()->pluck('full_name', 'id')->toArray();
    }

    /**
     * Get user options for assigned_to dropdown
     */
    public function getAssignedToOptions()
    {
        $users = User::orderBy('first_name')->orderBy('last_name')->get();
        $options = [];
        
        foreach ($users as $user) {
            $fullName = trim($user->first_name . ' ' . $user->last_name);
            if (empty($fullName)) {
                $fullName = $user->login;
            }
            $options[$user->id] = $fullName;
        }
        
        return $options;
    }

    /**
     * Scope for overdue tasks
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Scope for upcoming tasks (due within 3 days)
     */
    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>=', now())
                    ->where('due_date', '<=', now()->addDays(3))
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Scope for tasks assigned to a specific user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to_user_id', $userId);
    }

    /**
     * Filter scope for status filtering
     */
    public function scopeFilterByStatus($query, $status)
    {
        if ($status == 'active') {
            return $query->whereIn('status', ['pending', 'in_progress']);
        }
        
        return $query->where('status', $status);
    }

    /**
     * Mark task as completed
     */
    public function markCompleted()
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Get priority color for display
     */
    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'success',
            'medium' => 'warning', 
            'high' => 'danger',
            'urgent' => 'danger'
        ];

        return $colors[$this->priority] ?? 'secondary';
    }

    /**
     * Get status color for display
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success', 
            'cancelled' => 'secondary'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Check if task is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Check if task is due soon (within 3 days)
     */
    public function getIsDueSoonAttribute()
    {
        return $this->due_date && 
               $this->due_date->isFuture() && 
               $this->due_date->diffInDays(now()) <= 3 &&
               !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Accessor for assigned_to field (maps to assigned_to_user_id)
     */
    public function getAssignedToAttribute()
    {
        return $this->assigned_to_user_id;
    }

    /**
     * Mutator for assigned_to field (maps to assigned_to_user_id)
     */
    public function setAssignedToAttribute($value)
    {
        $this->assigned_to_user_id = $value;
    }

    /**
     * Accessor for created_by field (maps to created_by_user_id)
     */
    public function getCreatedByAttribute()
    {
        return $this->created_by_user_id;
    }

    /**
     * Mutator for created_by field (maps to created_by_user_id)
     */
    public function setCreatedByAttribute($value)
    {
        $this->created_by_user_id = $value;
    }
}