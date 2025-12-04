<?php

namespace App\Jobs;

use App\Models\Task;
use App\Notifications\TaskOverdueNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckOverdueTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tasks = Task::where('end_date', '<', now())
                     ->whereNotIn('status', ['completed', 'cancelled'])
                     ->get();

        foreach ($tasks as $task) {
            // Similar logic to reminders, avoid spamming.
            // We could check if we already notified about overdue status today.
            
            $task->assignee->notify(new TaskOverdueNotification($task));
        }
    }
}
