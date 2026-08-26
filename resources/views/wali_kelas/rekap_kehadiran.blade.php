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
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
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

        .wk-calendar-container {
            width: 100%;
            overflow-x: auto;
        }

        .wk-calendar-header-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 8px;
        }

        .wk-day-header {
            text-align: center;
            font-size: 0.8rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-bottom: 6px;
        }

        .wk-day-header.weekend {
            color: #ef4444;
        }

        .wk-heatmap-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .wk-day-box-empty {
            aspect-ratio: 1;
            border-radius: 12px;
            background-color: #f8fafc;
            border: 1px dashed #e2e8f0;
            opacity: 0.5;
        }

        .wk-day-box {
            aspect-ratio: 1;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            font-weight: 800;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            cursor: pointer;
            user-select: none;
        }

        .wk-day-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
        }

        .wk-day-box.is-today {
            box-shadow: 0 0 0 3px #2563eb, 0 6px 16px rgba(37, 99, 235, 0.35);
            z-index: 2;
        }

        .wk-today-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #2563eb;
            color: #ffffff;
            font-size: 0.55rem;
            font-weight: 800;
            padding: 2px 5px;
            border-radius: 999px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            line-height: 1;
            letter-spacing: 0.5px;
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

        .wk-day-box.is-future,
        .wk-day-box.future {
            background-color: #f8fafc !important;
            color: #94a3b8 !important;
            border: 1px dashed #cbd5e1 !important;
            opacity: 0.55;
        }

        .wk-day-pct-sub {
            font-size: 0.65rem;
            font-weight: 700;
            opacity: 0.85;
            margin-top: 2px;
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

        /* Modal Styles */
        .wk-modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s ease;
        }
        .wk-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .wk-modal-card {
            background: #ffffff;
            border-radius: 20px;
            max-width: 540px;
            width: 90%;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            transform: scale(0.95);
            transition: transform 0.25s ease;
        }
        .wk-modal-overlay.active .wk-modal-card {
            transform: scale(1);
        }
        .wk-modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .wk-modal-body {
            padding: 20px 24px;
            overflow-y: auto;
        }
        .wk-modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            background: #fafafa;
        }

        @keyframes wkPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.35; }
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
                        <a href="{{ route('wali-kelas.surat-izin') }}" class="wk-nav-link {{ request()->routeIs('wali-kelas.surat-izin') ? 'active' : '' }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <span>Surat Izin / Sakit</span>
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
                    <div>
                        <h3 class="wk-heatmap-title">Peta Kehadiran Kelas</h3>
                        <div class="wk-heatmap-subtitle">Rata-rata kehadiran kelas per hari sekolah, {{ $monthNameFormatted }}</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #10b981; display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; padding: 4px 10px; border-radius: 999px; border: 1px solid #a7f3d0;">
                            <span style="width: 7px; height: 7px; background-color: #10b981; border-radius: 50%; display: inline-block; animation: wkPulse 1.5s infinite;"></span>
                            Live Data
                        </span>
                        <button type="button" onclick="location.reload()" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 6px 12px; font-size: 0.775rem; font-weight: 700; color: #475569; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s ease;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15"/></svg>
                            Refresh
                        </button>
                    </div>
                </div>

                <div class="wk-calendar-container">
                    <div class="wk-calendar-header-grid">
                        <div class="wk-day-header">Sen</div>
                        <div class="wk-day-header">Sel</div>
                        <div class="wk-day-header">Rab</div>
                        <div class="wk-day-header">Kam</div>
                        <div class="wk-day-header">Jum</div>
                        <div class="wk-day-header weekend">Sab</div>
                        <div class="wk-day-header weekend">Min</div>
                    </div>

                    <div class="wk-heatmap-grid">
                        {{-- Empty padding cells for start of month --}}
                        @for($p = 0; $p < $paddingDays; $p++)
                            <div class="wk-day-box-empty"></div>
                        @endfor

                        {{-- Calendar Day Cells --}}
                        @foreach($calendarGrid as $gridItem)
                            <div class="wk-day-box {{ $gridItem['color_class'] }} {{ $gridItem['is_today'] ? 'is-today' : '' }} {{ !empty($gridItem['is_future']) ? 'is-future' : '' }}"
                                 onclick='openDayDetailModal(@json($gridItem))'
                                 title="{{ !empty($gridItem['is_future']) ? 'Tanggal mendatang (terkunci)' : 'Klik untuk melihat detail kehadiran tanggal '.$gridItem['day'] }}">
                                @if($gridItem['is_today'])
                                    <span class="wk-today-badge">HARI INI</span>
                                @endif
                                <span>{{ $gridItem['day'] }}</span>
                                @if(empty($gridItem['is_future']) && $gridItem['has_classes'] && !$gridItem['is_weekend'])
                                    <span class="wk-day-pct-sub">{{ $gridItem['pct'] }}%</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
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
                        <span>Libur / Tanpa KBM</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-sq" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;"></div>
                        <span>Mendatang (Terkunci)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-sq" style="background-color: #ffffff; border: 2px solid #2563eb;"></div>
                        <span>Hari Ini</span>
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
                                <th style="width: 5%; text-align: center;">NO</th>
                                <th style="width: 32%;">SISWA</th>
                                <th style="width: 9%; text-align: center;">HADIR</th>
                                <th style="width: 9%; text-align: center;">SAKIT</th>
                                <th style="width: 9%; text-align: center;">IZIN</th>
                                <th style="width: 9%; text-align: center;">ALPA</th>
                                <th style="width: 13%; text-align: center;">KEHADIRAN</th>
                                <th style="width: 14%; text-align: center;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaList as $siswa)
                                <tr>
                                    <td style="text-align: center; font-weight: 700; color: #64748b;">{{ $loop->iteration }}</td>
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
                                    <td colspan="8" style="text-align: center; padding: 36px; color: #64748b; font-weight: 600;">
                                        Belum ada data rekap siswa untuk periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </main>

    <!-- Modal Detail Kehadiran Harian -->
    <div id="wkDayDetailModal" class="wk-modal-overlay">
        <div class="wk-modal-card">
            <div class="wk-modal-header">
                <div>
                    <h4 id="wkModalDateTitle" style="font-weight: 800; font-size: 1.1rem; color: #1e2538; margin-bottom: 2px;">Detail Kehadiran</h4>
                    <div id="wkModalSubTitle" style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Rincian kehadiran kelas</div>
                </div>
                <button type="button" onclick="closeDayDetailModal()" style="background: none; border: none; font-size: 1.5rem; color: #94a3b8; cursor: pointer; padding: 0 4px; line-height: 1;">&times;</button>
            </div>
            <div class="wk-modal-body">
                <div id="wkModalBodyContent"></div>
            </div>
            <div class="wk-modal-footer">
                <button type="button" onclick="closeDayDetailModal()" style="background: #e2e8f0; color: #334155; border: none; padding: 8px 18px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">Tutup</button>
            </div>
        </div>
    </div>

    <script src="/js/live-clock.js"></script>
    <script>
        function openDayDetailModal(item) {
            const modal = document.getElementById('wkDayDetailModal');
            const dateTitle = document.getElementById('wkModalDateTitle');
            const subTitle = document.getElementById('wkModalSubTitle');
            const bodyContent = document.getElementById('wkModalBodyContent');

            dateTitle.innerText = item.formatted_date;

            let html = '';
            if (item.is_future) {
                subTitle.innerText = 'Tanggal Mendatang (Belum Terlaksana)';
                html = `
                    <div style="text-align: center; padding: 30px 10px; color: #64748b;">
                        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 12px; display: block; color: #94a3b8;">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <div style="font-weight: 700; font-size: 1rem; color: #475569;">Tanggal Belum Terlaksana</div>
                        <div style="font-size: 0.8rem; margin-top: 4px;">Absensi dan jurnal mengajar terkunci karena hari ini belum tiba.</div>
                    </div>
                `;
            } else if (item.is_weekend || !item.has_classes) {
                subTitle.innerText = 'Hari Libur / Tidak ada kegiatan KBM';
                html = `
                    <div style="text-align: center; padding: 30px 10px; color: #64748b;">
                        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 12px; display: block; color: #cbd5e1;">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <div style="font-weight: 700; font-size: 1rem; color: #475569;">Tidak ada jam pelajaran recorded</div>
                        <div style="font-size: 0.8rem; margin-top: 4px;">Tanggal ini terdeteksi sebagai hari libur atau belum ada jurnal mengajar yang diisi.</div>
                    </div>
                `;
            } else {
                subTitle.innerText = `Persentase Kehadiran: ${item.pct}% (${item.total_jurnal} Jurnal Mengajar)`;

                if (!item.absent_details || item.absent_details.length === 0) {
                    html = `
                        <div style="text-align: center; padding: 24px 10px; background: #ecfdf5; border-radius: 12px; border: 1px solid #a7f3d0; color: #065f46;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 8px; display: block; color: #059669;">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <div style="font-weight: 800; font-size: 1.05rem;">Nihil Ketidakhadiran!</div>
                            <div style="font-size: 0.85rem; font-weight: 600; margin-top: 4px;">Seluruh siswa tercatat hadir 100% pada semua mata pelajaran hari ini.</div>
                        </div>
                    `;
                } else {
                    html = `
                        <div style="margin-bottom: 12px; font-weight: 700; font-size: 0.85rem; color: #334155;">
                            Daftar Siswa Tidak Hadir (${item.absent_details.length} Catatan):
                        </div>
                        <div style="max-height: 280px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 12px;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 0.825rem;">
                                <thead>
                                    <tr style="background: #f8fafc; text-align: left; color: #64748b; font-weight: 700; border-bottom: 1px solid #e2e8f0;">
                                        <th style="padding: 10px 12px;">NAMA SISWA</th>
                                        <th style="padding: 10px 12px;">STATUS</th>
                                        <th style="padding: 10px 12px;">MAPEL</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    item.absent_details.forEach(st => {
                        let badgeBg = '#fef3c7'; let badgeColor = '#b45309';
                        if (st.status.toLowerCase() === 'sakit') { badgeBg = '#e0f2fe'; badgeColor = '#0369a1'; }
                        else if (st.status.toLowerCase().includes('alpa')) { badgeBg = '#fee2e2'; badgeColor = '#b91c1c'; }

                        html += `
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 10px 12px; font-weight: 700; color: #1e2538;">
                                    ${st.nama_siswa}
                                    <div style="font-size: 0.725rem; color: #94a3b8; font-weight: 500;">NISN: ${st.nisn}</div>
                                </td>
                                <td style="padding: 10px 12px;">
                                    <span style="background: ${badgeBg}; color: ${badgeColor}; padding: 3px 8px; border-radius: 6px; font-weight: 800; font-size: 0.725rem;">${st.status}</span>
                                </td>
                                <td style="padding: 10px 12px; font-weight: 600; color: #475569;">${st.mapel}</td>
                            </tr>
                        `;
                    });

                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                }
            }

            bodyContent.innerHTML = html;
            modal.classList.add('active');
        }

        function closeDayDetailModal() {
            const modal = document.getElementById('wkDayDetailModal');
            if (modal) modal.classList.remove('active');
        }

        // Close on backdrop click
        document.getElementById('wkDayDetailModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDayDetailModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDayDetailModal();
        });
    </script>
</body>
</html>
