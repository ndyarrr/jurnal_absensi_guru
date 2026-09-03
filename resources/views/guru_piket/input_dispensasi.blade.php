<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Dispensasi Siswa - Guru Piket</title>

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
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--pk-bg);
            color: var(--pk-text-dark);
            min-height: 100vh;
            display: flex;
        }

        .pk-sidebar {
            width: 250px; background-color: #ffffff; border-right: 1px solid #e2e8f0;
            padding: 24px 16px; display: flex; flex-direction: column; justify-content: space-between;
            min-height: 100vh; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100;
        }

        .pk-nav-menu { list-style: none; display: flex; flex-direction: column; gap: 6px; margin-top: 16px; flex: 1; }
        .pk-nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #475569; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: all 0.2s ease; }
        .pk-nav-link:hover { color: #1e2538; background: #f1f5f9; }
        .pk-nav-link.active { background-color: var(--pk-navy); color: #ffffff; font-weight: 800; box-shadow: 0 4px 12px rgba(30, 37, 56, 0.15); }

        .pk-main { flex: 1; margin-left: 250px; padding: 28px 36px; overflow-y: auto; width: calc(100% - 250px); }

        .pk-header-bar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
        }

        .pk-form-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid var(--pk-cream-border);
            padding: 32px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            max-width: 840px;
            margin: 0 auto;
        }

        .pk-form-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .pk-form-header h2 { font-size: 1.4rem; font-weight: 800; color: var(--pk-navy); }
        .pk-form-header p { font-size: 0.875rem; color: var(--pk-text-muted); font-weight: 600; margin-top: 4px; }

        .pk-form-group { margin-bottom: 20px; }

        .pk-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--pk-navy);
            margin-bottom: 8px;
        }

        .pk-input, .pk-select, .pk-textarea {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--pk-text-dark);
            outline: none;
            transition: all 0.2s ease;
        }

        .pk-input:focus, .pk-select:focus, .pk-textarea:focus {
            border-color: var(--pk-amber);
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
        }

        .pk-btn-submit {
            background-color: var(--pk-amber);
            color: #ffffff;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 800;
            border: none;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);
        }

        .pk-btn-submit:hover {
            background-color: #b45309;
            transform: translateY(-1px);
        }

        .pk-dispen-banner {
            background-color: #fffbebf8;
            border: 1px solid #fde68a;
            color: #b45309;
            padding: 16px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ttd-card {
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            padding: 16px;
            background: #fafafa;
            text-align: center;
        }

        @media (max-width: 992px) {
            .pk-sidebar { transform: translateX(-260px); }
            .pk-main { margin-left: 0; width: 100%; padding: 20px 16px; }
            body.sidebar-mobile-open .pk-sidebar { transform: translateX(0); }
        }
    </style>
</head>
<body class="dashboard-body">

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
                    <a href="{{ route('guru-piket.input-dispensasi') }}" class="pk-nav-link active">
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
            <div class="pk-sidebar-footer">Tahun Ajaran 2026/2027</div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="pk-main dash-main">
        <header class="pk-header-bar">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="button" class="dash-hamburger-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>

                <div>
                    <div style="font-size: 0.775rem; font-weight: 800; color: var(--pk-amber); text-transform: uppercase;">MEJA PIKET . DISPENSASI SISWA</div>
                    <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--pk-navy);">
                        {{ isset($isVerified) && $isVerified ? 'DETAIL DISPENSASI (TERVERIFIKASI)' : 'INPUT DISPENSASI / IZIN KELUAR SISWA' }}
                    </h1>
                </div>
            </div>

            <!-- Top Right Region (Date Widget & User Badge) -->
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

                <!-- User Profile Badge & Settings Widget -->
                @include('partials.dash-user-widget')
            </div>
        </header>

        @if(session('error'))
            <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 16px 20px; border-radius: 16px; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 1.2rem;"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if(isset($isVerified) && $isVerified)
            <!-- Banner Kunci Surat Terverifikasi -->
            <div style="background-color: #f0fdf4; border: 1.5px solid #86efac; color: #166534; padding: 18px 22px; border-radius: 16px; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 14px; box-shadow: 0 4px 14px rgba(22, 101, 52, 0.08);">
                <i class="fa-solid fa-lock" style="font-size: 1.8rem; color: #16a34a;"></i>
                <div>
                    <div style="font-size: 1.05rem; font-weight: 800; color: #14532d;">SURAT DISPENSASI TERVERIFIKASI & DISETUJUI (KUNCI PERMANEN)</div>
                    <p style="font-size: 0.85rem; font-weight: 600; margin-top: 2px; color: #166534;">Surat dispensasi nomor <strong>{{ $autoNomorSurat }}</strong> telah resmi disetujui. Seluruh data, lampiran file, dan pengesahan TTD Digital telah dikunci secara permanen dan tidak dapat diubah kembali.</p>
                </div>
            </div>
        @elseif(isset($isDutyToday) && !$isDutyToday)
            <div style="background-color: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; padding: 18px 22px; border-radius: 16px; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 14px; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.08);">
                <i class="fa-solid fa-shield-cat" style="font-size: 1.8rem; color: #dc2626;"></i>
                <div>
                    <div style="font-size: 1.05rem; font-weight: 800; color: #7f1d1d;">AKSES TERKUNCI: BUKAN PETUGAS PIKET HARI INI ({{ strtoupper($todayName ?? '') }})</div>
                    <p style="font-size: 0.85rem; font-weight: 600; margin-top: 2px; color: #991b1b;">Anda tidak terdaftar pada Jadwal Guru Piket hari {{ $todayName ?? 'ini' }}. Pengisian & penerbitan dispensasi siswa hanya dapat dilakukan oleh Guru Piket yang bertugas hari ini.</p>
                </div>
            </div>
        @else
            <div class="pk-dispen-banner">
                <i class="fa-solid fa-paper-plane" style="font-size: 1.3rem;"></i>
                <div>
                    <strong>Penerbitan Dispensasi Digital & Pengesahan TTD!</strong>
                    <p>Pilih kelas dan siswa, isi rincian kegiatan, lampirkan dokumen surat, lalu bubuhkan Tanda Tangan Digital (Siswa & Guru Piket) sebelum menerbitkan surat.</p>
                </div>
            </div>
        @endif

        <div class="pk-form-card">
            <div class="pk-form-header">
                <h2>{{ isset($isVerified) && $isVerified ? 'Detail Surat Dispensasi Siswa' : 'Form Pengajuan Dispensasi Siswa' }}</h2>
                <p>{{ isset($isVerified) && $isVerified ? 'Data surat resmi yang tersimpan di arsip digital sekolah.' : 'Silakan isi informasi kegiatan, jadwal izin keluar siswa, serta tanda tangan digital.' }}</p>
            </div>

            <form action="{{ route('guru-piket.store-dispensasi') }}" method="POST" enctype="multipart/form-data" id="formDispensasi">
                @csrf

                @if(isset($surat) && $surat->id_dispen)
                    <input type="hidden" name="id_dispen" value="{{ $surat->id_dispen }}">
                @endif

                <!-- Nomor Surat Otomatis -->
                <div class="pk-form-group">
                    <label class="pk-label"><i class="fa-solid fa-hashtag" style="margin-right: 6px;"></i>Nomor Surat</label>
                    <input type="text" name="nomor_surat" class="pk-input" value="{{ $autoNomorSurat }}" readonly style="background-color: #f1f5f9; cursor: not-allowed; font-weight: 800;">
                </div>

                <!-- Cascading Select (Pilih Kelas -> Pilih Siswa) -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div class="pk-form-group" style="margin-bottom: 0;">
                        <label class="pk-label"><i class="fa-solid fa-school" style="margin-right: 6px; color: var(--pk-amber);"></i>1. Pilih Kelas Dahulu</label>
                        <select id="select_kelas" class="pk-select" onchange="filterSiswaByKelas(this.value)" @if((isset($isDutyToday) && !$isDutyToday) || (isset($isVerified) && $isVerified)) disabled @endif>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id_kelas }}" @if(isset($surat) && $surat->id_kelas == $k->id_kelas) selected @endif>
                                    {{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pk-form-group" id="siswa_group" style="margin-bottom: 0;">
                        <label class="pk-label"><i class="fa-solid fa-user-graduate" style="margin-right: 6px; color: var(--pk-amber);"></i>2. Pilih Nama Siswa</label>
                        <select name="id_siswa" id="select_siswa" class="pk-select" @if((isset($isDutyToday) && !$isDutyToday) || (isset($isVerified) && $isVerified)) disabled @endif>
                            <option value="">-- Pilih Nama Siswa --</option>
                            @foreach($siswaList as $s)
                                <option value="{{ $s->id_siswa }}" data-kelas-id="{{ $s->id_kelas }}" @if(isset($surat) && $surat->id_siswa == $s->id_siswa) selected @endif>
                                    {{ $s->nama_siswa }} - NISN: {{ $s->nisn ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Nama Kegiatan & Lokasi -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="pk-form-group">
                        <label class="pk-label"><i class="fa-solid fa-trophy" style="margin-right: 6px;"></i>Nama Kegiatan / Keperluan</label>
                        <input type="text" name="nama_kegiatan" class="pk-input" value="{{ old('nama_kegiatan', $surat->nama_kegiatan ?? '') }}" placeholder="Contoh: Lomba O2SN Futsal / Tugas Ekstrakurikuler" required @if((isset($isDutyToday) && !$isDutyToday) || (isset($isVerified) && $isVerified)) disabled @endif>
                    </div>

                    <div class="pk-form-group">
                        <label class="pk-label"><i class="fa-solid fa-location-dot" style="margin-right: 6px;"></i>Lokasi Kegiatan</label>
                        <input type="text" name="lokasi_kegiatan" class="pk-input" value="{{ old('lokasi_kegiatan', $surat->lokasi_kegiatan ?? '') }}" placeholder="Contoh: GOR Tri Dharma Kota / Aula Utama" required @if((isset($isDutyToday) && !$isDutyToday) || (isset($isVerified) && $isVerified)) disabled @endif>
                    </div>
                </div>

                <!-- Tanggal & Jam -->
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 12px;">
                    <div class="pk-form-group">
                        <label class="pk-label"><i class="fa-solid fa-calendar" style="margin-right: 6px;"></i>Tgl Mulai</label>
                        <input type="date" name="tanggal_mulai" class="pk-input" value="{{ old('tanggal_mulai', $surat->tanggal_mulai ?? date('Y-m-d')) }}" required @if((isset($isDutyToday) && !$isDutyToday) || (isset($isVerified) && $isVerified)) disabled @endif>
                    </div>
                    <div class="pk-form-group">
                        <label class="pk-label"><i class="fa-solid fa-calendar-check" style="margin-right: 6px;"></i>Tgl Selesai</label>
                        <input type="date" name="tanggal_selesai" class="pk-input" value="{{ old('tanggal_selesai', $surat->tanggal_selesai ?? date('Y-m-d')) }}" required @if((isset($isDutyToday) && !$isDutyToday) || (isset($isVerified) && $isVerified)) disabled @endif>
                    </div>
                    <div class="pk-form-group">
                        <label class="pk-label"><i class="fa-solid fa-clock" style="margin-right: 6px;"></i>Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="pk-input" value="{{ old('jam_mulai', $surat->jam_mulai ?? '08:00') }}" required @if((isset($isDutyToday) && !$isDutyToday) || (isset($isVerified) && $isVerified)) disabled @endif>
                    </div>
                    <div class="pk-form-group">
                        <label class="pk-label"><i class="fa-solid fa-clock-rotate-left" style="margin-right: 6px;"></i>Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="pk-input" value="{{ old('jam_selesai', $surat->jam_selesai ?? '14:00') }}" required @if((isset($isDutyToday) && !$isDutyToday) || (isset($isVerified) && $isVerified)) disabled @endif>
                    </div>
                </div>

                <!-- Alasan Dispensasi -->
                <div class="pk-form-group">
                    <label class="pk-label"><i class="fa-solid fa-file-signature" style="margin-right: 6px;"></i>Alasan Dispensasi / Keterangan</label>
                    <textarea name="alasan_dispensasi" rows="3" class="pk-textarea" placeholder="Detail alasan permohonan dispen..." required @if((isset($isDutyToday) && !$isDutyToday) || (isset($isVerified) && $isVerified)) disabled @endif>{{ old('alasan_dispensasi', $surat->alasan_dispensasi ?? '') }}</textarea>
                </div>

                <!-- File Pendukung (Lampiran Surat / Undangan) & Preview Zoom -->
                <div class="pk-form-group">
                    <label class="pk-label"><i class="fa-solid fa-paperclip" style="margin-right: 6px;"></i>File Surat Undangan / Bukti Lampiran (Opsional)</label>

                    @if(isset($surat) && $surat->file_surat)
                        @php
                            $ext = strtolower(pathinfo($surat->file_surat, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                        @endphp
                        
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px; margin-bottom: 12px;" id="stored_file_preview_wrapper">
                            <div style="font-size: 0.8rem; font-weight: 800; color: var(--pk-navy); margin-bottom: 8px;">
                                <i class="fa-solid fa-file-circle-check" style="color: #10b981; margin-right: 4px;"></i>Lampiran Surat Tersimpan:
                            </div>

                            <div id="stored_file_preview_box" style="transition: opacity 0.2s ease;">
                                @if($isImage)
                                    <!-- Preview Gambar & Click to Zoom Full View Modal -->
                                    <div onclick="openFileZoomModal('{{ $surat->file_surat_url }}', '{{ addslashes($surat->nama_kegiatan) }}')" 
                                         style="cursor: pointer; position: relative; display: inline-block; max-width: 280px; border-radius: 12px; overflow: hidden; border: 2px solid #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.06); transition: transform 0.2s ease;"
                                         onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'" title="Klik untuk lihat gambar penuh">
                                        <img src="{{ $surat->file_surat_url }}" alt="Preview Surat" style="width: 100%; max-height: 180px; object-fit: cover; display: block;">
                                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(30,37,56,0.85); color: #ffffff; padding: 6px 10px; font-size: 0.75rem; font-weight: 700; text-align: center; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                            <i class="fa-solid fa-magnifying-glass-plus"></i> Klik untuk Perbesar (Full View)
                                        </div>
                                    </div>
                                @else
                                    <!-- Preview Document PDF -->
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <a href="{{ $surat->file_surat_url }}" target="_blank" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; padding: 8px 16px; border-radius: 10px; text-decoration: none; font-size: 0.85rem; font-weight: 800; display: inline-flex; align-items: center; gap: 8px;">
                                            <i class="fa-solid fa-file-pdf" style="font-size: 1.1rem; color: #dc2626;"></i> Buka / Pratinjau Dokumen PDF
                                        </a>
                                    </div>
                                @endif
                            </div>

                            @if(!((isset($isVerified) && $isVerified) || (isset($isDutyToday) && !$isDutyToday)))
                                <input type="hidden" name="hapus_file_surat" id="hapus_file_surat" value="0">
                                <div>
                                    <button type="button" onclick="toggleHapusStoredFile()" id="btn_hapus_stored_file" style="margin-top: 10px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 6px 14px; border-radius: 8px; font-weight: 700; font-size: 0.775rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-trash-can"></i> Hapus Lampiran Ini
                                    </button>
                                    <div id="stored_file_delete_msg" style="display: none; font-size: 0.775rem; font-weight: 700; color: #dc2626; margin-top: 8px;">
                                        <i class="fa-solid fa-triangle-exclamation" style="margin-right: 4px;"></i>Lampiran ini ditandai untuk dihapus saat Anda menekan tombol simpan. 
                                        <button type="button" onclick="toggleHapusStoredFile()" style="background: none; border: none; color: #2563eb; text-decoration: underline; cursor: pointer; font-weight: 800; margin-left: 4px;">Batal Hapus</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @elseif((isset($isVerified) && $isVerified) || (isset($isDutyToday) && !$isDutyToday))
                        <div style="padding: 10px 14px; background: #f1f5f9; border-radius: 10px; color: #64748b; font-size: 0.85rem; font-weight: 600; margin-bottom: 12px;">
                            <i class="fa-solid fa-circle-info" style="margin-right: 4px;"></i>(Tidak ada file lampiran tersimpan pada surat ini)
                        </div>
                    @endif

                    <!-- Sembunyikan Browse File Input sepenuhnya jika dalam Mode Kunci Edit / Terverifikasi -->
                    @if(!((isset($isVerified) && $isVerified) || (isset($isDutyToday) && !$isDutyToday)))
                        <input type="file" name="file_surat" id="input_file_surat" class="pk-input" accept=".pdf,.jpg,.jpeg,.png,.webp" onchange="handleFileSelectPreview(this)">
                        
                        <!-- Live Preview Container for Newly Selected Image File -->
                        <div id="live_file_preview_container" style="display: none; margin-top: 12px;">
                            <div style="font-size: 0.775rem; font-weight: 800; color: var(--pk-amber); margin-bottom: 6px;">
                                <i class="fa-solid fa-eye" style="margin-right: 4px;"></i>Pratinjau File Yang Dipilih:
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 8px;">
                                <div onclick="openLiveZoomModal()" style="cursor: pointer; display: inline-block; max-width: 240px; border-radius: 10px; overflow: hidden; border: 2px dashed var(--pk-amber); background: #ffffff; padding: 4px;" title="Klik untuk lihat gambar penuh">
                                    <img id="live_file_preview_img" src="" alt="Pratinjau File" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 6px; display: block;">
                                    <div style="font-size: 0.7rem; font-weight: 800; color: var(--pk-navy); text-align: center; margin-top: 4px; padding: 2px;">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i> Klik untuk Zoom Full View
                                    </div>
                                </div>
                                <button type="button" onclick="removeSelectedFileInput()" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 6px 14px; border-radius: 8px; font-weight: 700; font-size: 0.775rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-trash-can"></i> Hapus / Batal Upload File Ini
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- SECTION TANDA TANGAN DIGITAL (TTD SISWA & GURU PIKET) -->
                <div class="pk-form-group" style="margin-top: 28px; padding-top: 24px; border-top: 2px dashed #e2e8f0;">
                    <label class="pk-label" style="font-size: 0.95rem; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-signature" style="color: var(--pk-amber); font-size: 1.1rem;"></i>
                        <span>Pengesahan Tanda Tangan Digital (TTD Siswa & Guru Piket)</span>
                    </label>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <!-- TTD SISWA (PEMOHON) -->
                        <div class="ttd-card">
                            <div style="font-size: 0.85rem; font-weight: 800; color: var(--pk-navy); margin-bottom: 10px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                <i class="fa-solid fa-user-graduate" style="color: #2563eb;"></i>
                                <span>Tanda Tangan Siswa (Pemohon)</span>
                            </div>

                            @if(isset($surat) && $surat->ttd_siswa_url)
                                <div style="padding: 12px; background: #ffffff; border-radius: 10px; border: 1px solid #cbd5e1; min-height: 130px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <img src="{{ $surat->ttd_siswa_url }}" alt="TTD Siswa" style="max-height: 90px; max-width: 100%; object-fit: contain;">
                                    <div style="font-size: 0.8rem; font-weight: 800; color: #1e2538; margin-top: 6px;">( {{ $surat->ttd_siswa_signed_name ?? optional($surat->siswa)->nama_siswa }} )</div>
                                    @if($surat->ttd_siswa_signed_at)
                                        <div style="font-size: 0.725rem; color: #64748b;">Tertanda: {{ $surat->ttd_siswa_signed_at->format('d/m/Y H:i') }} WIB</div>
                                    @endif
                                </div>
                            @elseif(isset($isVerified) && $isVerified)
                                <div style="padding: 30px; color: #94a3b8; font-size: 0.85rem; font-weight: 700;">( Tanda Tangan Tidak Tersedia )</div>
                            @else
                                <canvas id="canvas_siswa" width="340" height="130" style="width: 100%; height: 130px; background: #ffffff; border: 1px dashed #94a3b8; border-radius: 10px; touch-action: none; cursor: crosshair; display: block; margin-bottom: 8px;"></canvas>
                                <input type="hidden" name="ttd_siswa_data" id="ttd_siswa_data">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <button type="button" onclick="clearCanvasSiswa()" style="padding: 5px 12px; font-size: 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; cursor: pointer; font-weight: 700; color: #475569;">
                                        <i class="fa-solid fa-eraser"></i> Bersihkan TTD Siswa
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- TTD GURU PIKET (PENGESAH) -->
                        <div class="ttd-card">
                            <div style="font-size: 0.85rem; font-weight: 800; color: var(--pk-navy); margin-bottom: 10px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                <i class="fa-solid fa-user-tie" style="color: var(--pk-amber);"></i>
                                <span>Tanda Tangan Guru Piket (Pengesah)</span>
                            </div>

                            @if(isset($surat) && $surat->ttd_guru_url)
                                <div style="padding: 12px; background: #ffffff; border-radius: 10px; border: 1px solid #cbd5e1; min-height: 130px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <img src="{{ $surat->ttd_guru_url }}" alt="TTD Guru" style="max-height: 90px; max-width: 100%; object-fit: contain;">
                                    <div style="font-size: 0.8rem; font-weight: 800; color: #1e2538; margin-top: 6px;">( {{ $surat->ttd_guru_signed_name ?? $namaGuruPiket }} )</div>
                                    @if($surat->ttd_guru_signed_at)
                                        <div style="font-size: 0.725rem; color: #64748b;">Tertanda: {{ $surat->ttd_guru_signed_at->format('d/m/Y H:i') }} WIB</div>
                                    @endif
                                </div>
                            @elseif(isset($isVerified) && $isVerified)
                                <div style="padding: 30px; color: #94a3b8; font-size: 0.85rem; font-weight: 700;">( Tanda Tangan Tidak Tersedia )</div>
                            @else
                                <canvas id="canvas_guru" width="340" height="130" style="width: 100%; height: 130px; background: #ffffff; border: 1px dashed #94a3b8; border-radius: 10px; touch-action: none; cursor: crosshair; display: block; margin-bottom: 8px;"></canvas>
                                <input type="hidden" name="ttd_guru_data" id="ttd_guru_data">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <button type="button" onclick="clearCanvasGuru()" style="padding: 5px 12px; font-size: 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; cursor: pointer; font-weight: 700; color: #475569;">
                                        <i class="fa-solid fa-eraser"></i> Bersihkan TTD Guru
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if(isset($isVerified) && $isVerified)
                    <a href="{{ route('guru-piket.digital-surat') }}" class="pk-btn-submit" style="display: block; text-align: center; text-decoration: none; background-color: var(--pk-navy); box-shadow: none;">
                        <i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i>Kembali ke Daftar Surat Piket Digital
                    </a>
                @else
                    <button type="submit" class="pk-btn-submit" @if(isset($isDutyToday) && !$isDutyToday) disabled style="opacity: 0.5; cursor: not-allowed; background-color: #94a3b8; box-shadow: none;" @endif>
                        <i class="fa-solid fa-paper-plane" style="margin-right: 8px;"></i>Simpan & Terbitkan Dispensasi Siswa
                    </button>
                @endif
            </form>
        </div>
    </main>

    <!-- Modal Full View Image Zoom -->
    <div id="fileZoomModal" style="display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(5px); align-items: center; justify-content: center; padding: 20px;" onclick="closeFileZoomModal()">
        <div style="position: relative; max-width: 90vw; max-height: 90vh; background: #ffffff; border-radius: 16px; padding: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); display: flex; flex-direction: column; align-items: center;" onclick="event.stopPropagation()">
            <div style="width: 100%; display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0;">
                <h3 id="zoomModalTitle" style="font-size: 1rem; font-weight: 800; color: var(--pk-navy); font-family: 'Plus Jakarta Sans', sans-serif;">
                    Pratinjau Gambar Lampiran Surat
                </h3>
                <button type="button" onclick="closeFileZoomModal()" style="background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 1.2rem; cursor: pointer; color: #475569; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    &times;
                </button>
            </div>
            <div style="overflow: auto; max-height: calc(85vh - 60px); text-align: center; width: 100%;">
                <img id="zoomModalImage" src="" alt="Full View Lampiran" style="max-width: 100%; max-height: 75vh; object-fit: contain; border-radius: 8px; border: 1px solid #cbd5e1;">
            </div>
        </div>
    </div>

    <script>
        let livePreviewDataUrl = '';

        function removeSelectedFileInput() {
            const input = document.getElementById('input_file_surat');
            const container = document.getElementById('live_file_preview_container');
            const imgEl = document.getElementById('live_file_preview_img');
            if (input) input.value = '';
            livePreviewDataUrl = '';
            if (imgEl) imgEl.src = '';
            if (container) container.style.display = 'none';
        }

        function toggleHapusStoredFile() {
            const hiddenInput = document.getElementById('hapus_file_surat');
            const previewBox = document.getElementById('stored_file_preview_box');
            const deleteMsg = document.getElementById('stored_file_delete_msg');
            const btn = document.getElementById('btn_hapus_stored_file');

            if (!hiddenInput) return;

            if (hiddenInput.value === '0') {
                hiddenInput.value = '1';
                if (previewBox) previewBox.style.opacity = '0.35';
                if (deleteMsg) deleteMsg.style.display = 'block';
                if (btn) btn.style.display = 'none';
            } else {
                hiddenInput.value = '0';
                if (previewBox) previewBox.style.opacity = '1';
                if (deleteMsg) deleteMsg.style.display = 'none';
                if (btn) btn.style.display = 'inline-flex';
            }
        }

        function handleFileSelectPreview(input) {
            const container = document.getElementById('live_file_preview_container');
            const imgEl = document.getElementById('live_file_preview_img');
            if (!input.files || !input.files[0]) {
                if (container) container.style.display = 'none';
                return;
            }

            const file = input.files[0];
            if (file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    livePreviewDataUrl = e.target.result;
                    if (imgEl) imgEl.src = livePreviewDataUrl;
                    if (container) container.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                if (container) container.style.display = 'none';
            }
        }

        function openFileZoomModal(imgUrl, title) {
            const modal = document.getElementById('fileZoomModal');
            const modalImg = document.getElementById('zoomModalImage');
            const modalTitle = document.getElementById('zoomModalTitle');
            if (modal && modalImg) {
                modalImg.src = imgUrl;
                if (modalTitle && title) {
                    modalTitle.textContent = 'Pratinjau Gambar Lampiran: ' + title;
                }
                modal.style.display = 'flex';
            }
        }

        function openLiveZoomModal() {
            if (livePreviewDataUrl) {
                openFileZoomModal(livePreviewDataUrl, 'File Yang Dipilih');
            }
        }

        function closeFileZoomModal() {
            const modal = document.getElementById('fileZoomModal');
            if (modal) modal.style.display = 'none';
        }

        function filterSiswaByKelas(kelasId) {
            const siswaGroup = document.getElementById('siswa_group');
            const siswaSelect = document.getElementById('select_siswa');
            if (!siswaSelect) return;
            const options = siswaSelect.querySelectorAll('option');
            
            if (!kelasId) {
                siswaSelect.value = '';
                options.forEach(opt => opt.style.display = 'block');
                return;
            }

            options.forEach(option => {
                if (!option.value) {
                    option.style.display = 'block';
                    return;
                }
                const optKelasId = option.getAttribute('data-kelas-id');
                if (optKelasId === kelasId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        function initSignatureCanvas(canvasId, inputId) {
            const canvas = document.getElementById(canvasId);
            const hiddenInput = document.getElementById(inputId);
            if (!canvas || !hiddenInput) return null;

            const ctx = canvas.getContext('2d');
            let isDrawing = false;

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return {
                    x: (clientX - rect.left) * (canvas.width / rect.width),
                    y: (clientY - rect.top) * (canvas.height / rect.height)
                };
            }

            function startDraw(e) {
                isDrawing = true;
                const pos = getPos(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
                ctx.strokeStyle = '#1e2538';
                ctx.lineWidth = 2.5;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                e.preventDefault();
            }

            function draw(e) {
                if (!isDrawing) return;
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
                e.preventDefault();
            }

            function stopDraw(e) {
                if (isDrawing) {
                    isDrawing = false;
                    hiddenInput.value = canvas.toDataURL('image/png');
                }
            }

            canvas.addEventListener('mousedown', startDraw);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDraw);
            canvas.addEventListener('mouseleave', stopDraw);

            canvas.addEventListener('touchstart', startDraw, { passive: false });
            canvas.addEventListener('touchmove', draw, { passive: false });
            canvas.addEventListener('touchend', stopDraw);

            return {
                clear: function() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    hiddenInput.value = '';
                }
            };
        }

        let sigSiswa = null;
        let sigGuru = null;

        document.addEventListener('DOMContentLoaded', function() {
            const kelasSelect = document.getElementById('select_kelas');
            if (kelasSelect && kelasSelect.value) {
                filterSiswaByKelas(kelasSelect.value);
            }

            sigSiswa = initSignatureCanvas('canvas_siswa', 'ttd_siswa_data');
            sigGuru = initSignatureCanvas('canvas_guru', 'ttd_guru_data');
        });

        function clearCanvasSiswa() {
            if (sigSiswa) sigSiswa.clear();
        }
        function clearCanvasGuru() {
            if (sigGuru) sigGuru.clear();
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
