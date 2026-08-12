<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Jurnal & Absensi Guru</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Dashboard CSS Module -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
</head>
<body class="dashboard-body">

    <div class="dash-layout">

        <!-- ===================================================================
             Left Sidebar Navigation
             =================================================================== -->
        <aside class="dash-sidebar">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="dash-brand">
                <svg width="42" height="42" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 75C20 70 30 65 50 65C70 65 80 70 80 75V80H20V75Z" fill="#252B3E"/>
                    <path d="M50 20L15 40L50 60L85 40L50 20Z" fill="#D97706"/>
                    <path d="M50 20L25 34.2857V60L50 45.7143V20Z" fill="#F59E0B"/>
                    <path d="M35 65L20 50V70L35 80V65Z" fill="#252B3E"/>
                    <path d="M65 65L80 50V70L65 80V65Z" fill="#252B3E"/>
                    <circle cx="50" cy="50" r="12" fill="#252B3E"/>
                    <path d="M46 50L49 53L55 47" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>

            <ul class="dash-menu">

                <!-- Active Dashboard -->
                <li class="dash-menu-item active">
                    <a href="{{ route('dashboard') }}" class="dash-menu-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Master Data Dropdown Category -->
                <li class="dash-menu-category">
                    <button type="button" class="dash-category-btn" onclick="toggleSubmenu('masterDataSub')">
                        <div class="dash-category-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <span>Master Data</span>
                        </div>
                        <svg class="dash-category-chevron" id="masterDataChevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <ul class="dash-sub-menu" id="masterDataSub">
                        <li>
                            <a href="{{ route('users.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Pengguna</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('siswa.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                </svg>
                                <span>Siswa</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('guru.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span>Guru</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kelas.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    <path d="M3 9h18M9 21V9"></path>
                                </svg>
                                <span>Kelas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('mapel.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                <span>Mapel</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Akademik Dropdown Category -->
                <li class="dash-menu-category">
                    <button type="button" class="dash-category-btn" onclick="toggleSubmenu('akademikSub')">
                        <div class="dash-category-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            <span>Akademik</span>
                        </div>
                        <svg class="dash-category-chevron" id="akademikChevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <ul class="dash-sub-menu" id="akademikSub">
                        <li>
                            <a href="{{ route('jadwal.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span>Jadwal Pelajaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jurnal.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <rect x="8" y="2" width="8" height="4" rx="1"></rect>
                                </svg>
                                <span>Jurnal Mengajar</span>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </aside>

        <!-- ===================================================================
             Main Content Region
             =================================================================== -->
        <main class="dash-main">

            <!-- Top Header Bar -->
            <header class="dash-top-bar">
                <div>
                    <h1 class="dash-header-title">Dashboard</h1>
                    <p class="dash-header-subtitle">Ringkasan Pengelolaan</p>
                </div>

                <div class="dash-top-right">
                    <!-- Date Widget -->
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

                    <!-- User Profile & Logout Widget -->
                    <div class="dash-user-widget">
                        <div style="display: flex; flex-direction: column; align-items: flex-end; line-height: 1.2;">
                            <span style="font-size: 0.875rem; font-weight: 700; color: #1e2538;">{{ Auth::user()->name ?? 'Administrator' }}</span>
                            <span style="font-size: 0.725rem; font-weight: 600; color: #847e73;">{{ Auth::user()->role_label ?? 'Admin' }}</span>
                        </div>
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="dash-user-avatar">
                        
                        <form action="{{ route('logout') }}" method="POST" style="margin-left: 6px;">
                            @csrf
                            <button type="submit" class="btn-logout-icon" title="Keluar dari Akun" style="background: none; border: none; cursor: pointer; color: #dc2626; padding: 4px; display: flex; align-items: center;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- ---------------------------------------------------------------
                 Summary Cards Grid (4 Items)
                 --------------------------------------------------------------- -->
            <section class="dash-summary-row">
                <!-- Card 1: Pengguna -->
                <div class="dash-summary-card">
                    <div class="dash-card-header">
                        <div class="dash-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <span>Pengguna</span>
                    </div>
                    <div class="dash-card-value">
                        {{ $stats['total_pengguna'] }} <span>Total</span>
                    </div>
                </div>

                <!-- Card 2: Siswa -->
                <div class="dash-summary-card">
                    <div class="dash-card-header">
                        <div class="dash-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                            </svg>
                        </div>
                        <span>Siswa</span>
                    </div>
                    <div class="dash-card-value">
                        {{ $stats['total_siswa'] }} <span>Total</span>
                    </div>
                </div>

                <!-- Card 3: Guru -->
                <div class="dash-summary-card">
                    <div class="dash-card-header">
                        <div class="dash-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 3-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <span>Guru</span>
                    </div>
                    <div class="dash-card-value">
                        {{ $stats['total_guru'] }} <span>Total</span>
                    </div>
                </div>

                <!-- Card 4: Kelas -->
                <div class="dash-summary-card">
                    <div class="dash-card-header">
                        <div class="dash-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                <path d="M3 9h18M9 21V9"></path>
                            </svg>
                        </div>
                        <span>Kelas</span>
                    </div>
                    <div class="dash-card-value">
                        {{ $stats['total_kelas'] }} <span>Total</span>
                    </div>
                </div>
            </section>

            <!-- Action Bar Button -->
            <div class="dash-action-bar">
                <a href="#" class="btn-unduh-csv" onclick="alert('Export CSV siap diunduh!')">
                    <span>Unduh CSV</span>
                </a>
            </div>

            <!-- ---------------------------------------------------------------
                 Main Grid Content (2 Columns)
                 --------------------------------------------------------------- -->
            <div class="dash-content-grid">

                <!-- LEFT COLUMN (Charts & Rekap) -->
                <div style="display: flex; flex-direction: column; gap: 20px;">

                    <!-- Panel 1: Grafik -->
                    <div class="dash-panel-card">
                        <div class="dash-panel-header">
                            <h2 class="dash-panel-title">Grafik</h2>
                            <select class="dash-select-filter">
                                <option value="7">7 Hari Terakhir</option>
                                <option value="30">30 Hari Terakhir</option>
                            </select>
                        </div>

                        <!-- Bar Chart Canvas/DOM -->
                        <div class="dash-chart-container">
                            <div class="chart-y-axis">
                                <span>30</span>
                                <span>25</span>
                                <span>20</span>
                                <span>15</span>
                                <span>10</span>
                                <span>5</span>
                                <span>0</span>
                            </div>

                            @foreach($chartData as $bar)
                                <div class="chart-bar-item">
                                    <span class="chart-bar-val">{{ $bar['val'] }}</span>
                                    <div class="chart-bar-fill {{ $bar['active'] ? 'active' : '' }}" style="height: {{ ($bar['val'] / 32) * 100 }}%;"></div>
                                    <span class="chart-bar-label">{{ $bar['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Panel 2: Rekap Jurnal Mengajar Hari ini -->
                    <div class="dash-panel-card">
                        <h2 class="dash-panel-title">Rekap Jurnal Mengajar Hari ini</h2>

                        <div class="dash-rekap-body">
                            <!-- Donut Progress -->
                            <div class="donut-wrapper">
                                <svg class="donut-svg" viewBox="0 0 100 100">
                                    <circle class="donut-bg" cx="50" cy="50" r="42"></circle>
                                    <circle class="donut-fill" cx="50" cy="50" r="42" style="stroke-dashoffset: {{ 263.89 - (263.89 * $stats['persentase'] / 100) }};"></circle>
                                </svg>
                                <div class="donut-center-text">
                                    <span class="donut-percent">{{ $stats['persentase'] }}%</span>
                                    <span class="donut-sub">Penyelesaian</span>
                                </div>
                            </div>

                            <!-- Stat Badges -->
                            <div class="rekap-stats-grid">
                                <!-- Badge 1: Total Jadwal -->
                                <div class="rekap-stat-card">
                                    <div class="rekap-icon-box">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </div>
                                    <div class="rekap-info-box">
                                        <span class="rekap-label">Total Jadwal</span>
                                        <span class="rekap-num">{{ $stats['total_jadwal'] }} <span class="rekap-unit">Sesi</span></span>
                                    </div>
                                </div>

                                <!-- Badge 2: Sudah Mengisi -->
                                <div class="rekap-stat-card">
                                    <div class="rekap-icon-box" style="background-color: #e6f4ea;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="#137333" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                    <div class="rekap-info-box">
                                        <span class="rekap-label">Sudah Mengisi</span>
                                        <span class="rekap-num">{{ $stats['sudah_mengisi'] }} <span class="rekap-unit">Sesi</span></span>
                                    </div>
                                </div>

                                <!-- Badge 3: Belum Mengisi -->
                                <div class="rekap-stat-card">
                                    <div class="rekap-icon-box" style="background-color: #fef7e0;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="#b06000" stroke-width="2.5">
                                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                            <line x1="12" y1="9" x2="12" y2="13"></line>
                                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                        </svg>
                                    </div>
                                    <div class="rekap-info-box">
                                        <span class="rekap-label">Belum Mengisi</span>
                                        <span class="rekap-num">{{ $stats['belum_mengisi'] }} <span class="rekap-unit">Sesi</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN (Aktivitas & Guru Belum Mengisi) -->
                <div style="display: flex; flex-direction: column; gap: 20px;">

                    <!-- Panel 3: Aktivitas -->
                    <div class="dash-panel-card">
                        <div class="dash-panel-header">
                            <h2 class="dash-panel-title">Aktivitas</h2>
                            <a href="{{ route('jurnal.index') }}" class="btn-lihat-semua">Lihat Semua</a>
                        </div>

                        <div class="dash-list-wrapper">
                            @foreach($aktivitasList as $act)
                                <div class="dash-list-item">
                                    <span class="activity-time">{{ $act['waktu'] }}</span>
                                    <div class="avatar-circle" style="background: {{ $act['bg'] }};">
                                        {{ substr($act['nama'], 0, 1) }}
                                    </div>
                                    <div class="item-info">
                                        <span class="item-name">{{ $act['nama'] }}</span>
                                        <span class="item-detail">{{ $act['detail'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Panel 4: Guru belum mengisi hari ini -->
                    <div class="dash-panel-card">
                        <div class="dash-panel-header">
                            <h2 class="dash-panel-title">Guru belum mengisi hari ini</h2>
                            <a href="{{ route('jurnal.index') }}" class="btn-lihat-semua">Lihat Semua</a>
                        </div>

                        <div class="dash-list-wrapper">
                            @foreach($guruBelumMengisi as $guru)
                                <div class="dash-list-item">
                                    <div class="avatar-circle" style="background: #f1ebd9; color: #847e73;">
                                        {{ substr($guru['nama'], 0, 1) }}
                                    </div>
                                    <div class="item-info">
                                        <span class="item-name">{{ $guru['nama'] }}</span>
                                        <span class="item-detail">{{ $guru['mapel'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>

        </main>

    </div>

    <!-- Toggle Submenu & Live Clock Script -->
    <script>
        function toggleSubmenu(id) {
            const el = document.getElementById(id);
            if (el.style.display === 'none' || el.style.display === '') {
                el.style.display = 'flex';
            } else {
                el.style.display = 'none';
            }
        }

        function updateLiveClock() {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const now = new Date();
            const dayName = days[now.getDay()];
            const dateNum = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const dateEl = document.getElementById('live_date_str');
            const timeEl = document.getElementById('live_time_str');

            if (dateEl) dateEl.innerText = `${dayName}, ${dateNum} ${monthName} ${year}`;
            if (timeEl) timeEl.innerText = `${hours}:${minutes}:${seconds} WIB`;
        }

        setInterval(updateLiveClock, 1000);
        updateLiveClock();
    </script>
</body>
</html>
