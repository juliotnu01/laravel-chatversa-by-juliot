<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('POST')) {
            return $this->user()->can('create', Project::class);
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return $this->user()->can('update', $this->project);
        }

        if ($this->isMethod('DELETE')) {
            return $this->user()->can('delete', $this->project);
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['planning', 'active', 'paused', 'completed', 'cancelled'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'due_date' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function attributes(): array
    {
        return [
            'due_date' => 'due date',
        ];
    }

    public function prepareForValidation(): void
    {
        if (!$this->has('owner_id')) {
            $this->merge([
                'owner_id' => $this->user()->id,
            ]);
        }
    }
}