<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    public function index(Request $request): JsonResponse
    {
        $query = Project::where('owner_id', $request->user()->id)
            ->with(['owner', 'tasks'])
            ->withCount(['tasks', 'completedTasks']);

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
        if ($request->has('active') && $request->active) {
            $query->active();
        }

        if ($request->has('high_priority') && $request->high_priority) {
            $query->highPriority();
        }

        if ($request->has('due_this_week') && $request->due_this_week) {
            $query->dueThisWeek();
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

        $projects = $query->paginate($request->input('per_page', 10));

        return response()->json(new ProjectCollection($projects));
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        return response()->json(new ProjectResource($project->load(['owner', 'tasks'])), 201);
    }

    public function show(Project $project): JsonResponse
    {
        $project->load(['owner', 'tasks.assignee']);

        return response()->json(new ProjectResource($project));
    }

    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());

        return response()->json(new ProjectResource($project->load(['owner', 'tasks'])));
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}