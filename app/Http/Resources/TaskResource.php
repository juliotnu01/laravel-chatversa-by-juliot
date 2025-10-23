<?php

namespace App\Http\Resources;

class TaskResource extends BaseResource
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
            'estimated_hours' => $this->estimated_hours,
            'actual_hours' => $this->actual_hours,
            
            // Attributes computados
            'is_overdue' => $this->is_overdue,
            'days_until_due' => $this->days_until_due,
            'status_badge' => $this->status_badge,
            
            // Timestamps
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Relaciones
            'project' => $this->whenLoaded('project', function () {
                return new ProjectResource($this->project);
            }),
            
            'assignee' => $this->whenLoaded('assignee', function () {
                return new UserResource($this->assignee);
            }),
        ];
    }
}