<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru Mengajar - Jurnal & Absensi Guru</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Modules for Consistent Admin Theme -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <script src="/js/sidebar-toggle.js"></script>

    <style>
        body, button, input, select, textarea, table, th, td, h1, h2, h3, h4, h5, h6, span, p, a, label {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        }

        :root {
            --gm-bg: #f8f6f1;
            --gm-navy: #23293b;
            --gm-cream: #f7f3eb;
            --gm-cream-border: #e8e2d5;
            --gm-text-dark: #1e2538;
            --gm-text-muted: #64748b;
        }

        .gm-welcome-card {
            background: linear-gradient(135deg, var(--gm-navy), #171c2b);
            color: #ffffff;
            border-radius: 20px;
            padding: 32px 28px;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(35, 41, 59, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .gm-welcome-title {
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0 0 6px 0;
            color: #ffffff;
        }
        .gm-welcome-subtitle {
            font-size: 0.95rem;
            color: #94a3b8;
            margin: 0;
        }

        .gm-stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }
        .gm-stat-card {
            background: #ffffff;
            border: 1px solid var(--gm-cream-border);
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .gm-stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .gm-stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        .gm-stat-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-box {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--gm-cream-border);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);
            padding: 24px;
            margin-bottom: 24px;
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 16px;
        }
        .table-custom th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border-bottom: 2px solid #e2e8f0;
        }
        .table-custom td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
            vertical-align: middle;
        }
        
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
        }
        .badge-done { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .badge-pending { background: #fef3c7; color: #b45309; border: 1px solid #fde047; }
    </style>
</head>
<body class="dashboard-body">

    <div class="dash-layout">

        <!-- Left Sidebar Navigation for Guru Mengajar -->
        <aside class="dash-sidebar">
            @include('partials.dash-brand')

            <ul class="dash-menu">
                <li class="dash-menu-item active">
                    <a href="{{ route('guru-mengajar.dashboard') }}" class="dash-menu-link" style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="20" height="20" style="width: 20px; height: 20px; flex-shrink: 0;">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                        <span>Dashboard Guru</span>
                    </a>
                </li>

                <li class="dash-menu-item">
                    <a href="{{ route('jurnal.index') }}" class="dash-menu-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" style="width: 20px; height: 20px; flex-shrink: 0;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        <span>Jurnal Mengajar Saya</span>
                    </a>
                </li>

                <li class="dash-menu-item">
                    <a href="{{ route('jadwal.index') }}" class="dash-menu-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" style="width: 20px; height: 20px; flex-shrink: 0;">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>Jadwal Pelajaran Saya</span>
                    </a>
                </li>
            </ul>

            @include('partials.dash-sidebar-footer')
        </aside>

        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Main Content Region -->
        <main class="dash-main">

            <!-- Top Header Bar -->
            <header class="dash-top-bar">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button type="button" class="dash-hamburger-btn" onclick="toggleSidebar()" title="Menu">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <div>
                        <h1 class="dash-header-title">Dashboard Guru Mengajar</h1>
                        <p class="dash-header-subtitle">Kelola jadwal mengajar harian & pengisian jurnal presensi siswa</p>
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

            <div class="dash-content" style="padding: 24px;">

                @if(session('success'))
                    <div style="background: #dcfce7; color: #15803d; border: 1px solid #86efac; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Hero Welcome Banner -->
                <div class="gm-welcome-card">
                    <div>
                        <h2 class="gm-welcome-title">Selamat Datang, {{ $namaGuru }}!</h2>
                        <p class="gm-welcome-subtitle">
                            NIP: <b>{{ $nipGuru }}</b> | NUPTK: <b>{{ $nuptkGuru }}</b>
                        </p>
                    </div>

                    <a href="{{ route('jurnal.create') }}" style="background: #ffffff; color: var(--gm-navy); padding: 12px 24px; border-radius: 12px; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.2s ease;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Input Jurnal Mengajar Baru</span>
                    </a>
                </div>

                <!-- Stat Cards Grid -->
                <div class="gm-stat-grid">
                    <div class="gm-stat-card">
                        <div class="gm-stat-icon" style="background: #eff6ff; color: #2563eb;">
                            <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <div>
                            <div class="gm-stat-number">{{ $totalJadwalHariIni }}</div>
                            <div class="gm-stat-label">Jadwal Mengajar Hari Ini ({{ $todayName }})</div>
                        </div>
                    </div>

                    <div class="gm-stat-card">
                        <div class="gm-stat-icon" style="background: #f0fdf4; color: #16a34a;">
                            <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <div>
                            <div class="gm-stat-number">{{ $jurnalTerisiCount }}</div>
                            <div class="gm-stat-label">Jurnal Terisi Hari Ini</div>
                        </div>
                    </div>

                    <div class="gm-stat-card">
                        <div class="gm-stat-icon" style="background: #fef3c7; color: #b45309;">
                            <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        </div>
                        <div>
                            <div class="gm-stat-number">{{ $jurnalBelumTerisiCount }}</div>
                            <div class="gm-stat-label">Jurnal Belum Terisi</div>
                        </div>
                    </div>
                </div>

                <!-- Today's Schedule Table -->
                <div class="card-box">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <span>Jadwal Pelajaran Saya - {{ $todayName }}, {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}</span>
                            </h3>
                            <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #64748b;">Daftar kelas dan jam pelajaran yang harus diampu hari ini.</p>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Jam Ke / Waktu</th>
                                    <th>Kelas & Ruangan</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Status Jurnal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayJadwals as $jadwal)
                                    @php
                                        $isFilled = in_array($jadwal->id_jadwal, $filledJadwalIds);
                                        $jamMulai = optional($jadwal->jamPelajaran)->jam_mulai ? \Carbon\Carbon::parse($jadwal->jamPelajaran->jam_mulai)->format('H:i') : '-';
                                        $jamSelesai = optional($jadwal->jamPelajaran)->jam_selesai ? \Carbon\Carbon::parse($jadwal->jamPelajaran->jam_selesai)->format('H:i') : '-';
                                        $kelasName = optional($jadwal->kelas)->tingkat . ' ' . optional(optional($jadwal->kelas)->jurusan)->kode_jurusan . ' ' . optional($jadwal->kelas)->rombel;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong style="color: #0f172a; font-size: 0.95rem; display: block;">Jam ke-{{ optional($jadwal->jamPelajaran)->jam_ke ?? '-' }}</strong>
                                            <span style="font-size: 0.8rem; color: #64748b; font-weight: 600;">{{ $jamMulai }} - {{ $jamSelesai }} WIB</span>
                                        </td>
                                        <td>
                                            <strong style="color: var(--gm-navy); font-size: 0.95rem; display: block;">{{ $kelasName }}</strong>
                                            <span style="font-size: 0.8rem; color: #64748b;">Ruang: {{ optional($jadwal->ruangan)->nama_ruangan ?? 'Ruang Kelas' }}</span>
                                        </td>
                                        <td style="font-weight: 700; color: #334155;">
                                            {{ optional($jadwal->mapel)->nama_mapel ?? 'Mata Pelajaran' }}
                                        </td>
                                        <td>
                                            @if($isFilled)
                                                <span class="badge-status badge-done">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                    <span>Sudah Diisi</span>
                                                </span>
                                            @else
                                                <span class="badge-status badge-pending">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                                    <span>Belum Diisi</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($isFilled)
                                                <a href="{{ route('jurnal.index') }}" style="background: #e2e8f0; color: #334155; padding: 8px 14px; border-radius: 8px; font-weight: 700; font-size: 0.8rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                    <span>Lihat Jurnal</span>
                                                </a>
                                            @else
                                                <a href="{{ route('jurnal.create', ['id_jadwal' => $jadwal->id_jadwal]) }}" style="background: var(--gm-navy); color: #ffffff; padding: 8px 14px; border-radius: 8px; font-weight: 700; font-size: 0.8rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(35, 41, 59, 0.2);">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                    <span>Isi Jurnal Sekarang</span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #94a3b8; padding: 36px 20px;">
                                            <svg width="40" height="40" fill="none" stroke="#cbd5e1" stroke-width="2" viewBox="0 0 24 24" style="margin: 0 auto 10px auto; display: block;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                            <strong>Tidak Ada Jadwal Mengajar Hari Ini</strong>
                                            <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #94a3b8;">Anda tidak memiliki jadwal pelajaran di hari {{ $todayName }}.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
            const timeEl = document.getElementById('live_time_str');
            if (timeEl) timeEl.innerText = timeStr;
        }
        setInterval(updateClock, 1000);
    </script>
</body>
</html>
