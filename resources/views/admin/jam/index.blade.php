<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Master Data Jam Pelajaran & Jam Pulang - Jurnal Absensi Guru</title>
    
    <!-- CSS Modules -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/jadwal.css') }}">
    <script src="/js/sidebar-toggle.js"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .jam-tab-btn {
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 700;
            border-radius: 12px;
            border: 1px solid var(--dash-cream-border);
            background: #ffffff;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .jam-tab-btn.active {
            background: var(--dash-navy);
            color: #ffffff;
            border-color: var(--dash-navy);
            box-shadow: 0 4px 12px rgba(35, 41, 59, 0.2);
        }
        .setting-info-card {
            background: linear-gradient(135deg, #f8fafc, #edf2f7);
            border: 1px solid #cbd5e1;
            border-radius: 16px;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .badge-istirahat {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fcd34d;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-pelajaran {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
    </style>
</head>
<body class="dashboard-body">

    <div class="dash-layout">
        
        <!-- ===================================================================
             Left Sidebar Navigation (Fixed / Sticky Position)
             =================================================================== -->
        <aside class="dash-sidebar">
            @include('partials.dash-brand')

            <ul class="dash-menu">
                <!-- Dashboard Item -->
                <li class="dash-menu-item">
                    <a href="{{ route('dashboard') }}" class="dash-menu-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Master Data Category -->
                <li class="dash-menu-category">
                    <button type="button" class="dash-category-btn" onclick="toggleSubmenu('masterDataSub')">
                        <div class="dash-category-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <span>Master Data</span>
                        </div>
                        <svg class="dash-category-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <ul class="dash-sub-menu" id="masterDataSub" style="display: flex;">
                        <li>
                            <a href="{{ route('users.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Pengguna</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('siswa.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                </svg>
                                <span>Siswa</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('guru.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                </svg>
                                <span>Guru</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kelas.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    <path d="M3 9h18M9 21V9"></path>
                                </svg>
                                <span>Kelas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('mapel.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                <span>Mapel</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Akademik Category -->
                <li class="dash-menu-category">
                    <button type="button" class="dash-category-btn" onclick="toggleSubmenu('akademikSub')">
                        <div class="dash-category-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            <span>Akademik</span>
                        </div>
                        <svg class="dash-category-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <ul class="dash-sub-menu" id="akademikSub" style="display: flex;">
                        <li>
                            <a href="{{ route('jadwal.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span>Jadwal Pelajaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jam.index') }}" class="dash-sub-link" style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #ffffff;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span>Jam & Jam Pulang</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jurnal.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <rect x="8" y="2" width="8" height="4" rx="1"></rect>
                                </svg>
                                <span>Jurnal Mengajar</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            @include('partials.dash-sidebar-footer')
        </aside>

        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Main Content Container -->
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
                        <h1 class="dash-header-title">Master Jam Pelajaran & Waktu Pulang</h1>
                        <p class="dash-header-subtitle">Pengaturan Durasi, Jam Masuk & Generator Slot Otomatis</p>
                    </div>
                </div>

                <div class="dash-top-right">
                    <div class="dash-date-widget">
                        <svg class="dash-date-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                        </svg>
                        <div class="dash-date-info">
                            <span class="date-str" id="live_date_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
                            <span class="time-str" id="live_time_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }} WIB</span>
                        </div>
                    </div>

                    @include('partials.dash-user-widget')
                </div>
            </header>

            <div class="dash-content-body" style="padding: 24px;">
                <div id="ajaxAlertContainer"></div>

                @if(session('success'))
                    <div class="flash-alert" style="background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Navigation Tabs (Kelompok Hari) -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('jam.index', ['tab' => 'Senin-Kamis']) }}" class="jam-tab-btn {{ $activeTab === 'Senin-Kamis' ? 'active' : '' }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                            </svg>
                            <span>Senin – Kamis (40 Menit/Jam)</span>
                        </a>

                        <a href="{{ route('jam.index', ['tab' => 'Jumat']) }}" class="jam-tab-btn {{ $activeTab === 'Jumat' ? 'active' : '' }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span>Jumat (30 Menit/Jam)</span>
                        </a>
                    </div>
                </div>

                <!-- Current Setting Summary Card -->
                @php $curSetting = $settings[$activeTab] ?? null; @endphp
                <div class="setting-info-card">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="background: var(--dash-navy); color: #fff; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight: 800; font-size: 1rem; color: #1e2538;">
                                Pengaturan Waktu {{ $activeTab }}
                            </div>
                            <div style="font-size: 0.85rem; color: #64748b; font-weight: 600; margin-top: 2px;">
                                Jam Masuk: <strong>{{ \Carbon\Carbon::parse($curSetting->jam_masuk ?? '07:00')->format('H.i') }} WIB</strong> • 
                                Durasi: <strong>{{ $curSetting->durasi_per_jam ?? 40 }} Menit/Jam</strong> • 
                                <span style="color: #dc2626; font-weight: 800;">Jam Pulang: {{ \Carbon\Carbon::parse($curSetting->jam_pulang ?? '14:30')->format('H.i') }} WIB</span>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="action-btn-icon edit" onclick="toggleSettingForm()" title="Ubah Pengaturan Waktu" style="padding: 8px 14px; width: auto; font-size: 0.85rem; font-weight: 700; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span id="settingBtnText">Ubah Pengaturan Waktu</span>
                    </button>
                </div>

                <!-- Inline Expandable Form (Hide/Unhide) -->
                <div id="settingInlineForm" style="display: none; margin-top: 12px; margin-bottom: 24px; background: #ffffff; border: 1px solid var(--dash-border-subtle); border-radius: 18px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                    <div style="font-weight: 800; font-size: 1rem; color: #1e2538; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
                        <span style="display: flex; align-items: center; gap: 8px;"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg> Form Pengaturan Waktu &amp; Auto Generate Slot ({{ $activeTab }})</span>
                        <button type="button" onclick="toggleSettingForm()" style="background: none; border: none; font-weight: 700; color: #64748b; cursor: pointer; font-size: 0.85rem;">✕ Tutup Form</button>
                    </div>

                    <form action="{{ route('jam.settings') }}" method="POST">
                        @csrf
                        <input type="hidden" name="hari_kategori" value="{{ $activeTab }}">

                        <!-- SECTION 1: Jam Operasional & Durasi KBM -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; margin-bottom: 18px;">
                            <div style="font-size: 0.875rem; font-weight: 800; color: #1e293b; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 16 14"></polyline></svg>
                                    <span>1. Jam Masuk, Jam Pulang & Durasi KBM</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 14px; font-size: 0.8rem; font-weight: 700; color: #475569;">
                                    <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                        <input type="radio" name="mode_durasi_kbm" value="seragam" onchange="toggleKbmMode('seragam')" {{ old('mode_durasi_kbm', $detectedSetting['mode_durasi_kbm'] ?? ($curSetting->mode_durasi_kbm ?? 'seragam')) === 'seragam' ? 'checked' : '' }}>
                                        <span>⏱️ Durasi Sama Semua Jam</span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                        <input type="radio" name="mode_durasi_kbm" value="variatif" onchange="toggleKbmMode('variatif')" {{ old('mode_durasi_kbm', $detectedSetting['mode_durasi_kbm'] ?? ($curSetting->mode_durasi_kbm ?? 'seragam')) === 'variatif' ? 'checked' : '' }}>
                                        <span>📊 Durasi Variatif (Misal Jam 1-4 = 40m, Jam 5+ = 35m)</span>
                                    </label>
                                </div>
                            </div>

                            @if ($errors->has('durasi_per_jam') || $errors->has('durasi_jam_utama') || $errors->has('sampai_jam_ke') || $errors->has('durasi_jam_setelahnya'))
                                <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 10px 14px; border-radius: 8px; font-size: 0.825rem; font-weight: 600; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    <span>{{ $errors->first('durasi_per_jam') ?: ($errors->first('durasi_jam_utama') ?: ($errors->first('sampai_jam_ke') ?: $errors->first('durasi_jam_setelahnya'))) }}</span>
                                </div>
                            @endif

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 12px;">
                                <div class="form-field-group">
                                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Jam Masuk Sekolah</label>
                                    <input type="time" name="jam_masuk" class="form-field-input" value="{{ old('jam_masuk', $detectedSetting['jam_masuk'] ?? \Carbon\Carbon::parse($curSetting->jam_masuk ?? '07:00')->format('H:i')) }}" required>
                                </div>

                                <div class="form-field-group">
                                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Jam Pulang Sekolah</label>
                                    <input type="time" name="jam_pulang" class="form-field-input" value="{{ old('jam_pulang', $detectedSetting['jam_pulang'] ?? \Carbon\Carbon::parse($curSetting->jam_pulang ?? '14:30')->format('H:i')) }}" required>
                                </div>

                                <!-- Mode KBM Seragam Container -->
                                <div id="kbm_seragam_container" class="form-field-group">
                                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Durasi (Menit / Jam KBM)</label>
                                    <input type="number" id="durasi_per_jam_input" name="durasi_per_jam" class="form-field-input" value="{{ old('durasi_per_jam', $detectedSetting['durasi_per_jam'] ?? ($curSetting->durasi_per_jam ?? ($activeTab === 'Jumat' ? 30 : 40))) }}">
                                </div>
                            </div>

                            <!-- Mode KBM Variatif Container -->
                            <div id="kbm_variatif_container" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;">
                                <div class="form-field-group">
                                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Durasi Jam Utama (Menit)</label>
                                    <input type="number" id="durasi_jam_utama_input" name="durasi_jam_utama" class="form-field-input" value="{{ old('durasi_jam_utama', $detectedSetting['durasi_jam_utama'] ?? ($curSetting->durasi_jam_utama ?? 40)) }}" placeholder="Contoh: 40 (Jam 1 s/d X)">
                                </div>
                                <div class="form-field-group">
                                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Sampai Jam Ke-</label>
                                    <input type="number" id="sampai_jam_ke_input" name="sampai_jam_ke" class="form-field-input" value="{{ old('sampai_jam_ke', $detectedSetting['sampai_jam_ke'] ?? ($curSetting->sampai_jam_ke ?? 4)) }}" placeholder="Contoh: 4">
                                </div>
                                <div class="form-field-group">
                                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Durasi Jam Setelahnya (Menit)</label>
                                    <input type="number" id="durasi_jam_setelahnya_input" name="durasi_jam_setelahnya" class="form-field-input" value="{{ old('durasi_jam_setelahnya', $detectedSetting['durasi_jam_setelahnya'] ?? ($curSetting->durasi_jam_setelahnya ?? 35)) }}" placeholder="Contoh: 35 (Jam 5 dst)">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Pengaturan Jam Istirahat -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; margin-bottom: 18px;">
                            <div style="font-size: 0.875rem; font-weight: 800; color: #1e293b; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                                    <span>2. Pengaturan Jam Istirahat</span>
                                </div>
                            </div>

                            @if ($errors->has('durasi_istirahat_1') || $errors->has('setelah_jam_ke_1') || $errors->has('jam_mulai_istirahat_1') || $errors->has('jam_selesai_istirahat_1'))
                                <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 10px 14px; border-radius: 8px; font-size: 0.825rem; font-weight: 600; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    <span>{{ $errors->first('durasi_istirahat_1') ?: ($errors->first('setelah_jam_ke_1') ?: ($errors->first('jam_mulai_istirahat_1') ?: $errors->first('jam_selesai_istirahat_1'))) }}</span>
                                </div>
                            @endif

                            <!-- ISTIRAHAT 1 -->
                            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 14px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                                    <span style="font-size: 0.825rem; font-weight: 800; color: #334155;">Istirahat 1</span>
                                    <div style="display: flex; align-items: center; gap: 14px; font-size: 0.8rem; font-weight: 700; color: #475569;">
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="radio" name="mode_istirahat_1" value="durasi" onchange="toggleBreakMode('1', 'durasi')" {{ old('mode_istirahat_1', $detectedSetting['mode_istirahat_1'] ?? ($curSetting->mode_istirahat_1 ?? 'durasi')) === 'durasi' ? 'checked' : '' }}>
                                            <span>Mode Durasi & Jam Ke-</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="radio" name="mode_istirahat_1" value="pukul" onchange="toggleBreakMode('1', 'pukul')" {{ old('mode_istirahat_1', $detectedSetting['mode_istirahat_1'] ?? ($curSetting->mode_istirahat_1 ?? 'durasi')) === 'pukul' ? 'checked' : '' }}>
                                            <span>Mode Pukul (Jam Mulai - Selesai)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Mode Durasi Fields -->
                                <div id="break_1_durasi_container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                                    <div class="form-field-group">
                                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Istirahat 1 (Durasi Menit) <span style="color: #dc2626;">*</span></label>
                                        <input type="number" id="durasi_istirahat_1_input" name="durasi_istirahat_1" class="form-field-input" value="{{ old('durasi_istirahat_1', $detectedSetting['durasi_istirahat_1'] ?? ($curSetting->durasi_istirahat_1 ?? '')) }}" placeholder="Contoh: 20 (Wajib Isi)">
                                    </div>
                                    <div class="form-field-group">
                                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Istirahat 1 Setelah Jam Ke- <span style="color: #dc2626;">*</span></label>
                                        <input type="number" id="setelah_jam_ke_1_input" name="setelah_jam_ke_1" class="form-field-input" value="{{ old('setelah_jam_ke_1', $detectedSetting['setelah_jam_ke_1'] ?? ($curSetting->setelah_jam_ke_1 ?? '')) }}" placeholder="Contoh: 4 (Wajib Isi)">
                                    </div>
                                </div>

                                <!-- Mode Pukul Fields -->
                                <div id="break_1_pukul_container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                                    <div class="form-field-group">
                                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Jam Mulai Istirahat 1 <span style="color: #dc2626;">*</span></label>
                                        <input type="time" id="jam_mulai_istirahat_1_input" name="jam_mulai_istirahat_1" class="form-field-input" value="{{ old('jam_mulai_istirahat_1', $detectedSetting['jam_mulai_istirahat_1'] ?? ($curSetting->jam_mulai_istirahat_1 ? \Carbon\Carbon::parse($curSetting->jam_mulai_istirahat_1)->format('H:i') : '')) }}">
                                    </div>
                                    <div class="form-field-group">
                                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Jam Selesai Istirahat 1 <span style="color: #dc2626;">*</span></label>
                                        <input type="time" id="jam_selesai_istirahat_1_input" name="jam_selesai_istirahat_1" class="form-field-input" value="{{ old('jam_selesai_istirahat_1', $detectedSetting['jam_selesai_istirahat_1'] ?? ($curSetting->jam_selesai_istirahat_1 ? \Carbon\Carbon::parse($curSetting->jam_selesai_istirahat_1)->format('H:i') : '')) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- ISTIRAHAT 2 -->
                            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">
                                @if ($errors->has('durasi_istirahat_2') || $errors->has('setelah_jam_ke_2') || $errors->has('jam_mulai_istirahat_2') || $errors->has('jam_selesai_istirahat_2'))
                                    <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 10px 14px; border-radius: 8px; font-size: 0.825rem; font-weight: 600; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        <span>{{ $errors->first('durasi_istirahat_2') ?: ($errors->first('setelah_jam_ke_2') ?: ($errors->first('jam_mulai_istirahat_2') ?: $errors->first('jam_selesai_istirahat_2'))) }}</span>
                                    </div>
                                @endif

                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                                    <span style="font-size: 0.825rem; font-weight: 800; color: #334155;">Istirahat 2 <span style="font-weight: 600; color: #64748b;">(Opsional / Tambahan)</span></span>
                                    <div style="display: flex; align-items: center; gap: 14px; font-size: 0.8rem; font-weight: 700; color: #475569;">
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="radio" name="mode_istirahat_2" value="durasi" onchange="toggleBreakMode('2', 'durasi')" {{ old('mode_istirahat_2', $detectedSetting['mode_istirahat_2'] ?? ($curSetting->mode_istirahat_2 ?? 'durasi')) === 'durasi' ? 'checked' : '' }}>
                                            <span>Mode Durasi & Jam Ke-</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="radio" name="mode_istirahat_2" value="pukul" onchange="toggleBreakMode('2', 'pukul')" {{ old('mode_istirahat_2', $detectedSetting['mode_istirahat_2'] ?? ($curSetting->mode_istirahat_2 ?? 'durasi')) === 'pukul' ? 'checked' : '' }}>
                                            <span>Mode Pukul (Jam Mulai - Selesai)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Mode Durasi Fields -->
                                <div id="break_2_durasi_container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                                    <div class="form-field-group">
                                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Istirahat 2 (Durasi Menit)</label>
                                        <input type="number" id="durasi_istirahat_2_input" name="durasi_istirahat_2" class="form-field-input" value="{{ old('durasi_istirahat_2', $detectedSetting['durasi_istirahat_2'] ?? ($curSetting->durasi_istirahat_2 ?? '')) }}" placeholder="Contoh: 30 (Kosongkan jika tidak ada)">
                                    </div>
                                    <div class="form-field-group">
                                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Istirahat 2 Setelah Jam Ke-</label>
                                        <input type="number" id="setelah_jam_ke_2_input" name="setelah_jam_ke_2" class="form-field-input" value="{{ old('setelah_jam_ke_2', $detectedSetting['setelah_jam_ke_2'] ?? ($curSetting->setelah_jam_ke_2 ?? '')) }}" placeholder="Contoh: 7 (Kosongkan jika tidak ada)">
                                    </div>
                                </div>

                                <!-- Mode Pukul Fields -->
                                <div id="break_2_pukul_container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                                    <div class="form-field-group">
                                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Jam Mulai Istirahat 2</label>
                                        <input type="time" id="jam_mulai_istirahat_2_input" name="jam_mulai_istirahat_2" class="form-field-input" value="{{ old('jam_mulai_istirahat_2', $detectedSetting['jam_mulai_istirahat_2'] ?? ($curSetting->jam_mulai_istirahat_2 ? \Carbon\Carbon::parse($curSetting->jam_mulai_istirahat_2)->format('H:i') : '')) }}">
                                    </div>
                                    <div class="form-field-group">
                                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Jam Selesai Istirahat 2</label>
                                        <input type="time" id="jam_selesai_istirahat_2_input" name="jam_selesai_istirahat_2" class="form-field-input" value="{{ old('jam_selesai_istirahat_2', $detectedSetting['jam_selesai_istirahat_2'] ?? ($curSetting->jam_selesai_istirahat_2 ? \Carbon\Carbon::parse($curSetting->jam_selesai_istirahat_2)->format('H:i') : '')) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Keterangan Tambahan -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; margin-bottom: 20px;">
                            <div style="font-size: 0.875rem; font-weight: 800; color: #1e293b; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                <span>3. Informasi & Keterangan Tambahan</span>
                            </div>
                            <div class="form-field-group">
                                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Keterangan Tambahan</label>
                                <input type="text" name="keterangan" class="form-field-input" value="{{ $curSetting->keterangan ?? '' }}" placeholder="Contoh: Hari Reguler Sekolah (1 Jam = 40 Menit)">
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                            <button type="button" onclick="toggleSettingForm()" class="btn-modal-cancel" style="padding: 8px 18px; border-radius: 10px;">Batal</button>
                            <button type="submit" class="btn-modal-submit" style="background: #059669; padding: 10px 24px; border-radius: 10px; font-weight: 700; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 6px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                <span>Simpan & Generate Slot</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Data Table Card -->
                <div class="jadwal-card-container">
                    <div class="jadwal-card-header" style="justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span>Daftar Slot Jam Pelajaran (Hari {{ $activeTab }})</span>
                        </div>

                        <button type="button" class="btn-export-pill" onclick="openCreateSlotModal()" style="font-size: 0.825rem;">
                            + Tambah Slot Manual
                        </button>
                    </div>

                    <div style="padding: 24px; width: 100%; overflow-x: auto;">
                        <div style="margin-bottom: 12px; font-size: 0.8rem; color: #64748b; font-weight: 600; display: flex; align-items: center; justify-content: space-between;">
                            <span style="display: inline-flex; align-items: center; gap: 6px;">
                                <svg width="16" height="16" fill="none" stroke="#eab308" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18h6"></path><path d="M10 22h4"></path><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"></path></svg>
                                <span><strong>Tip Drag & Drop:</strong> Tarik baris jam (menggunakan ikon ⋮⋮) ke atas / bawah untuk mengatur ulang urutan jam pelajaran & jam istirahat.</span>
                            </span>
                            <span style="color: #059669; font-weight: 700;">2 Jam Istirahat Didukung</span>
                        </div>

                        <table class="jadwal-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 5%; text-align: center;">Urut</th>
                                    <th style="width: 10%;">Jam Ke</th>
                                    <th style="width: 30%;">Waktu (Jam Mulai - Selesai)</th>
                                    <th style="width: 15%;">Durasi</th>
                                    <th style="width: 25%;">Tipe / Keterangan</th>
                                    <th style="width: 15%; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="jamTableBody">
                                @forelse($jamList as $j)
                                    @php
                                        $waktuRange = \Carbon\Carbon::parse($j->jam_mulai)->format('H.i') . ' - ' . \Carbon\Carbon::parse($j->jam_selesai)->format('H.i');
                                    @endphp
                                    <tr id="jam-row-{{ $j->id_jam }}" 
                                        data-id="{{ $j->id_jam }}"
                                        draggable="true" 
                                        ondragstart="onDragStartJam(event, {{ $j->id_jam }})"
                                        ondragover="allowDropJam(event)"
                                        ondragleave="leaveDropJam(event)"
                                        ondrop="onDropJam(event, {{ $j->id_jam }})"
                                        style="transition: background-color 0.2s ease;">
                                        
                                        <td style="text-align: center; color: #94a3b8; cursor: grab;" title="Tarik untuk geser urutan jam">
                                            ⋮⋮
                                        </td>
                                        <td style="font-weight: 800; font-size: 0.95rem; color: var(--dash-navy);">
                                            @if($j->is_istirahat || $j->jam_ke == 0)
                                                -
                                            @else
                                                Jam {{ $j->jam_ke }}
                                            @endif
                                        </td>
                                        <td style="font-weight: 700; font-family: monospace; font-size: 0.9rem;">{{ $waktuRange }} WIB</td>
                                        <td style="font-weight: 600; color: #475569;">{{ $j->durasi_menit ?? \Carbon\Carbon::parse($j->jam_mulai)->diffInMinutes(\Carbon\Carbon::parse($j->jam_selesai)) }} Menit</td>
                                        <td>
                                            @if(!$j->bisa_diisi_mapel || $j->is_istirahat || $j->jam_ke == 0)
                                                <span class="badge-istirahat" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; inline-flex; align-items: center; gap: 4px;">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: -2px;"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                                                    <span>{{ $j->keterangan ?? 'Non-KBM / Istirahat' }}</span>
                                                </span>
                                            @else
                                                <span class="badge-pelajaran" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; inline-flex; align-items: center; gap: 4px;">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: -2px;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                                    <span>{{ $j->keterangan ?? 'Jam ke-' . $j->jam_ke }}</span>
                                                </span>
                                            @endif
                                            @if($j->berlaku_hari)
                                                <div style="margin-top: 4px;">
                                                    <span style="background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; padding: 2px 8px; border-radius: 12px; font-weight: 700; font-size: 0.72rem; inline-flex; align-items: center; gap: 3px;">
                                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                                        <span>{{ $j->berlaku_hari }} Saja</span>
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-icons-cell" style="justify-content: center; gap: 6px;">
                                                <button type="button" class="action-btn-icon edit" title="Edit Slot Jam / Istirahat" onclick="openEditSlotModal({{ $j->id_jam }}, {{ $j->jam_ke }}, '{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}', '{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}', {{ $j->is_istirahat ? 1 : 0 }}, {{ $j->bisa_diisi_mapel ? 1 : 0 }}, '{{ addslashes($j->keterangan ?? '') }}', '{{ addslashes($j->berlaku_hari ?? 'Semua Hari') }}')">
                                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                </button>
                                                <form action="{{ route('jam-pelajaran.destroy', $j) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus slot jam ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn-icon delete" title="Hapus Slot">
                                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 40px; color: #847e73;">
                                            Belum ada slot jam pelajaran untuk kelompok hari {{ $activeTab }}.<br>
                                            Klik <strong>Auto Generate Slot</strong> untuk membangkitkan jam pelajaran otomatis.
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



    <!-- Modal 3: Tambah Slot Manual -->
    @php
        $berlakuHariOptions = $activeTab === 'Jumat'
            ? []
            : ['Senin', 'Selasa', 'Rabu', 'Kamis'];
    @endphp
    <div class="modal-overlay" id="createSlotModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Tambah Slot Jam Manual ({{ $activeTab }})</h3>
                <button type="button" class="btn-close-modal" onclick="closeCreateSlotModal()">&times;</button>
            </div>

            <form action="{{ route('jam-pelajaran.store') }}" method="POST" class="modal-form-grid">
                @csrf
                <input type="hidden" name="hari_kategori" value="{{ $activeTab }}">

                <div class="form-field-group">
                    <label>Jam Ke- (Isi 0 jika Istirahat/Apel)</label>
                    <input type="number" name="jam_ke" class="form-field-input" value="1" required>
                </div>

                <div class="form-field-group">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-field-input" required>
                </div>

                <div class="form-field-group">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-field-input" required>
                </div>

                <div class="form-field-group">
                    <label>Tipe / Keterangan</label>
                    <input type="text" name="keterangan" class="form-field-input" placeholder="Contoh: Pembiasaan / Upacara / Jam ke-1">
                </div>

                <div class="form-field-group">
                    <label>Berlaku Pada Hari</label>
                    <select name="berlaku_hari" class="form-field-input">
                        <option value="Semua Hari">Semua Hari (Default)</option>
                        @foreach($berlakuHariOptions as $hari)
                            <option value="{{ $hari }}">Hari {{ $hari }} Saja</option>
                        @endforeach
                    </select>
                    @if($activeTab === 'Jumat')
                        <small style="display: block; margin-top: 6px; color: #64748b; font-weight: 600;">Tab Jumat hanya untuk slot hari Jumat — tidak perlu pilih hari tertentu.</small>
                    @endif
                </div>

                <div class="form-field-group" style="grid-column: span 2; display: flex; align-items: center; gap: 8px; margin-top: 6px;">
                    <input type="hidden" name="bisa_diisi_mapel" value="0">
                    <input type="checkbox" name="bisa_diisi_mapel" id="create_bisa_diisi_mapel" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="create_bisa_diisi_mapel" style="margin: 0; cursor: pointer; font-weight: 700; color: #1e2538;">Izinkan Diisi Mata Pelajaran (KBM)</label>
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeCreateSlotModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Simpan Slot</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 4: Edit Slot Jam / Istirahat -->
    <div class="modal-overlay" id="editSlotModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Edit Slot Jam / Istirahat</h3>
                <button type="button" class="btn-close-modal" onclick="closeEditSlotModal()">&times;</button>
            </div>

            <form id="editSlotForm" method="POST" class="modal-form-grid">
                @csrf
                @method('PUT')

                <div class="form-field-group">
                    <label>Jam Ke- (Isi 0 jika Istirahat/Apel)</label>
                    <input type="number" name="jam_ke" id="edit_jam_ke" class="form-field-input" required>
                </div>

                <div class="form-field-group">
                    <label>Waktu Mulai</label>
                    <input type="time" name="jam_mulai" id="edit_jam_mulai" class="form-field-input" required>
                </div>

                <div class="form-field-group">
                    <label>Waktu Selesai</label>
                    <input type="time" name="jam_selesai" id="edit_jam_selesai" class="form-field-input" required>
                </div>

                <div class="form-field-group">
                    <label>Tipe / Keterangan</label>
                    <input type="text" name="keterangan" id="edit_keterangan" class="form-field-input" placeholder="Contoh: Upacara / Apel / Jam ke-1">
                </div>

                <div class="form-field-group">
                    <label>Berlaku Pada Hari</label>
                    <select name="berlaku_hari" id="edit_berlaku_hari" class="form-field-input">
                        <option value="Semua Hari">Semua Hari (Default)</option>
                        @foreach($berlakuHariOptions as $hari)
                            <option value="{{ $hari }}">Hari {{ $hari }} Saja</option>
                        @endforeach
                    </select>
                    @if($activeTab === 'Jumat')
                        <small style="display: block; margin-top: 6px; color: #64748b; font-weight: 600;">Tab Jumat hanya untuk slot hari Jumat — tidak perlu pilih hari tertentu.</small>
                    @endif
                </div>

                <div class="form-field-group" style="grid-column: span 2; margin-top: 6px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="hidden" name="bisa_diisi_mapel" value="0">
                        <input type="checkbox" name="bisa_diisi_mapel" id="edit_bisa_diisi_mapel" value="1" style="width: 18px; height: 18px; cursor: pointer;">
                        <label for="edit_bisa_diisi_mapel" style="margin: 0; cursor: pointer; font-weight: 700; color: #1e2538;">Izinkan Diisi Mata Pelajaran (KBM)</label>
                    </div>
                    <div style="font-size: 0.73rem; color: #64748b; margin-top: 4px; margin-left: 26px; display: flex; align-items: center; gap: 4px;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <span>Jika dinonaktifkan, slot ini akan ditandai Non-KBM. Jika sebelumnya sudah ada mapel di slot ini, mapel akan diberi tanda warning <span style="color: #dc2626; font-weight: 700; display: inline-flex; align-items: center; gap: 2px;"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> Tertimpa</span> di matriks jadwal agar dapat dipindahkan ke jam lain.</span>
                    </div>
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditSlotModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit" style="background: #0284c7;">Simpan Perubahan Slot</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleKbmMode(mode) {
            const seragamContainer  = document.getElementById('kbm_seragam_container');
            const variatifContainer = document.getElementById('kbm_variatif_container');

            if (!seragamContainer || !variatifContainer) return;

            if (mode === 'seragam') {
                seragamContainer.style.display  = 'block';
                variatifContainer.style.display = 'none';

                const durInput = document.getElementById('durasi_per_jam_input');
                const utmInput = document.getElementById('durasi_jam_utama_input');
                const smpInput = document.getElementById('sampai_jam_ke_input');
                const ljtInput = document.getElementById('durasi_jam_setelahnya_input');

                if (durInput) durInput.required = true;
                if (utmInput) utmInput.required = false;
                if (smpInput) smpInput.required = false;
                if (ljtInput) ljtInput.required = false;
            } else {
                seragamContainer.style.display  = 'none';
                variatifContainer.style.display = 'grid';

                const durInput = document.getElementById('durasi_per_jam_input');
                const utmInput = document.getElementById('durasi_jam_utama_input');
                const smpInput = document.getElementById('sampai_jam_ke_input');
                const ljtInput = document.getElementById('durasi_jam_setelahnya_input');

                if (durInput) durInput.required = false;
                if (utmInput) utmInput.required = true;
                if (smpInput) smpInput.required = true;
                if (ljtInput) ljtInput.required = true;
            }
        }

        function toggleBreakMode(breakNo, mode) {
            const durasiContainer = document.getElementById('break_' + breakNo + '_durasi_container');
            const pukulContainer  = document.getElementById('break_' + breakNo + '_pukul_container');
            
            if (!durasiContainer || !pukulContainer) return;

            if (mode === 'durasi') {
                durasiContainer.style.display = 'grid';
                pukulContainer.style.display  = 'none';
                
                if (breakNo === '1') {
                    const durInput = document.getElementById('durasi_istirahat_1_input');
                    const setInput = document.getElementById('setelah_jam_ke_1_input');
                    const jmMulai  = document.getElementById('jam_mulai_istirahat_1_input');
                    const jmSelesai= document.getElementById('jam_selesai_istirahat_1_input');
                    if (durInput) durInput.required = true;
                    if (setInput) setInput.required = true;
                    if (jmMulai) jmMulai.required = false;
                    if (jmSelesai) jmSelesai.required = false;
                }
            } else {
                durasiContainer.style.display = 'none';
                pukulContainer.style.display  = 'grid';
                
                if (breakNo === '1') {
                    const durInput = document.getElementById('durasi_istirahat_1_input');
                    const setInput = document.getElementById('setelah_jam_ke_1_input');
                    const jmMulai  = document.getElementById('jam_mulai_istirahat_1_input');
                    const jmSelesai= document.getElementById('jam_selesai_istirahat_1_input');
                    if (durInput) durInput.required = false;
                    if (setInput) setInput.required = false;
                    if (jmMulai) jmMulai.required = true;
                    if (jmSelesai) jmSelesai.required = true;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modeKbmInput = document.querySelector('input[name="mode_durasi_kbm"]:checked');
            const mode1Input   = document.querySelector('input[name="mode_istirahat_1"]:checked');
            const mode2Input   = document.querySelector('input[name="mode_istirahat_2"]:checked');
            toggleKbmMode(modeKbmInput ? modeKbmInput.value : 'seragam');
            toggleBreakMode('1', mode1Input ? mode1Input.value : 'durasi');
            toggleBreakMode('2', mode2Input ? mode2Input.value : 'durasi');
        });

        function toggleSubmenu(id) {
            const el = document.getElementById(id);
            if (el) el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'flex' : 'none';
        }

        function toggleSettingForm() {
            const el = document.getElementById('settingInlineForm');
            const btnText = document.getElementById('settingBtnText');
            if (!el) return;

            if (el.style.display === 'none' || el.style.display === '') {
                el.style.display = 'block';
                if (btnText) btnText.innerText = 'Sembunyikan Form';
            } else {
                el.style.display = 'none';
                if (btnText) btnText.innerText = 'Ubah Pengaturan Waktu';
            }
        }

        function openCreateSlotModal() { document.getElementById('createSlotModal').style.display = 'flex'; }
        function closeCreateSlotModal() { document.getElementById('createSlotModal').style.display = 'none'; }

        function openEditSlotModal(id, jamKe, jamMulai, jamSelesai, isIstirahat, bisaDiisiMapel, keterangan, berlakuHari) {
            document.getElementById('editSlotForm').action = '/jam-pelajaran/' + id;
            document.getElementById('edit_jam_ke').value = jamKe;
            document.getElementById('edit_jam_mulai').value = jamMulai;
            document.getElementById('edit_jam_selesai').value = jamSelesai;
            document.getElementById('edit_keterangan').value = keterangan;
            const selectHari = document.getElementById('edit_berlaku_hari');
            if (selectHari) {
                const val = berlakuHari || 'Semua Hari';
                selectHari.value = Array.from(selectHari.options).some(function (o) { return o.value === val; })
                    ? val
                    : 'Semua Hari';
            }
            const chk = document.getElementById('edit_bisa_diisi_mapel');
            if (chk) chk.checked = (bisaDiisiMapel == 1);
            document.getElementById('editSlotModal').style.display = 'flex';
        }

        function closeEditSlotModal() {
            document.getElementById('editSlotModal').style.display = 'none';
        }

        /* ---- Drag & Drop Reorder Jam List ---- */
        let draggedJamId = null;

        function onDragStartJam(event, idJam) {
            draggedJamId = idJam;
            event.dataTransfer.setData('text/plain', idJam.toString());
            event.dataTransfer.effectAllowed = 'move';
        }

        function allowDropJam(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            const tr = event.currentTarget.closest('tr');
            if (tr) tr.style.backgroundColor = '#f1f5f9';
        }

        function leaveDropJam(event) {
            const tr = event.currentTarget.closest('tr');
            if (tr) tr.style.backgroundColor = '';
        }

        function onDropJam(event, targetJamId) {
            event.preventDefault();
            const tr = event.currentTarget.closest('tr');
            if (tr) tr.style.backgroundColor = '';

            const sourceId = event.dataTransfer.getData('text/plain');
            if (!sourceId || sourceId == targetJamId) return;

            const tbody = document.getElementById('jamTableBody');
            const sourceRow = document.getElementById('jam-row-' + sourceId);
            const targetRow = document.getElementById('jam-row-' + targetJamId);

            if (sourceRow && targetRow && tbody) {
                const rows = Array.from(tbody.querySelectorAll('tr[id^="jam-row-"]'));
                const srcIdx = rows.indexOf(sourceRow);
                const tgtIdx = rows.indexOf(targetRow);

                if (srcIdx < tgtIdx) {
                    targetRow.after(sourceRow);
                } else {
                    targetRow.before(sourceRow);
                }

                const newOrder = Array.from(tbody.querySelectorAll('tr[id^="jam-row-"]'))
                                      .map(row => row.getAttribute('data-id'));

                const formData = new FormData();
                newOrder.forEach(id => formData.append('order[]', id));

                fetch('{{ route("jam.reorder") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        setTimeout(() => window.location.reload(), 300);
                    }
                });
            }
        }
    </script>
    <script src="/js/sidebar-toggle.js"></script>
    <script src="/js/live-clock.js"></script>
</body>
</html>
