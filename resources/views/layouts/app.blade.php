<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jurnal & Absensi Guru')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

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
</head>
<body>

    <!-- Application Navigation Bar -->
    <nav class="app-navbar">
        <div class="navbar-container">
            <a href="{{ url('/') }}" class="navbar-brand">
                <span class="logo-icon"></span>
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
            </ul>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="main-wrapper">
        @if(session('success'))
            <div class="alert alert-success">
                <span>✅</span>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>