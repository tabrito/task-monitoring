@extends('layouts.app')

@section('title', 'Tambah Task - TaskFlow')

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
    <span>Tambah Task</span>
  </nav>

  <div class="task-form-card">
    <div class="task-form-header">
      <div class="task-form-heading">
        <div class="task-form-icon">
          <i class="fa-solid fa-list-check"></i>
        </div>
        <div>
          <h2>Tambah Task</h2>
          <p>Lengkapi informasi tugas yang akan dikerjakan.</p>
        </div>
      </div>

    </div>

    @if ($errors->any())
    <div class="task-form-alert">
      <i class="fa-solid fa-circle-exclamation"></i>
      <div>
        <strong>Data belum dapat disimpan.</strong>
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST">
      @csrf

      <div class="task-form-body">

        <section class="task-form-section">
          <div class="task-form-section-heading">
            <h3>Informasi Tugas</h3>
            <p>Masukkan informasi dasar mengenai tugas.</p>
          </div>

          <div class="task-form-grid">
            <div class="task-field full-width">
              <label for="title">
                Nama Task <span class="required-mark">*</span>
              </label>
              <div class="task-input-wrapper">
                <i class="fa-solid fa-heading"></i>
                <input
                  type="text"
                  id="title"
                  name="title"
                  value="{{ old('title') }}"
                  placeholder="Contoh: Membuat halaman dashboard"
                  maxlength="255"
                  required>
              </div>
              @error('title')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="task-field full-width">
              <label for="description">Deskripsi Task</label>
              <textarea
                id="description"
                name="description"
                rows="5"
                placeholder="Jelaskan tujuan, ruang lingkup, dan detail pekerjaan...">{{ old('description') }}</textarea>
              @error('description')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>

          </div>
        </section>

        <section class="task-form-section">
          <div class="task-form-section-heading">
            <h3>Jadwal dan Status</h3>
            <p>Tentukan target penyelesaian dan prioritas tugas.</p>
          </div>

          <div class="task-form-grid">
            <div class="task-field">
              <label for="project_id">
                Proyek <span class="required-mark">*</span>
              </label>

              <div class="task-input-wrapper">
                <i class="fa-solid fa-diagram-project"></i>

                @if (isset($selectedProject) && $selectedProject)
                {{-- Proyek terkunci jika berasal dari detail proyek --}}
                <select id="project_id" name="project_id" disabled>
                  <option value="{{ $selectedProject->id }}" selected>
                    {{ $selectedProject->name }}
                  </option>
                </select>

                {{-- Nilai tetap dikirim meskipun select disabled --}}
                <input
                  type="hidden"
                  name="project_id"
                  value="{{ $selectedProject->id }}">
                @else
                {{-- Dropdown aktif jika berasal dari menu Manajemen Task --}}
                <select id="project_id" name="project_id" required>
                  <option value="">Pilih proyek</option>

                  @foreach ($projects as $project)
                  <option
                    value="{{ $project->id }}"
                    {{ old('project_id') == $project->id ? 'selected' : '' }}>
                    {{ $project->name }}
                  </option>
                  @endforeach
                </select>
                @endif
              </div>

              @error('project_id')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="task-field">
              <label for="deadline">Deadline</label>
              <div class="task-input-wrapper">
                <i class="fa-regular fa-calendar"></i>
                <input
                  type="date"
                  id="deadline"
                  name="deadline"
                  value="{{ old('deadline') }}">
              </div>
              @error('deadline')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="task-field">
              <label for="priority">
                Prioritas <span class="required-mark">*</span>
              </label>
              <div class="task-input-wrapper">
                <i class="fa-solid fa-flag"></i>
                <select id="priority" name="priority" required>
                  <option value="low" {{ old('priority', 'medium') == 'low' ? 'selected' : '' }}>
                    Rendah
                  </option>
                  <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>
                    Sedang
                  </option>
                  <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                    Tinggi
                  </option>
                  <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>
                    Mendesak
                  </option>
                </select>
              </div>
              @error('priority')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="task-field">
              <label for="progress">Progress (%)</label>
              <div class="task-input-wrapper">
                <i class="fa-solid fa-chart-line"></i>
                <input
                  type="number"
                  id="progress"
                  name="progress"
                  min="0"
                  max="100"
                  value="{{ old('progress', 0) }}"
                  placeholder="0 - 100">
              </div>
              <small class="task-field-hint">
                Isi 0 jika pekerjaan belum dimulai.
              </small>
              @error('progress')
              <small class="task-field-error">{{ $message }}</small>
              @enderror
            </div>
          </div>
        </section>

        <div class="task-form-information">
          <i class="fa-solid fa-circle-info"></i>
          <p>
            Pastikan proyek dan penanggung jawab sudah sesuai sebelum
            menyimpan tugas.
          </p>
        </div>
      </div>

      <div class="task-form-footer">
        <a href="{{ route('tasks.index') }}" class="btn-task-cancel">
          <i class="fa-solid fa-arrow-left"></i>
          Batal
        </a>

        <button type="submit" class="btn-task-save">
          <i class="fa-solid fa-floppy-disk"></i>
          Simpan Task
        </button>
      </div>
    </form>
  </div>
</div>
@endsection