<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['assignee', 'reporter']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('assignee_id')) {
            $query->where('assignee_id', $request->assignee_id);
        }

        $tasks = $query->latest()->paginate(20);
        $users = User::all();

        return view('admin.tasks.index', compact('tasks', 'users'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'task_ids' => ['required', 'array'],
            'task_ids.*' => ['exists:tasks,id'],
            'action' => ['required', 'in:delete,mark_completed,mark_in_progress'],
        ]);

        $count = count($request->task_ids);

        if ($request->action === 'delete') {
            Task::whereIn('id', $request->task_ids)->delete();
            return back()->with('success', "$count tasks deleted successfully.");
        }

        if ($request->action === 'mark_completed') {
            Task::whereIn('id', $request->task_ids)->update(['status' => 'completed']);
            return back()->with('success', "$count tasks marked as completed.");
        }

        if ($request->action === 'mark_in_progress') {
            Task::whereIn('id', $request->task_ids)->update(['status' => 'in_progress']);
            return back()->with('success', "$count tasks marked as in progress.");
        }

        return back()->with('error', 'Invalid action.');
    }

    public function reassign(Request $request, Task $task)
    {
        $request->validate([
            'assignee_id' => ['required', 'exists:users,id'],
        ]);

        $task->update(['assignee_id' => $request->assignee_id]);

        // Notify the new assignee (optional, but good practice)
        // $task->assignee->notify(new TaskAssignedNotification($task));

        return back()->with('success', 'Task reassigned successfully.');
    }
}
