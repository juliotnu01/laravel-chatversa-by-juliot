<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    public function index(Request $request): JsonResponse
    {
        $query = Task::whereHas('project', function ($query) use ($request) {
                $query->where('owner_id', $request->user()->id);
            })
            ->orWhere('assigned_to', $request->user()->id)
            ->with(['project', 'assignee']);

        // Filtros
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Scopes
        if ($request->has('pending') && $request->pending) {
            $query->pending();
        }

        if ($request->has('completed') && $request->completed) {
            $query->completed();
        }

        if ($request->has('high_priority') && $request->high_priority) {
            $query->highPriority();
        }

        // Ordenamiento
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = str_starts_with($sortField, '-') ? 'desc' : 'asc';
        $sortField = ltrim($sortField, '-');
        
        $validSortFields = ['name', 'due_date', 'priority', 'created_at', 'updated_at'];
        if (in_array($sortField, $validSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest();
        }

        $tasks = $query->paginate($request->input('per_page', 15));

        return response()->json(new TaskCollection($tasks));
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        return response()->json(new TaskResource($task->load(['project', 'assignee'])), 201);
    }

    public function show(Task $task): JsonResponse
    {
        $task->load(['project', 'assignee']);

        return response()->json(new TaskResource($task));
    }

    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());

        return response()->json(new TaskResource($task->load(['project', 'assignee'])));
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): JsonResponse
    {
        $task->update(['status' => $request->status]);

        return response()->json(new TaskResource($task->load(['project', 'assignee'])));
    }
}