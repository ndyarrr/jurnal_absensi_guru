<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas Perwalian - {{ $namaKelas }}</title>

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

        /* Header Bar */
        .wk-header-bar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
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
            margin-bottom: 4px;
        }

        .wk-subtitle {
            font-size: 0.875rem;
            color: var(--wk-text-muted);
            font-weight: 600;
        }

        /* Top Filters */
        .wk-table-filters {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .wk-search-input-dark {
            padding: 11px 18px;
            border-radius: 12px;
            border: 1px solid #1e2538;
            background-color: #262c3e;
            color: #ffffff;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            outline: none;
            width: 220px;
            transition: all 0.2s ease;
        }

        .wk-search-input-dark::placeholder {
            color: #94a3b8;
        }

        .wk-search-input-dark:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        .wk-select-filter-dark {
            padding: 11px 18px;
            border-radius: 12px;
            border: 1px solid #1e2538;
            background-color: #262c3e;
            color: #ffffff;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            outline: none;
            cursor: pointer;
        }

        /* ---- Student Cards Grid (3 Columns) ---- */
        .wk-siswa-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .wk-siswa-card {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 130px;
            transition: all 0.2s ease;
        }

        .wk-siswa-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .wk-siswa-top {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }

        .btn-view-siswa {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-left: auto;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .btn-view-siswa:hover {
            background-color: #1e2538;
            color: #ffffff;
            border-color: #1e2538;
        }

        .wk-siswa-avatar-large {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #64748b;
            color: #ffffff;
            font-weight: 800;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .wk-siswa-info-name {
            font-size: 1rem;
            font-weight: 800;
            color: var(--wk-text-dark);
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .wk-siswa-info-nisn {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
        }

        .wk-siswa-bottom-pills {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: auto;
            flex-wrap: wrap;
        }

        .wk-att-group {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .wk-att-badge {
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 0.725rem;
            font-weight: 800;
            line-height: 1;
        }

        .wk-att-badge.hadir {
            background-color: #f1f5f9;
            color: #1e2538;
            border: 1px solid #cbd5e1;
        }

        .wk-att-badge.sakit {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .wk-att-badge.izin {
            background-color: #fefce8;
            color: #a16207;
            border: 1px solid #fef08a;
        }

        .wk-att-badge.alpa {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .wk-status-badge {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.775rem;
            font-weight: 800;
            border: 1px solid transparent;
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

        /* Responsive Media Queries */
        @media (max-width: 1200px) {
            .wk-siswa-grid {
                grid-template-columns: repeat(2, 1fr);
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
            .wk-siswa-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="dashboard-body">

    <!-- Sidebar Backdrop Overlay (Mobile) -->
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
                    <a href="{{ route('wali-kelas.perwalian') }}" class="wk-nav-link active">
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
                    <h1 class="wk-title">Kelas Perwalian . {{ $namaKelas }}</h1>
                    <div class="wk-subtitle">Profil singkat & status kehadiran terkini tiap siswa (Mode Pemantauan)</div>
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

        <!-- Controls Row Below Header (Counter Tag & Table Filters) -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
            <div class="wk-tagline" style="margin-bottom: 0;">
                {{ $totalSiswa }} SISWA
                @if($paguSiswa > 0)
                    <span style="opacity: 0.45; font-weight: 600; margin: 0 6px;">·</span>
                    PAGU {{ $paguSiswa }}
                @endif
            </div>

            <!-- Search & Filter Controls -->
            <form method="GET" action="{{ route('wali-kelas.perwalian') }}" class="wk-table-filters">
                <input type="text" name="search" class="wk-search-input-dark" placeholder="Cari nama siswa" value="{{ request('search') }}">
                <select name="status" class="wk-select-filter-dark" onchange="this.form.submit()">
                    <option value="">Semua status</option>
                    <option value="baik" {{ request('status') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="pantau" {{ request('status') == 'pantau' ? 'selected' : '' }}>Perlu pantau</option>
                    <option value="tindak-lanjut" {{ request('status') == 'tindak-lanjut' ? 'selected' : '' }}>Perlu tindak lanjut</option>
                </select>
            </form>
        </div>

        <!-- 3 Columns Student Status Cards Grid -->
        <section class="wk-siswa-grid">
            @forelse($siswaList as $siswa)
                <div class="wk-siswa-card">
                    <div class="wk-siswa-top">
                        <div class="wk-siswa-avatar-large">
                            {{ strtoupper(mb_substr($siswa->nama_siswa, 0, 2)) }}
                        </div>
                        <div>
                            <div class="wk-siswa-info-name">{{ $siswa->nama_siswa }}</div>
                            <div class="wk-siswa-info-nisn">NISN {{ $siswa->nisn ?? '-' }}</div>
                        </div>
                        <button type="button" class="btn-view-siswa" title="Lihat Detail Siswa" onclick="openDetailModal('{{ addslashes($siswa->nama_siswa) }}', '{{ $siswa->nisn ?? '-' }}', '{{ addslashes($siswa->no_telepon ?? '-') }}', '{{ addslashes($namaKelas) }}', '{{ $siswa->pct }}%', '{{ $siswa->hadir_count }}', '{{ $siswa->sakit_count }}', '{{ $siswa->izin_count }}', '{{ $siswa->alpa_count }}', '{{ addslashes($siswa->status_label) }}')">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>

                    <div class="wk-siswa-bottom-pills">
                        <div class="wk-att-group">
                            <span class="wk-att-badge hadir" title="Hadir: {{ $siswa->hadir_count }} hari">{{ $siswa->hadir_count }} H</span>
                            <span class="wk-att-badge sakit" title="Sakit: {{ $siswa->sakit_count }} hari">{{ $siswa->sakit_count }} S</span>
                            <span class="wk-att-badge izin" title="Izin: {{ $siswa->izin_count }} hari">{{ $siswa->izin_count }} I</span>
                            <span class="wk-att-badge alpa" title="Alpa: {{ $siswa->alpa_count }} hari">{{ $siswa->alpa_count }} A</span>
                        </div>
                        <span class="wk-status-badge {{ $siswa->status_key }}">{{ $siswa->status_label }}</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; background: #ffffff; border-radius: 16px; padding: 48px; text-align: center; border: 1px solid #e2e8f0; color: #64748b;">
                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 12px auto; color: #94a3b8; display: block;">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <div style="font-weight: 700; font-size: 1.05rem; color: #1e2538; margin-bottom: 4px;">Tidak Ada Data Siswa</div>
                    <div style="font-size: 0.875rem;">Belum ada data siswa di kelas ini atau tidak ditemukan dengan filter saat ini.</div>
                </div>
            @endforelse
        </section>

    </main>

    <!-- Detail Siswa Modal Popup -->
    <div id="detailModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: #ffffff; border-radius: 20px; padding: 28px; width: 100%; max-width: 440px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); font-family: 'Plus Jakarta Sans', sans-serif;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #1e2538; margin: 0;">Detail Data Siswa</h3>
                <button type="button" onclick="closeDetailModal()" style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer; line-height: 1;">&times;</button>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div style="display: flex; align-items: center; gap: 14px; background: #f8fafc; padding: 14px; border-radius: 14px; border: 1px solid #e2e8f0;">
                    <div id="modal_avatar" style="width: 46px; height: 46px; border-radius: 50%; background: linear-gradient(135deg, #1e2538, #3b82f6); color: #fff; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                        --
                    </div>
                    <div>
                        <div id="modal_nama" style="font-weight: 800; font-size: 1.05rem; color: #1e2538;">-</div>
                        <div id="modal_nis" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">-</div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <span style="font-size: 0.725rem; color: #64748b; font-weight: 700; display: block; text-transform: uppercase; margin-bottom: 2px;">No. Telepon</span>
                        <span id="modal_telp" style="font-size: 0.875rem; font-weight: 800; color: #1e2538;">-</span>
                    </div>

                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <span style="font-size: 0.725rem; color: #64748b; font-weight: 700; display: block; text-transform: uppercase; margin-bottom: 2px;">Kelas</span>
                        <span id="modal_kelas" style="font-size: 0.875rem; font-weight: 800; color: #1e2538;">-</span>
                    </div>

                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0; grid-column: span 2;">
                        <span style="font-size: 0.725rem; color: #64748b; font-weight: 700; display: block; text-transform: uppercase; margin-bottom: 6px;">Rekap Rincian Presensi</span>
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <span style="background: #f1f5f9; color: #1e2538; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.8rem; border: 1px solid #cbd5e1;" id="modal_hadir">Hadir: 0</span>
                            <span style="background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.8rem; border: 1px solid #bfdbfe;" id="modal_sakit">Sakit: 0</span>
                            <span style="background: #fefce8; color: #a16207; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.8rem; border: 1px solid #fef08a;" id="modal_izin">Izin: 0</span>
                            <span style="background: #fef2f2; color: #b91c1c; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.8rem; border: 1px solid #fca5a5;" id="modal_alpa">Alpa: 0</span>
                        </div>
                    </div>

                    <div style="background: #f8fafc; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0; grid-column: span 2; display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <span style="font-size: 0.725rem; color: #64748b; font-weight: 700; display: block; text-transform: uppercase;">Persentase Kehadiran</span>
                            <span id="modal_pct" style="font-size: 1rem; font-weight: 800; color: #0284c7;">-</span>
                        </div>
                        <div>
                            <span style="font-size: 0.725rem; color: #64748b; font-weight: 700; display: block; text-transform: uppercase; text-align: right;">Status Siswa</span>
                            <span id="modal_status" style="font-size: 0.875rem; font-weight: 800; color: #15803d;">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 24px;">
                <button type="button" onclick="closeDetailModal()" style="width: 100%; background: #1e2538; color: #ffffff; border: none; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 0.9rem; cursor: pointer;">Tutup</button>
            </div>
        </div>
    </div>

    <script src="/js/live-clock.js"></script>
    <script>
        function openDetailModal(nama, nis, telp, kelas, pct, hadir, sakit, izin, alpa, status) {
            document.getElementById('modal_nama').innerText = nama;
            document.getElementById('modal_nis').innerText = nis;
            document.getElementById('modal_telp').innerText = telp && telp !== '' ? telp : '-';
            document.getElementById('modal_kelas').innerText = kelas;
            document.getElementById('modal_pct').innerText = pct;
            document.getElementById('modal_hadir').innerText = 'Hadir: ' + hadir;
            document.getElementById('modal_sakit').innerText = 'Sakit: ' + sakit;
            document.getElementById('modal_izin').innerText = 'Izin: ' + izin;
            document.getElementById('modal_alpa').innerText = 'Alpa: ' + alpa;
            document.getElementById('modal_status').innerText = status;
            document.getElementById('modal_avatar').innerText = nama.substring(0, 2).toUpperCase();
            document.getElementById('detailModal').style.display = 'flex';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').style.display = 'none';
        }
    </script>
</body>
</html>
