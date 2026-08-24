<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kehadiran - Wali Kelas</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Modular Dashboard CSS -->
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

        /* Main Content Layout */
        .wk-main {
            flex: 1;
            margin-left: 250px;
            padding: 28px 36px;
            overflow-y: auto;
            width: calc(100% - 250px);
            transition: all 0.3s ease;
        }

        /* Header Bar */
        .wk-header-bar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .wk-tagline {
            font-size: 0.8rem;
            font-weight: 800;
            color: #a17b4c;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .wk-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--wk-text-dark);
            letter-spacing: -0.02em;
            margin: 0 0 4px 0;
        }

        .wk-subtitle {
            font-size: 0.875rem;
            color: var(--wk-text-muted);
            font-weight: 600;
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

        /* Month Navigation Selector */
        .wk-month-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .wk-month-btn {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e2538;
            text-decoration: none;
            font-weight: 800;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .wk-month-btn:hover {
            background-color: var(--wk-navy);
            color: #ffffff;
            border-color: var(--wk-navy);
        }

        .wk-month-label {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1e2538;
        }

        /* 4 Stat Cards Row */
        .wk-stat-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .wk-stat-card {
            border-radius: 16px;
            padding: 20px 22px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .wk-stat-card.cream {
            background-color: var(--wk-cream);
            border: 1px solid var(--wk-cream-border);
            color: #1e2538;
        }

        .wk-stat-card.navy {
            background-color: var(--wk-navy);
            border: 1px solid var(--wk-navy);
            color: #ffffff;
        }

        .wk-stat-card.white {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            color: #1e2538;
        }

        .wk-stat-title {
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 8px;
            opacity: 0.85;
        }

        .wk-stat-value {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .wk-stat-subtext {
            font-size: 0.75rem;
            font-weight: 600;
            opacity: 0.75;
        }

        /* Peta Kehadiran Kelas (Calendar Heatmap Section) */
        .wk-heatmap-card {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
        }

        .wk-heatmap-header {
            margin-bottom: 20px;
        }

        .wk-heatmap-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #1e2538;
            margin: 0 0 2px 0;
        }

        .wk-heatmap-subtitle {
            font-size: 0.825rem;
            color: #64748b;
            font-weight: 600;
        }

        .wk-heatmap-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .wk-day-box {
            aspect-ratio: 1;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 800;
            transition: all 0.2s ease;
            position: relative;
            cursor: pointer;
        }

        .wk-day-box.color-95 {
            background-color: #064e3b;
            color: #ffffff;
        }

        .wk-day-box.color-85 {
            background-color: #059669;
            color: #ffffff;
        }

        .wk-day-box.color-70 {
            background-color: #d97706;
            color: #ffffff;
        }

        .wk-day-box.color-low {
            background-color: #dc2626;
            color: #ffffff;
        }

        .wk-day-box.libur {
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .wk-heatmap-legend {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
            font-size: 0.775rem;
            font-weight: 700;
            color: #64748b;
            border-top: 1px solid #f1f5f9;
            padding-top: 14px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-sq {
            width: 14px;
            height: 14px;
            border-radius: 4px;
        }

        /* Rekap Per Siswa Table Section */
        .wk-table-card {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
        }

        .wk-table-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .wk-table-filters {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .wk-search-input {
            padding: 10px 16px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            color: #1e2538;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            outline: none;
            width: 200px;
        }

        .wk-search-input:focus {
            border-color: #3b82f6;
        }

        .wk-select-filter {
            padding: 10px 16px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            color: #1e2538;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            outline: none;
            cursor: pointer;
        }

        .btn-export-csv {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: #1e2538;
            padding: 10px 16px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-export-csv:hover {
            background-color: var(--wk-navy);
            color: #ffffff;
            border-color: var(--wk-navy);
        }

        .wk-rekap-table {
            width: 100%;
            border-collapse: collapse;
        }

        .wk-rekap-table th {
            text-align: left;
            font-size: 0.75rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            padding: 12px 16px;
            border-bottom: 2px solid #f1f5f9;
        }

        .wk-rekap-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
            font-weight: 700;
            color: #1e2538;
        }

        .wk-status-badge {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.775rem;
            font-weight: 800;
            border: 1px solid transparent;
            display: inline-block;
        }

        .wk-status-badge.baik {
            background-color: #e6f4ea;
            color: #137333;
            border-color: #ceead6;
        }

        .wk-status-badge.pantau {
            background-color: #fef7e0;
            color: #b06000;
            border-color: #feefc3;
        }

        .wk-status-badge.tindak-lanjut {
            background-color: #fce8e6;
            color: #c5221f;
            border-color: #fad2cf;
        }

        @media (max-width: 1200px) {
            .wk-stat-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .wk-heatmap-grid {
                grid-template-columns: repeat(8, 1fr);
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
            .wk-heatmap-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }
    </style>
</head>
<body class="dashboard-body">

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar Navigation -->
    <aside class="wk-sidebar dash-sidebar">
            <div>
                @include('partials.dash-brand')

                <ul class="wk-nav-menu">
                    <li>
                        <a href="{{ route('wali-kelas.dashboard') }}" class="wk-nav-link">
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
                        <a href="{{ route('wali-kelas.rekap-kehadiran') }}" class="wk-nav-link active">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                            <span>Rekap Kehadiran</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="wk-nav-link">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span>Laporan Bulanan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="wk-nav-link">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                            <span>Surat Izin / Sakit</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="wk-nav-link">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            <span>Pengaturan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; width: 100%; padding: 10px; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.85rem;">
                        <span>Keluar Akun</span>
                    </button>
                </form>
                <div class="wk-sidebar-footer">Tahun Ajaran 2026/2027</div>
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
                        <h1 class="wk-title">Rekap Kehadiran</h1>
                        <div class="wk-subtitle">Riwayat kehadiran harian siswa per bulan</div>

                        <!-- Month Selector -->
                        <div class="wk-month-nav">
                            <a href="?month={{ $prevMonth }}&year={{ $prevYear }}" class="wk-month-btn" title="Bulan Sebelumnya">&lt;</a>
                            <span class="wk-month-label">{{ $monthNameFormatted }}</span>
                            <a href="?month={{ $nextMonth }}&year={{ $nextYear }}" class="wk-month-btn" title="Bulan Berikutnya">&gt;</a>
                        </div>
                    </div>
                </div>

                <!-- Top Right Profile & Clock Widgets -->
                <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
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

                    <div class="wk-user-badge">
                        @if(auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="Foto" class="wk-user-avatar-circle" style="object-fit: cover;">
                        @else
                            <div class="wk-user-avatar-circle">
                                {{ strtoupper(mb_substr($namaWali, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <div class="wk-user-info-name">{{ $namaWali }}</div>
                            <div class="wk-user-info-role">Wali kelas {{ $namaKelas }}</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- 4 Top Metric Cards Grid -->
            <section class="wk-stat-cards-grid">
                <!-- Rata-rata hadir -->
                <div class="wk-stat-card cream">
                    <div class="wk-stat-title">Rata-rata hadir</div>
                    <div class="wk-stat-value">{{ $avgHadirPct }}%</div>
                    <div class="wk-stat-subtext">Akumulasi {{ $monthNameFormatted }}</div>
                </div>

                <!-- Total sakit -->
                <div class="wk-stat-card navy">
                    <div class="wk-stat-title">Total sakit</div>
                    <div class="wk-stat-value">{{ $totalSakit }}</div>
                    <div class="wk-stat-subtext">kejadian bulan ini</div>
                </div>

                <!-- Total izin -->
                <div class="wk-stat-card white">
                    <div class="wk-stat-title">Total izin</div>
                    <div class="wk-stat-value">{{ $totalIzin }}</div>
                    <div class="wk-stat-subtext">kejadian bulan ini</div>
                </div>

                <!-- Total alpa -->
                <div class="wk-stat-card cream">
                    <div class="wk-stat-title">Total alpa</div>
                    <div class="wk-stat-value">{{ $totalAlpa }}</div>
                    <div class="wk-stat-subtext">{{ $distinctAlpaSiswa }} siswa berbeda</div>
                </div>
            </section>

            <!-- Peta Kehadiran Kelas (Calendar Heatmap Grid) -->
            <section class="wk-heatmap-card">
                <div class="wk-heatmap-header">
                    <h3 class="wk-heatmap-title">Peta Kehadiran Kelas</h3>
                    <div class="wk-heatmap-subtitle">Rata-rata kehadiran kelas per hari sekolah, {{ $monthNameFormatted }}</div>
                </div>

                <div class="wk-heatmap-grid">
                    @foreach($calendarGrid as $gridItem)
                        <div class="wk-day-box {{ $gridItem['color_class'] }}" title="Tanggal {{ $gridItem['day'] }}: {{ $gridItem['pct'] }}% Hadir">
                            {{ $gridItem['day'] }}
                        </div>
                    @endforeach
                </div>

                <!-- Legend -->
                <div class="wk-heatmap-legend">
                    <div class="legend-item">
                        <div class="legend-sq" style="background-color: #064e3b;"></div>
                        <span>&ge;95% hadir</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-sq" style="background-color: #059669;"></div>
                        <span>85-94% hadir</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-sq" style="background-color: #d97706;"></div>
                        <span>70-84% hadir</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-sq" style="background-color: #dc2626;"></div>
                        <span>&lt;70% hadir</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-sq" style="background-color: #f1f5f9; border: 1px solid #e2e8f0;"></div>
                        <span>Libur/akhir pekan</span>
                    </div>
                </div>
            </section>

            <!-- Rekap Per Siswa Section -->
            <section class="wk-table-card">
                <div class="wk-table-header-row">
                    <div>
                        <h3 class="wk-heatmap-title">Rekap per siswa . {{ $monthNameFormatted }}</h3>
                        <div class="wk-heatmap-subtitle">Akumulasi kehadiran sebulan</div>
                    </div>

                    <div class="wk-table-filters">
                        <!-- Search Form -->
                        <form method="GET" action="{{ route('wali-kelas.rekap-kehadiran') }}" style="display: flex; gap: 10px;">
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <input type="text" name="search" class="wk-search-input" placeholder="Cari nama siswa" value="{{ request('search') }}">
                            <select name="status" class="wk-select-filter" onchange="this.form.submit()">
                                <option value="">Semua status</option>
                                <option value="baik" {{ request('status') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="pantau" {{ request('status') == 'pantau' ? 'selected' : '' }}>Perlu pantau</option>
                                <option value="tindak-lanjut" {{ request('status') == 'tindak-lanjut' ? 'selected' : '' }}>Perlu tindak lanjut</option>
                            </select>
                        </form>

                        <!-- Unduh CSV -->
                        <a href="{{ route('wali-kelas.rekap-kehadiran.export-csv', ['month' => $month, 'year' => $year]) }}" class="btn-export-csv">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            <span>Unduh CSV</span>
                        </a>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table class="wk-rekap-table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">SISWA</th>
                                <th style="width: 10%; text-align: center;">Hadir</th>
                                <th style="width: 10%; text-align: center;">Sakit</th>
                                <th style="width: 10%; text-align: center;">Izin</th>
                                <th style="width: 10%; text-align: center;">Alpa</th>
                                <th style="width: 12%; text-align: center;">KEHADIRAN</th>
                                <th style="width: 13%; text-align: center;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaList as $siswa)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div class="wk-user-avatar-circle" style="width: 36px; height: 36px; font-size: 0.85rem; background: #64748b;">
                                                {{ strtoupper(mb_substr($siswa->nama_siswa, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 800; color: #1e2538;">{{ $siswa->nama_siswa }}</div>
                                                <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">NISN: {{ $siswa->nisn ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">{{ $siswa->hadir_count }}</td>
                                    <td style="text-align: center;">{{ $siswa->sakit_count }}</td>
                                    <td style="text-align: center;">{{ $siswa->izin_count }}</td>
                                    <td style="text-align: center;">{{ $siswa->alpa_count }}</td>
                                    <td style="text-align: center; color: #0284c7; font-weight: 800;">{{ $siswa->pct }}%</td>
                                    <td style="text-align: center;">
                                        <span class="wk-status-badge {{ $siswa->status_key }}">{{ $siswa->status_label }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 36px; color: #64748b; font-weight: 600;">
                                        Belum ada data rekap siswa untuk periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </main>

    <script src="/js/live-clock.js"></script>
</body>
</html>
