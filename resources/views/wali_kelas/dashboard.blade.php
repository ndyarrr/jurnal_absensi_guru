<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Wali Kelas - {{ $namaKelas }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Modular Dashboard CSS for consistent admin styling -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <script src="/js/sidebar-toggle.js"></script>

    <style>
        :root {
            --wk-bg: #f8f6f1;
            --wk-navy: #1e2538;
            --wk-cream: #f7f3eb;
            --wk-cream-border: #e8e2d5;
            --wk-white: #ffffff;
            --wk-text-dark: #1e2538;
            --wk-text-muted: #64748b;
            --wk-blue: #2563eb;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--wk-bg);
            color: var(--wk-text-dark);
            min-height: 100vh;
            display: flex;
        }

        /* ---- Sidebar ---- */
        .wk-sidebar {
            width: 250px;
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-shrink: 0;
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }

        .wk-brand-box {
            position: relative;
            padding: 10px 0 16px 0;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 16px;
        }

        .wk-brand-logo {
            height: 44px;
            width: auto;
        }

        .wk-nav-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .wk-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #475569;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .wk-nav-link:hover {
            color: #1e2538;
            background: #f1f5f9;
        }

        .wk-nav-link.active {
            background-color: var(--wk-navy);
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(30, 37, 56, 0.15);
        }

        .wk-sidebar-footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
            font-size: 0.775rem;
            color: #64748b;
            font-weight: 700;
        }

        /* ---- Main Content Layout ---- */
        .wk-main {
            flex: 1;
            margin-left: 250px;
            padding: 28px 36px;
            overflow-y: auto;
            width: calc(100% - 250px);
        }

        /* Top Header */
        .wk-header-bar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
        }

        .wk-tagline {
            font-size: 0.775rem;
            font-weight: 800;
            color: var(--wk-blue);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .wk-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--wk-text-dark);
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }


        .wk-user-badge {
            background-color: var(--wk-cream);
            border-radius: 16px;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid var(--wk-cream-border);
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .wk-user-avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e2538, #3b82f6);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wk-user-info-name {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--wk-text-dark);
            line-height: 1.2;
        }

        .wk-user-info-role {
            font-size: 0.775rem;
            color: #64748b;
            font-weight: 600;
        }

        /* ---- 5 Metric Cards Row ---- */
        .wk-metrics-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .wk-metric-card {
            border-radius: 18px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 125px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
        }

        .wk-metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.07);
        }

        .wk-metric-card.cream {
            background-color: var(--wk-cream);
            color: var(--wk-text-dark);
            border: 1px solid var(--wk-cream-border);
        }

        .wk-metric-card.navy {
            background: linear-gradient(135deg, #1e2538, #0f172a);
            color: #ffffff;
        }

        .wk-metric-card.white {
            background-color: #ffffff;
            color: var(--wk-text-dark);
            border: 1px solid #e2e8f0;
        }

        .wk-metric-label {
            font-size: 0.825rem;
            font-weight: 700;
            opacity: 0.9;
        }

        .wk-metric-val {
            font-size: 2.1rem;
            font-weight: 800;
            line-height: 1.1;
            margin: 4px 0;
        }

        .wk-metric-sub {
            font-size: 0.75rem;
            font-weight: 600;
            opacity: 0.8;
        }

        /* ---- Middle Section (Chart & Legend) ---- */
        .wk-middle-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .wk-card-box {
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        }

        .wk-card-box.cream {
            background-color: var(--wk-cream);
            border: 1px solid var(--wk-cream-border);
        }

        .wk-card-box.white {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
        }

        .wk-card-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--wk-text-dark);
            margin-bottom: 2px;
        }

        .wk-card-subtitle {
            font-size: 0.825rem;
            color: var(--wk-text-muted);
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* Chart Styling matching Admin Blue Gradient */
        .wk-chart-container {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 165px;
            padding-top: 16px;
        }

        .wk-chart-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            height: 100%;
            justify-content: flex-end;
        }

        .wk-chart-val {
            font-size: 0.825rem;
            font-weight: 800;
            color: var(--wk-text-dark);
        }

        .wk-chart-bar-wrapper {
            width: 48px;
            height: 105px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .wk-chart-bar {
            width: 100%;
            border-radius: 8px;
            transition: height 0.4s ease;
        }

        .wk-chart-bar.navy {
            background: linear-gradient(180deg, #60a5fa 0%, #2563eb 100%);
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
        }

        .wk-chart-bar.cream {
            background: #ffffff;
            border: 1px solid #cbd5e1;
        }

        .wk-chart-label {
            font-size: 0.775rem;
            font-weight: 700;
            color: var(--wk-text-dark);
        }

        /* Legend List */
        .wk-legend-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 8px;
        }

        .wk-legend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
        }

        .wk-legend-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .wk-legend-box {
            width: 14px;
            height: 14px;
            border-radius: 4px;
        }

        .wk-legend-name {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--wk-text-dark);
        }

        .wk-legend-code {
            font-size: 0.85rem;
            font-weight: 800;
            color: #64748b;
        }

        /* ---- Bottom Table Section ---- */
        .wk-table-card {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
        }

        .wk-table-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .wk-table-filters {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .wk-search-input {
            padding: 9px 14px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            outline: none;
            width: 210px;
            transition: all 0.2s ease;
        }

        .wk-search-input:focus {
            border-color: #2563eb;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .wk-select-filter {
            padding: 9px 14px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            outline: none;
            cursor: pointer;
        }

        /* Table Design */
        .wk-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .wk-table th {
            text-align: left;
            padding: 12px 14px;
            font-size: 0.75rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .wk-table td {
            padding: 14px;
            font-size: 0.875rem;
            color: var(--wk-text-dark);
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .wk-siswa-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .wk-siswa-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #334155;
            font-weight: 800;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .wk-siswa-name {
            font-weight: 800;
            color: var(--wk-text-dark);
            line-height: 1.2;
        }

        .wk-siswa-nis {
            font-size: 0.775rem;
            color: #64748b;
            font-weight: 600;
        }

        .wk-day-badge {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.775rem;
            color: #ffffff;
        }

        .wk-day-badge.hadir { background-color: #10b981; }
        .wk-day-badge.sakit { background-color: #d97706; }
        .wk-day-badge.izin { background-color: #78350f; }
        .wk-day-badge.alpa { background-color: #ef4444; }

        .wk-status-pill {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.775rem;
            font-weight: 800;
            display: inline-block;
        }

        .wk-status-pill.baik {
            background-color: #dcfce7;
            color: #15803d;
        }

        .wk-status-pill.perhatian {
            background-color: #fef3c7;
            color: #b45309;
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .wk-metrics-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            .wk-middle-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .wk-sidebar {
                transform: translateX(-260px);
            }
            .wk-main {
                margin-left: 0;
                width: 100%;
                padding: 20px 16px;
            }
            body.sidebar-mobile-open .wk-sidebar {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="dashboard-body">

    <!-- Sidebar Backdrop Overlay (Mobile) -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar Navigation (Unified Admin Style) -->
    <aside class="wk-sidebar dash-sidebar">
        <div>
            @include('partials.dash-brand')

            <ul class="wk-nav-menu">
                <li>
                    <a href="{{ route('wali-kelas.dashboard') }}" class="wk-nav-link active">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('wali-kelas.perwalian') }}" class="wk-nav-link">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Kelas Perwalian</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('wali-kelas.rekap-kehadiran') }}" class="wk-nav-link">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        <span>Rekap Kehadiran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('wali-kelas.surat-izin') }}" class="wk-nav-link {{ request()->routeIs('wali-kelas.surat-izin') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span>Surat Izin / Sakit</span>
                    </a>
                </li>
            </ul>
        </div>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; width: 100%; padding: 10px; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.85rem;">
                    <span>Keluar Akun</span>
                </button>
            </form>

            <div class="wk-sidebar-footer">
                Tahun Ajaran 2026/2027
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="wk-main dash-main">

        <!-- Top Header Bar -->
        <header class="wk-header-bar">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="button" class="dash-hamburger-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>

                <div>
                    <div class="wk-tagline">KELAS PERWALIAN . {{ $namaKelas }}</div>
                    <h1 class="wk-title">DASHBOARD WALI KELAS</h1>
                    <div class="wk-subtitle">{{ $todayFormatted }} . Rekap kehadiran minggu ke-4</div>
                </div>
            </div>

            <!-- Top Right Region (Date Widget & Profile Badge) -->
            <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                <!-- Live Clock & Date Widget -->
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

                <!-- Top Right Profile Badge & Settings Widget -->
                @include('partials.dash-user-widget')
            </div>
        </header>

        <!-- 5 Summary Metrics Row -->
        <section class="wk-metrics-grid">
            <div class="wk-metric-card cream">
                <div class="wk-metric-label">Total siswa</div>
                <div class="wk-metric-val">{{ $totalSiswa }}</div>
                <div class="wk-metric-sub">di kelas {{ $namaKelas }}</div>
            </div>

            <div class="wk-metric-card navy">
                <div class="wk-metric-label">Hadir hari ini</div>
                <div class="wk-metric-val">{{ $hadirCount }}</div>
                <div class="wk-metric-sub">{{ $persentaseHadir }}% dari total siswa</div>
            </div>

            <div class="wk-metric-card white">
                <div class="wk-metric-label">Sakit</div>
                <div class="wk-metric-val">{{ $sakitCount }}</div>
                <div class="wk-metric-sub">dengan surat dokter</div>
            </div>

            <div class="wk-metric-card cream">
                <div class="wk-metric-label">Izin</div>
                <div class="wk-metric-val">{{ $izinCount }}</div>
                <div class="wk-metric-sub">acara keluarga</div>
            </div>

            <div class="wk-metric-card navy">
                <div class="wk-metric-label">Alpa</div>
                <div class="wk-metric-val">{{ $alpaCount }}</div>
                <div class="wk-metric-sub">belum ada keterangan</div>
            </div>
        </section>

        <!-- Middle Section Grid (Chart + Legend) -->
        <section class="wk-middle-grid">
            
            <!-- Weekly Attendance Percentage Chart -->
            <div class="wk-card-box cream">
                <h3 class="wk-card-title">Persentase Kehadiran Mingguan</h3>
                <div class="wk-card-subtitle">Rata-rata tingkat kehadiran siswa, 4 minggu terakhir</div>

                <div class="wk-chart-container">
                    @foreach($weeklyStats as $idx => $stat)
                        <div class="wk-chart-col">
                            <span class="wk-chart-val">{{ $stat['persentase'] }}%</span>
                            <div class="wk-chart-bar-wrapper">
                                <div class="wk-chart-bar {{ $idx % 2 === 0 ? 'navy' : 'cream' }}" style="height: {{ $stat['persentase'] }}%;"></div>
                            </div>
                            <span class="wk-chart-label">{{ $stat['minggu'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Attendance Code Legend Box -->
            <div class="wk-card-box white">
                <h3 class="wk-card-title">Keterangan Kode Absensi</h3>
                <div class="wk-card-subtitle">Status kehadiran harian</div>

                <div class="wk-legend-list">
                    <div class="wk-legend-item">
                        <div class="wk-legend-left">
                            <div class="wk-legend-box" style="background-color: #10b981;"></div>
                            <span class="wk-legend-name">Hadir</span>
                        </div>
                        <span class="wk-legend-code">H</span>
                    </div>

                    <div class="wk-legend-item">
                        <div class="wk-legend-left">
                            <div class="wk-legend-box" style="background-color: #d97706;"></div>
                            <span class="wk-legend-name">Sakit</span>
                        </div>
                        <span class="wk-legend-code">S</span>
                    </div>

                    <div class="wk-legend-item">
                        <div class="wk-legend-left">
                            <div class="wk-legend-box" style="background-color: #78350f;"></div>
                            <span class="wk-legend-name">Izin</span>
                        </div>
                        <span class="wk-legend-code">I</span>
                    </div>

                    <div class="wk-legend-item">
                        <div class="wk-legend-left">
                            <div class="wk-legend-box" style="background-color: #ef4444;"></div>
                            <span class="wk-legend-name">Alpa</span>
                        </div>
                        <span class="wk-legend-code">A</span>
                    </div>
                </div>
            </div>

        </section>

        <!-- Bottom Student Attendance Table Card -->
        <section class="wk-table-card">
            <div class="wk-table-header-bar">
                <h3 class="wk-card-title">Rekap kehadiran siswa . minggu ke-4</h3>

                <form method="GET" action="{{ route('wali-kelas.dashboard') }}" class="wk-table-filters">
                    <input type="text" name="search" class="wk-search-input" placeholder="Cari nama siswa" value="{{ request('search') }}">
                    <select name="status" class="wk-select-filter">
                        <option value="">Semua status</option>
                        <option value="baik">Hadir Baik</option>
                        <option value="perhatian">Perlu Perhatian</option>
                    </select>
                </form>
            </div>

            <div style="overflow-x: auto;">
                <table class="wk-table">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th style="text-align: center;">Sen</th>
                            <th style="text-align: center;">Sel</th>
                            <th style="text-align: center;">Rab</th>
                            <th style="text-align: center;">Kam</th>
                            <th style="text-align: center;">Jum</th>
                            <th style="text-align: center;">Kehadiran</th>
                            <th style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $idx => $siswa)
                            <tr>
                                <td>
                                    <div class="wk-siswa-cell">
                                        <div class="wk-siswa-avatar">
                                            {{ strtoupper(mb_substr($siswa->nama_siswa, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="wk-siswa-name">{{ $siswa->nama_siswa }}</div>
                                            <div class="wk-siswa-nis">NIS {{ $siswa->nisn ?? '240' . ($idx + 10) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center;"><span class="wk-day-badge hadir">H</span></td>
                                <td style="text-align: center;"><span class="wk-day-badge hadir">H</span></td>
                                <td style="text-align: center;"><span class="wk-day-badge hadir">H</span></td>
                                <td style="text-align: center;"><span class="wk-day-badge hadir">H</span></td>
                                <td style="text-align: center;"><span class="wk-day-badge hadir">H</span></td>
                                <td style="text-align: center; font-weight: 800;">100%</td>
                                <td style="text-align: center;">
                                    <span class="wk-status-pill baik">Baik</span>
                                </td>
                            </tr>
                        @empty
                            <!-- Sample Mock Data matching exact image mockup if DB is empty -->
                            @php
                                $sampleSiswa = [
                                    ['nama' => 'Marvel Algara', 'nis' => 'NIS 24012', 'h1'=>'hadir','h2'=>'hadir','h3'=>'hadir','h4'=>'hadir','h5'=>'hadir', 'pct'=>'100%', 'status'=>'baik', 'status_label'=>'Baik'],
                                    ['nama' => 'Aditya Pratama', 'nis' => 'NIS 24013', 'h1'=>'hadir','h2'=>'hadir','h3'=>'sakit','h4'=>'hadir','h5'=>'hadir', 'pct'=>'80%', 'status'=>'baik', 'status_label'=>'Baik'],
                                    ['nama' => 'Bintang Ramadhan', 'nis' => 'NIS 24014', 'h1'=>'hadir','h2'=>'izin','h3'=>'hadir','h4'=>'hadir','h5'=>'hadir', 'pct'=>'80%', 'status'=>'baik', 'status_label'=>'Baik'],
                                    ['nama' => 'Cantika Putri', 'nis' => 'NIS 24015', 'h1'=>'hadir','h2'=>'hadir','h3'=>'hadir','h4'=>'alpa','h5'=>'hadir', 'pct'=>'80%', 'status'=>'perhatian', 'status_label'=>'Perhatian'],
                                ];
                            @endphp

                            @foreach($sampleSiswa as $s)
                                <tr>
                                    <td>
                                        <div class="wk-siswa-cell">
                                            <div class="wk-siswa-avatar">
                                                {{ strtoupper(mb_substr($s['nama'], 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="wk-siswa-name">{{ $s['nama'] }}</div>
                                                <div class="wk-siswa-nis">{{ $s['nis'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align: center;"><span class="wk-day-badge {{ $s['h1'] }}">{{ strtoupper(substr($s['h1'],0,1)) }}</span></td>
                                    <td style="text-align: center;"><span class="wk-day-badge {{ $s['h2'] }}">{{ strtoupper(substr($s['h2'],0,1)) }}</span></td>
                                    <td style="text-align: center;"><span class="wk-day-badge {{ $s['h3'] }}">{{ strtoupper(substr($s['h3'],0,1)) }}</span></td>
                                    <td style="text-align: center;"><span class="wk-day-badge {{ $s['h4'] }}">{{ strtoupper(substr($s['h4'],0,1)) }}</span></td>
                                    <td style="text-align: center;"><span class="wk-day-badge {{ $s['h5'] }}">{{ strtoupper(substr($s['h5'],0,1)) }}</span></td>
                                    <td style="text-align: center; font-weight: 800;">{{ $s['pct'] }}</td>
                                    <td style="text-align: center;">
                                        <span class="wk-status-pill {{ $s['status'] }}">{{ $s['status_label'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <script src="/js/live-clock.js"></script>
</body>
</html>
