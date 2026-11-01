<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = trim($request->input('search', ''));

        $projects = Project::with('manager')
            ->withCount('tasks')

            // Filter proyek berdasarkan role
            ->when($user->role === 'manager', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            })
            ->when($user->role === 'member', function ($query) use ($user) {
                $query->whereHas('tasks', function ($taskQuery) use ($user) {
                    $taskQuery->where('assigned_to', $user->id);
                });
            })

            // Fitur pencarian proyek
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('manager', function ($managerQuery) use ($search) {
                            $managerQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $managers = \App\Models\User::where('role', 'manager')
            ->orderBy('name')
            ->get();

        return view('projects.create', compact('managers'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:planning,in_progress,completed,on_hold',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'name.required' => 'Nama proyek wajib diisi.',
            'manager_id.exists' => 'Manager yang dipilih tidak valid.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        \App\Models\Project::create($validated);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function show(Project $project)
    {
        $project->load(['manager', 'tasks']);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $users = User::orderBy('name')->get();

        return view('projects.edit', compact('project', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'required|exists:users,id',
            'status' => 'required|in:on_track,concern,overdue,completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($validated);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        if ($project->tasks()->exists()) {
            return redirect()
                ->route('projects.index')
                ->with(
                    'error',
                    'Proyek tidak dapat dihapus karena masih memiliki task. Hapus atau pindahkan task terlebih dahulu.'
                );
        }

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }
}
