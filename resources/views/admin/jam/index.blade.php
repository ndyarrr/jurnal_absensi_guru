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
            <a href="{{ route('dashboard') }}" class="dash-brand">
                <svg width="42" height="42" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 75C20 70 30 65 50 65C70 65 80 70 80 75V80H20V75Z" fill="#252B3E"/>
                    <path d="M50 20L15 40L50 60L85 40L50 20Z" fill="#D97706"/>
                    <path d="M50 20L25 34.2857V60L50 45.7143V20Z" fill="#F59E0B"/>
                    <path d="M35 65L20 50V70L35 80V65Z" fill="#252B3E"/>
                    <path d="M65 65L80 50V70L65 80V65Z" fill="#252B3E"/>
                    <circle cx="50" cy="50" r="12" fill="#252B3E"/>
                    <path d="M46 50L49 53L55 47" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>

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
        </aside>

        <!-- Main Content Container -->
        <main class="dash-main">
            
            <!-- Top Header Bar -->
            <header class="dash-top-bar">
                <div>
                    <h1 class="dash-header-title">Master Jam Pelajaran & Waktu Pulang</h1>
                    <p class="dash-header-subtitle">Pengaturan Durasi, Jam Masuk & Generator Slot Otomatis</p>
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

                    <div class="dash-user-widget">
                        <div style="display: flex; flex-direction: column; align-items: flex-end; line-height: 1.2;">
                            <span style="font-size: 0.875rem; font-weight: 700; color: #1e2538;">{{ Auth::user()->name ?? 'Administrator' }}</span>
                            <span style="font-size: 0.725rem; font-weight: 600; color: #847e73;">{{ Auth::user()->role_label ?? 'Admin' }}</span>
                        </div>
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="dash-user-avatar">
                        
                        <form action="{{ route('logout') }}" method="POST" style="margin-left: 6px;">
                            @csrf
                            <button type="submit" title="Keluar dari Akun" style="background: none; border: none; cursor: pointer; color: #dc2626; padding: 4px; display: flex; align-items: center;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="dash-content-body" style="padding: 24px;">
                <div id="ajaxAlertContainer"></div>

                @if(session('success'))
                    <div class="flash-alert" style="background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 600; margin-bottom: 16px;">
                        ✅ {{ session('success') }}
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

                    <button type="button" class="btn-jadwal-tambah" onclick="openGeneratorModal()" style="background: linear-gradient(135deg, #10b981, #059669); border: none; color: #fff;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                        <span>⚡ Auto Generate Slot {{ $activeTab }}</span>
                    </button>
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

                    <button type="button" class="action-btn-icon edit" onclick="openSettingModal()" title="Ubah Pengaturan Waktu" style="padding: 8px 14px; width: auto; font-size: 0.85rem; font-weight: 700; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Ubah Waktu Pulang</span>
                    </button>
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
                            <span>💡 <strong>Tip Drag & Drop:</strong> Tarik baris jam (menggunakan ikon ⋮⋮) ke atas / bawah untuk mengatur ulang urutan jam pelajaran & jam istirahat.</span>
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
                                            @if($j->is_istirahat || $j->jam_ke == 0)
                                                <span class="badge-istirahat" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;">☕ {{ $j->keterangan ?? 'Istirahat' }}</span>
                                            @else
                                                <span class="badge-pelajaran" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;">📖 {{ $j->keterangan ?? 'Jam Pelajaran' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-icons-cell" style="justify-content: center; gap: 6px;">
                                                <button type="button" class="action-btn-icon edit" title="Edit Slot Jam / Istirahat" onclick="openEditSlotModal({{ $j->id_jam }}, {{ $j->jam_ke }}, '{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}', '{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}', {{ $j->is_istirahat ? 1 : 0 }}, '{{ addslashes($j->keterangan ?? '') }}')">
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
                                            Klik <strong>⚡ Auto Generate Slot</strong> untuk membangkitkan jam pelajaran otomatis.
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

    <!-- Modal 1: Auto Generator Slot (Supports 2 Istirahat) -->
    <div class="modal-overlay" id="generatorModal" style="display: none;">
        <div class="modal-content-card" style="max-width: 580px;">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">⚡ Auto Generate Slot Jam ({{ $activeTab }})</h3>
                <button type="button" class="btn-close-modal" onclick="closeGeneratorModal()">&times;</button>
            </div>

            <form action="{{ route('jam.generate') }}" method="POST" class="modal-form-grid">
                @csrf
                <input type="hidden" name="hari_kategori" value="{{ $activeTab }}">

                <div class="form-field-group">
                    <label>Jam Masuk Sekolah</label>
                    <input type="time" name="jam_masuk" class="form-field-input" value="{{ \Carbon\Carbon::parse($curSetting->jam_masuk ?? '07:00')->format('H:i') }}" required>
                </div>

                <div class="form-field-group">
                    <label>Jam Pulang Sekolah</label>
                    <input type="time" name="jam_pulang" class="form-field-input" value="{{ \Carbon\Carbon::parse($curSetting->jam_pulang ?? '14:30')->format('H:i') }}" required>
                </div>

                <div class="form-field-group" style="grid-column: span 2;">
                    <label>Durasi 1 Jam Pelajaran (Menit)</label>
                    <input type="number" name="durasi_per_jam" class="form-field-input" value="{{ $activeTab === 'Jumat' ? 30 : 40 }}" required>
                </div>

                <!-- Istirahat 1 -->
                <div class="form-field-group">
                    <label>☕ Istirahat 1 (Menit)</label>
                    <input type="number" name="durasi_istirahat_1" class="form-field-input" value="{{ $activeTab === 'Jumat' ? 15 : 20 }}" required>
                </div>

                <div class="form-field-group">
                    <label>Istirahat 1 Setelah Jam Ke-</label>
                    <input type="number" name="setelah_jam_ke_1" class="form-field-input" value="{{ $activeTab === 'Jumat' ? 3 : 4 }}" required>
                </div>

                <!-- Istirahat 2 (Sholat / Makan) -->
                @if($activeTab !== 'Jumat')
                    <div class="form-field-group">
                        <label>☕ Istirahat 2 / Sholat (Menit)</label>
                        <input type="number" name="durasi_istirahat_2" class="form-field-input" value="30">
                    </div>

                    <div class="form-field-group">
                        <label>Istirahat 2 Setelah Jam Ke-</label>
                        <input type="number" name="setelah_jam_ke_2" class="form-field-input" value="7">
                    </div>
                @endif
                
                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeGeneratorModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit" style="background: #10b981;">Generate Jam Pelajaran</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Edit Waktu Pulang & Pengaturan -->
    <div class="modal-overlay" id="settingModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Edit Waktu Pulang & Durasi ({{ $activeTab }})</h3>
                <button type="button" class="btn-close-modal" onclick="closeSettingModal()">&times;</button>
            </div>

            <form action="{{ route('jam.settings') }}" method="POST" class="modal-form-grid">
                @csrf
                <input type="hidden" name="hari_kategori" value="{{ $activeTab }}">

                <div class="form-field-group">
                    <label>Jam Masuk Sekolah</label>
                    <input type="time" name="jam_masuk" class="form-field-input" value="{{ \Carbon\Carbon::parse($curSetting->jam_masuk ?? '07:00')->format('H:i') }}" required>
                </div>

                <div class="form-field-group">
                    <label>Jam Pulang Sekolah</label>
                    <input type="time" name="jam_pulang" class="form-field-input" value="{{ \Carbon\Carbon::parse($curSetting->jam_pulang ?? '14:30')->format('H:i') }}" required>
                </div>

                <div class="form-field-group">
                    <label>Durasi 1 Jam Pelajaran (Menit)</label>
                    <input type="number" name="durasi_per_jam" class="form-field-input" value="{{ $curSetting->durasi_per_jam ?? 40 }}" required>
                </div>

                <div class="form-field-group">
                    <label>Keterangan Tambahan</label>
                    <input type="text" name="keterangan" class="form-field-input" value="{{ $curSetting->keterangan ?? '' }}" placeholder="Contoh: Hari Reguler Sekolah">
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeSettingModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: Tambah Slot Manual -->
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
                    <label>Jam Ke- (Isi 0 jika Istirahat)</label>
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
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" class="form-field-input" placeholder="Contoh: Jam Ke-1 / Istirahat">
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
                    <label>Jam Ke- (Isi 0 jika Istirahat)</label>
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
                    <input type="text" name="keterangan" id="edit_keterangan" class="form-field-input" placeholder="Contoh: Istirahat 1 / Sholat Dzuhur">
                </div>

                <div class="form-field-group" style="grid-column: span 2; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_istirahat" id="edit_is_istirahat" value="1" style="width: 18px; height: 18px;">
                    <label for="edit_is_istirahat" style="margin: 0; cursor: pointer; font-weight: 700; color: #1e2538;">Tandai Sebagai Jam Istirahat ☕</label>
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditSlotModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit" style="background: #0284c7;">Simpan Perubahan Slot</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSubmenu(id) {
            const el = document.getElementById(id);
            if (el) el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'flex' : 'none';
        }

        function openGeneratorModal() { document.getElementById('generatorModal').style.display = 'flex'; }
        function closeGeneratorModal() { document.getElementById('generatorModal').style.display = 'none'; }

        function openSettingModal() { document.getElementById('settingModal').style.display = 'flex'; }
        function closeSettingModal() { document.getElementById('settingModal').style.display = 'none'; }

        function openCreateSlotModal() { document.getElementById('createSlotModal').style.display = 'flex'; }
        function closeCreateSlotModal() { document.getElementById('createSlotModal').style.display = 'none'; }

        function openEditSlotModal(id, jamKe, jamMulai, jamSelesai, isIstirahat, keterangan) {
            document.getElementById('editSlotForm').action = '/jam-pelajaran/' + id;
            document.getElementById('edit_jam_ke').value = jamKe;
            document.getElementById('edit_jam_mulai').value = jamMulai;
            document.getElementById('edit_jam_selesai').value = jamSelesai;
            document.getElementById('edit_keterangan').value = keterangan;
            document.getElementById('edit_is_istirahat').checked = (isIstirahat == 1);
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
    <script src="/js/live-clock.js"></script>
</body>
</html>
