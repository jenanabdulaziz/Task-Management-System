<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $userId = Auth::id();
        
        $stats = $this->dashboardService->getUserStats($userId);
        $upcomingTasks = $this->dashboardService->getUpcomingTasks($userId);
        $startedTasks = $this->dashboardService->getStartedTasks($userId);
        $overdueTasks = $this->dashboardService->getOverdueTasks($userId);

        $adminStats = null;
        if (Auth::user()->isAdmin() || Auth::user()->isManager()) {
            $adminStats = $this->dashboardService->getAdminStats();
        }

        return view('dashboard', compact('stats', 'upcomingTasks', 'startedTasks', 'overdueTasks', 'adminStats'));
    }
}
