@extends('layouts.app')

@section('title', 'Edit Pengguna - TaskFlow')

@push('page-styles')
@vite('resources/css/pages/user-form.css')
@endpush

@section('content')
<div class="user-form-page">

  <nav class="user-breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('dashboard') }}">
      <i class="fa-solid fa-house"></i>
      Dashboard
    </a>
    <i class="fa-solid fa-chevron-right breadcrumb-separator"></i>
    <a href="{{ route('users.index') }}">Manajemen Pengguna</a>
    <i class="fa-solid fa-chevron-right breadcrumb-separator"></i>
    <span>Edit Pengguna</span>
  </nav>

  <div class="user-form-card">
    <div class="user-form-header">
      <div class="user-form-heading">
        <div class="user-form-icon">
          <i class="fa-solid fa-user-pen"></i>
        </div>
        <div>
          <h2>Edit Pengguna</h2>
          <p>Perbarui informasi akun pengguna TaskFlow.</p>
        </div>
      </div>

      <span class="user-required-note">
        <span class="required-mark">*</span> Wajib diisi
      </span>
    </div>

    @if ($errors->any())
    <div class="user-form-alert" role="alert">
      <i class="fa-solid fa-circle-exclamation"></i>
      <div>
        <strong>Periksa kembali data Anda.</strong>
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="user-form-body">
        <div class="user-form-section">
          <div class="user-form-section-heading">
            <h3>Informasi Akun</h3>
            <p>Pastikan informasi pengguna sudah benar.</p>
          </div>

          <div class="user-form-grid">
            <div class="user-field">
              <label for="name">
                Nama Lengkap <span class="required-mark">*</span>
              </label>
              <div class="user-input-wrapper">
                <i class="fa-regular fa-user"></i>
                <input
                  type="text"
                  id="name"
                  name="name"
                  value="{{ old('name', $user->name) }}"
                  placeholder="Masukkan nama lengkap"
                  autocomplete="name"
                  required>
              </div>
              @error('name')
              <small class="user-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="user-field">
              <label for="email">
                Email <span class="required-mark">*</span>
              </label>
              <div class="user-input-wrapper">
                <i class="fa-regular fa-envelope"></i>
                <input
                  type="email"
                  id="email"
                  name="email"
                  value="{{ old('email', $user->email) }}"
                  placeholder="nama@email.com"
                  autocomplete="email"
                  required>
              </div>
              @error('email')
              <small class="user-field-error">{{ $message }}</small>
              @enderror
            </div>

            <div class="user-field full-width">
              <label for="password">Password Baru</label>
              <div class="user-input-wrapper">
                <i class="fa-solid fa-lock"></i>
                <input
                  type="password"
                  id="password"
                  name="password"
                  minlength="8"
                  placeholder="Kosongkan jika tidak ingin mengubah password"
                  autocomplete="new-password">
              </div>
              <small class="user-field-hint">
                <i class="fa-solid fa-circle-info"></i>
                Isi hanya jika ingin mengganti password. Minimal 8 karakter.
              </small>
              @error('password')
              <small class="user-field-error">{{ $message }}</small>
              @enderror
            </div>
          </div>
        </div>

        <div class="user-role-information">
          <div class="user-role-information-icon">
            <i class="fa-solid fa-shield-halved"></i>
          </div>
          <div>
            <strong>Keamanan akun</strong>
            <p>
              Password lama akan tetap digunakan jika kolom password baru
              dikosongkan.
            </p>
          </div>
        </div>
      </div>

      <div class="user-form-footer">
        <p class="user-form-footer-note">
          <i class="fa-solid fa-asterisk"></i>
          Pastikan perubahan data sudah sesuai sebelum disimpan.
        </p>

        <div class="user-form-actions">
          <a href="{{ route('users.index') }}" class="btn-user-cancel">
            <i class="fa-solid fa-arrow-left"></i>
            Batal
          </a>

          <button type="submit" class="btn-user-save">
            <i class="fa-solid fa-floppy-disk"></i>
            Simpan Perubahan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection