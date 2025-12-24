@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Sistem Perpustakaan</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">

    <style>
    .main-sidebar {
        background-color: #2A2771 !important;
    }

    .nav-sidebar .nav-link {
        color: #ffffff !important;
    }

    .nav-sidebar > .nav-item > .nav-link:hover {
        background-color: #4C40FF !important;
        color: white !important;
    }

    .nav-sidebar > .nav-item > .nav-link.active {
        background-color: #4C40FF !important;
        color: white !important;
    }

    .nav-treeview > .nav-item > .nav-link {
        background-color: rgba(255,255,255,0.10) !important;
        color: #ffffff !important;
        
        margin: 6px 18px !important;
        padding: 10px 15px !important;

        border-radius: 8px !important;
        width: auto !important; 
        display: block;
    }

    .nav-treeview > .nav-item > .nav-link:hover {
        background-color: #4C40FF !important;
        color: white !important;
    }

    .nav-treeview > .nav-item > .nav-link.active {
        background-color: #4C40FF !important;
        color: white !important;
    }

    .nav-sidebar .nav-link.active i,
    .nav-sidebar .nav-link:hover i,
    .nav-treeview .nav-link.active i {
        color: white !important;
    }

    .brand-link {
        background-color: #2A2771 !important;
        border-bottom: 1px solid #4C40FF !important;
    }

    .sidebar {
        height: calc(100vh - 60px);
        overflow-y: auto;
        padding-bottom: 80px;
    }

    .sidebar-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #2A2771;
        border-top: 1px solid #4C40FF;
        padding: 12px 20px;
    }

    .sidebar-mini.sidebar-collapse .sidebar-footer .nav-link span {
        display: none;
    }

    .nav-treeview > .nav-item > .nav-link {
        background: transparent !important;
        padding-left: 25px !important;
        border-radius: 8px !important;
    }

    .nav-treeview > .nav-item > .nav-link:hover {
        background: #4C40FF !important;
        color: #fff !important;
    }

    .nav-treeview > .nav-item > .nav-link.active {
        background: #4C40FF !important;
        color: #fff !important;
    }

    .main-sidebar, .sidebar, body {
        overflow-x: hidden !important;
    }

    .brand-logo {
    width: 55px !important;   /* Gedein logo */
    height: auto !important;
    object-fit: contain;
    }
    </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- NAVBAR -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="/dashboard" class="nav-link">Halaman Utama</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link" style="cursor:pointer;"
                    onclick="window.location='{{ route('profile') }}'">
                    Selamat Datang, <b>{{ auth()->user()->username }}</b>
                </span>
            </li>
        </ul>
    </nav>

    <!-- SIDEBAR -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="/dashboard" class="brand-link d-flex align-items-center">
            <img src="{{ asset('logo.png') }}" 
                alt="Logo" 
                class="brand-image"
                style="opacity: .9; width:40px; margin-right:10px;">

            <span class="brand-text font-weight-light" style="font-size:17px;">
                Sistem Perpustakaan
            </span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview {{ request()->is('peminjaman*') || request()->is('pengembalian*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-folder"></i>
                            <p>
                                Transaksi
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/peminjaman" class="nav-link {{ request()->is('peminjaman*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Peminjaman</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/pengembalian" class="nav-link {{ request()->is('pengembalian*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengembalian</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="/buku" class="nav-link {{ request()->is('buku*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Data Buku</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/anggota" class="nav-link {{ request()->is('anggota*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Data Anggota</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/denda" class="nav-link {{ request()->is('denda*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-dollar-sign"></i>
                            <p>Denda</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/laporan" class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-print"></i>
                            <p>Laporan</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="sidebar-footer">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
            class="nav-link text-white d-flex align-items-center">
                <i class="fas fa-sign-out-alt mr-2"></i>
                <span>Logout</span>
            </a>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </aside>
    <!-- CONTENT -->
    <div class="content-wrapper p-4">
        @yield('content')
    </div>
</div>

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- AdminLTE JS -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session("success") }}',
    showConfirmButton: false,
    timer: 1700
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: '{{ session("error") }}',
});
</script>
@endif

</body>
</html>
