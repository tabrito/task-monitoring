 @extends('layouts.app')

 @section('title', 'Detail Task')
 @section('page-title', 'Detail Task')
 @section('page-description', 'Informasi lengkap mengenai tugas.')

 @push('page-styles')
 @vite('resources/css/pages/task-detail.css')
 @endpush

 @section('content')
 <div class="task-detail-page">

   <div class="task-detail-toolbar">
     <nav class="task-detail-breadcrumb" aria-label="Breadcrumb">
       <a href="{{ route('dashboard') }}">
         <i class="fa-solid fa-house"></i> Dashboard
       </a>
       <i class="fa-solid fa-chevron-right"></i>
       <a href="{{ route('tasks.index') }}">Manajemen Task</a>
       <i class="fa-solid fa-chevron-right"></i>
       <span>Detail Task</span>
     </nav>

     @if (
     auth()->user()->role === 'manager' &&
     $task->project?->manager_id === auth()->id()
     )
     <a href="{{ route('tasks.edit', $task->id) }}" class="task-detail-edit">
       <i class="fas fa-pen"></i>
       Edit Task
     </a>
     @endif
   </div>

   @if (session('success'))
   <div class="task-detail-alert">
     {{ session('success') }}
   </div>
   @endif

   <div class="task-detail-card">
     <div class="task-detail-header">
       <div>
         <span class="task-detail-label">INFORMASI TASK</span>
         <h2>{{ $task->title }}</h2>
         <p>
           {{ $task->project?->name ?? 'Proyek belum tersedia' }}
         </p>
       </div>

       <span class="task-detail-status status-{{ $task->status }}">
         @switch($task->status)
         @case('todo')
         To Do
         @break
         @case('in_progress')
         In Progress
         @break
         @case('review')
         Review
         @break
         @case('completed')
         Completed
         @break
         @default
         {{ ucfirst(str_replace('_', ' ', $task->status)) }}
         @endswitch
       </span>
     </div>

     <div class="task-detail-body">
       <div class="task-detail-section">
         <h3>Deskripsi Task</h3>
         <p class="task-detail-description">
           {{ $task->description ?: 'Belum ada deskripsi untuk task ini.' }}
         </p>
       </div>

       <div class="task-detail-section">
         <h3>Informasi Pengerjaan</h3>

         <div class="task-detail-grid">
           <div class="task-detail-item">
             <span>Penanggung Jawab</span>
             <strong>
               {{ $task->assignee?->name ?? 'Belum ditentukan' }}
             </strong>
           </div>

           <div class="task-detail-item">
             <span>Dibuat Oleh</span>
             <strong>
               {{ $task->creator?->name ?? 'Tidak diketahui' }}
             </strong>
           </div>

           <div class="task-detail-item">
             <span>Prioritas</span>
             <strong class="priority-{{ $task->priority }}">
               {{ ucfirst($task->priority) }}
             </strong>
           </div>

           <div class="task-detail-item">
             <span>Deadline</span>
             <strong>
               {{ $task->deadline
                                ? \Carbon\Carbon::parse($task->deadline)->format('d M Y')
                                : 'Belum ditentukan' }}
             </strong>
           </div>
         </div>
       </div>

       <div class="task-detail-section">
         <div class="task-progress-inline-form">
           <div class="task-progress-group">
             <div class="task-progress-heading">
               <h3>Progres Pengerjaan</h3>
               <strong>{{ $task->progress }}%</strong>
             </div>

             <div class="task-progress-track">
               <div class="task-progress-fill"
                 style="width: {{ min(100, max(0, (int) $task->progress)) }}%">
               </div>
             </div>
           </div>

           <div class="update-task-group">
             @if (auth()->id() === $task->assigned_to)
             <form action="{{ route('tasks.updateProgress', $task->id) }}"
               method="POST" class="task-progress-inline-form">
               @csrf
               @method('PATCH')

               <input type="number" name="progress"
                 min="0" max="100"
                 value="{{ $task->progress }}"
                 aria-label="Persentase progres task"
                 required>

               <button type="submit" title="Simpan progres" aria-label="Simpan progres">
                 <i class="fas fa-save"></i>
               </button>
             </form>
             @endif
           </div>
         </div>
       </div>

       <div class="task-detail-meta">
         <span>
           <i class="far fa-calendar-plus"></i>
           Dibuat:
           {{ $task->created_at?->format('d M Y, H:i') ?? '-' }}
         </span>

         <span>
           <i class="far fa-clock"></i>
           Diperbarui:
           {{ $task->updated_at?->format('d M Y, H:i') ?? '-' }}
         </span>
       </div>
     </div>
   </div>
 </div>
 @endsection