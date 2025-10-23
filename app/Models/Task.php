<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'priority',
        'due_date',
        'estimated_hours',
        'actual_hours',
        'project_id',
        'assigned_to',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    protected $appends = [
        'is_overdue',
        'days_until_due',
        'status_badge',
    ];

    // RELACIONES
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // ATTRIBUTES
    protected function isOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->due_date && $this->due_date->isPast() && !in_array($this->status, ['completed', 'cancelled'])
        );
    }

    protected function daysUntilDue(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->due_date) return null;
                return $this->due_date->diffInDays(now(), false);
            }
        );
    }

    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->status) {
                    'pending' => ['color' => 'gray', 'label' => 'Pending'],
                    'in_progress' => ['color' => 'blue', 'label' => 'In Progress'],
                    'completed' => ['color' => 'green', 'label' => 'Completed'],
                    'cancelled' => ['color' => 'red', 'label' => 'Cancelled'],
                    default => ['color' => 'gray', 'label' => 'Unknown'],
                };
            }
        );
    }

    // SCOPES
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }
}