<?php

namespace App\Jobs;

use App\Models\Task;
use App\Notifications\TaskReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTaskReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get tasks due in the next 24 hours that haven't been completed
        $tasks = Task::where('end_date', '>', now())
                     ->where('end_date', '<=', now()->addHours(24))
                     ->whereNotIn('status', ['completed', 'cancelled'])
                     ->get();

        foreach ($tasks as $task) {
            // Check if we already sent a reminder recently (optional logic, simplified here)
            // For now, we assume this job runs once a day or we track sent reminders
            // A better approach would be to have a 'reminder_sent_at' column on tasks
            
            // To avoid spamming, we could check if a notification was sent in the last 24h
            // For simplicity in this MVP, we'll just send it.
            // In a real app, we'd add a flag to the task or a separate table.
            
            $task->assignee->notify(new TaskReminderNotification($task));
        }
    }
}
