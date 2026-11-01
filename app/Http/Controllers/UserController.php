<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,manager,member',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }


    public function edit(\App\Models\User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make(
                $validated['password']
            );
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Cegah pengguna menghapus akun sendiri.
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Periksa apakah pengguna masih menjadi manager proyek.
        $hasManagedProjects = Project::where('manager_id', $user->id)->exists();

        if ($hasManagedProjects) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'Pengguna tidak dapat dihapus karena masih menjadi manager proyek. Pindahkan pengelolaan proyek ke pengguna lain terlebih dahulu.'
                );
        }

        // Periksa apakah pengguna masih menjadi pembuat task.
        $hasCreatedTasks = \App\Models\Task::where('created_by', $user->id)->exists();

        if ($hasCreatedTasks) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'Pengguna tidak dapat dihapus karena masih tercatat sebagai pembuat task.'
                );
        }

        // Periksa apakah pengguna masih menjadi penanggung jawab task.
        $hasAssignedTasks = \App\Models\Task::where('assigned_to', $user->id)->exists();

        if ($hasAssignedTasks) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'Pengguna tidak dapat dihapus karena masih memiliki task yang ditugaskan kepadanya.'
                );
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
