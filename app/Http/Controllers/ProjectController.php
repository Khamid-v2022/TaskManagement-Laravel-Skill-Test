<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('name')->get();
        return view('projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project = Project::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'project' => $project,
        ]);
    }
    
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'project' => $project->fresh(),
        ]);
    }
    
    public function destroy(Project $project)
    {
        $project->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
        ]);
    }
}
