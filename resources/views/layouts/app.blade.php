<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jurnal & Absensi Guru')</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Modular CSS Architecture -->
    <link rel="stylesheet" href="{{ asset('css/base/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/buttons.css') }}">

    <!-- Page Specific Module CSS -->
    <link rel="stylesheet" href="{{ asset('css/modules/siswa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/jurusan.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/kelas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/guru.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/jadwal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/jurnal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/mapel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/user.css') }}">
</head>
<body>

    <!-- Application Navigation Bar -->
    <nav class="app-navbar">
        <div class="navbar-container">
            <a href="{{ url('/') }}" class="navbar-brand">
                <span class="logo-icon" style="display: inline-flex; align-items: center;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </span>
                <span>Jurnal Absensi</span>
                <span class="badge">v1.0</span>
            </a>
            <ul class="navbar-menu">
                <li><a href="{{ route('jurnal.index') }}" class="{{ request()->is('jurnal*') ? 'active' : '' }}">Jurnal Mengajar</a></li>
                <li><a href="{{ route('jadwal.index') }}" class="{{ request()->is('jadwal*') ? 'active' : '' }}">Jadwal Pelajaran</a></li>
                <li><a href="{{ route('guru.index') }}" class="{{ request()->is('guru*') ? 'active' : '' }}">Guru</a></li>
                <li><a href="{{ route('siswa.index') }}" class="{{ request()->is('siswa*') ? 'active' : '' }}">Siswa</a></li>
                <li><a href="{{ route('kelas.index') }}" class="{{ request()->is('kelas*') ? 'active' : '' }}">Kelas</a></li>
                <li><a href="{{ route('jurusan.index') }}" class="{{ request()->is('jurusan*') ? 'active' : '' }}">Jurusan</a></li>
                <li><a href="{{ route('mapel.index') }}" class="{{ request()->is('mapel*') ? 'active' : '' }}">Mapel</a></li>
                <li><a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}">Pengguna</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="main-wrapper">
        @if(session('success'))
            <div class="alert alert-success">
                <span style="display: inline-flex; align-items: center;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </span>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <span style="display: inline-flex; align-items: center;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </span>
                <div>
                    <strong>Perhatian!</strong> {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>