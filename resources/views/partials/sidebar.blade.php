<aside class="sidebar">
  <div class="brand">
    <div class="brand-icon">T</div>
    <div>
      <h2>TaskFlow</h2>
      <span>Project Management</span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <p class="nav-label">MENU UTAMA</p>

    <a href="{{ route('dashboard') }}"
      class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <i class="fa-solid fa-house"></i>
      <span>Dashboard</span>
    </a>

    <a href="{{ route('projects.index') }}"
      class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
      <i class="fa-solid fa-folder-open"></i>
      <span>Manajemen Proyek</span>
    </a>

    <a href="{{ route('tasks.index') }}"
      class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
      <i class="fa-solid fa-list-check"></i>
      <span>Manajemen Tugas</span>
    </a>

    <a href="{{ route('users.index') }}"
      class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
      <i class="fa-solid fa-users"></i>
      <span>Manajemen Pengguna</span>
    </a>
  </nav>

  <div class="sidebar-bottom">
    <p>TaskFlow v1.0</p>
  </div>
</aside>