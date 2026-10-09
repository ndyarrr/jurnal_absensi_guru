<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Surat Ijin Masuk Kelas / Meninggalkan Kelas - Guru Piket</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Modular Dashboard CSS -->
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

        .pk-main {
            flex: 1;
            margin-left: 250px;
            padding: 28px 36px;
            overflow-y: auto;
            width: calc(100% - 250px);
        }

        .pk-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
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
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--pk-text-dark);
            letter-spacing: -0.01em;
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .pk-subtitle {
            font-size: 0.85rem;
            color: var(--pk-text-muted);
            font-weight: 600;
        }

        .pk-card-box {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 28px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
            margin-bottom: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group-full {
            grid-column: span 2;
        }

        .form-label {
            display: block;
            font-weight: 700;
            font-size: 0.875rem;
            color: var(--pk-navy);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            font-family: inherit;
            font-size: 0.9rem;
            color: #1e293b;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .form-control[readonly] {
            background-color: #f1f5f9;
            color: #475569;
            cursor: not-allowed;
            font-weight: 700;
        }

        .form-control:focus:not([readonly]) {
            outline: none;
            border-color: var(--pk-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Searchable Select */
        .searchable-select {
            position: relative;
        }
        .ss-dropdown {
            position: absolute;
            top: 100%; left: 0; right: 0;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-height: 240px;
            overflow-y: auto;
            z-index: 50;
            display: none;
            margin-top: 4px;
        }
        .ss-option {
            padding: 12px 16px;
            font-size: 0.875rem;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.15s ease;
        }
        .ss-option:hover {
            background: #eff6ff;
            color: var(--pk-blue);
        }

        .btn-submit {
            background-color: var(--pk-navy);
            color: #ffffff;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 0.95rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
        }

        .btn-submit:hover:not(:disabled) {
            background-color: #121724;
            transform: translateY(-1px);
        }

        .btn-submit:disabled {
            background-color: #94a3b8;
            cursor: not-allowed;
            opacity: 0.7;
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

        @media (max-width: 992px) {
            .pk-sidebar { transform: translateX(-260px); }
            .pk-main { margin-left: 0; width: 100%; padding: 20px 16px; }
            body.sidebar-mobile-open .pk-sidebar { transform: translateX(0); }
            .form-grid { grid-template-columns: 1fr; }
            .form-group-full { grid-column: span 1; }
        }

        /* Status & aksi tanda tangan digital */
        .ttd-chip { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 8px; font-size: 0.74rem; font-weight: 800; white-space: nowrap; }
        .ttd-done { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .ttd-wait { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
        .ttd-btn { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; cursor: pointer; font-family: inherit; }
        .ttd-btn:hover { background: #dbeafe; }
        .ttd-print { background: var(--pk-navy); color: #fff; border: 0; padding: 6px 12px; border-radius: 8px; font-weight: 800; font-size: 0.78rem; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
        .ttd-print.disabled { background: #e2e8f0; color: #94a3b8; pointer-events: none; }
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
                    <a href="{{ route('guru-piket.dashboard') }}" class="pk-nav-link">
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
                    <a href="{{ route('guru-piket.surat-izin-masuk') }}" class="pk-nav-link active">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line></svg>
                        <span>Surat Ijin Masuk</span>
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
            <form action="{{ route('logout') }}" method="POST" data-confirm-type="logout">
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
            <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 280px;">
                <button type="button" class="dash-hamburger-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>

                <div>
                    <div class="pk-tagline">MEJA GURU PIKET</div>
                    <h1 class="pk-title">SURAT IJIN MASUK KELAS / MENINGGALKAN KELAS</h1>
                    <div class="pk-subtitle">Penerbitan Surat Ijin Masuk / Meninggalkan Kelas untuk Siswa</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
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
                @include('partials.dash-user-widget')
            </div>
        </header>

        <!-- Flash Notifications -->
        @if(session('success'))
            <div style="background-color: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; padding: 16px 20px; border-radius: 16px; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 16px 20px; border-radius: 16px; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 1.2rem;"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Banner Akses Terkunci Jika Bukan Petugas Piket Hari Ini -->
        @if(isset($isDutyToday) && !$isDutyToday)
            <div style="background-color: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; padding: 18px 22px; border-radius: 16px; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 14px; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.08);">
                <i class="fa-solid fa-shield-cat" style="font-size: 1.8rem; color: #dc2626;"></i>
                <div>
                    <div style="font-size: 1.05rem; font-weight: 800; color: #7f1d1d;">AKSES TERKUNCI: BUKAN PETUGAS PIKET HARI INI ({{ strtoupper($todayName ?? '') }})</div>
                    <p style="font-size: 0.85rem; font-weight: 600; margin-top: 2px; color: #991b1b;">Anda tidak terdaftar pada Jadwal Guru Piket hari {{ $todayName ?? 'ini' }}. Pengisian & penerbitan surat ijin masuk siswa hanya dibuka untuk Guru Piket yang bertugas hari ini.</p>
                </div>
            </div>
        @endif

        <!-- Form Card: Terbitkan Surat Izin Masuk / Meninggalkan Kelas -->
        <section class="pk-card-box">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--pk-navy); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-pen-to-square" style="color: var(--pk-blue);"></i>
                Form Penerbitan Surat Ijin Masuk / Meninggalkan Kelas
            </h3>

            <form action="{{ route('guru-piket.store-surat-izin-masuk') }}" method="POST" class="form-grid">
                @csrf

                <!-- Cari & Pilih Siswa (Required) -->
                <div class="form-group-full">
                    <label class="form-label">Cari & Pilih Siswa <span style="color:#dc2626;">*</span></label>
                    <input type="hidden" name="id_siswa" id="sim_id_siswa" required>
                    <div class="searchable-select">
                        <input type="text" class="form-control" id="sim_siswa_search_input" placeholder="Ketik nama siswa atau NISN..." autocomplete="off" onclick="openSiswaDropdown()" onkeyup="filterSiswaDropdown()" @if(isset($isDutyToday) && !$isDutyToday) disabled @endif required>
                        <div class="ss-dropdown" id="sim_siswa_dropdown">
                            @foreach($siswaList as $s)
                                @php
                                    $kStr = optional($s->kelas)->tingkat . ' ' . optional(optional($s->kelas)->jurusan)->kode_jurusan . ' ' . optional($s->kelas)->rombel;
                                @endphp
                                <div class="ss-option" data-id="{{ $s->id_siswa }}" data-idkelas="{{ $s->id_kelas }}" data-nama="{{ $s->nama_siswa }}" data-kelas="{{ trim($kStr) ?: '-' }}" onclick="pickSiswa('{{ $s->id_siswa }}', '{{ $s->id_kelas }}', '{{ addslashes($s->nama_siswa) }}', '{{ addslashes(trim($kStr) ?: '-') }}')">
                                    <div style="font-weight: 700; color: #0f172a;">{{ $s->nama_siswa }}</div>
                                    <small style="color: #64748b;">NISN: {{ $s->nisn }} | Kelas: {{ trim($kStr) ?: '-' }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- NAMA SISWA (Readonly) -->
                <div>
                    <label class="form-label" for="sim_input_nama">NAMA SISWA <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="nama_siswa" id="sim_input_nama" class="form-control" placeholder="Otomatis terisi dari pilihan siswa..." readonly required>
                </div>

                <!-- KELAS / KONSENTRASI KEAHLIAN (Readonly) -->
                <div>
                    <label class="form-label" for="sim_input_kelas">KELAS / KONSENTRASI KEAHLIAN <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="kelas_str" id="sim_input_kelas" class="form-control" placeholder="Otomatis terisi dari pilihan siswa..." readonly required>
                </div>

                <!-- Combined Hidden Field for jam_pelajaran_ke -->
                <input type="hidden" name="jam_pelajaran_ke" id="sim_input_jam_ke" required>

                <!-- MULAI JAM KE -->
                <div>
                    <label class="form-label" for="sim_jam_mulai">MULAI JAM KE <span style="color:#dc2626;">*</span></label>
                    <select id="sim_jam_mulai" class="form-control" onchange="onJamMulaiChange()" @if(isset($isDutyToday) && !$isDutyToday) disabled @endif required>
                        <option value="">-- Pilih Jam Mulai --</option>
                    </select>
                </div>

                <!-- SAMPAI JAM KE (Opsional - Jika Meninggalkan Kelas) -->
                <div>
                    <label class="form-label" for="sim_jam_selesai">SAMPAI JAM KE- <small style="color:#64748b; font-weight:500;">(Isi hanya jika Meninggalkan Kelas)</small></label>
                    <select id="sim_jam_selesai" class="form-control" onchange="updateCombinedJamKe()" @if(isset($isDutyToday) && !$isDutyToday) disabled @endif>
                        <option value="">-- Kosongkan jika Izin Masuk Kelas --</option>
                    </select>
                </div>

                <div class="form-group-full" style="margin-top: -10px;">
                    <small style="color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 14px; border-radius: 10px; display: block;" id="sim_schedule_info">
                        Status Surat: <span id="sim_status_jenis_label" style="font-weight: 800; color: #64748b;">Belum memilih jam</span>
                    </small>
                </div>

                <!-- GURU PIKET (Readonly) -->
                <div>
                    <label class="form-label" for="sim_input_guru_piket">GURU PIKET</label>
                    <input type="text" name="nama_guru_piket" id="sim_input_guru_piket" class="form-control" value="{{ $namaGuruPiket }}" readonly>
                </div>

                <!-- PIKET WAKASEK -->
                <div>
                    <label class="form-label" for="sim_input_wakasek">PIKET WAKASEK</label>
                    <input type="text" name="nama_piket_wakasek" id="sim_input_wakasek" class="form-control" value="{{ $namaPiketWaka ?? '' }}" readonly @if(isset($isDutyToday) && !$isDutyToday) disabled @endif>
                    <small style="display:block; margin-top:6px; font-weight:600; color: {{ !empty($namaPiketWaka) ? '#64748b' : '#dc2626' }};">
                        @if(!empty($namaPiketWaka))
                            Terisi otomatis dari Jadwal Piket. Tanda tangannya diberikan sendiri oleh Piket Wakasek lewat akunnya.
                        @else
                            Belum ada Piket Waka terjadwal hari ini, surat belum bisa diterbitkan.
                        @endif
                    </small>
                </div>

                <!-- ALASAN -->
                <div class="form-group-full">
                    <label class="form-label" for="sim_input_alasan">ALASAN <span style="color:#dc2626;">*</span></label>
                    <textarea name="alasan" id="sim_input_alasan" class="form-control" rows="3" placeholder="Contoh: Bapak lagi sakit / Terlambat masuk sekolah..." @if(isset($isDutyToday) && !$isDutyToday) disabled @endif required></textarea>
                </div>

                <div class="form-group-full" style="display: flex; align-items: flex-end; justify-content: flex-end;">
                    <button type="submit" class="btn-submit" @if(isset($isDutyToday) && !$isDutyToday) disabled @endif>
                        <i class="fa-solid fa-file-signature"></i> Terbitkan Surat Ijin
                    </button>
                </div>
            </form>
        </section>


        <!-- Daftar Surat & Status Tanda Tangan Digital -->
        <section class="pk-card-box" style="margin-top: 24px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--pk-navy); margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-signature" style="color: var(--pk-blue);"></i>
                Tanda Tangan Digital Surat Ijin
            </h3>
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 16px;">
                Setiap pihak menandatangani slotnya sendiri. Surat baru bisa dicetak setelah siswa, guru piket, dan piket wakasek semuanya selesai tanda tangan.
            </p>

            <div style="overflow-x: auto;">
                <table class="pk-table">
                    <thead>
                        <tr>
                            <th>No. Surat</th>
                            <th>Siswa</th>
                            <th>Jam Ke</th>
                            <th>Tanda Tangan</th>
                            <th style="text-align: center;">Cetak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratMasukList as $s)
                            @php
                                $slotTtd = [
                                    'siswa' => ['Siswa', $s->nama_siswa],
                                    'guru-piket' => ['Guru Piket', $s->nama_guru_piket],
                                    'wakasek' => ['Piket Wakasek', $s->nama_piket_wakasek],
                                ];
                            @endphp
                            <tr>
                                <td style="font-weight: 800; font-family: monospace; color: var(--pk-navy);">
                                    {{ $s->nomor_surat }}
                                    <div style="font-family: inherit; font-weight: 600; font-size: 0.72rem; color: #64748b;">{{ $s->tanggal ? $s->tanggal->format('d/m/Y') : '-' }}</div>
                                </td>
                                <td style="font-weight: 800; color: var(--pk-navy);">
                                    {{ $s->nama_siswa }}
                                    <div style="font-weight: 600; font-size: 0.72rem; color: #64748b;">{{ $s->kelas_str }}</div>
                                </td>
                                <td style="font-weight: 700; color: #334155;">{{ $s->jam_pelajaran_ke }}</td>
                                <td>
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        @foreach($slotTtd as $peran => [$label, $namaSlot])
                                            @if($s->sudahTtd($peran))
                                                <span class="ttd-chip ttd-done" title="Ditandatangani {{ optional($s->{\App\Models\SuratIzinMasuk::PERAN_TTD[$peran] . '_signed_at'})->format('d/m/Y H:i') }}">
                                                    <i class="fa-solid fa-circle-check"></i> {{ $label }}
                                                </span>
                                            @elseif($s->bolehDitandatangani($user, $peran))
                                                <button type="button" class="ttd-chip ttd-btn"
                                                    onclick="openSignModal({{ $s->id_surat_izin_masuk }}, '{{ $peran }}', 'Tanda Tangan {{ $label }}', @js($namaSlot ?? '-'), @js($s->nomor_surat))">
                                                    <i class="fa-solid fa-pen-nib"></i> TTD {{ $label }}
                                                </button>
                                            @else
                                                <span class="ttd-chip ttd-wait" title="Menunggu {{ $namaSlot ?: $label }}">
                                                    <i class="fa-regular fa-clock"></i> {{ $label }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    @if($s->ttdLengkap())
                                        <a href="{{ route('guru-piket.surat-izin-masuk.cetak', $s->id_surat_izin_masuk) }}" target="_blank" rel="noopener" class="ttd-print">
                                            <i class="fa-solid fa-print"></i> Cetak
                                        </a>
                                    @else
                                        <span class="ttd-print disabled" title="Cetak aktif setelah semua tanda tangan lengkap">
                                            <i class="fa-solid fa-lock"></i> {{ $s->jumlahTtd() }}/3 TTD
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: #64748b; padding: 24px;">Belum ada surat ijin masuk yang diterbitkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $suratMasukList->links('pagination::bootstrap-4') }}
            </div>
        </section>

    </main>

    <!-- Modal Tanda Tangan Digital -->
    <div id="signModal" style="display: none; position: fixed; z-index: 9999; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); align-items: center; justify-content: center;" onclick="closeSignModal()">
        <div onclick="event.stopPropagation()" style="max-width: 460px; width: 94%; background: #ffffff; border-radius: 16px; padding: 24px; box-shadow: 0 25px 60px rgba(0,0,0,0.35); font-family: 'Plus Jakarta Sans', sans-serif;">
            <h3 id="sign_title" style="font-size: 1.05rem; font-weight: 800; color: var(--pk-navy); margin-bottom: 4px;"></h3>
            <p id="sign_sub" style="font-size: 0.82rem; color: #64748b; margin-bottom: 14px;"></p>

            <div style="border: 1.5px dashed #94a3b8; border-radius: 10px; background: #fafafa; padding: 6px;">
                <canvas id="sign_canvas" width="680" height="340" style="width: 100%; height: 170px; background: #fff; touch-action: none; display: block; cursor: crosshair; border-radius: 6px;"></canvas>
            </div>
            <div id="sign_error" style="display: none; margin-top: 10px; color: #dc2626; font-size: 0.82rem; font-weight: 700;"></div>

            <div style="display: flex; justify-content: space-between; gap: 10px; margin-top: 16px;">
                <button type="button" onclick="closeSignModal()" style="background: #fff; border: 1px solid #cbd5e1; color: #475569; padding: 10px 16px; border-radius: 10px; font-weight: 700; cursor: pointer;">Batal</button>
                <div style="display: flex; gap: 8px;">
                    <button type="button" id="sign_clear" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #334155; padding: 10px 16px; border-radius: 10px; font-weight: 700; cursor: pointer;">Bersihkan</button>
                    <button type="button" id="sign_save" style="background: var(--pk-navy); border: 0; color: #fff; padding: 10px 18px; border-radius: 10px; font-weight: 800; cursor: pointer;" disabled>Simpan TTD</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const todayJadwals = @json($todayJadwals ?? []);
        let currentSelectedClassId = null;

        function openSiswaDropdown() {
            document.getElementById('sim_siswa_dropdown').style.display = 'block';
        }

        function filterSiswaDropdown() {
            const input = document.getElementById('sim_siswa_search_input').value.toLowerCase();
            const options = document.querySelectorAll('#sim_siswa_dropdown .ss-option');
            options.forEach(opt => {
                const text = opt.innerText.toLowerCase();
                opt.style.display = text.includes(input) ? 'block' : 'none';
            });
            document.getElementById('sim_siswa_dropdown').style.display = 'block';
        }

        function populateJamOptions(idKelas) {
            currentSelectedClassId = idKelas;
            const selectMulai = document.getElementById('sim_jam_mulai');
            const selectSesai = document.getElementById('sim_jam_selesai');
            const infoEl = document.getElementById('sim_schedule_info');

            if (!selectMulai || !selectSesai) return;

            const classJadwals = todayJadwals.filter(j => j.id_kelas == idKelas);
            const mapelMap = {};
            if (classJadwals.length > 0) {
                classJadwals.forEach(j => {
                    if (j.jam_ke) {
                        mapelMap[parseInt(j.jam_ke)] = j.mapel ? j.mapel.nama_mapel : '';
                    }
                });
            }

            let htmlMulai = '<option value="">-- Pilih Jam Mulai --</option>';
            let htmlSesai = '<option value="">-- Kosongkan jika Izin Masuk Kelas --</option>';

            for (let i = 1; i <= 10; i++) {
                const mapelName = mapelMap[i] ? ` (${mapelMap[i]})` : '';
                htmlMulai += `<option value="${i}">Jam Ke-${i}${mapelName}</option>`;
                htmlSesai += `<option value="${i}" data-jam="${i}">s/d Jam Ke-${i}${mapelName}</option>`;
            }

            selectMulai.innerHTML = htmlMulai;
            selectSesai.innerHTML = htmlSesai;

            if (infoEl) {
                if (classJadwals.length > 0) {
                    const mapelText = classJadwals.map(j => 'Jam ' + j.jam_ke + ': ' + (j.mapel ? j.mapel.nama_mapel : '-')).join(' | ');
                    infoEl.innerHTML = '💡 <strong>Jadwal kelas hari ini:</strong> ' + mapelText + '<div style="margin-top:4px;">Status Surat: <span id="sim_status_jenis_label" style="font-weight: 800; color: #64748b;">Belum memilih jam</span></div>';
                } else {
                    infoEl.innerHTML = '💡 Kosongkan "Sampai Jam Ke-" jika <strong>Izin Masuk Kelas</strong>, atau isi jika <strong>Meninggalkan Kelas</strong>.<div style="margin-top:4px;">Status Surat: <span id="sim_status_jenis_label" style="font-weight: 800; color: #64748b;">Belum memilih jam</span></div>';
                }
            }

            onJamMulaiChange();
        }

        function onJamMulaiChange() {
            const selectMulai = document.getElementById('sim_jam_mulai');
            const selectSesai = document.getElementById('sim_jam_selesai');
            if (!selectMulai || !selectSesai) return;

            const mulaiVal = parseInt(selectMulai.value);

            // Disable and hide options in selectSesai that are <= mulaiVal
            const options = selectSesai.querySelectorAll('option');
            options.forEach(opt => {
                const jamVal = parseInt(opt.getAttribute('data-jam'));
                if (!isNaN(jamVal)) {
                    if (!isNaN(mulaiVal) && jamVal <= mulaiVal) {
                        opt.disabled = true;
                        opt.style.display = 'none';
                    } else {
                        opt.disabled = false;
                        opt.style.display = 'block';
                    }
                }
            });

            // If current selesai selection is <= mulaiVal, reset it to empty
            const currentSesaiVal = parseInt(selectSesai.value);
            if (!isNaN(mulaiVal) && !isNaN(currentSesaiVal) && currentSesaiVal <= mulaiVal) {
                selectSesai.value = '';
            }

            updateCombinedJamKe();
        }

        function updateCombinedJamKe() {
            const selectMulai = document.getElementById('sim_jam_mulai');
            const selectSesai = document.getElementById('sim_jam_selesai');
            const hiddenInput = document.getElementById('sim_input_jam_ke');
            const labelStatus = document.getElementById('sim_status_jenis_label');

            if (!selectMulai || !hiddenInput) return;

            const mulaiVal = selectMulai.value;
            const selesaiVal = selectSesai ? selectSesai.value : '';

            if (!mulaiVal) {
                hiddenInput.value = '';
                if (labelStatus) labelStatus.innerHTML = '<span style="color:#64748b;">Belum memilih jam</span>';
                return;
            }

            if (selesaiVal) {
                hiddenInput.value = `ke- ${mulaiVal} s/d ${selesaiVal}`;
                if (labelStatus) labelStatus.innerHTML = '<span style="color:#d97706; font-weight:800;"><i class="fa-solid fa-person-walking-arrow-right"></i> Meninggalkan Kelas (Jam ke- ' + mulaiVal + ' s/d ' + selesaiVal + ')</span>';
            } else {
                hiddenInput.value = `ke- ${mulaiVal}`;
                if (labelStatus) labelStatus.innerHTML = '<span style="color:#16a34a; font-weight:800;"><i class="fa-solid fa-door-open"></i> Izin Masuk Kelas (Jam ke- ' + mulaiVal + ')</span>';
            }
        }

        function pickSiswa(id, idKelas, nama, kelas) {
            document.getElementById('sim_id_siswa').value = id;
            document.getElementById('sim_siswa_search_input').value = nama + ' (' + kelas + ')';
            document.getElementById('sim_input_nama').value = nama;
            document.getElementById('sim_input_kelas').value = kelas;
            document.getElementById('sim_siswa_dropdown').style.display = 'none';

            // Populate jam options dynamically for selected class
            populateJamOptions(idKelas);
        }

        // Initialize default jam options
        document.addEventListener('DOMContentLoaded', function() {
            populateJamOptions(null);
        });

        document.addEventListener('click', function(e) {
            const ss = document.querySelector('.searchable-select');
            if (ss && !ss.contains(e.target)) {
                const dd = document.getElementById('sim_siswa_dropdown');
                if (dd) dd.style.display = 'none';
            }
        });

        let signTarget = null;
        let signHasInk = false;

        // Kanvas TTD digambar dengan kode sendiri (mouse, sentuh, pena) seperti di surat dispensasi,
        // jadi tidak bergantung pada library dari CDN.
        (function initSignCanvas() {
            const canvas = document.getElementById('sign_canvas');
            const btnSave = document.getElementById('sign_save');
            const ctx = canvas.getContext('2d');
            let drawing = false;

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                return {
                    x: (e.clientX - rect.left) * (canvas.width / rect.width),
                    y: (e.clientY - rect.top) * (canvas.height / rect.height)
                };
            }

            function startDraw(e) {
                drawing = true;
                try { canvas.setPointerCapture(e.pointerId); } catch (err) {}
                const pos = getPos(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
                ctx.lineTo(pos.x + 0.01, pos.y + 0.01);
                ctx.strokeStyle = '#0f172a';
                ctx.lineWidth = 4;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.stroke();
                signHasInk = true;
                btnSave.disabled = false;
                e.preventDefault();
            }

            function draw(e) {
                if (!drawing) return;
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
                e.preventDefault();
            }

            function stopDraw() {
                drawing = false;
            }

            canvas.addEventListener('pointerdown', startDraw);
            canvas.addEventListener('pointermove', draw);
            canvas.addEventListener('pointerup', stopDraw);
            canvas.addEventListener('pointercancel', stopDraw);
            canvas.addEventListener('pointerleave', stopDraw);
        })();

        function clearSignCanvas() {
            const canvas = document.getElementById('sign_canvas');
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
            signHasInk = false;
            document.getElementById('sign_save').disabled = true;
        }

        function openSignModal(id, peran, judul, nama, nomor) {
            signTarget = { id: id, peran: peran };
            document.getElementById('sign_title').textContent = judul;
            document.getElementById('sign_sub').textContent = (nama && nama !== '-' ? nama + ' · ' : '') + nomor;
            document.getElementById('sign_error').style.display = 'none';
            document.getElementById('sign_save').textContent = 'Simpan TTD';
            clearSignCanvas();
            document.getElementById('signModal').style.display = 'flex';
        }

        function closeSignModal() {
            document.getElementById('signModal').style.display = 'none';
        }

        document.getElementById('sign_clear').addEventListener('click', clearSignCanvas);

        document.getElementById('sign_save').addEventListener('click', function () {
            if (!signHasInk || !signTarget) return;

            const btn = this;
            const errEl = document.getElementById('sign_error');
            const canvas = document.getElementById('sign_canvas');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';
            errEl.style.display = 'none';

            fetch(`/guru-piket/surat-izin-masuk/${signTarget.id}/ttd/${signTarget.peran}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ signature: canvas.toDataURL('image/png') })
            })
            .then(r => r.json().then(j => ({ ok: r.ok, body: j })))
            .then(({ ok, body }) => {
                if (ok && body.success) {
                    window.location.reload();
                    return;
                }
                errEl.textContent = body.message || 'Gagal menyimpan tanda tangan.';
                errEl.style.display = 'block';
                btn.disabled = false;
                btn.textContent = 'Simpan TTD';
            })
            .catch(() => {
                errEl.textContent = 'Terjadi kesalahan saat menyimpan tanda tangan.';
                errEl.style.display = 'block';
                btn.disabled = false;
                btn.textContent = 'Simpan TTD';
            });
        });

        // Setelah surat diterbitkan, langsung buka kotak TTD siswa (perangkat diserahkan ke siswa).
        @if(session('open_sign_surat_id'))
            @php $autoSurat = \App\Models\SuratIzinMasuk::find(session('open_sign_surat_id')); @endphp
            @if($autoSurat && $autoSurat->bolehDitandatangani($user, 'siswa'))
                document.addEventListener('DOMContentLoaded', function () {
                    openSignModal({{ $autoSurat->id_surat_izin_masuk }}, 'siswa', 'Tanda Tangan Siswa', @js($autoSurat->nama_siswa), @js($autoSurat->nomor_surat));
                });
            @endif
        @endif
    </script>
</body>
</html>