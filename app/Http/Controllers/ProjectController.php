<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');

        $projects = Auth::user()->currentTeam->projects()
            ->when($status && $status != 'all', function($query) use ($status){
                $query->where('status', $status);
            })
            ->withCount('tasks')
            ->latest()
            ->get();

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
            'filters' => [
                'status' => $status,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:not_started,in_progress,completed,on_hold'],
            'due_date' => ['required', 'date'],
        ]);

        Auth::user()->currentTeam->projects()->create($validated);

        return redirect()->route('projects.index');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $project->load(['tasks' => function ($query) {
            $query->latest();
        }]);

        return Inertia::render('Projects/Show', [
            'project' => $project,
            'tasks' => $project->tasks,
            'taskStats' => [
                'total' => $project->tasks->count(),
                'completed' => $project->tasks->where('status', 'completed')->count(),
            ],
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:not_started,in_progress,completed,on_hold'],
            'due_date' => ['required', 'date'],
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index');
    }
}
