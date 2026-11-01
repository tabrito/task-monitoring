@extends('layouts.app')

@section('title', 'Edit Proyek - TaskFlow')

@push('page-styles')
@vite('resources/css/pages/project-form.css')
@endpush

@section('content')
<div class="project-form-page">
  {{-- Breadcrumb --}}
  <div class="project-breadcrumb">
    <a href="{{ route('dashboard') }}">
      <i class="fa-solid fa-house"></i> Dashboard
    </a> <i class="fa-solid fa-chevron-right"></i>
    <a href="{{ route('projects.index') }}">Manajemen Proyek</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span>Edit Proyek</span>
  </div>

  <div class="project-form-card">
    <div class="project-form-header">
      <div class="project-form-icon">
        <i class="fa-solid fa-pen-to-square"></i>
      </div>
      <div>
        <h2>Edit Proyek</h2>
        <p>Perbarui kolom di bawah sesuai kebutuhan.</p>
      </div>
    </div>

    @if ($errors->any())
    <div class="project-form-alert">
      <i class="fa-solid fa-circle-exclamation"></i>
      <div>
        <strong>Periksa kembali data berikut:</strong>
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    <form action="{{ route('projects.update', $project) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="project-form-body">
        <div class="project-form-section">
          <h3>Informasi Umum</h3>

          <div class="project-form-grid">
            <div class="project-field full-width">
              <label for="name">Nama Proyek <span>*</span></label>
              <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $project->name) }}"
                placeholder="Masukkan nama proyek"
                required>
              @error('name')
              <small class="field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="project-field full-width">
              <label for="description">Deskripsi Proyek</label>
              <textarea
                id="description"
                name="description"
                rows="4"
                placeholder="Jelaskan tujuan dan ruang lingkup proyek">{{ old('description', $project->description) }}</textarea>
              @error('description')
              <small class="field-error">{{ $message }}</small>
              @enderror
            </div>
          </div>
        </div>

        <div class="project-form-section timeline-section">
          <h3>Jadwal Proyek</h3>

          <div class="project-form-grid">
            <div class="project-field">
              <label for="start_date">Tanggal Mulai</label>
              <input
                type="date"
                id="start_date"
                name="start_date"
                value="{{ old('start_date', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}">
              @error('start_date')
              <small class="field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="project-field">
              <label for="end_date">Tanggal Selesai</label>
              <input
                type="date"
                id="end_date"
                name="end_date"
                value="{{ old('end_date', $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '') }}">
              @error('end_date')
              <small class="field-error">{{ $message }}</small>
              @enderror
            </div>
          </div>
        </div>
      </div>

      <div class="project-form-footer">
        <a href="{{ route('projects.index') }}" class="btn-project-cancel">
          Batal
        </a>
        <button type="submit" class="btn-project-save">
          <i class="fa-solid fa-floppy-disk"></i>
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection