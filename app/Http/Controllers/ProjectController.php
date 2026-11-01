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

        // Admin tidak memiliki akses manajemen proyek
        abort_if($user->role !== 'user', 403);

        $search = trim($request->input('search', ''));

        $projects = Project::where('user_id', $user->id)
            ->withCount('tasks')

            // Pencarian proyek
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'name.required' => 'Nama proyek wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        \App\Models\Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'user_id' => auth()->id(),
            'status' => 'planning',
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

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
        // Pastikan proyek merupakan milik user yang sedang login
        abort_unless($project->user_id === auth()->id(), 403);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        // Pastikan proyek merupakan milik user yang sedang login
        abort_unless($project->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'name.required' => 'Nama proyek wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
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
