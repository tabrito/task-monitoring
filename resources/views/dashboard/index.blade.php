@extends('layouts.app')

@section('title', 'Dashboard - TaskFlow')

@push('page-styles')
@vite('resources/css/pages/dashboard.css')
@endpush

@section('content')

{{-- Statistics --}}
<div class="stats-grid mb-4">

  <div class="stats-grid-item">
    <div class="card stat-card maroon-accent">
      <div class="card-body">
        <div class="stat-top">
          <span class="stat-label">Total Tasks</span>
          <span class="stat-icon">
            <i class="fa-solid fa-list-check"></i>
          </span>
        </div>
        <div class="stat-number">{{ $totalTasks }}</div>
        <div class="stat-caption">Seluruh tugas tercatat</div>
      </div>
    </div>
  </div>

  <div class="stats-grid-item">
    <div class="card stat-card brown-accent">
      <div class="card-body">
        <div class="stat-top">
          <span class="stat-label">In Progress</span>
          <span class="stat-icon">
            <i class="fa-solid fa-clock"></i>
          </span>
        </div>
        <div class="stat-number">{{ $inProgressTasks }}</div>
        <div class="stat-caption">Tugas sedang dikerjakan</div>
      </div>
    </div>
  </div>

  <div class="stats-grid-item">
    <div class="card stat-card brown-accent">
      <div class="card-body">
        <div class="stat-top">
          <span class="stat-label">Completed</span>
          <span class="stat-icon">
            <i class="fa-solid fa-circle-check"></i>
          </span>
        </div>
        <div class="stat-number">{{ $completedTasks }}</div>
        <div class="stat-caption">Tugas telah selesai</div>
      </div>
    </div>
  </div>

  <div class="stats-grid-item">
    <div class="card stat-card maroon-accent">
      <div class="card-body">
        <div class="stat-top">
          <span class="stat-label">Overdue</span>
          <span class="stat-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
          </span>
        </div>
        <div class="stat-number">{{ $overdueTasks }}</div>
        <div class="stat-caption">Tugas melewati deadline</div>
      </div>
    </div>
  </div>

</div>

{{-- Recent Tasks --}}
<section class="content-card">

  <div class="content-card-header">
    <div>
      <h2 class="section-title">Recent Tasks</h2>
      <p class="section-description">
        Daftar tugas terbaru dalam proyek.
      </p>
    </div>

    <a href="{{ route('tasks.index') }}" class="btn-maroon">
      Lihat Semua <i class="fa-solid fa-arrow-right ms-1"></i>
    </a>
  </div>

  <div class="table-responsive">
    <table class="table align-middle dashboard-table">
      <thead>
        <tr>
          <th>Nama Tugas</th>
          <th>Catatan</th>
          <th>Proyek</th>
          <th style="min-width: 130px;">Progres</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($recentTasks as $task)

        @php
        $statusLabels = [
        'todo' => 'To Do',
        'in_progress' => 'In Progress',
        'review' => 'Review',
        'completed' => 'Completed',
        ];

        $statusClasses = [
        'todo' => 'status-todo',
        'in_progress' => 'status-progress',
        'review' => 'status-review',
        'completed' => 'status-completed',
        ];

        $progress = max(0, min(100, (int) $task->progress));
        @endphp

        <tr>
          <td>
            <div class="task-title">{{ $task->title }}</div>
            <small class="task-deadline">
              {{ $task->description ?? '-' }}
            </small>
          </td>

          <td>{{ $task->note ?? '-' }}</td>

          <td>{{ $task->project?->name ?? '-' }}</td>

          <td>
            <div class="d-flex justify-content-between mb-2">
              <span class="progress-value">{{ $progress }}%</span>
            </div>

            <div class="progress">
              <div
                class="progress-bar"
                role="progressbar"
                style="width: {{ $progress }}%;"
                aria-valuenow="{{ $progress }}"
                aria-valuemin="0"
                aria-valuemax="100">
              </div>
            </div>
          </td>
        </tr>

        @empty
        <tr>
          <td colspan="5" class="empty-state">
            <i class="fa-solid fa-clipboard-list empty-icon"></i>
            <div class="fw-bold mb-1">Belum ada tugas</div>
            <div>Tambahkan tugas pertama melalui menu Tasks.</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</section>

@endsection