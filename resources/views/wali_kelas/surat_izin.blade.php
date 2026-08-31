<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Izin & Sakit - Wali Kelas {{ $namaKelas }}</title>

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
            flex-wrap: wrap;
        }

        .wk-tagline {
            font-size: 0.775rem;
            font-weight: 800;
            color: #94a3b8;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .wk-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--wk-text-dark);
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .wk-subtitle {
            font-size: 0.875rem;
            color: #847e73;
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

        /* Top 3 Stat Cards Row */
        .wk-stats-3grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .wk-stat-card {
            border-radius: 18px;
            padding: 22px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 130px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);
            transition: all 0.2s ease;
        }

        .wk-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        }

        .wk-stat-card.tan {
            background-color: #d8c7b0;
            color: #1e2538;
            border: 1px solid #c9b79e;
        }

        .wk-stat-card.navy {
            background-color: #2b324b;
            color: #ffffff;
        }

        .wk-stat-card.white {
            background-color: #ffffff;
            color: #1e2538;
            border: 1px solid #e2e8f0;
        }

        .wk-stat-title {
            font-size: 0.85rem;
            font-weight: 700;
        }

        .wk-stat-num {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
            margin: 6px 0;
        }

        .wk-stat-sub {
            font-size: 0.775rem;
            font-weight: 600;
            opacity: 0.9;
        }

        /* Main Section Box */
        .wk-section-box {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 24px 28px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.02);
        }

        .wk-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .wk-section-title-group h2 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--wk-text-dark);
            margin-bottom: 2px;
        }

        .wk-section-title-group p {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
        }

        .wk-filter-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .wk-search-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .wk-search-input {
            padding: 10px 16px 10px 38px;
            border-radius: 24px;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 500;
            outline: none;
            width: 220px;
            transition: all 0.2s ease;
            color: #1e2538;
        }

        .wk-search-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .wk-search-icon {
            position: absolute;
            left: 14px;
            width: 16px;
            height: 16px;
            color: #94a3b8;
            pointer-events: none;
        }

        .wk-select-filter {
            padding: 10px 36px 10px 16px;
            border-radius: 24px;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
        }

        /* Table Design */
        .wk-table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .wk-table {
            width: 100%;
            border-collapse: collapse;
        }

        .wk-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 0.75rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #cbd5e1;
        }

        .wk-table td {
            padding: 16px;
            font-size: 0.9rem;
            color: var(--wk-text-dark);
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .wk-siswa-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .wk-siswa-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #717d8a;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .wk-siswa-name {
            font-weight: 700;
            color: #1e2538;
            font-size: 0.925rem;
        }

        /* Type Badges */
        .wk-type-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-block;
            background-color: #d8c7b0;
            color: #4a3e2e;
        }

        /* Status Badges */
        .wk-status-pill {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-block;
        }

        .wk-status-pill.menunggu {
            background-color: #f3e5e5;
            color: #854d4e;
        }

        .wk-status-pill.terverifikasi {
            background-color: #e1efe0;
            color: #2e6332;
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .wk-stats-3grid {
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

    <!-- Sidebar Overlay Mobile -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar Navigation -->
    <aside class="wk-sidebar dash-sidebar">
        <div>
            @include('partials.dash-brand')

            <ul class="wk-nav-menu">
                <li>
                    <a href="{{ route('wali-kelas.dashboard') }}" class="wk-nav-link {{ request()->routeIs('wali-kelas.dashboard') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('wali-kelas.perwalian') }}" class="wk-nav-link {{ request()->routeIs('wali-kelas.perwalian') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Kelas Perwalian</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('wali-kelas.rekap-kehadiran') }}" class="wk-nav-link {{ request()->routeIs('wali-kelas.rekap-kehadiran') ? 'active' : '' }}">
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
                    <h1 class="wk-title">Surat Izin & Sakit</h1>
                    <div class="wk-subtitle">Pengajuan dari siswa / orang tua yg masuk</div>
                </div>
            </div>

            <!-- Top Right Profile Badge -->
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

                <!-- Top Right Profile Badge & Settings Widget -->
                @include('partials.dash-user-widget')
            </div>
        </header>

        <!-- Top 3 Stat Cards Row -->
        <section class="wk-stats-3grid">
            <div class="wk-stat-card tan">
                <div class="wk-stat-title">Menunggu verifikasi</div>
                <div class="wk-stat-num">{{ $menungguCount }}</div>
                <div class="wk-stat-sub">perlu ditinjau</div>
            </div>

            <div class="wk-stat-card navy">
                <div class="wk-stat-title">Terverifikasi</div>
                <div class="wk-stat-num">{{ $terverifikasiCount }}</div>
                <div class="wk-stat-sub">minggu ini</div>
            </div>

            <div class="wk-stat-card white">
                <div class="wk-stat-title">Tanpa keterangan</div>
                <div class="wk-stat-num">{{ $tanpaKeteranganCount }}</div>
                <div class="wk-stat-sub">belum ada surat masuk</div>
            </div>
        </section>

        <!-- Table / Submissions Section -->
        <section class="wk-section-box">
            <div class="wk-section-header">
                <div class="wk-section-title-group">
                    <h2>Daftar Pengajuan</h2>
                    <p>Terbaru di atas</p>
                </div>

                <!-- Filters -->
                <form action="{{ route('wali-kelas.surat-izin') }}" method="GET" class="wk-filter-controls">
                    <div class="wk-search-box">
                        <svg class="wk-search-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa" class="wk-search-input" onchange="this.form.submit()">
                    </div>

                    <select name="jenis" class="wk-select-filter" onchange="this.form.submit()">
                        <option value="">Semua jenis</option>
                        <option value="Sakit" {{ request('jenis') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Izin" {{ request('jenis') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Alpa" {{ request('jenis') == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                    </select>

                    <select name="status" class="wk-select-filter" onchange="this.form.submit()">
                        <option value="">Semua status</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Terverifikasi" {{ request('status') == 'Terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                    </select>
                </form>
            </div>

            <!-- Table -->
            <div class="wk-table-responsive">
                <table class="wk-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">NO</th>
                            <th>SISWA</th>
                            <th>JENIS</th>
                            <th>DIAJUKAN</th>
                            <th>LAMPIRAN</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $item)
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #64748b;">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="wk-siswa-cell">
                                        <div class="wk-siswa-avatar">
                                            {{ $item['initials'] }}
                                        </div>
                                        <span class="wk-siswa-name">{{ $item['nama_siswa'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="wk-type-badge">{{ $item['jenis'] }}</span>
                                </td>
                                <td>{{ $item['diajukan'] }}</td>
                                <td>{{ $item['lampiran'] }}</td>
                                <td>
                                    <span class="wk-status-pill {{ strtolower($item['status']) }}">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 24px; color: #64748b;">
                                    Tidak ada pengajuan surat izin/sakit ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <script>
        function updateLiveClock() {
            const timeEl = document.getElementById('live_time_str');
            if (!timeEl) return;
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            timeEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
        setInterval(updateLiveClock, 1000);
    </script>
</body>
</html>
