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

                @php
                    $master = request()->routeIs([
                        'admin.data_pengguna*',
                        'admin.data_jurusan*',
                        'admin.data_akademik*',
                        'admin.data_dosen*',
                        'admin.data_mahasiswa*',
                        'admin.data_matkul*'
                    ]) && !request()->routeIs(['admin.data_kelas_matkul*', 'admin.detail_kelas*', 'admin.pertemuan*']);
                @endphp

                <li class="nav-item {{ $master ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $master ? 'active' : '' }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Data Master
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.data_pengguna') }}" class="nav-link pl-4 {{ request()->routeIs('admin.data_pengguna*') ? 'text-primary font-weight-bold' : '' }}">
                                <i class="fas fa-users-cog nav-icon"></i>
                                <p>Data Pengguna</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.data_akademik') }}" class="nav-link pl-4 {{ request()->routeIs('admin.data_akademik*') ? 'text-primary font-weight-bold' : '' }}">
                                <i class="fas fa-graduation-cap nav-icon"></i>
                                <p>Data Akademik</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.data_jurusan') }}" class="nav-link pl-4 {{ request()->routeIs('admin.data_jurusan*') ? 'text-primary font-weight-bold' : '' }}">
                                <i class="fas fa-university nav-icon"></i>
                                <p>Data Jurusan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.data_dosen') }}" class="nav-link pl-4 {{ request()->routeIs('admin.data_dosen*') ? 'text-primary font-weight-bold' : '' }}">
                                <i class="fas fa-user-tie nav-icon"></i>
                                <p>Data Dosen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.data_mahasiswa') }}" class="nav-link pl-4 {{ request()->routeIs('admin.data_mahasiswa*') ? 'text-primary font-weight-bold' : '' }}">
                                <i class="fas fa-user-graduate nav-icon"></i>
                                <p>Data Mahasiswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.data_matkul') }}" class="nav-link pl-4 {{ request()->routeIs('admin.data_matkul*') && !request()->routeIs(['admin.data_kelas_matkul*', 'admin.detail_kelas*', 'admin.pertemuan*']) ? 'text-primary font-weight-bold' : '' }}">
                                <i class="fas fa-book nav-icon"></i>
                                <p>Data Matkul</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @php
                    $kelas = request()->routeIs([
                        'admin.data_kelas_matkul*',
                        'admin.data_detail_kelas*',
                        'admin.data_pertemuan*',
                        'admin.data_presensi*'
                    ]);
                @endphp

                <li class="nav-item">
                    <a href="{{ route('admin.data_kelas_matkul') }}" class="nav-link {{ $kelas ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>Kelas Mata Kuliah</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is('admin/ganti-password*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Ganti Password</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
