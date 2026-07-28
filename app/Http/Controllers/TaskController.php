<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\Models\Project;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::orderBy('name')->get();
        $filter = $request->query('project', 'all');    // all | unassigned | {id}

        $query = Task::with('project')->orderBy('priority');

        if ($filter === 'unassigned') {
            $query->whereNull('project_id');
        } elseif ($filter !== 'all' && $filter !== '') {
            $query->where('project_id', $filter);
        }

        $tasks = $query->get();

        return view('tasks.index', compact('tasks', 'projects', 'filter'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $validated['project_id'] = $validated['project_id'] ?? null;

        $maxPriority = Task::max('priority') ?? 0;

        $task = Task::create([
            'name' => $validated['name'],
            'priority' => $maxPriority + 1,
            'status' => 'pending',
            'description' => $validated['description'] ?? null,
            'project_id' => $validated['project_id'],
        ]);

        $task->load('project');

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
            'project_id' => 'nullable|exists:projects,id',
        ]);
        $validated['project_id'] = $validated['project_id'] ?? null;

        $task->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'project_id' => $validated['project_id'],
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'task' => $task->fresh()->load('project'),
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

        $order = $validated['order'];

        DB::transaction(function () use ($order) {
            // foreach ($validated['order'] as $index => $taskId) {
            //     Task::where('id', $taskId)->update([
            //         'priority' => $index + 1, // top = 1
            //     ]);
            // }
            $slots = Task::whereIn('id', $order)
                ->orderBy('priority')
                ->pluck('priority')
                ->values();

            foreach ($order as $index => $taskId) {
                Task::where('id', $taskId)->update([
                    'priority' => $slots[$index],
                ]);
            }
        });
        return response()->json([
            'success' => true,
            'message' => 'Tasks reordered successfully',
        ]);
    }
}
