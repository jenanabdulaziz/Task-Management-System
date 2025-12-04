<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;

class ExportController extends Controller
{
    public function exportTasks()
    {
        $filename = "tasks_export_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Title', 'Description', 'Status', 'Priority', 'Start Date', 'Due Date', 'Assignee', 'Reporter', 'Created At'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            Task::with(['assignee', 'reporter'])->chunk(100, function($tasks) use ($file) {
                foreach ($tasks as $task) {
                    fputcsv($file, [
                        $task->id,
                        $task->title,
                        $task->description,
                        $task->status,
                        $task->priority,
                        $task->start_date,
                        $task->end_date,
                        $task->assignee->name,
                        $task->reporter->name,
                        $task->created_at
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
