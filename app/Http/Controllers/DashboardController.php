<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Admin langsung diarahkan ke menu manajemen user
        if ($user->role === 'admin') {
            return redirect()->route('users.index');
        }

        // Hanya user yang dapat mengakses dashboard task
        abort_unless($user->role === 'user', 403);

        // Query task berdasarkan proyek milik user
        $taskQuery = Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });

        $totalTasks = (clone $taskQuery)->count();

        $inProgressTasks = (clone $taskQuery)
            ->where('progress', '>', 0)
            ->where('progress', '<', 100)
            ->count();

        $completedTasks = (clone $taskQuery)
            ->where('progress', 100)
            ->count();

        $overdueTasks = (clone $taskQuery)
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<', now()->toDateString())
            ->where('progress', '<', 100)
            ->count();

        $recentTasks = (clone $taskQuery)
            ->with('project')
            ->latest()
            ->take(5)
            ->get();

        $totalProjects = Project::where('user_id', $user->id)->count();

        return view('dashboard.index', compact(
            'totalTasks',
            'inProgressTasks',
            'completedTasks',
            'overdueTasks',
            'recentTasks',
            'totalProjects'
        ));
    }
}
