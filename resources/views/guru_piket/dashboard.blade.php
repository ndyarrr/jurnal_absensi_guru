<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru Piket - Jurnal & Absensi</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Modular Dashboard CSS for consistent admin styling -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <script src="/js/sidebar-toggle.js"></script>

    <style>
        :root {
            --pk-bg: #f8f6f1;
            --pk-navy: #1e2538;
            --pk-cream: #f7f3eb;
            --pk-cream-border: #e8e2d5;
            --pk-white: #ffffff;
            --pk-text-dark: #1e2538;
            --pk-text-muted: #64748b;
            --pk-blue: #2563eb;
            --pk-amber: #d97706;
            --pk-emerald: #10b981;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--pk-bg);
            color: var(--pk-text-dark);
            min-height: 100vh;
            display: flex;
        }

        /* ---- Sidebar ---- */
        .pk-sidebar {
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

        .pk-nav-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            margin-top: 16px;
        }

        .pk-nav-link {
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

        .pk-nav-link:hover {
            color: #1e2538;
            background: #f1f5f9;
        }

        .pk-nav-link.active {
            background-color: var(--pk-navy);
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(30, 37, 56, 0.15);
        }

        .pk-sidebar-footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
            font-size: 0.775rem;
            color: #64748b;
            font-weight: 700;
        }

        /* ---- Main Layout ---- */
        .pk-main {
            flex: 1;
            margin-left: 250px;
            padding: 28px 36px;
            overflow-y: auto;
            width: calc(100% - 250px);
        }

        .pk-header-bar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
        }

        .pk-tagline {
            font-size: 0.775rem;
            font-weight: 800;
            color: var(--pk-blue);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .pk-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--pk-text-dark);
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .pk-subtitle {
            font-size: 0.875rem;
            color: var(--pk-text-muted);
            font-weight: 600;
        }

        .pk-user-badge {
            background-color: var(--pk-cream);
            border-radius: 16px;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid var(--pk-cream-border);
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .pk-user-avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e2538, #2563eb);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ---- Stats Grid ---- */
        .pk-metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .pk-metric-card {
            border-radius: 18px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 125px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
        }

        .pk-metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.07);
        }

        .pk-metric-card.cream {
            background-color: var(--pk-cream);
            color: var(--pk-text-dark);
            border: 1px solid var(--pk-cream-border);
        }

        .pk-metric-card.navy {
            background: linear-gradient(135deg, #1e2538, #0f172a);
            color: #ffffff;
        }

        .pk-metric-card.white {
            background-color: #ffffff;
            color: var(--pk-text-dark);
            border: 1px solid #e2e8f0;
        }

        .pk-metric-card.amber {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #ffffff;
        }

        .pk-metric-label {
            font-size: 0.825rem;
            font-weight: 700;
            opacity: 0.9;
        }

        .pk-metric-val {
            font-size: 2.1rem;
            font-weight: 800;
            line-height: 1.1;
            margin: 4px 0;
        }

        .pk-metric-sub {
            font-size: 0.75rem;
            font-weight: 600;
            opacity: 0.85;
        }

        /* ---- Quick Actions Section ---- */
        .pk-actions-banner {
            background-color: #ffffff;
            border: 1px solid var(--pk-cream-border);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);
        }

        .pk-actions-info h3 {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--pk-navy);
            margin-bottom: 4px;
        }

        .pk-actions-info p {
            font-size: 0.85rem;
            color: var(--pk-text-muted);
            font-weight: 600;
        }

        .pk-btn-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pk-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.875rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .pk-btn-navy {
            background-color: var(--pk-navy);
            color: #ffffff;
        }

        .pk-btn-navy:hover {
            background-color: #121724;
            transform: translateY(-1px);
        }

        .pk-btn-amber {
            background-color: var(--pk-amber);
            color: #ffffff;
        }

        .pk-btn-amber:hover {
            background-color: #b45309;
            transform: translateY(-1px);
        }

        .pk-btn-cream {
            background-color: var(--pk-cream);
            color: var(--pk-navy);
            border: 1px solid var(--pk-cream-border);
        }

        .pk-btn-cream:hover {
            background-color: #efe7d9;
        }

        /* ---- Recent Data Table ---- */
        .pk-card-box {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
            margin-bottom: 24px;
        }

        .pk-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .pk-card-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--pk-navy);
        }

        .pk-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .pk-table th {
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

        .pk-table td {
            padding: 14px;
            font-size: 0.875rem;
            color: var(--pk-text-dark);
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .pk-badge-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 800;
            display: inline-block;
        }

        .pk-badge-approved {
            background-color: #dcfce7;
            color: #15803d;
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .pk-metrics-grid { grid-template-columns: repeat(2, 1fr); }
            .pk-actions-banner { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 992px) {
            .pk-sidebar { transform: translateX(-260px); }
            .pk-main { margin-left: 0; width: 100%; padding: 20px 16px; }
            body.sidebar-mobile-open .pk-sidebar { transform: translateX(0); }
        }
    </style>
</head>
<body class="dashboard-body">

    <!-- Sidebar Backdrop Overlay (Mobile) -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar Navigation -->
    <aside class="pk-sidebar dash-sidebar">
        <div>
            @include('partials.dash-brand')

            <ul class="pk-nav-menu">
                <li>
                    <a href="{{ route('guru-piket.dashboard') }}" class="pk-nav-link active">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
                        <span>Dashboard Piket</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru-piket.input-surat') }}" class="pk-nav-link">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        <span>Foto & Input Surat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru-piket.input-dispensasi') }}" class="pk-nav-link">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        <span>Input Dispensasi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru-piket.digital-surat') }}" class="pk-nav-link">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                        <span>Surat Piket Digital</span>
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

            <div class="pk-sidebar-footer">
                Tahun Ajaran 2026/2027
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="pk-main dash-main">

        <!-- Top Header Bar -->
        <header class="pk-header-bar">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="button" class="dash-hamburger-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>

                <div>
                    <div class="pk-tagline">MEJA GURU PIKET</div>
                    <h1 class="pk-title">DASHBOARD GURU PIKET</h1>
                    <div class="pk-subtitle">{{ $todayFormatted }} . Digitalisasi Surat & Dispensasi Siswa</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                <!-- Live Clock & Date Widget -->
                <div class="dash-date-widget">
                    <svg class="dash-date-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <div class="dash-date-info">
                        <span class="date-str" id="live_date_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
                        <span class="time-str" id="live_time_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }} WIB</span>
                    </div>
                </div>

                <!-- User Profile Badge & Settings Widget -->
                @include('partials.dash-user-widget')
            </div>
        </header>

        <!-- Flash Success Notification -->
        @if(session('success'))
            <div style="background-color: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; padding: 16px 20px; border-radius: 16px; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <!-- 4 Metric Cards Row -->
        <section class="pk-metrics-grid">
            <div class="pk-metric-card cream">
                <div class="pk-metric-label">Surat Masuk Hari Ini</div>
                <div class="pk-metric-val">{{ $suratMasukHariIni }}</div>
                <div class="pk-metric-sub">proses digitalisasi meja piket</div>
            </div>

            <div class="pk-metric-card amber">
                <div class="pk-metric-label">Total Dispensasi Siswa</div>
                <div class="pk-metric-val">{{ $totalDispensasiCount }}</div>
                <div class="pk-metric-sub">terbit & aktif langsung</div>
            </div>

            <div class="pk-metric-card navy">
                <div class="pk-metric-label">Dispensasi Disetujui</div>
                <div class="pk-metric-val">{{ $dispenDisetujuiCount }}</div>
                <div class="pk-metric-sub">surat piket aktif</div>
            </div>

            <div class="pk-metric-card white">
                <div class="pk-metric-label">Siswa Izin / Sakit</div>
                <div class="pk-metric-val">{{ $siswaIzinSakitCount }}</div>
                <div class="pk-metric-sub">terdaftar otomatis di kelas</div>
            </div>
        </section>

        <!-- Quick Action Banner -->
        <section class="pk-actions-banner">
            <div class="pk-actions-info">
                <h3><i class="fa-solid fa-bolt" style="color: var(--pk-amber); margin-right: 8px;"></i>Pusat Tindakan Guru Piket</h3>
                <p>Proses cepat surat izin terlambat jam 8/9 pagi & pengajuan dispensasi tanpa menumpuk kertas fisik.</p>
            </div>
            <div class="pk-btn-group">
                <a href="{{ route('guru-piket.input-surat') }}" class="pk-btn pk-btn-navy">
                    <i class="fa-solid fa-camera"></i> Foto & Input Surat Izin
                </a>
                <a href="{{ route('guru-piket.input-dispensasi') }}" class="pk-btn pk-btn-amber">
                    <i class="fa-solid fa-paper-plane"></i> Input Dispensasi Siswa
                </a>
                <a href="{{ route('guru-piket.digital-surat') }}" class="pk-btn pk-btn-cream">
                    <i class="fa-solid fa-folder-open"></i> Arsip Surat Piket
                </a>
            </div>
        </section>

        <!-- Tables Section: Dispensasi & Surat Izin Terkini -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            
            <!-- Table 1: Permohonan Dispensasi Keluar Siswa -->
            <section class="pk-card-box">
                <div class="pk-card-header">
                    <h3 class="pk-card-title"><i class="fa-solid fa-file-contract" style="margin-right: 8px; color: var(--pk-amber);"></i>Dispensasi Siswa</h3>
                    <a href="{{ route('guru-piket.digital-surat') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--pk-blue); text-decoration: none;">Lihat Semua &rarr;</a>
                </div>
                <div style="overflow-x: auto;">
                    <table class="pk-table">
                        <thead>
                            <tr>
                                <th>Siswa / Kelas</th>
                                <th>Kegiatan</th>
                                <th>Action / Surat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentDispensasi as $dispen)
                                @php
                                    $namaKelas = optional(optional($dispen->siswa)->kelas)->tingkat
                                        . ' ' . optional(optional(optional($dispen->siswa)->kelas)->jurusan)->kode_jurusan
                                        . ' ' . optional(optional($dispen->siswa)->kelas)->rombel;
                                @endphp
                                <tr>
                                    <td>
                                        <div style="font-weight: 800; color: var(--pk-navy);">{{ optional($dispen->siswa)->nama_siswa ?? 'Siswa' }}</div>
                                        <div style="font-size: 0.775rem; color: #64748b;">{{ trim($namaKelas) ?: '-' }}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700;">{{ $dispen->nama_kegiatan }}</div>
                                        <div style="font-size: 0.775rem; color: #64748b;">{{ $dispen->jam_mulai }} - {{ $dispen->jam_selesai }}</div>
                                    </td>
                                    <td>
                                        <button type="button" onclick="showDispenModal('{{ $dispen->nomor_surat }}', '{{ optional($dispen->siswa)->nama_siswa ?? 'Siswa' }}', '{{ optional($dispen->siswa)->nisn ?? '-' }}', '{{ trim($namaKelas) ?: '-' }}', '{{ addslashes($dispen->nama_kegiatan) }}', '{{ addslashes($dispen->lokasi_kegiatan ?? 'Lingkungan Sekolah') }}', '{{ $dispen->tanggal_mulai }}', '{{ $dispen->jam_mulai }} - {{ $dispen->jam_selesai }}', '{{ addslashes($dispen->alasan_dispensasi ?? '-') }}', '{{ $dispen->barcode_token }}')" style="background: #fce7f3; border: 1px solid #f472b6; color: #be185d; padding: 5px 10px; border-radius: 8px; font-weight: 800; font-size: 0.75rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fa-solid fa-envelope-open-text" style="color: #ec4899;"></i> Surat Pink
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #64748b; padding: 20px;">Belum ada data dispensasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Table 2: Surat Izin/Sakit Fisik Ter-digitalisasi -->
            <section class="pk-card-box">
                <div class="pk-card-header">
                    <h3 class="pk-card-title"><i class="fa-solid fa-envelope-open-text" style="margin-right: 8px; color: var(--pk-blue);"></i>Surat Izin Digital (Piket)</h3>
                    <a href="{{ route('guru-piket.digital-surat') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--pk-blue); text-decoration: none;">Lihat Semua &rarr;</a>
                </div>
                <div style="overflow-x: auto;">
                    <table class="pk-table">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Jenis</th>
                                <th>Status Rekap</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPermohonan as $perm)
                                <tr>
                                    <td>
                                        <div style="font-weight: 800; color: var(--pk-navy);">{{ optional($perm->siswa)->nama_siswa ?? 'Siswa' }}</div>
                                        <div style="font-size: 0.775rem; color: #64748b;">
                                            {{ optional(optional($perm->siswa)->kelas)->tingkat }}
                                            {{ optional(optional(optional($perm->siswa)->kelas)->jurusan)->kode_jurusan }}
                                            {{ optional(optional($perm->siswa)->kelas)->rombel }}
                                        </div>
                                    </td>
                                    <td>
                                        <span style="font-weight: 700; font-size: 0.825rem; color: #334155;">{{ $perm->jenis_izin }}</span>
                                    </td>
                                    <td>
                                        <span class="pk-badge-status pk-badge-approved">Terdaftar di Rekap</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #64748b; padding: 20px;">Belum ada data surat izin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div>

    </main>

    <!-- Modal UI Surat Dispensasi Digital (Persegi Panjang Berwarna Merah Muda / Pink) -->
    <div id="dispenModal" style="display: none; position: fixed; z-index: 1000; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center;" onclick="closeDispenModal()">
        <div onclick="event.stopPropagation()" style="max-width: 680px; width: 92%; padding: 0; border-radius: 24px; overflow: hidden; background-color: #fff0f5; border: 2px solid #f472b6; box-shadow: 0 25px 50px -12px rgba(236, 72, 153, 0.25); position: relative;">
            
            <!-- Header Surat Pink Header -->
            <div style="background: linear-gradient(135deg, #ec4899, #be185d); color: #ffffff; padding: 24px 28px; position: relative;">
                <button type="button" onclick="closeDispenModal()" style="position: absolute; top: 18px; right: 20px; background: rgba(255,255,255,0.2); border: none; color: #ffffff; width: 32px; height: 32px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s;">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 50px; height: 50px; background: #ffffff; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #be185d; font-size: 1.6rem; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 800; letter-spacing: 0.1em; opacity: 0.9; text-transform: uppercase;">SURAT DISPENSASI SISWA (DIGITAL)</div>
                        <h2 style="font-size: 1.35rem; font-weight: 800; margin: 2px 0 0 0;">PERIZINAN KELUAR / KEGIATAN</h2>
                        <div style="font-size: 0.775rem; opacity: 0.85;">Jurnal & Absensi Guru Piket Sekolah</div>
                    </div>
                </div>
            </div>

            <!-- Body Surat Digital Merah Muda -->
            <div style="padding: 24px 28px; background-color: #fff0f5;" id="printableDispenArea">
                <!-- Status Badge Banner -->
                <div style="display: flex; align-items: center; justify-content: space-between; background-color: #fce7f3; border: 1px dashed #f472b6; padding: 12px 18px; border-radius: 14px; margin-bottom: 20px;">
                    <div>
                        <span style="font-size: 0.75rem; color: #9d174d; font-weight: 700; text-transform: uppercase;">NOMOR SURAT</span>
                        <div id="dp_no_surat" style="font-weight: 800; font-size: 1rem; color: #831843;">DISPEN/2026/08/001</div>
                    </div>
                    <div style="background-color: #be185d; color: #ffffff; padding: 6px 14px; border-radius: 20px; font-weight: 800; font-size: 0.775rem; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(190, 24, 93, 0.3);">
                        <i class="fa-solid fa-circle-check"></i> SAH & DISETUJUI PIKET
                    </div>
                </div>

                <!-- Detail Content Box -->
                <div style="background: #ffffff; border: 1px solid #fbcfe8; border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px rgba(244, 114, 182, 0.08); margin-bottom: 20px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                        <tr style="border-bottom: 1px solid #fce7f3;">
                            <td style="padding: 8px 0; color: #9d174d; font-weight: 700; width: 35%;">Nama Siswa</td>
                            <td style="padding: 8px 0; font-weight: 800; color: #1e2538;" id="dp_nama_siswa">-</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #fce7f3;">
                            <td style="padding: 8px 0; color: #9d174d; font-weight: 700;">NISN</td>
                            <td style="padding: 8px 0; font-weight: 700; color: #475569;" id="dp_nisn">-</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #fce7f3;">
                            <td style="padding: 8px 0; color: #9d174d; font-weight: 700;">Kelas & Jurusan</td>
                            <td style="padding: 8px 0; font-weight: 800; color: #831843;" id="dp_kelas">-</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #fce7f3;">
                            <td style="padding: 8px 0; color: #9d174d; font-weight: 700;">Nama Kegiatan</td>
                            <td style="padding: 8px 0; font-weight: 800; color: #be185d;" id="dp_kegiatan">-</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #fce7f3;">
                            <td style="padding: 8px 0; color: #9d174d; font-weight: 700;">Lokasi Kegiatan</td>
                            <td style="padding: 8px 0; font-weight: 700; color: #334155;" id="dp_lokasi">-</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #fce7f3;">
                            <td style="padding: 8px 0; color: #9d174d; font-weight: 700;">Masa Berlaku</td>
                            <td style="padding: 8px 0; font-weight: 800; color: #be185d;" id="dp_waktu">-</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #9d174d; font-weight: 700; vertical-align: top;">Keperluan / Alasan</td>
                            <td style="padding: 8px 0; font-weight: 600; color: #475569; line-height: 1.4;" id="dp_alasan">-</td>
                        </tr>
                    </table>
                </div>

                <!-- Footer Signatures & QR Code Section -->
                <div style="display: flex; align-items: center; justify-content: space-between; background-color: #fce7f3; border: 1px solid #f9a8d4; border-radius: 16px; padding: 16px 20px; gap: 16px;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <img id="dp_qrcode" src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=DISPENSI_VERIFIED" alt="QR Code" style="width: 70px; height: 70px; border-radius: 10px; border: 2px solid #ec4899; background: #ffffff; padding: 4px;">
                        <div>
                            <div style="font-size: 0.75rem; font-weight: 800; color: #be185d; text-transform: uppercase;">VERIFIKASI DIGITAL</div>
                            <div style="font-size: 0.725rem; color: #9d174d; margin-top: 2px;">Terscan otomatis oleh Guru Piket & Pengajar Kelas</div>
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <div style="font-size: 0.725rem; color: #9d174d; font-weight: 700;">Disahkan oleh:</div>
                        <div style="font-weight: 800; color: #831843; font-size: 0.875rem; margin-top: 2px;">Petugas Piket Sekolah</div>
                        <div style="font-size: 0.725rem; color: #be185d; font-weight: 700; margin-top: 2px;"><i class="fa-solid fa-stamp"></i> Stempel Digital Piket</div>
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div style="background-color: #fce7f3; padding: 16px 28px; border-top: 1px solid #fbcfe8; display: flex; align-items: center; justify-content: space-between;">
                <button type="button" onclick="closeDispenModal()" style="background: #ffffff; border: 1px solid #f472b6; color: #9d174d; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Tutup Surat
                </button>
                <button type="button" onclick="window.print()" style="background: linear-gradient(135deg, #ec4899, #be185d); color: #ffffff; border: none; padding: 10px 22px; border-radius: 12px; font-weight: 800; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);">
                    <i class="fa-solid fa-print"></i> Cetak / Simpan Surat
                </button>
            </div>
        </div>
    </div>

    <script>
        function showDispenModal(noSurat, siswa, nisn, kelas, kegiatan, lokasi, tanggal, jam, alasan, token) {
            document.getElementById('dp_no_surat').innerText = noSurat;
            document.getElementById('dp_nama_siswa').innerText = siswa;
            document.getElementById('dp_nisn').innerText = nisn;
            document.getElementById('dp_kelas').innerText = kelas;
            document.getElementById('dp_kegiatan').innerText = kegiatan;
            document.getElementById('dp_lokasi').innerText = lokasi;
            document.getElementById('dp_waktu').innerText = tanggal + ' (' + jam + ' WIB)';
            document.getElementById('dp_alasan').innerText = alasan;
            
            const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=' + encodeURIComponent('DISPEN:' + noSurat + '|TOKEN:' + token);
            document.getElementById('dp_qrcode').src = qrUrl;

            document.getElementById('dispenModal').style.display = 'flex';
        }

        function closeDispenModal() {
            document.getElementById('dispenModal').style.display = 'none';
        }

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

