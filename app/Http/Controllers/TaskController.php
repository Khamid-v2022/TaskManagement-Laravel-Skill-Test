<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('priority')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $maxPriority = Task::max('priority') ?? 0;

        $task = Task::create([
            'name' => $validated['name'],
            'priority' => $maxPriority + 1,
            'status' => 'pending',
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'task' => $task,
        ]);
    }
}
