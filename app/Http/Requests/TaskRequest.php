<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('POST')) {
            return $this->user()->can('create', Task::class);
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return $this->user()->can('update', $this->task);
        }

        if ($this->isMethod('DELETE')) {
            return $this->user()->can('delete', $this->task);
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed', 'cancelled'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'due_date' => ['nullable', 'date', 'after:today'],
            'estimated_hours' => ['nullable', 'integer', 'min:0'],
            'actual_hours' => ['nullable', 'integer', 'min:0'],
            'project_id' => ['required', 'exists:projects,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'due_date' => 'due date',
            'estimated_hours' => 'estimated hours',
            'actual_hours' => 'actual hours',
            'project_id' => 'project',
            'assigned_to' => 'assigned to',
        ];
    }
}