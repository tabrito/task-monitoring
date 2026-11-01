@extends('layouts.app')

@section('title', 'Manajemen Pengguna - TaskFlow')

@push('page-styles')
@vite('resources/css/pages/users.css')
@endpush

@section('content')

<div class="users-page">

  {{-- Page Header --}}
  <div class="users-page-header">
    <div>
      <h1 class="users-heading">Daftar Pengguna</h1>
      <p class="users-subheading">
        Kelola pengguna yang dapat dipilih sebagai manager proyek.
      </p>
    </div>

    <a href="{{ route('users.create') }}" class="btn-user-primary">
      <i class="fa-solid fa-user-plus"></i>
      <span>Tambah Pengguna</span>
    </a>
  </div>

  @if (session('success'))
  <div class="users-alert users-alert-success">
    {{ session('success') }}
  </div>
  @endif

  @if (session('error'))
  <div class="users-alert users-alert-error">
    {{ session('error') }}
  </div>
  @endif

  {{-- Users Card --}}
  <div class="users-card">

    <div class="users-card-header">
      <div class="users-card-title">
        <div class="users-card-icon">
          <i class="fa-solid fa-users"></i>
        </div>

        <div>
          <h2>Semua Pengguna</h2>
          <p>Informasi akun pengguna yang terdaftar.</p>
        </div>
      </div>

      <span class="users-total">
        {{ $users->total() }} Pengguna
      </span>
    </div>

    {{-- Users Table --}}
    <div class="table-responsive">
      <table class="users-table">
        <thead>
          <tr>
            <th class="users-number">No.</th>
            <th>Nama Pengguna</th>
            <th>Email</th>
            <th>Tanggal Ditambahkan</th>
            <th class="users-actions-heading">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($users as $user)
          <tr>
            <td class="users-number">
              {{ $users->firstItem() + $loop->index }}
            </td>

            <td>
              <div class="user-profile">
                <div class="user-avatar">
                  {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div class="user-profile-info">
                  <span class="user-name">
                    {{ $user->name }}
                  </span>
                  <span class="badge badge-{{ strtolower($user->role) }}">
                    {{ ucfirst($user->role) }}
                  </span>
                </div>
              </div>
            </td>

            <td>
              <div class="user-email">
                <i class="fa-regular fa-envelope"></i>
                <span>{{ $user->email }}</span>
              </div>
            </td>

            <td class="user-date">
              <i class="fa-regular fa-calendar"></i>
              {{ $user->created_at?->format('d/m/Y') ?? '-' }}
            </td>

            <td>
              <div class="user-action-buttons">
                <a
                  href="{{ route('users.edit', $user) }}"
                  class="user-action-btn user-action-edit"
                  title="Edit pengguna"
                  aria-label="Edit pengguna">
                  <i class="fa-solid fa-pen"></i>
                  <span>Edit</span>
                </a>

                <form
                  action="{{ route('users.destroy', $user) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                  @csrf
                  @method('DELETE')

                  <button
                    type="submit"
                    class="user-action-btn user-action-delete"
                    title="Hapus pengguna"
                    aria-label="Hapus pengguna">
                    <i class="fa-solid fa-trash"></i>
                    <span>Hapus</span>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5">
              <div class="users-empty">
                <div class="users-empty-icon">
                  <i class="fa-solid fa-users"></i>
                </div>

                <h3>Belum Ada Pengguna</h3>
                <p>
                  Tambahkan pengguna baru untuk mulai mengelola akun di TaskFlow.
                </p>

                <a href="{{ route('users.create') }}" class="btn-user-primary">
                  <i class="fa-solid fa-user-plus"></i>
                  Tambah Pengguna
                </a>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if ($users->hasPages())
    <div class="users-pagination">
      <div class="users-pagination-info">
        Menampilkan {{ $users->firstItem() ?? 0 }}
        –{{ $users->lastItem() ?? 0 }}
        dari {{ $users->total() }} pengguna
      </div>

      <div>
        {{ $users->links() }}
      </div>
    </div>
    @endif

  </div>
</div>

@endsection