<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    public function creating(Task $task): void
    {
        // Validar que la tarea pertenezca a un proyecto válido
        if (!$task->project_id) {
            throw new \InvalidArgumentException('Task must belong to a project.');
        }
    }

    public function created(Task $task): void
    {
        // Actualizar el progreso del proyecto cuando se crea una nueva tarea
        $task->project->updateProgress();
    }

    public function updated(Task $task): void
    {
        // Si el estado de la tarea cambió, actualizar el progreso del proyecto
        if ($task->isDirty('status')) {
            $task->project->updateProgress();
        }

        // Si se cambió el tiempo real, verificar que no sea mayor al estimado
        if ($task->isDirty('actual_hours') && $task->estimated_hours) {
            if ($task->actual_hours > $task->estimated_hours * 1.5) {
                // Podríamos enviar una notificación aquí
                \Log::warning("Task {$task->id} exceeded estimated hours by 50%");
            }
        }
    }

    public function deleted(Task $task): void
    {
        // Actualizar el progreso del proyecto cuando se elimina una tarea
        $task->project->updateProgress();
    }

    public function restored(Task $task): void
    {
        // Actualizar el progreso del proyecto cuando se restaura una tarea
        $task->project->updateProgress();
    }
}