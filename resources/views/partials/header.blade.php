<header class="top-header">
  <div class="header-left">
    <h2>@yield('page-title', 'Dashboard')</h2>
    <p>Kelola proyek dan pekerjaan tim dengan mudah.</p>
  </div>

  <div class="header-right">
    <span class="header-date">
      {{ now()->translatedFormat('d F Y') }}
    </span>

    <div class="user-profile">
      <div class="user-avatar">
        <i class="fa-solid fa-user"></i>
      </div>
      <span>Pengguna</span>
    </div>
  </div>
</header>