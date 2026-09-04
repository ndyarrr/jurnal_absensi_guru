<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guru Mengajar') - Jurnal & Absensi Guru</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Shared Admin Design System (sidebar, topbar, tokens) -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <!-- Guru Mengajar Page-Specific Styles -->
    <link rel="stylesheet" href="{{ asset('css/modules/guru_mengajar.css') }}">

    <script src="/js/sidebar-toggle.js"></script>
    @stack('head')
</head>
<body class="dashboard-body">

    <!-- Sidebar Backdrop Overlay (Mobile) -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="dash-layout">
        <!-- Sidebar Navigation (Unified Admin Style) -->
        <aside class="dash-sidebar">
            <div>
                @include('partials.dash-brand')

                <ul class="gm-nav-menu">
                    <li>
                        <a href="{{ route('guru-mengajar.dashboard') }}" class="gm-nav-link {{ request()->routeIs('guru-mengajar.dashboard') ? 'active' : '' }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru-mengajar.jadwal') }}" class="gm-nav-link {{ request()->routeIs('guru-mengajar.jadwal') ? 'active' : '' }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span>Jadwal Mengajar</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru-mengajar.jurnal') }}" class="gm-nav-link {{ request()->routeIs('guru-mengajar.jurnal') ? 'active' : '' }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg>
                            <span>Jurnal Harian</span>
                        </a>
                    </li>

                    @if(auth()->user()->isAdmin())
                    <li style="margin-top: 12px; border-top: 1px dashed var(--dash-cream-border); padding-top: 12px;">
                        <a href="{{ route('dashboard') }}" class="gm-nav-link">
                            <i class="fa-solid fa-user-shield"></i>
                            <span>Kembali ke Admin</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            <div class="dash-sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                    @csrf
                    <button type="submit" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; width: 100%; padding: 10px; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.85rem;">
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="dash-main">

            <!-- Top Header Bar -->
            <header class="dash-top-bar">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button type="button" class="dash-hamburger-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                    <div>
                        <h1 class="dash-header-title">@yield('page-title', 'Beranda Guru')</h1>
                        <div class="dash-header-subtitle">@yield('page-subtitle', \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y'))</div>
                    </div>
                </div>

                <div class="dash-top-right">
                    <div class="dash-date-widget">
                        <svg class="dash-date-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <rect x="7" y="14" width="3" height="3" fill="currentColor"></rect>
                        </svg>
                        <div class="dash-date-info">
                            <span class="date-str" id="live_date_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
                            <span class="time-str" id="live_time_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }} WIB</span>
                        </div>
                    </div>

                    @include('partials.dash-user-widget')
                </div>
            </header>

            @if(session('success'))
                <div class="gm-alert success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="gm-alert error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')

        </main>
    </div>

    <script src="/js/live-clock.js"></script>
    @stack('scripts')
</body>
</html>