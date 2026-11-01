@extends('layouts.app')

@section('title', 'Manajemen Proyek - TaskFlow')

@push('page-styles')
@vite('resources/css/pages/projects.css')
@endpush

@section('content')

<div class="projects-page">

  {{-- Page Header --}}
  <div class="projects-page-header">
    <div>
      <h1 class="projects-heading">Daftar Proyek</h1>
      <p class="projects-subheading">
        Kelola dan pantau seluruh proyek tim.
      </p>
    </div>

    <a href="{{ route('projects.create') }}" class="btn-project-primary">
      <i class="fa-solid fa-plus"></i>
      <span>Tambah Proyek</span>
    </a>
  </div>

  {{-- Notifications --}}
  @if(session('success'))
  <div class="projects-alert projects-alert-success">
    <i class="fa-solid fa-circle-check"></i>
    <span>{{ session('success') }}</span>
  </div>
  @endif

  @if(session('error'))
  <div class="projects-alert projects-alert-error">
    <i class="fa-solid fa-circle-exclamation"></i>
    <span>{{ session('error') }}</span>
  </div>
  @endif

  {{-- Search Projects --}}
  <form action="{{ route('projects.index') }}" method="GET" class="projects-search-form">
    <div class="projects-search-input">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input
        type="search"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari nama proyek, deskripsi, atau manager..."
        aria-label="Cari proyek">

      @if(request('search'))
      <a href="{{ route('projects.index') }}"
        class="projects-search-clear"
        title="Hapus pencarian"
        aria-label="Hapus pencarian">
        <i class="fa-solid fa-xmark"></i>
      </a>
      @endif
    </div>

    <button type="submit" class="btn-project-search">
      <i class="fa-solid fa-magnifying-glass"></i>
      Cari
    </button>
  </form>

  @if(request()->filled('search'))
  <div class="projects-search-result">
    Hasil pencarian untuk
    <strong>"{{ request('search') }}"</strong>:
    {{ $projects->total() }} proyek ditemukan.
  </div>
  @endif

  {{-- Projects Table --}}
  <div class="projects-card">

    <div class="projects-card-header">
      <div>
        <h2>Semua Proyek</h2>
        <p>Daftar proyek yang terdaftar di TaskFlow.</p>
      </div>

      <span class="projects-total">
        {{ $projects->total() }} Proyek
      </span>
    </div>

    <div class="table-responsive">
      <table class="projects-table">
        <thead>
          <tr>
            <th class="column-number">No.</th>
            <th>Nama Proyek</th>
            <th>Tugas</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Status</th>
            <th class="column-actions">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($projects as $project)
          <tr class="project-row"
            onclick="window.location='{{ route('projects.show', $project) }}'">
            <td class="column-number">
              {{ $projects->firstItem() + $loop->index }}
            </td>

            <td class="project-info">
              <div class="project-name">
                {{ $project->name }}
              </div>

              <div class="project-description">
                {{ $project->description ?: 'Tidak ada deskripsi' }}
              </div>
            </td>

            <td>
              <span class="project-task-count">
                {{ $project->tasks_count }}
              </span>
            </td>

            <td class="project-date">
              {{ $project->start_date?->format('d/m/Y') ?? '-' }}
            </td>

            <td class="project-date">
              {{ $project->end_date?->format('d/m/Y') ?? '-' }}
            </td>

            <td>
              @php
              $projectStatus = $project->status ?? 'unknown';

              $statusLabels = [
              'on_track' => 'On Track',
              'concern' => 'Concern',
              'overdue' => 'Overdue',
              'completed' => 'Completed',
              ];

              $statusClasses = [
              'on_track' => 'status-on-track',
              'concern' => 'status-concern',
              'overdue' => 'status-overdue',
              'completed' => 'status-completed',
              ];
              @endphp

              <span class="project-status {{ $statusClasses[$projectStatus] ?? 'status-unknown' }}">
                <span class="status-dot"></span>
                {{ $statusLabels[$projectStatus] ?? ucfirst(str_replace('_', ' ', $projectStatus)) }}
              </span>
            </td>

            <td>
              <div class="project-actions" onclick="event.stopPropagation();">
                {{-- Semua role dapat melihat detail proyek --}}

                <a
                  href="{{ route('projects.edit', $project) }}"
                  class="project-action-btn action-edit"
                  title="Edit proyek"
                  aria-label="Edit proyek">
                  <i class="fa-solid fa-pen"></i>
                  Edit
                </a>

                <form
                  action="{{ route('projects.destroy', $project) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus proyek ini?')">
                  @csrf
                  @method('DELETE')

                  <button
                    class="project-action-btn action-delete"
                    type="submit"
                    title="Hapus proyek"
                    aria-label="Hapus proyek">
                    <i class="fa-solid fa-trash"></i>
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8">
              <div class="projects-empty">
                <div class="projects-empty-icon">
                  <i class="fa-solid fa-folder-open"></i>
                </div>

                <h3>Belum Ada Proyek</h3>
                <p>Tambahkan proyek pertama untuk mulai mengelola pekerjaan tim.</p>

                <a href="{{ route('projects.create') }}" class="btn-project-primary">
                  <i class="fa-solid fa-plus"></i>
                  Tambah Proyek
                </a>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($projects->hasPages())
    <div class="projects-pagination">
      <div class="pagination-info">
        Menampilkan {{ $projects->firstItem() ?? 0 }}
        –{{ $projects->lastItem() ?? 0 }}
        dari {{ $projects->total() }} proyek
      </div>

      <div>
        {{ $projects->links() }}
      </div>
    </div>
    @endif

  </div>
</div>

@endsection