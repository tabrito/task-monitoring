 @extends('layouts.app')

 @section('title', 'Detail Proyek - TaskFlow')

 @push('page-styles')
 @vite('resources/css/pages/project-detail.css')
 @endpush

 @section('content')
 <div class="project-detail-page">
   <div class="project-detail-header">
     <nav class="project-detail-breadcrumb" aria-label="Breadcrumb">
       <a href="{{ route('dashboard') }}">
         <i class="fa-solid fa-house"></i> Dashboard
       </a>
       <i class="fa-solid fa-chevron-right"></i>
       <a href="{{ route('projects.index') }}">Manajemen Proyek</a>
       <i class="fa-solid fa-chevron-right"></i>
       <span>Detail Proyek</span>
     </nav>

     @if (in_array(auth()->user()->role, ['admin', 'project_manager']))
     <a href="{{ route('projects.edit', $project) }}" class="btn-project-edit">
       <i class="fa-solid fa-pen"></i>
       Edit Proyek
     </a>
     @endif
   </div>

   @php
   $statusLabels = [
   'on_track' => 'Sesuai Jadwal',
   'concern' => 'Perlu Perhatian',
   'overdue' => 'Terlambat',
   'completed' => 'Selesai',
   ];

   $statusClass = [
   'on_track' => 'status-on-track',
   'concern' => 'status-concern',
   'overdue' => 'status-overdue',
   'completed' => 'status-completed',
   ];
   @endphp

   <div class="project-detail-card">
     <div class="project-detail-card-header">
       <div class="project-detail-icon">
         <i class="fa-solid fa-folder-open"></i>
       </div>

       <div class="project-detail-main">
         <h2>{{ $project->name }}</h2>
         <span class="project-status {{ $statusClass[$project->status] ?? 'status-unknown' }}">
           <span class="status-dot"></span>
           {{ $statusLabels[$project->status] ?? ucfirst(str_replace('_', ' ', $project->status)) }}
         </span>
       </div>
     </div>

     <div class="project-detail-body">
       <div class="project-detail-section">
         <h3>Informasi Proyek</h3>

         <div class="project-detail-grid">
           <div class="project-detail-item">
             <span class="detail-label">Nama Proyek</span>
             <span class="detail-value">{{ $project->name }}</span>
           </div>

           <div class="project-detail-item">
             <span class="detail-label">Manajer Proyek</span>
             <span class="detail-value">
               {{ $project->manager?->name ?? 'Belum ditentukan' }}
             </span>
           </div>

           <div class="project-detail-item">
             <span class="detail-label">Tanggal Mulai</span>
             <span class="detail-value">
               {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') : '-' }}
             </span>
           </div>

           <div class="project-detail-item">
             <span class="detail-label">Tanggal Selesai</span>
             <span class="detail-value">
               {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') : '-' }}
             </span>
           </div>

           <div class="project-detail-item">
             <span class="detail-label">Jumlah Task</span>
             <span class="detail-value">
               {{ $project->tasks->count() }} task
             </span>
           </div>

           <div class="project-detail-item">
             <span class="detail-label">Dibuat Pada</span>
             <span class="detail-value">
               {{ $project->created_at?->format('d/m/Y H:i') ?? '-' }}
             </span>
           </div>
         </div>
       </div>

       <div class="project-detail-section">
         <h3>Deskripsi Proyek</h3>
         <div class="project-description-box">
           {{ $project->description ?: 'Belum ada deskripsi untuk proyek ini.' }}
         </div>
       </div>

       <div class="project-detail-section">
         <div class="project-task-header">
           <div class="project-task-header-actions">
             <h3>Daftar Task</h3>

             <span class="project-task-badge">
               {{ $project->tasks->count() }} task
             </span>

           </div>

           <div class="project-task-header-actions">

             @if (in_array(auth()->user()->role, ['admin', 'project_manager']))
             <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}"
               class="btn-project-primary">
               <i class="fa-solid fa-plus"></i>
               <span>Tambah Task</span>
             </a>
             @endif
           </div>
         </div>

         @if ($project->tasks->isNotEmpty())
         <div class="project-task-list">
           @foreach ($project->tasks as $task)
           <div class="project-task-item">
             <div class="project-task-info">
               <span class="project-task-name">{{ $task->title }}</span>
               <span class="project-task-assignee">
                 Penanggung jawab:
                 {{ $task->assignee?->name ?? 'Belum ditentukan' }}
               </span>
             </div>

             <div class="project-task-progress">
               <div class="project-progress-track">
                 <div class="project-progress-fill"
                   style="width: {{ min(100, max(0, (int) $task->progress)) }}%">
                 </div>
               </div>
               <span>{{ (int) $task->progress }}%</span>
             </div>

             <a href="{{ route('tasks.show', $task) }}"
               class="project-task-action"
               title="Lihat task"
               aria-label="Lihat task">
               <i class="fa-solid fa-arrow-up-right-from-square"></i>
             </a>
           </div>
           @endforeach
         </div>
         @else
         <div class="project-no-task">
           <i class="fa-regular fa-clipboard"></i>
           <p>Belum ada task pada proyek ini.</p>
         </div>
         @endif
       </div>
     </div>
   </div>
 </div>
 @endsection