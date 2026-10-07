<aside class="main-sidebar sidebar-dark-primary elevation-4">
<a href="{{ route('dashboard') }}" class="brand-link">
    <span class="brand-text font-weight-light ml-3">SISTEM MANAJEMEN</span>
</a>

<div class="sidebar">
<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

    <li class="nav-item">
      <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="" class="nav-link">
        <i class="nav-icon fas fa-key"></i>
        <p>Ganti Password</p>
      </a>
    </li>

  </ul>
</nav>
</div>
</aside>
