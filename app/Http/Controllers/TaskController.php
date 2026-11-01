<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Task::with(['project', 'assignee', 'creator']);

        // Filter task berdasarkan role
        if ($user->role === 'manager') {
            $query->whereHas('project', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        } elseif ($user->role === 'member') {
            $query->where('assigned_to', $user->id);
        }

        // Pencarian task
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter prioritas
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter proyek
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Daftar proyek sesuai role
        $projects = Project::query()
            ->when($user->role === 'manager', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            })
            ->when($user->role === 'member', function ($q) use ($user) {
                $q->whereHas('tasks', function ($taskQuery) use ($user) {
                    $taskQuery->where('assigned_to', $user->id);
                });
            })
            ->orderBy('name')
            ->get();

        // Statistik berdasarkan task yang dapat diakses pengguna
        $statsQuery = clone $query;

        $inProgressTasks = (clone $statsQuery)
            ->where('status', 'in_progress')
            ->count();

        $completedTasks = (clone $statsQuery)
            ->where('status', 'completed')
            ->count();

        $overdueTasks = (clone $statsQuery)
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<', now()->toDateString())
            ->where('status', '!=', 'completed')
            ->count();

        return view('tasks.index', compact(
            'tasks',
            'projects',
            'inProgressTasks',
            'completedTasks',
            'overdueTasks'
        ));
    }

    public function create(Request $request)
    {
        $selectedProject = null;

        if ($request->filled('project_id')) {
            $selectedProject = Project::findOrFail($request->query('project_id'));
        }

        $user = auth()->user();

        $projects = Project::when(
            $user->role === 'manager',
            function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            }
        )->orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('tasks.create', compact(
            'projects',
            'users',
            'selectedProject'
        ));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:todo,in_progress,review,completed',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $validated['progress'] = (int) ($validated['progress'] ?? 0);
        $validated['created_by'] = auth()->id();

        Task::create($validated);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task berhasil ditambahkan.');
    }


    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $user = auth()->user();

        $projects = Project::when(
            $user->role === 'manager',
            function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            }
        )->orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:todo,in_progress,review,completed',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $task->update($validated);

        return redirect()
            ->route('tasks.show', $task->id)
            ->with('success', 'Task berhasil diperbarui.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task berhasil dihapus.');
    }

    public function updateProgress(Request $request, Task $task)
    {
        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $progress = (int) $validated['progress'];

        $task->progress = $progress;

        if ($progress === 0) {
            // Progres 0% berarti task belum dikerjakan.
            $task->status = 'todo';
        } elseif ($progress === 100) {
            // Progres 100% berarti task selesai.
            $task->status = 'completed';
        } else {
            // Progres 1–99% berarti task sedang dikerjakan.
            $task->status = 'in_progress';
        }

        $task->save();

        return redirect()
            ->back()
            ->with('success', 'Progres dan status task berhasil diperbarui.');
    }
}
