<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Satpam') - Jurnal & Absensi Guru</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/satpam.css') }}">

    <script src="/js/sidebar-toggle.js"></script>
    @stack('head')
</head>
<body class="dashboard-body">

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="dash-layout">
        <aside class="dash-sidebar">
            <div>
                <div class="sp-brand">
                     @include('partials.dash-brand')
                </div>
                
                <ul class="sp-nav-menu">
                    <li>
                        <a href="{{ route('satpam.dashboard') }}" class="sp-nav-link {{ request()->routeIs('satpam.dashboard') ? 'active' : '' }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('satpam.cek-izin') }}" class="sp-nav-link {{ request()->routeIs('satpam.cek-izin') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-check"></i>
                            <span>Cek Dispensasi Siswa</span>
                        </a>
                    </li>
    
                    @if(auth()->user()->isAdmin())
                    <li style="margin-top: 12px; border-top: 1px dashed var(--dash-cream-border); padding-top: 12px;">
                        <a href="{{ route('dashboard') }}" class="sp-nav-link">
                            <i class="fa-solid fa-user-shield"></i>
                            <span>Kembali ke Admin</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            <div class="dash-sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" style="width: 100%;" data-confirm-type="logout">
                    @csrf
                    <button type="submit" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; width: 100%; padding: 10px; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.85rem;">
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="dash-main">

            <header class="dash-top-bar">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button type="button" class="dash-hamburger-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                    <div>
                        <h1 class="dash-header-title">@yield('page-title', 'Dashboard')</h1>
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
                <div class="sp-alert success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="sp-alert error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')

        </main>
    </div>

    <script src="/js/live-clock.js"></script>
    @stack('scripts')
    @include('partials.confirm-modal')
</body>
</html>