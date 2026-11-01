
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::get('/profile', [
        \App\Http\Controllers\ProfileController::class,
        'edit'
    ])->name('profile.edit');

    Route::patch('/profile', [
        \App\Http\Controllers\ProfileController::class,
        'update'
    ])->name('profile.update');

    Route::delete('/profile', [
        \App\Http\Controllers\ProfileController::class,
        'destroy'
    ])->name('profile.destroy');

    Route::patch('/tasks/{task}/progress', [
        \App\Http\Controllers\TaskController::class,
        'updateProgress'
    ])->name('tasks.updateProgress');
    Route::resource('tasks', TaskController::class);

    Route::patch('/tasks/{task}/note', [TaskController::class, 'updateNote'])
        ->name('tasks.updateNote');
});

require __DIR__ . '/auth.php';
