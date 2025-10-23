<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class UserResource extends BaseResource
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
            'email' => $this->email,
            
            // Attributes computados
            'is_online' => $this->is_online,
            'login_status' => $this->login_status,
            'projects_count' => $this->projects_count,
            'tasks_count' => $this->tasks_count,
            
            // Timestamps
            'last_login_at' => $this->last_login_at?->toISOString(),
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Relaciones
            'projects' => $this->whenLoaded('projects', function () {
                return ProjectResource::collection($this->projects);
            }),
            
            'assigned_tasks' => $this->whenLoaded('assignedTasks', function () {
                return TaskResource::collection($this->assignedTasks);
            }),
        ];
    }
}