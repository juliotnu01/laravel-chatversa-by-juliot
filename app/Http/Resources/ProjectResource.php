<?php

namespace App\Http\Resources;

class ProjectResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date?->toISOString(),
            'progress' => $this->progress,
            
            // Attributes computados
            'progress_percentage' => $this->progress_percentage,
            'is_overdue' => $this->is_overdue,
            'days_until_due' => $this->days_until_due,
            'status_badge' => $this->status_badge,
            
            // Timestamps
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Relaciones
            'owner' => $this->whenLoaded('owner', function () {
                return new UserResource($this->owner);
            }),
            
            'tasks' => $this->whenLoaded('tasks', function () {
                return TaskResource::collection($this->tasks);
            }),
            
            // Counts
            'tasks_count' => $this->whenCounted('tasks'),
            'completed_tasks_count' => $this->whenCounted('completedTasks'),
        ];
    }
}