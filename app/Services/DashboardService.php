<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function getUserStats($userId)
    {
        return [
            'upcoming_count' => Task::where('assignee_id', $userId)->upcoming()->count(),
            'started_count' => Task::where('assignee_id', $userId)->started()->count(),
            'overdue_count' => Task::where('assignee_id', $userId)->overdue()->count(),
            'completed_count' => Task::where('assignee_id', $userId)->where('status', 'completed')->count(),
        ];
    }

    public function getAdminStats()
    {
        return [
            'total_users' => User::count(),
            'total_tasks' => Task::count(),
            'overdue_tasks' => Task::overdue()->count(),
            'active_tasks' => Task::whereIn('status', ['not_started', 'in_progress'])->count(),
        ];
    }

    public function getUpcomingTasks($userId, $limit = 5)
    {
        return Task::where('assignee_id', $userId)
            ->upcoming()
            ->orderBy('start_date')
            ->limit($limit)
            ->get();
    }

    public function getStartedTasks($userId, $limit = 5)
    {
        return Task::where('assignee_id', $userId)
            ->started()
            ->orderBy('start_date')
            ->limit($limit)
            ->get();
    }

    public function getOverdueTasks($userId, $limit = 5)
    {
        return Task::where('assignee_id', $userId)
            ->overdue()
            ->orderBy('end_date')
            ->limit($limit)
            ->get();
    }
}
