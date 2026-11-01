@extends('layouts.app')

@section('title', 'Manajemen Task - TaskFlow')

@push('page-styles')
@vite('resources/css/pages/tasks.css')
@endpush

@section('content')
<div class="tasks-page">

  <div class="tasks-page-header">
    <div>
      <h2 class="tasks-heading">Daftar Task</h2>
      <p class="tasks-subheading">
        Pantau dan kelola seluruh tugas dalam proyek.
      </p>
    </div>

    @if (in_array(auth()->user()->role, ['manager']))
    <a href="{{ route('tasks.create') }}" class="btn-task-primary">
      <i class="fa-solid fa-plus"></i>
      Tambah Task
    </a>
    @endif
  </div>

  @if (session('success'))
  <div class="tasks-alert tasks-alert-success">
    <i class="fa-solid fa-circle-check"></i>
    {{ session('success') }}
  </div>
  @endif

  @if (session('error'))
  <div class="tasks-alert tasks-alert-error">
    <i class="fa-solid fa-circle-exclamation"></i>
    {{ session('error') }}
  </div>
  @endif

  <form action="{{ route('tasks.index') }}" method="GET" class="task-filter-form">
    <div class="task-filter-search">
      <i class="fas fa-search"></i>
      <input
        type="text"
        name="search"
        placeholder="Cari judul atau deskripsi task..."
        value="{{ request('search') }}">
    </div>

    <select name="status" aria-label="Filter status">
      <option value="">Semua Status</option>
      <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>To Do</option>
      <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
      <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>Review</option>
      <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
    </select>

    <select name="priority" aria-label="Filter prioritas">
      <option value="">Semua Prioritas</option>
      <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
      <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
      <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
      <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
    </select>

    <select name="project_id" aria-label="Filter proyek">
      <option value="">Semua Proyek</option>
      @foreach ($projects as $project)
      <option value="{{ $project->id }}"
        {{ request('project_id') == $project->id ? 'selected' : '' }}>
        {{ $project->name }}
      </option>
      @endforeach
    </select>

    <button type="submit" class="task-filter-submit">
      <i class="fas fa-filter"></i>
      Filter
    </button>

    <a href="{{ route('tasks.index') }}" class="task-filter-reset">
      Reset
    </a>
  </form>

  <div class="tasks-summary">
    <div class="tasks-summary-card">
      <div class="tasks-summary-icon summary-total">
        <i class="fa-solid fa-list-check"></i>
      </div>
      <div>
        <p>Total Task</p>
        <h3>{{ $tasks->total() }}</h3>
      </div>
    </div>

    <div class="tasks-summary-card">
      <div class="tasks-summary-icon summary-progress">
        <i class="fa-solid fa-spinner"></i>
      </div>
      <div>
        <p>Sedang Dikerjakan</p>
        <h3>{{ $inProgressTasks ?? 0 }}</h3>
      </div>
    </div>

    <div class="tasks-summary-card">
      <div class="tasks-summary-icon summary-completed">
        <i class="fa-solid fa-circle-check"></i>
      </div>
      <div>
        <p>Selesai</p>
        <h3>{{ $completedTasks ?? 0 }}</h3>
      </div>
    </div>

    <div class="tasks-summary-card">
      <div class="tasks-summary-icon summary-overdue">
        <i class="fa-solid fa-clock"></i>
      </div>
      <div>
        <p>Terlambat</p>
        <h3>{{ $overdueTasks ?? 0 }}</h3>
      </div>
    </div>
  </div>

  <div class="tasks-card">
    <div class="tasks-card-header">
      <div>
        <h3>Semua Task</h3>
        <p>Daftar pekerjaan yang tercatat di TaskFlow.</p>
      </div>

      <span class="tasks-total">
        {{ $tasks->total() }} task
      </span>
    </div>

    <div class="tasks-table-wrapper">
      <table class="tasks-table">
        <thead>
          <tr>
            <th>No.</th>
            <th>Task</th>
            <th>Penanggung Jawab</th>
            <th>Deadline</th>
            <th>Progress</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($tasks as $task)
          @php
          $status = strtolower($task->status ?? 'todo');

          $statusLabels = [
          'todo' => 'Belum Dimulai',
          'in_progress' => 'Dikerjakan',
          'review' => 'Review',
          'completed' => 'Selesai',
          'on_track' => 'Sesuai Jadwal',
          'concern' => 'Perlu Perhatian',
          'overdue' => 'Terlambat',
          ];

          $statusClasses = [
          'todo' => 'task-status-todo',
          'in_progress' => 'task-status-progress',
          'review' => 'task-status-review',
          'completed' => 'task-status-completed',
          'on_track' => 'task-status-progress',
          'concern' => 'task-status-review',
          'overdue' => 'task-status-overdue',
          ];

          $progress = (int) ($task->progress ?? 0);
          $progress = max(0, min(100, $progress));
          @endphp

          <tr>
            <td class="task-number">
              {{ $tasks->firstItem() + $loop->index }}
            </td>

            <td>
              <div class="task-info">
                <span class="task-name">
                  {{ $task->title }}
                </span>
                <span class="task-project">
                  {{ $task->project?->name ?? 'Belum ada proyek' }}
                </span>
              </div>
            </td>

            <td>
              <div class="task-assignee">
                <div class="task-avatar">
                  {{ strtoupper(substr($task->assignee?->name ?? 'U', 0, 1)) }}
                </div>
                <span>
                  {{ $task->assignee?->name ?? 'Belum ditentukan' }}
                </span>
              </div>
            </td>

            <td>
              <span class="task-deadline">
                @if ($task->deadline)
                {{ \Illuminate\Support\Carbon::parse($task->deadline)->format('d M Y') }}
                @else
                <span class="task-no-deadline">Belum ditentukan</span>
                @endif
              </span>
            </td>

            <td>
              <div class="task-progress-inline">
                <div class="task-progress-inline-heading">
                  <span>Progres</span>
                  <strong>{{ $task->progress }}%</strong>
                </div>

                <div class="task-progress-inline-track">
                  <div class="task-progress-inline-fill"
                    style="width: {{ min(100, max(0, (int) $task->progress)) }}%">
                  </div>
                </div>
              </div>
            </td>

            <td>
              <span class="task-status {{ $statusClasses[$status] ?? 'task-status-todo' }}">
                <span class="task-status-dot"></span>
                {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
              </span>
            </td>

            <td>
              <div class="task-actions">
                {{-- Detail Task --}}
                <a
                  href="{{ route('tasks.show', $task) }}"
                  class="task-action-btn task-action-detail"
                  title="Detail Task"
                  aria-label="Detail Task">
                  <i class="fa-solid fa-eye"></i>
                </a>

                {{-- Edit: Admin dan Manager proyek terkait --}}
                @if (
                auth()->user()->role === 'manager' &&
                $task->project?->manager_id === auth()->id()
                )
                <a
                  href="{{ route('tasks.edit', $task) }}"
                  class="task-action-btn task-action-edit"
                  title="Edit Task"
                  aria-label="Edit Task">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                @endif

                {{-- Hapus: hanya Admin --}}
                @if (auth()->user()->role === 'manager')
                <form
                  action="{{ route('tasks.destroy', $task->id) }}"
                  method="POST"
                  class="task-delete-form"
                  onsubmit="return confirm('Apakah kamu yakin ingin menghapus task ini?')">
                  @csrf
                  @method('DELETE')

                  <button
                    type="submit"
                    class="task-action-btn task-action-delete"
                    title="Hapus Task"
                    aria-label="Hapus Task">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8">
              <div class="tasks-empty">
                <div class="tasks-empty-icon">
                  <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <h3>Belum Ada Task</h3>
                <p>
                  Mulai dengan menambahkan task untuk proyekmu.
                </p>
                <a href="{{ route('tasks.create') }}" class="btn-task-primary">
                  <i class="fa-solid fa-plus"></i>
                  Tambah Task
                </a>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($tasks->hasPages())
    <div class="tasks-pagination">
      <span>
        Menampilkan {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }}
        dari {{ $tasks->total() }} task
      </span>

      {{ $tasks->links() }}
    </div>
    @endif
  </div>
</div>
@endsection