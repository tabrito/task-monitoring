<?php

namespace App\Http\Controllers;

use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Query task berdasarkan role
        $taskQuery = Task::query();

        if ($user->role === 'manager') {
            $taskQuery->whereHas('project', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            });
        } elseif ($user->role === 'member') {
            $taskQuery->where('assigned_to', $user->id);
        }

        // Total task
        $totalTasks = (clone $taskQuery)->count();

        // Task dalam pengerjaan
        $inProgressTasks = (clone $taskQuery)
            ->where('status', 'in_progress')
            ->count();

        // Task selesai
        $completedTasks = (clone $taskQuery)
            ->where('status', 'completed')
            ->count();

        // Task terlambat
        $overdueTasks = (clone $taskQuery)
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<', now()->toDateString())
            ->where('status', '!=', 'completed')
            ->count();

        // Lima task terbaru sesuai role
        $recentTasks = (clone $taskQuery)
            ->with(['project', 'assignee'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalTasks',
            'inProgressTasks',
            'completedTasks',
            'overdueTasks',
            'recentTasks'
        ));
    }
}
