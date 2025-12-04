<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'priority',
        'status',
        'assignee_id',
        'reporter_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'object', 'object_type', 'object_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())
                     ->where('status', 'not_started');
    }

    public function scopeStarted($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'in_progress')
              ->orWhere(function ($sub) {
                  $sub->where('start_date', '<=', now())
                      ->where('status', '!=', 'completed')
                      ->where('status', '!=', 'cancelled');
              });
        });
    }

    public function scopeOverdue($query)
    {
        return $query->where('end_date', '<', now())
                     ->where('status', '!=', 'completed')
                     ->where('status', '!=', 'cancelled');
    }

    public function isOverdue()
    {
        return $this->end_date < now() && !in_array($this->status, ['completed', 'cancelled']);
    }

    public function updateStatusBasedOnDates()
    {
        // Don't auto-update if manually set to completed or cancelled
        if (in_array($this->status, ['completed', 'cancelled'])) {
            return;
        }

        $now = now();
        
        if ($now->lt($this->start_date)) {
            $this->status = 'not_started';
        } elseif ($now->gte($this->start_date) && $now->lte($this->end_date)) {
            $this->status = 'in_progress';
        }
        
        $this->save();
    }

    public function getCurrentStatusAttribute()
    {
        if (in_array($this->status, ['completed', 'cancelled'])) {
            return $this->status;
        }

        $now = now();
        
        if ($now->lt($this->start_date)) {
            return 'not_started';
        } elseif ($now->gte($this->start_date) && $now->lte($this->end_date)) {
            return 'in_progress';
        } elseif ($this->isOverdue()) {
            return 'overdue';
        }
        
        return $this->status;
    }

    public function getStatusBadgeClass()
    {
        $status = $this->current_status;
        
        return match($status) {
            'not_started' => 'bg-gray-100 text-gray-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'overdue' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPriorityBadgeClass()
    {
        return match($this->priority) {
            'low' => 'bg-gray-100 text-gray-800',
            'normal' => 'bg-blue-100 text-blue-800',
            'high' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
