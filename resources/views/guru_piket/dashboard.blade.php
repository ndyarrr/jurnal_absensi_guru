<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Guru Piket - Jurnal & Absensi</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.min.js"></script>

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
                                        <button type="button" onclick="showDispenModal('{{ $dispen->id_dispen }}', '{{ $dispen->nomor_surat }}', '{{ addslashes(optional($dispen->siswa)->nama_siswa ?? 'Siswa') }}', '{{ optional($dispen->siswa)->nisn ?? '-' }}', '{{ trim($namaKelas) ?: '-' }}', '{{ addslashes($dispen->nama_kegiatan) }}', '{{ addslashes($dispen->lokasi_kegiatan ?? 'Lingkungan Sekolah') }}', '{{ $dispen->tanggal_mulai }}', '{{ $dispen->jam_mulai }} - {{ $dispen->jam_selesai }}', '{{ addslashes($dispen->alasan_dispensasi ?? '-') }}', '{{ $dispen->barcode_token }}', '{{ $dispen->ttd_siswa_url ? addslashes($dispen->ttd_siswa_url) : '' }}', '{{ addslashes($dispen->ttd_siswa_signed_name ?? '') }}', '{{ $dispen->ttd_siswa_signed_at ? $dispen->ttd_siswa_signed_at->format('d/m/Y H:i') : '' }}')" style="background: #fce7f3; border: 1px solid #f472b6; color: #be185d; padding: 5px 10px; border-radius: 8px; font-weight: 800; font-size: 0.75rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                             <i class="fa-solid fa-envelope-open-text" style="color: #ec4899;"></i> Surat
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

    <!-- Modal Surat Dispensasi Digital — Tampilan Surat Resmi -->
    <div id="dispenModal" style="display: none; position: fixed; z-index: 1000; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center;" onclick="closeDispenModal()">
        <div onclick="event.stopPropagation()" style="
            max-width: 720px; width: 95%; max-height: 90vh; overflow-y: auto;
            background: #ffffff; border-radius: 8px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
            position: relative;
            font-family: 'Times New Roman', Times, serif;
        ">
            <!-- Tombol Tutup -->
            <button type="button" onclick="closeDispenModal()" style="
                position: absolute; top: 12px; right: 14px; z-index: 10;
                background: #f1f5f9; border: none; width: 30px; height: 30px;
                border-radius: 50%; font-size: 1.1rem; cursor: pointer;
                color: #475569; display: flex; align-items: center; justify-content: center;
                font-family: sans-serif;
            ">&times;</button>

            <!-- Isi Surat yang Bisa Dicetak -->
            <div id="printableDispenArea" style="padding: 40px 50px; color: #111111;">

                <!-- KOP SURAT -->
                <div style="text-align: center; border-bottom: 3px double #000; padding-bottom: 12px; margin-bottom: 20px;">
                    <div style="font-size: 1rem; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em;">PEMERINTAH DAERAH</div>
                    <div style="font-size: 1.4rem; font-weight: bold; text-transform: uppercase; letter-spacing: 0.07em; margin: 2px 0;">SMK NEGERI 1</div>
                    <div style="font-size: 0.85rem;">Jl. Pendidikan No. 1, Kota — Kode Pos 00000</div>
                    <div style="font-size: 0.8rem;">Telepon: (021) 0000-0000 &nbsp;|&nbsp; Website: www.smkn1.sch.id</div>
                </div>

                <!-- JUDUL SURAT -->
                <div style="text-align: center; margin-bottom: 16px;">
                    <div style="font-size: 1.1rem; font-weight: bold; text-transform: uppercase; text-decoration: underline;">SURAT DISPENSASI SISWA</div>
                    <div style="font-size: 0.9rem;" id="dp_no_surat_formal">Nomor: —</div>
                </div>

                <!-- PEMBUKA -->
                <p style="text-align: justify; margin-bottom: 14px; font-size: 0.95rem; line-height: 1.8;">
                    Yang bertanda tangan di bawah ini, Petugas Piket SMK Negeri 1, dengan ini memberikan
                    <strong>izin dispensasi</strong> kepada siswa yang namanya tercantum di bawah ini untuk meninggalkan
                    lingkungan sekolah guna mengikuti kegiatan sebagaimana dimaksud:
                </p>

                <!-- DATA SISWA -->
                <table style="width: 100%; border-collapse: collapse; font-size: 0.92rem; margin-bottom: 16px;">
                    <tr>
                        <td style="padding: 4px 0; width: 38%; vertical-align: top;">Nama Siswa</td>
                        <td style="padding: 4px 0; width: 4%; vertical-align: top;">:</td>
                        <td style="padding: 4px 0; font-weight: bold;" id="dp_nama_siswa">—</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; vertical-align: top;">NISN</td>
                        <td style="padding: 4px 0; vertical-align: top;">:</td>
                        <td style="padding: 4px 0;" id="dp_nisn">—</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; vertical-align: top;">Kelas / Jurusan</td>
                        <td style="padding: 4px 0; vertical-align: top;">:</td>
                        <td style="padding: 4px 0; font-weight: bold;" id="dp_kelas">—</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; vertical-align: top;">Kegiatan</td>
                        <td style="padding: 4px 0; vertical-align: top;">:</td>
                        <td style="padding: 4px 0; font-weight: bold;" id="dp_kegiatan">—</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; vertical-align: top;">Lokasi</td>
                        <td style="padding: 4px 0; vertical-align: top;">:</td>
                        <td style="padding: 4px 0;" id="dp_lokasi">—</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; vertical-align: top;">Waktu Dispensasi</td>
                        <td style="padding: 4px 0; vertical-align: top;">:</td>
                        <td style="padding: 4px 0; font-weight: bold;" id="dp_waktu">—</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; vertical-align: top;">Alasan / Keperluan</td>
                        <td style="padding: 4px 0; vertical-align: top;">:</td>
                        <td style="padding: 4px 0; line-height: 1.6;" id="dp_alasan">—</td>
                    </tr>
                </table>

                <!-- PENUTUP -->
                <p style="text-align: justify; margin-bottom: 24px; font-size: 0.95rem; line-height: 1.8;">
                    Demikian surat dispensasi ini diberikan untuk dapat dipergunakan sebagaimana mestinya.
                    Siswa yang bersangkutan wajib kembali ke sekolah setelah kegiatan selesai dan melaporkan diri
                    kepada guru piket yang bertugas.
                </p>

                <!-- TANDA TANGAN -->
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-top: 8px;">
                    <!-- Kiri: Penerima -->
                    <div style="text-align: center; width: 220px;">
                        <div>Siswa yang bersangkutan,</div>
                        <div style="min-height: 64px; display:flex; flex-direction:column; justify-content:flex-end; align-items:center; padding: 4px 0;" id="dp_ttd_siswa_area">
                            <div style="color:#888; font-style: italic; font-size: 0.8rem;">( area tanda tangan siswa )</div>
                        </div>
                        <div id="dp_ttd_siswa"></div>
                    </div>
                    <!-- Kanan: Petugas -->
                    <div style="text-align: center; width: 220px;">
                        <div id="dp_kota_tgl" style="margin-bottom: 2px;"></div>
                        <div>Petugas Piket,</div>
                        <div style="height: 64px;"></div>
                        <div style="border-top: 1px solid #000; padding-top: 4px; font-weight: bold;">( __________________ )</div>
                        <div style="font-size: 0.8rem; color: #555;">NIP. —</div>
                    </div>
                </div>

                <!-- Catatan kecil nomor surat -->
                <div style="margin-top: 28px; border-top: 1px solid #ccc; padding-top: 8px; font-size: 0.75rem; color: #666; display: flex; justify-content: space-between;">
                    <span>No. Surat: <span id="dp_no_surat_footer">—</span></span>
                    <span>Diterbitkan oleh Sistem Piket Digital Sekolah</span>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div style="
                background: #f8fafc; border-top: 1px solid #e2e8f0;
                padding: 14px 24px; display: flex; align-items: center; justify-content: space-between;
                font-family: 'Plus Jakarta Sans', sans-serif;
                border-radius: 0 0 8px 8px;
            ">
                <button type="button" onclick="closeDispenModal()" style="background: #ffffff; border: 1px solid #cbd5e1; color: #475569; padding: 8px 18px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Tutup
                </button>
                <button type="button" onclick="window.print()" style="background: #1e2538; color: #ffffff; border: none; padding: 9px 20px; border-radius: 8px; font-weight: 800; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-print"></i> Cetak Surat
                </button>
            </div>
        </div>
    </div>

    <script>
        let dpSignaturePad = null;

        function escapeHtml(s) {
            if (s == null) return '';
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function renderTtdSiswaArea(idDispen, namaSiswa, ttdUrl, signedName, signedAt) {
            const area = document.getElementById('dp_ttd_siswa_area');
            const label = document.getElementById('dp_ttd_siswa');
            if (!area) return;

            if (ttdUrl) {
                area.innerHTML = `
                    <img src="${escapeHtml(ttdUrl)}" alt="TTD ${escapeHtml(signedName || namaSiswa)}" style="max-height:80px; max-width:200px; display:block; margin:0 auto 4px auto;" onerror="this.style.display='none';">
                `;
                if (label) {
                    label.style.borderTop = '1px solid #000';
                    label.style.paddingTop = '4px';
                    label.style.fontWeight = 'bold';
                    label.innerHTML = `( ${escapeHtml(signedName || namaSiswa)} )`;
                }
            } else {
                renderTtdSiswaPad(idDispen, namaSiswa);
            }
        }

        function renderTtdSiswaPad(idDispen, namaSiswa) {
            const area = document.getElementById('dp_ttd_siswa_area');
            const label = document.getElementById('dp_ttd_siswa');
            if (!area) return;

            area.innerHTML = `
                <div style="border:1px dashed #555; border-radius:4px; padding:6px; background:#fafafa; max-width: 320px; margin: 0 auto;">
                    <canvas id="dp_canvas_siswa" width="300" height="120" style="width:100%; height:120px; background:#fff; touch-action: none; display:block; margin: 0 auto; cursor: crosshair; border-radius:2px;"></canvas>
                    <div style="display:flex; gap:6px; justify-content:center; margin-top:6px;">
                        <button type="button" id="dp_btn_clear_ttd" style="font-size:0.7rem; padding:3px 10px; background:#e5e7eb; border:1px solid #9ca3af; color:#374151; border-radius:3px; cursor:pointer;">
                            Bersihkan
                        </button>
                        <button type="button" id="dp_btn_simpan_ttd" style="font-size:0.7rem; padding:3px 10px; background:#1e2538; border:1px solid #1e2538; color:#fff; border-radius:3px; cursor:pointer;" disabled>
                            Simpan TTD
                        </button>
                    </div>
                </div>
            `;
            if (label) {
                label.style.borderTop = '1px solid #000';
                label.style.paddingTop = '4px';
                label.style.fontWeight = 'bold';
                label.innerHTML = `( <span style="color:#888;">menunggu tanda tangan</span> )`;
            }

            const canvas = document.getElementById('dp_canvas_siswa');
            if (!canvas || typeof SignaturePad === 'undefined') {
                if (label) label.innerHTML = '( ' + escapeHtml(namaSiswa) + ' )';
                return;
            }

            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = 300 * ratio;
            canvas.height = 120 * ratio;
            canvas.getContext('2d').scale(ratio, ratio);

            if (dpSignaturePad) { try { dpSignaturePad.off(); } catch (e) {} }
            dpSignaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: '#0f172a',
                minWidth: 0.8,
                maxWidth: 2.4
            });

            const btnSimpan = document.getElementById('dp_btn_simpan_ttd');
            const btnClear  = document.getElementById('dp_btn_clear_ttd');

            function updateSimpanState() {
                if (!btnSimpan) return;
                btnSimpan.disabled = dpSignaturePad.isEmpty();
            }
            dpSignaturePad.addEventListener('endStroke', updateSimpanState);
            dpSignaturePad.addEventListener('beginStroke', updateSimpanState);
            updateSimpanState();

            if (btnClear) {
                btnClear.addEventListener('click', function () {
                    dpSignaturePad.clear();
                    updateSimpanState();
                });
            }

            if (btnSimpan) {
                btnSimpan.addEventListener('click', function () {
                    if (dpSignaturePad.isEmpty()) return;
                    const dataUrl = dpSignaturePad.toDataURL('image/png');
                    btnSimpan.disabled = true;
                    btnSimpan.textContent = 'Menyimpan...';

                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    const url = `/guru-piket/dispensasi/${idDispen}/ttd-siswa`;

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            signature: dataUrl,
                            nama_siswa: namaSiswa
                        })
                    })
                    .then(r => r.json().then(j => ({ status: r.status, body: j })))
                    .then(({ status, body }) => {
                        if (status >= 200 && status < 300 && body.success) {
                            renderTtdSiswaArea(idDispen, namaSiswa, body.url, namaSiswa, body.signed_at);
                        } else {
                            alert(body.message || 'Gagal menyimpan tanda tangan.');
                            btnSimpan.disabled = false;
                            btnSimpan.textContent = 'Simpan TTD';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan saat menyimpan tanda tangan.');
                        btnSimpan.disabled = false;
                        btnSimpan.textContent = 'Simpan TTD';
                    });
                });
            }
        }

        function showDispenModal(idDispen, noSurat, siswa, nisn, kelas, kegiatan, lokasi, tanggal, jam, alasan, token, ttdUrl, ttdSignedName, ttdSignedAt) {
            document.getElementById('dp_no_surat_formal').innerText = 'Nomor: ' + noSurat;
            document.getElementById('dp_no_surat_footer').innerText = noSurat;
            document.getElementById('dp_nama_siswa').innerText = siswa;
            document.getElementById('dp_nisn').innerText = nisn;
            document.getElementById('dp_kelas').innerText = kelas;
            document.getElementById('dp_kegiatan').innerText = kegiatan;
            document.getElementById('dp_lokasi').innerText = lokasi;
            document.getElementById('dp_waktu').innerText = tanggal + ' (' + jam + ' WIB)';
            document.getElementById('dp_alasan').innerText = alasan;

            const now = new Date();
            const day = String(now.getDate()).padStart(2, '0');
            const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            const month = monthNames[now.getMonth()];
            const year = now.getFullYear();
            document.getElementById('dp_kota_tgl').innerText = 'Kota, ' + day + ' ' + month + ' ' + year;

            renderTtdSiswaArea(idDispen, siswa, ttdUrl || '', ttdSignedName || '', ttdSignedAt || '');

            document.getElementById('dispenModal').style.display = 'flex';
        }

        function closeDispenModal() {
            if (dpSignaturePad) { try { dpSignaturePad.off(); } catch (e) {} dpSignaturePad = null; }
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

