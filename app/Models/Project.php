<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'priority',
        'due_date',
        'owner_id',
        'progress',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    protected $appends = [
        'progress_percentage',
        'is_overdue',
        'days_until_due',
        'status_badge',
    ];

    // RELACIONES
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function completedTasks(): HasMany
    {
        return $this->hasMany(Task::class)->where('status', 'completed');
    }

    // ATTRIBUTES
    protected function progressPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                $totalTasks = $this->tasks()->count();
                if ($totalTasks === 0) return 0;
                
                $completedTasks = $this->completedTasks()->count();
                return round(($completedTasks / $totalTasks) * 100);
            }
        );
    }

    protected function isOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->due_date && $this->due_date->isPast() && $this->status !== 'completed'
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
                    'planning' => ['color' => 'gray', 'label' => 'Planning'],
                    'active' => ['color' => 'green', 'label' => 'Active'],
                    'paused' => ['color' => 'yellow', 'label' => 'Paused'],
                    'completed' => ['color' => 'blue', 'label' => 'Completed'],
                    'cancelled' => ['color' => 'red', 'label' => 'Cancelled'],
                    default => ['color' => 'gray', 'label' => 'Unknown'],
                };
            }
        );
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('due_date', [now(), now()->addWeek()]);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    // HELPERS
    public function updateProgress(): void
    {
        $this->update(['progress' => $this->progress_percentage]);
    }
}