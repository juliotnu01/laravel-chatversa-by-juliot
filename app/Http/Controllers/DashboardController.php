<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        return Inertia::render('Dashboard', [
            'user' => $user->load(['projects', 'assignedTasks']),
            'initialProjects' => Project::where('owner_id', $user->id)
                ->with(['owner', 'tasks'])
                ->withCount(['tasks', 'completedTasks'])
                ->latest()
                ->take(10)
                ->get(),
            'initialTasks' => Task::where('assigned_to', $user->id)
                ->orWhereHas('project', function ($query) use ($user) {
                    $query->where('owner_id', $user->id);
                })
                ->with(['project', 'assignee'])
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }
}