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

        // Admin tidak memiliki akses ke manajemen task
        abort_if($user->role !== 'user', 403);

        // Ambil task dari proyek milik user yang login
        $query = Task::with('project')
            ->whereHas('project', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Pencarian task
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter prioritas
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter proyek
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Daftar task
        $tasks = (clone $query)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Daftar proyek milik user
        $projects = Project::where('user_id', $user->id)
            ->orderBy('name')
            ->get();

        // Statistik task berdasarkan progress
        $inProgressTasks = (clone $query)
            ->where('progress', '>', 0)
            ->where('progress', '<', 100)
            ->count();

        $completedTasks = (clone $query)
            ->where('progress', 100)
            ->count();

        $overdueTasks = (clone $query)
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<', now()->toDateString())
            ->where('progress', '<', 100)
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
        $user = auth()->user();

        $projects = Project::where('user_id', $user->id)
            ->orderBy('name')
            ->get();

        $selectedProject = null;

        if ($request->filled('project_id')) {
            $selectedProject = $projects->firstWhere(
                'id',
                $request->query('project_id')
            );

            abort_if(!$selectedProject, 404);
        }

        return view('tasks.create', compact(
            'projects',
            'selectedProject'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $project = Project::where('user_id', auth()->id())
            ->findOrFail($validated['project_id']);

        Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'project_id' => $project->id,
            'deadline' => $validated['deadline'] ?? null,
            'priority' => $validated['priority'],
            'progress' => (int) ($validated['progress'] ?? 0),
        ]);

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

        // Pastikan task berada dalam proyek milik user yang login
        $project = Project::where('user_id', $user->id)
            ->findOrFail($task->project_id);

        $projects = Project::where('user_id', $user->id)
            ->orderBy('name')
            ->get();

        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        $user = auth()->user();

        // Pastikan task berada dalam proyek milik user yang login
        Project::where('user_id', $user->id)
            ->findOrFail($task->project_id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'progress' => 'required|integer|min:0|max:100',
        ], [
            'title.required' => 'Nama task wajib diisi.',
            'project_id.required' => 'Proyek wajib dipilih.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'progress.required' => 'Progress wajib diisi.',
            'progress.min' => 'Progress minimal 0%.',
            'progress.max' => 'Progress maksimal 100%.',
        ]);

        // Pastikan proyek tujuan juga milik user yang login
        $project = Project::where('user_id', $user->id)
            ->findOrFail($validated['project_id']);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'note' => $validated['note'] ?? null,
            'project_id' => $project->id,
            'deadline' => $validated['deadline'] ?? null,
            'priority' => $validated['priority'],
            'progress' => $validated['progress'],
        ]);

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

        $task->save();

        return redirect()
            ->back()
            ->with('success', 'Progres dan status task berhasil diperbarui.');
    }

    public function updateNote(Request $request, Task $task)
    {
        $user = auth()->user();

        // Pastikan task berada dalam proyek milik user yang login
        Project::where('user_id', $user->id)
            ->findOrFail($task->project_id);

        $validated = $request->validate([
            'note' => 'required|string|max:5000',
        ], [
            'note.required' => 'Catatan tidak boleh kosong.',
            'note.max' => 'Catatan maksimal 5000 karakter.',
        ]);

        $task->update([
            'note' => $validated['note'],
        ]);

        return redirect()
            ->route('tasks.show', $task->id)
            ->with('success', 'Catatan task berhasil diperbarui.');
    }
}
