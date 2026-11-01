@extends('layouts.app')

@section('title', 'Tambah Proyek - TaskFlow')

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
    <span>Tambah Proyek</span>
  </div>

  {{-- Form Card --}}
  <div class="project-form-card">

    <div class="project-form-header">
      <div class="project-form-heading">
        <div class="project-form-icon">
          <i class="fa-solid fa-folder-plus"></i>
        </div>

        <div>
          <h2>Tambah Proyek</h2>
          <p>Lengkapi informasi berikut untuk membuat proyek baru.</p>
        </div>
      </div>

    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="project-form-alert">
      <i class="fa-solid fa-circle-exclamation"></i>

      <div>
        <strong>Periksa kembali input Anda.</strong>
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    <form action="{{ route('projects.store') }}" method="POST">
      @csrf

      <div class="project-form-body">

        <div class="project-form-section">
          <h3>
            <i class="fa-regular fa-rectangle-list"></i>
            Detail Proyek
          </h3>
          <p class="project-form-section-description">
            Masukkan identitas dan gambaran umum proyek.
          </p>
        </div>

        <div class="project-form-grid">

          {{-- Project Name --}}
          <div class="project-field full-width">
            <label for="name">
              Nama Proyek <span class="required">*</span>
            </label>

            <input
              type="text"
              id="name"
              name="name"
              value="{{ old('name') }}"
              class="@error('name') is-invalid @enderror"
              placeholder="Contoh: Pengembangan Website TaskFlow"
              maxlength="255"
              required
              autofocus>

            @error('name')
            <small class="field-error">{{ $message }}</small>
            @enderror
          </div>

          {{-- Description --}}
          <div class="project-field full-width">
            <label for="description">Deskripsi Proyek</label>

            <textarea
              id="description"
              name="description"
              rows="4"
              class="@error('description') is-invalid @enderror"
              placeholder="Jelaskan tujuan, ruang lingkup, atau hasil yang ingin dicapai...">{{ old('description') }}</textarea>

            <small class="field-hint">
              Berikan deskripsi singkat agar tim memahami tujuan proyek.
            </small>

            @error('description')
            <small class="field-error">{{ $message }}</small>
            @enderror
          </div>

          {{-- Manager --}}
          <div class="project-field">
            <label for="manager_id">Manager Proyek</label>

            <div class="project-input-wrapper">
              <i class="fa-solid fa-user-tie"></i>

              <select
                id="manager_id"
                name="manager_id"
                class="@error('manager_id') is-invalid @enderror">
                <option value="">Pilih manager proyek</option>

                @foreach ($managers as $manager)
                <option
                  value="{{ $manager->id }}"
                  @selected(old('manager_id')==$manager->id)>
                  {{ $manager->name }}
                </option>
                @endforeach
              </select>
            </div>

            <small class="field-hint">
              Manager dapat ditentukan sesuai kebutuhan proyek.
            </small>

            @error('manager_id')
            <small class="field-error">{{ $message }}</small>
            @enderror
          </div>

          {{-- Status --}}
          <div class="project-field">
            <label for="status">
              Status Proyek <span class="required">*</span>
            </label>

            <div class="project-input-wrapper">
              <i class="fa-solid fa-list-check"></i>

              <select
                id="status"
                name="status"
                class="@error('status') is-invalid @enderror"
                required>
                <option value="planning" @selected(old('status', 'planning' )==='planning' )>
                  Planning
                </option>
                <option value="in_progress" @selected(old('status')==='in_progress' )>
                  In Progress
                </option>
                <option value="completed" @selected(old('status')==='completed' )>
                  Completed
                </option>
                <option value="on_hold" @selected(old('status')==='on_hold' )>
                  On Hold
                </option>
              </select>
            </div>

            @error('status')
            <small class="field-error">{{ $message }}</small>
            @enderror
          </div>

        </div>

        {{-- Timeline Section --}}
        <div class="project-form-section timeline-section">
          <h3>
            <i class="fa-regular fa-calendar-days"></i>
            Jadwal Proyek
          </h3>
          <p class="project-form-section-description">
            Tentukan rencana waktu pelaksanaan proyek.
          </p>
        </div>

        <div class="project-form-grid">

          {{-- Start Date --}}
          <div class="project-field">
            <label for="start_date">Tanggal Mulai</label>

            <div class="project-input-wrapper">
              <i class="fa-regular fa-calendar"></i>

              <input
                type="date"
                id="start_date"
                name="start_date"
                value="{{ old('start_date') }}"
                class="@error('start_date') is-invalid @enderror">
            </div>

            @error('start_date')
            <small class="field-error">{{ $message }}</small>
            @enderror
          </div>

          {{-- End Date --}}
          <div class="project-field">
            <label for="end_date">Tanggal Selesai</label>

            <div class="project-input-wrapper">
              <i class="fa-regular fa-calendar-check"></i>

              <input
                type="date"
                id="end_date"
                name="end_date"
                value="{{ old('end_date') }}"
                class="@error('end_date') is-invalid @enderror">
            </div>

            @error('end_date')
            <small class="field-error">{{ $message }}</small>
            @enderror
          </div>

        </div>

      </div>

      {{-- Form Actions --}}
      <div class="project-form-footer">
        <a href="{{ route('projects.index') }}" class="btn-project-cancel">
          <i class="fa-solid fa-arrow-left"></i>
          Kembali
        </a>

        <button type="submit" class="btn-project-save">
          <i class="fa-solid fa-floppy-disk"></i>
          Simpan Proyek
        </button>
      </div>

    </form>
  </div>

  <p class="project-form-footer-note">
    <i class="fa-solid fa-shield-halved"></i>
    Pastikan informasi proyek sudah benar sebelum disimpan.
  </p>

</div>

@endsection