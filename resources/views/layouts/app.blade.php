<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'TaskFlow')</title>

  {{-- Favicon --}}
  <link rel="icon" type="image/jpg" href="{{ asset('images/task-logo.jpg') }}">

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

  @vite([
  'resources/css/app.css',
  'resources/js/app.js'
  ])

  @stack('page-styles')

</head>

<body>
  <div class="taskflow-layout">
    <aside class="taskflow-sidebar">
      <div class="taskflow-brand">
        <img src="{{ asset('images/task-logo-trans.png') }}" alt="TaskFlow Logo" class="taskflow-logo">
        TaskFlow
      </div>

      <nav>
        <a href="{{ route('dashboard') }}"
          class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>

        <a href="{{ route('projects.index') }}"
          class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">
          <i class="fa-solid fa-diagram-project"></i> Projects
        </a>

        <a href="{{ route('tasks.index') }}"
          class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}">
          <i class="fa-solid fa-list-check"></i> Tasks
        </a>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('users.index') }}"
          class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
          <i class="fa-solid fa-users"></i> Users
        </a>
        @endif
      </nav>
    </aside>

    <div class="taskflow-main">
      <header class="taskflow-header">
        <div>
          <div class="fw-bold fs-5">@yield('page-title', 'TaskFlow')</div>
          <small class="text-muted">Project &amp; Task Monitoring</small>
        </div>

        <div class="d-flex align-items-center gap-3">
          @auth
          <span class="taskflow-user">
            <i class="fa-regular fa-circle-user me-1"></i>
            {{ auth()->user()->name }}
          </span>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">
              <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
            </button>
          </form>
          @endauth
        </div>
      </header>

      <main class="taskflow-content">
        @hasSection('page-title')
        <h1 class="taskflow-page-title">@yield('page-title')</h1>
        @endif

        @hasSection('page-description')
        <p class="taskflow-page-description">@yield('page-description')</p>
        @endif

        @yield('content')
      </main>
    </div>
  </div>
</body>

</html>