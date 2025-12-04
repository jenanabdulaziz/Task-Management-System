<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for tasks due soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find tasks due within the next 24 hours that are not completed/cancelled
        $tasks = \App\Models\Task::where('end_date', '>', now())
            ->where('end_date', '<=', now()->addHours(24))
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->get();

        $count = 0;
        foreach ($tasks as $task) {
            // Check if we haven't sent a reminder today (optional logic, for now just send)
            // In a real app, we'd check a log table to avoid duplicate reminders
            
            if ($task->assignee) {
                $task->assignee->notify(new \App\Notifications\TaskReminderNotification($task));
                $count++;
            }
        }

        $this->info("Sent {$count} task reminders.");
    }
}
