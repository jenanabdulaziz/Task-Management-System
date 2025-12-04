<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject(__('New Task Assigned') . ': ' . $this->task->title)
                    ->line(__('You have been assigned a new task.'))
                    ->line(__('Task') . ': ' . $this->task->title)
                    ->line(__('Due Date') . ': ' . $this->task->end_date->format('M d, Y H:i'))
                    ->action(__('View Task'), route('tasks.show', $this->task))
                    ->line(__('Thank you for using our application!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'message' => __('You have been assigned a new task') . ': ' . $this->task->title,
            'link' => route('tasks.show', $this->task),
            'type' => 'assignment',
        ];
    }
}
