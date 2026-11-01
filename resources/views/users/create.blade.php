@extends('layouts.app')

@section('title', 'Tambah Pengguna - TaskFlow')

@push('page-styles')
@vite('resources/css/pages/user-form.css')
@endpush

@section('content')

<div class="user-form-page">

  {{-- Breadcrumb --}}
  <div class="user-breadcrumb">
    <a href="{{ route('dashboard') }}">
      <i class="fa-solid fa-house"></i>
      Dashboard
    </a>
    <i class="fa-solid fa-chevron-right"></i>
    <a href="{{ route('users.index') }}">Manajemen Pengguna</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span>Tambah Pengguna</span>
  </div>

  {{-- Form Card --}}
  <div class="user-form-card">

    <div class="user-form-header">
      <div class="user-form-heading">
        <div class="user-form-icon">
          <i class="fa-solid fa-user-plus"></i>
        </div>

        <div>
          <h2>Informasi Pengguna</h2>
          <p>Lengkapi informasi akun pengguna yang akan ditambahkan.</p>
        </div>
      </div>

      <span class="user-required-note">
        <span>*</span> Wajib diisi
      </span>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="user-form-alert">
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

    <form action="{{ route('users.store') }}" method="POST">
      @csrf

      <div class="user-form-body">

        <div class="user-form-section">
          <h3>
            <i class="fa-regular fa-id-card"></i>
            Data Akun
          </h3>
          <p>Masukkan identitas dan informasi login pengguna.</p>
        </div>

        <div class="user-form-grid">

          {{-- Name --}}
          <div class="user-field">
            <label for="name">
              Nama Lengkap <span class="required">*</span>
            </label>

            <div class="user-input-wrapper">
              <i class="fa-regular fa-user"></i>

              <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                class="@error('name') is-invalid @enderror"
                placeholder="Masukkan nama lengkap"
                maxlength="255"
                autocomplete="name"
                required
                autofocus>
            </div>

            @error('name')
            <small class="user-field-error">{{ $message }}</small>
            @enderror
          </div>

          {{-- Email --}}
          <div class="user-field">
            <label for="email">
              Email <span class="required">*</span>
            </label>

            <div class="user-input-wrapper">
              <i class="fa-regular fa-envelope"></i>

              <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                class="@error('email') is-invalid @enderror"
                placeholder="nama@email.com"
                autocomplete="email"
                required>
            </div>

            <small class="user-field-hint">
              Gunakan email yang valid dan belum terdaftar.
            </small>

            @error('email')
            <small class="user-field-error">{{ $message }}</small>
            @enderror
          </div>

          {{-- Role --}}
          <div class="user-field">
            <label for="role">
              Peran Pengguna <span class="required">*</span>
            </label>

            <div class="user-input-wrapper">
              <i class="fa-solid fa-user-tag"></i>

              <select
                name="role"
                id="role"
                class="@error('role') is-invalid @enderror"
                required>
                <option value="member" @selected(old('role', 'member' )==='member' )>
                  Anggota Tim
                </option>

                <option value="manager" @selected(old('role')==='manager' )>
                  Manager
                </option>

                <option value="admin" @selected(old('role')==='admin' )>
                  Admin
                </option>
              </select>
            </div>

            <small class="user-field-hint">
              Peran menentukan hak akses pengguna di dalam sistem.
            </small>

            @error('role')
            <small class="user-field-error">{{ $message }}</small>
            @enderror
          </div>

          {{-- Password --}}
          <div class="user-field">
            <label for="password">
              Password <span class="required">*</span>
            </label>

            <div class="user-input-wrapper">
              <i class="fa-solid fa-lock"></i>

              <input
                type="password"
                id="password"
                name="password"
                class="@error('password') is-invalid @enderror"
                placeholder="Minimal 8 karakter"
                minlength="8"
                autocomplete="new-password"
                required>
            </div>

            <small class="user-field-hint">
              Gunakan password minimal 8 karakter.
            </small>

            @error('password')
            <small class="user-field-error">{{ $message }}</small>
            @enderror
          </div>

        </div>

        {{-- Role Information --}}
        <div class="user-role-information">
          <div class="user-role-information-icon">
            <i class="fa-solid fa-circle-info"></i>
          </div>

          <div>
            <strong>Informasi Peran</strong>
            <p>
              <b>Anggota Tim</b> mengerjakan tugas,
              <b>Manager</b> mengelola proyek, dan
              <b>Admin</b> memiliki akses administratif sesuai konfigurasi sistem.
            </p>
          </div>
        </div>

      </div>

      {{-- Form Actions --}}
      <div class="user-form-footer">
        <a href="{{ route('users.index') }}" class="btn-user-cancel">
          <i class="fa-solid fa-xmark"></i>
          Batal
        </a>

        <button type="submit" class="btn-user-save">
          <i class="fa-solid fa-user-plus"></i>
          Simpan Pengguna
        </button>
      </div>

    </form>
  </div>

  <p class="user-form-footer-note">
    <i class="fa-solid fa-shield-halved"></i>
    Pastikan informasi akun sudah benar sebelum disimpan.
  </p>

</div>

@endsection