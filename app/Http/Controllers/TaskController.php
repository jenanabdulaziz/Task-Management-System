<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\FileUploadService;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function index(Request $request)
    {
        $query = $this->getFilteredQuery($request);
        $tasks = $query->with(['assignee', 'reporter'])->latest()->paginate(10);
        $users = User::active()->get();

        return view('tasks.index', compact('tasks', 'users'));
    }

    public function export(Request $request)
    {
        $query = $this->getFilteredQuery($request);
        $tasks = $query->with(['assignee', 'reporter'])->latest()->get();

        $filename = "tasks_export_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Title', 'Status', 'Priority', 'Start Date', 'Due Date', 'Assignee', 'Reporter', 'Created At'];

        $callback = function() use($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                $row = [
                    $task->id,
                    $task->title,
                    $task->status,
                    $task->priority,
                    $task->start_date->format('Y-m-d H:i'),
                    $task->end_date->format('Y-m-d H:i'),
                    $task->assignee ? $task->assignee->name : 'Unassigned',
                    $task->reporter ? $task->reporter->name : 'Unknown',
                    $task->created_at->format('Y-m-d H:i'),
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getFilteredQuery(Request $request)
    {
        $query = Task::query();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assignee_id')) {
            $query->where('assignee_id', $request->assignee_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Date Range Filter
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_date', '<=', $request->date_to);
        }

        // Role-based scoping
        if (Auth::user()->role === 'user') {
            $query->where(function($q) {
                $q->where('assignee_id', Auth::id())
                  ->orWhere('reporter_id', Auth::id());
            });
        }

        return $query;
    }

    public function create()
    {
        $users = User::active()->get();
        return view('tasks.create', compact('users'));
    }

    public function store(CreateTaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'priority' => $request->priority,
            'assignee_id' => $request->assignee_id,
            'reporter_id' => Auth::id(),
            'status' => 'not_started',
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $this->fileUploadService->upload($task, $file, Auth::id());
            }
        }

        // Send notification to assignee
        if ($task->assignee) {
            $task->assignee->notify(new TaskAssignedNotification($task));
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->updateStatusBasedOnDates();
        $task->load(['attachments', 'activityLogs.actor']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $users = User::active()->get();
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $oldAssigneeId = $task->assignee_id;
        $task->update($request->validated());

        // Notify new assignee if assignee changed
        if ($task->assignee_id !== $oldAssigneeId && $task->assignee) {
            $task->assignee->notify(new TaskAssignedNotification($task));
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $this->fileUploadService->upload($task, $file, Auth::id());
            }
        }

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function complete(Task $task)
    {
        $this->authorize('update', $task);
        
        $task->update(['status' => 'completed']);
        
        ActivityLog::log('completed', $task);
        
        return back()->with('success', 'Task marked as completed.');
    }

    public function cancel(Task $task)
    {
        $this->authorize('update', $task);
        
        $task->update(['status' => 'cancelled']);
        
        ActivityLog::log('cancelled', $task);
        
        return back()->with('success', 'Task cancelled.');
    }
}
