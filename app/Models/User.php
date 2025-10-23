<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'is_online',
        'login_status',
        'projects_count',
        'tasks_count',
    ];

    // RELACIONES
    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // ATTRIBUTES
    protected function isOnline(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->last_login_at && $this->last_login_at->gt(now()->subMinutes(5))
        );
    }

    protected function loginStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->last_login_at) return 'never_logged_in';
                return $this->is_online ? 'online' : 'last_seen_' . $this->last_login_at->diffForHumans();
            }
        );
    }

    protected function projectsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->projects()->count()
        );
    }

    protected function tasksCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->assignedTasks()->count()
        );
    }

    // HELPERS
    public function recordLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }
}