<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Surat Izin Siswa - Guru Piket</title>

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

        .pk-form-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid var(--pk-cream-border);
            padding: 32px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            max-width: 800px;
            margin: 0 auto;
        }

        .pk-form-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .pk-form-header h2 { font-size: 1.4rem; font-weight: 800; color: var(--pk-navy); }
        .pk-form-header p { font-size: 0.875rem; color: var(--pk-text-muted); font-weight: 600; margin-top: 4px; }

        .pk-form-group {
            margin-bottom: 20px;
        }

        .pk-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--pk-navy);
            margin-bottom: 8px;
        }

        .pk-input, .pk-select {
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

        .pk-input:focus, .pk-select:focus {
            border-color: var(--pk-blue);
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .pk-upload-box {
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pk-upload-box:hover {
            border-color: var(--pk-blue);
            background-color: #f0f6ff;
        }

        .pk-upload-icon {
            font-size: 2rem;
            color: var(--pk-blue);
            margin-bottom: 8px;
        }

        .pk-btn-submit {
            background-color: var(--pk-navy);
            color: #ffffff;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 800;
            border: none;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(30, 37, 56, 0.15);
        }

        .pk-btn-submit:hover {
            background-color: #121724;
            transform: translateY(-1px);
        }

        .pk-alert-banner {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 16px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
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
                    <a href="{{ route('guru-piket.input-surat') }}" class="pk-nav-link active">
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
                    <div style="font-size: 0.775rem; font-weight: 800; color: var(--pk-blue); text-transform: uppercase;">MEJA PIKET . DIGITALISASI SURAT</div>
                    <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--pk-navy);">FOTO & INPUT SURAT IZIN SISWA</h1>
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

        @if(isset($isDutyToday) && !$isDutyToday)
            <div style="background-color: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; padding: 18px 22px; border-radius: 16px; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 14px; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.08);">
                <i class="fa-solid fa-shield-cat" style="font-size: 1.8rem; color: #dc2626;"></i>
                <div>
                    <div style="font-size: 1.05rem; font-weight: 800; color: #7f1d1d;">AKSES TERKUNCI: BUKAN PETUGAS PIKET HARI INI ({{ strtoupper($todayName ?? '') }})</div>
                    <p style="font-size: 0.85rem; font-weight: 600; margin-top: 2px; color: #991b1b;">Anda tidak terdaftar pada Jadwal Guru Piket hari {{ $todayName ?? 'ini' }}. Pengisian & digitalisasi surat izin siswa hanya dapat dilakukan oleh Guru Piket yang bertugas hari ini.</p>
                </div>
            </div>
        @else
            <div class="pk-alert-banner">
                <i class="fa-solid fa-circle-info" style="font-size: 1.3rem;"></i>
                <div>
                    <strong>Bebas Kertas Fisik di Kelas!</strong>
                    <p>Pilih kelas dahulu untuk menyaring daftar siswa, lalu foto surat izin orang tua (terutama surat terlambat jam 8/9 pagi). Setelah disimpan, status siswa langsung terdaftar di rekap absensi kelas.</p>
                </div>
            </div>
        @endif

        <div class="pk-form-card">
            <div class="pk-form-header">
                <h2>Form Digitalisasi Surat Orang Tua / Dokter</h2>
                <p>Pilih kelas, pilih siswa, dan upload foto/scan fisik surat izin.</p>
            </div>

            <form action="{{ route('guru-piket.store-surat') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Step 1 & Step 2: Cascading Select (Pilih Kelas -> Pilih Siswa) -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div class="pk-form-group" style="margin-bottom: 0;">
                        <label class="pk-label"><i class="fa-solid fa-school" style="margin-right: 6px; color: var(--pk-blue);"></i>1. Pilih Kelas Dahulu</label>
                        <select id="select_kelas" class="pk-select" onchange="filterSiswaByKelas(this.value)" @if(isset($isDutyToday) && !$isDutyToday) disabled @endif>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id_kelas }}">
                                    {{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pk-form-group" id="siswa_group" style="margin-bottom: 0; display: none;">
                        <label class="pk-label"><i class="fa-solid fa-user-graduate" style="margin-right: 6px; color: var(--pk-blue);"></i>2. Pilih Nama Siswa</label>
                        <select name="id_siswa" id="select_siswa" class="pk-select" @if(isset($isDutyToday) && !$isDutyToday) disabled @endif>
                            <option value="">-- Pilih Nama Siswa --</option>
                            @foreach($siswaList as $s)
                                <option value="{{ $s->id_siswa }}" data-kelas-id="{{ $s->id_kelas }}">
                                    {{ $s->nama_siswa }} - NISN: {{ $s->nisn ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Grid Status & Tanggal -->
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="pk-form-group">
                        <label class="pk-label"><i class="fa-solid fa-list-check" style="margin-right: 6px;"></i>Status Ketidakhadiran</label>
                        <select name="jenis_izin" class="pk-select" required @if(isset($isDutyToday) && !$isDutyToday) disabled @endif>
                            <option value="Sakit">Sakit (Surat Dokter / Ortu)</option>
                            <option value="Izin">Izin (Kepentingan Keluarga)</option>
                        </select>
                    </div>

                    <div class="pk-form-group">
                        <label class="pk-label"><i class="fa-solid fa-calendar-day" style="margin-right: 6px;"></i>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="pk-input" value="{{ date('Y-m-d') }}" required @if(isset($isDutyToday) && !$isDutyToday) disabled @endif>
                    </div>

                    <div class="pk-form-group">
                        <label class="pk-label"><i class="fa-solid fa-calendar-check" style="margin-right: 6px;"></i>Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="pk-input" value="{{ date('Y-m-d') }}" required @if(isset($isDutyToday) && !$isDutyToday) disabled @endif>
                    </div>
                </div>

                <!-- Upload / Foto Surat -->
                <div class="pk-form-group">
                    <label class="pk-label"><i class="fa-solid fa-camera" style="margin-right: 6px;"></i>Foto / Upload Surat Fisik (Orang Tua / Dokter)</label>
                    <div class="pk-upload-box" @if(!isset($isDutyToday) || $isDutyToday) onclick="document.getElementById('foto_input').click()" @else style="opacity: 0.6; cursor: not-allowed;" @endif>
                        <i class="fa-solid fa-cloud-arrow-up pk-upload-icon"></i>
                        <div style="font-weight: 800; font-size: 0.95rem; color: var(--pk-navy);">Klik di sini untuk Memfoto atau Upload Surat</div>
                        <div style="font-size: 0.775rem; color: #64748b; margin-top: 4px;">Format gambar: JPG, PNG, WEBP (Maksimal 5MB)</div>
                        <input type="file" name="foto_surat" id="foto_input" accept="image/*" capture="environment" style="display: none;" onchange="previewImage(this)" @if(isset($isDutyToday) && !$isDutyToday) disabled @endif>
                    </div>
                    <div id="preview_container" style="margin-top: 12px; display: none; text-align: center;">
                        <img id="image_preview" src="" alt="Preview Surat" style="max-height: 200px; border-radius: 12px; border: 1px solid #cbd5e1;">
                    </div>
                </div>

                <button type="submit" class="pk-btn-submit" @if(isset($isDutyToday) && !$isDutyToday) disabled style="opacity: 0.5; cursor: not-allowed; background-color: #94a3b8; box-shadow: none;" @endif>
                    <i class="fa-solid fa-floppy-disk" style="margin-right: 8px;"></i>Simpan & Daftarkan ke Rekap Absensi Kelas
                </button>
            </form>
        </div>
    </main>

    <script>
        function filterSiswaByKelas(kelasId) {
            const siswaGroup = document.getElementById('siswa_group');
            const siswaSelect = document.getElementById('select_siswa');
            const options = siswaSelect.querySelectorAll('option');
            
            siswaSelect.value = '';

            if (!kelasId) {
                siswaGroup.style.display = 'none';
                siswaSelect.removeAttribute('required');
                return;
            }

            siswaGroup.style.display = 'block';
            siswaSelect.setAttribute('required', 'required');
            
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

        document.addEventListener('DOMContentLoaded', function() {
            const kelasSelect = document.getElementById('select_kelas');
            if (kelasSelect) {
                filterSiswaByKelas(kelasSelect.value);
            }
        });

        function previewImage(input) {
            const container = document.getElementById('preview_container');
            const preview = document.getElementById('image_preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
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
