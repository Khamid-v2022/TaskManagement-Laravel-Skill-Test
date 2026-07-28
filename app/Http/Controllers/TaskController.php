<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function update(Request $request, Task $task) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $task->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'task' => $task->fresh(),
        ]);
    }

    public function destroy(Task $task) {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ]);
    }

    // Reorder tasks
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer', 'exists:tasks,id'],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['order'] as $index => $taskId) {
                Task::where('id', $taskId)->update([
                    'priority' => $index + 1, // top = 1
                ]);
            }
        });
        return response()->json([
            'success' => true,
            'message' => 'Tasks reordered successfully',
        ]);
    }
}
