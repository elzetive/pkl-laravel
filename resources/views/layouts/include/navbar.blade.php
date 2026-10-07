<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul  class="navbar-nav">
    <li class="nav-item">
      <a href="#" class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a href="#" class="nav-link" data-toggle="dropdown">
        {{ Auth::user()->nama ?? Auth::user()->username }}
        <i class="far fa-user ml-1"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="{{ route('profile.edit') }}" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> Profil
        </a>
        <div class="dropdown-divider"></div>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                <i class="fas fa-sign-out-alt mr-2"></i>Keluar
            </button>
        </form>
      </div>
    </li>
  </ul>
</nav>
