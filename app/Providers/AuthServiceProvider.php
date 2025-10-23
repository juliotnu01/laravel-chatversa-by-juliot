<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Task::class => TaskPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate para verificar si el usuario puede administrar proyectos
        Gate::define('manage-projects', function (User $user) {
            return $user->exists;
        });

        // Gate para verificar si el usuario es propietario del proyecto
        Gate::define('project-owner', function (User $user, Project $project) {
            return $project->owner_id === $user->id;
        });

        // Gate para verificar si el usuario está asignado a una tarea
        Gate::define('task-assignee', function (User $user, Task $task) {
            return $task->assigned_to === $user->id;
        });

        // Gate para verificar si el usuario puede cambiar el estado de una tarea
        Gate::define('update-task-status', function (User $user, Task $task) {
            return $task->project->owner_id === $user->id || $task->assigned_to === $user->id;
        });
    }
}
