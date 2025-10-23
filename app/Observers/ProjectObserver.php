<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    public function creating(Project $project): void
    {
        // Asegurar que el owner_id esté establecido
        if (!$project->owner_id && auth()->check()) {
            $project->owner_id = auth()->id();
        }
    }

    public function updating(Project $project): void
    {
        // Si el progreso cambió, actualizar el timestamp
        if ($project->isDirty('progress')) {
            $project->updated_at = now();
        }
    }

    public function deleted(Project $project): void
    {
        // Soft delete de las tareas relacionadas
        $project->tasks()->delete();
    }

    public function restored(Project $project): void
    {
        // Restaurar las tareas relacionadas
        $project->tasks()->restore();
    }

    public function forceDeleted(Project $project): void
    {
        // Eliminar permanentemente las tareas relacionadas
        $project->tasks()->forceDelete();
    }
}