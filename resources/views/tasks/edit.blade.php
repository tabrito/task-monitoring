@extends('layouts.app')

@section('title', 'Edit Task')

@push('page-styles')
@vite('resources/css/pages/task-form.css')
@endpush

@section('content')
<div class="task-form-page">

  <nav class="task-form-breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('dashboard') }}">
      <i class="fa-solid fa-house"></i> Dashboard
    </a>
    <i class="fa-solid fa-chevron-right"></i>
    <a href="{{ route('tasks.index') }}">Manajemen Task</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span>Edit Task</span>
  </nav>

  <div class="task-form-card">
    <div class="task-form-header">
      <div class="task-form-icon">
        <i class="fas fa-pen-to-square"></i>
      </div>
      <div>
        <h2>Edit Task</h2>
        <p>Ubah informasi tugas sesuai kebutuhan.</p>
      </div>
    </div>

    @if ($errors->any())
    <div class="task-form-alert">
      <strong>Periksa kembali data yang dimasukkan.</strong>
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="task-form-body">
        <div class="task-form-section">
          <h3>Informasi Dasar</h3>

          <div class="task-form-grid">
            <div class="task-form-field full-width">
              <label for="title">Judul Task <span>*</span></label>
              <input type="text" name="title" id="title"
                value="{{ old('title', $task->title) }}"
                placeholder="Masukkan judul task" required>
              @error('title')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="task-form-field full-width">
              <label for="description">Deskripsi</label>
              <textarea name="description" id="description" rows="4"
                placeholder="Jelaskan detail tugas">{{ old('description', $task->description) }}</textarea>
              @error('description')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="task-form-field">
              <label for="project_id">Proyek <span>*</span></label>
              <select name="project_id" id="project_id" required>
                <option value="">Pilih Proyek</option>
                @foreach ($projects as $project)
                <option value="{{ $project->id }}"
                  {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                  {{ $project->name }}
                </option>
                @endforeach
              </select>
              @error('project_id')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="task-form-field">
              <label for="assigned_to">Penanggung Jawab</label>
              <select name="assigned_to" id="assigned_to">
                <option value="">Belum Ditentukan</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}"
                  {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                  {{ $user->name }}
                </option>
                @endforeach
              </select>
              @error('assigned_to')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>
          </div>
        </div>

        <div class="task-form-section">
          <h3>Pengaturan Pengerjaan</h3>

          <div class="task-form-grid">
            <div class="task-form-field">
              <label for="deadline">Deadline</label>
              <input type="date" name="deadline" id="deadline"
                value="{{ old('deadline', $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('Y-m-d') : '') }}">
              @error('deadline')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="task-form-field">
              <label for="priority">Prioritas <span>*</span></label>
              <select name="priority" id="priority" required>
                @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'] as $value => $label)
                <option value="{{ $value }}"
                  {{ old('priority', $task->priority) == $value ? 'selected' : '' }}>
                  {{ $label }}
                </option>
                @endforeach
              </select>
              @error('priority')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="task-form-field">
              <label for="status">Status <span>*</span></label>
              <select name="status" id="status" required>
                @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'review' => 'Review', 'completed' => 'Completed'] as $value => $label)
                <option value="{{ $value }}"
                  {{ old('status', $task->status) == $value ? 'selected' : '' }}>
                  {{ $label }}
                </option>
                @endforeach
              </select>
              @error('status')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="task-form-field">
              <label for="progress">Progres (%) <span>*</span></label>
              <input type="number" name="progress" id="progress"
                min="0" max="100"
                value="{{ old('progress', $task->progress) }}" required>
              <small class="task-field-hint">Masukkan angka 0 sampai 100.</small>
              @error('progress')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>
          </div>
        </div>
      </div>

      <div class="task-form-footer">
        <a href="{{ route('tasks.show', $task->id) }}" class="task-form-cancel">
          Batal
        </a>
        <button type="submit" class="task-form-submit">
          <i class="fas fa-save"></i>
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection