<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akademik - Jadwal Pelajaran | Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Modular Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <li class="dash-menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
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
                    <ul class="dash-sub-menu" id="masterDataSub">
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
                            <a href="{{ route('jadwal.index') }}" class="dash-sub-link" style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #ffffff;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span>Jadwal Pelajaran</span>
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

        <!-- ===================================================================
             Main Content Region
             =================================================================== -->
        <main class="dash-main">

            <!-- Top Header Bar -->
            <header class="dash-top-bar">
                <div>
                    <h1 class="dash-header-title">Akademik - Jadwal Pelajaran</h1>
                    <p class="dash-header-subtitle">Statistik Jadwal</p>
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

            <!-- Container for AJAX Toast Notifications -->
            <div id="ajaxAlertContainer"></div>

            <!-- Auto-fading Session Flash Alerts -->
            @if(session('success'))
                <div class="flash-alert" style="background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 600; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="flash-alert" style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 600; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- ---------------------------------------------------------------
                 Controls Bar: Real-Time Search & Action Buttons
                 --------------------------------------------------------------- -->
            <div class="jadwal-controls-row">
                <!-- Real-time Search Form -->
                <div style="position: relative; width: 340px;">
                    <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #94a3b8; pointer-events: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="jadwalSearchInput" class="form-field-input" style="padding-left: 42px; border-radius: 14px;" placeholder="Cari Nama Jadwal" value="{{ request('search') }}" autocomplete="off">
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; align-items: center; gap: 12px;">
                    <!-- Kelola Jam & Pulang Button -->
                    <a href="{{ route('jam.index') }}" class="btn-export-pill" style="background: #ffffff; color: var(--dash-navy); border: 1.5px solid var(--dash-navy); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>Kelola Jam & Waktu Pulang</span>
                    </a>

                    <!-- Export Button -->
                    <button type="button" class="btn-export-pill" onclick="showToast('Export data jadwal sedang diunduh...', 'success')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Export</span>
                    </button>

                    <!-- Tambah Jadwal Button -->
                    <button type="button" class="btn-jadwal-tambah" onclick="openCreateModal()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Tambah Jadwal</span>
                    </button>
                </div>
            </div>

            <style>
                .jam-tab-btn {
                    padding: 8px 16px;
                    font-size: 0.85rem;
                    font-weight: 700;
                    border-radius: 12px;
                    border: 1px solid var(--dash-cream-border);
                    background: #ffffff;
                    color: #475569;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                }
                .jam-tab-btn.active {
                    background: var(--dash-navy);
                    color: #ffffff;
                    border-color: var(--dash-navy);
                    box-shadow: 0 4px 12px rgba(35, 41, 59, 0.2);
                }
                .matrix-cell-slot {
                    background-color: #f8fafc;
                    border: 2px dashed #cbd5e1;
                    border-radius: 14px;
                    padding: 8px;
                    vertical-align: top;
                    height: 115px;
                    min-width: 155px;
                    transition: all 0.2s ease;
                }
                .matrix-cell-slot.drop-target-active {
                    background-color: #dcfce7 !important;
                    border-color: #16a34a !important;
                    transform: scale(1.02);
                }
                .matrix-drag-box {
                    background: #ffffff;
                    border: 1.5px solid #cbd5e1;
                    border-left: 4px solid var(--dash-navy);
                    border-radius: 12px;
                    padding: 8px 10px;
                    cursor: grab;
                    user-select: none;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    transition: all 0.2s ease;
                }
                .matrix-drag-box:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
                }
                .matrix-drag-box:active {
                    cursor: grabbing;
                    opacity: 0.8;
                }
                .matrix-empty-target {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 6px;
                    color: #94a3b8;
                    font-size: 0.75rem;
                    font-weight: 700;
                    cursor: pointer;
                    border-radius: 10px;
                    border: 1px dashed #cbd5e1;
                    margin-top: 4px;
                    transition: all 0.2s ease;
                }
                .matrix-empty-target:hover {
                    background: #f1f5f9;
                    color: var(--dash-navy);
                    border-color: var(--dash-navy);
                }
            </style>

            <!-- View Mode Switcher -->
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <div style="display: flex; gap: 8px;">
                    <button type="button" id="btnViewMatrix" class="jam-tab-btn active" onclick="switchJadwalView('matrix')">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                        <span>Matriks Drag & Drop</span>
                    </button>

                    <button type="button" id="btnViewList" class="jam-tab-btn" onclick="switchJadwalView('list')">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3.01" y2="6"></line>
                            <line x1="3" y1="12" x2="3.01" y2="12"></line>
                            <line x1="3" y1="18" x2="3.01" y2="18"></line>
                        </svg>
                        <span>Tabel List</span>
                    </button>
                </div>

                <!-- Matrix Class Filter -->
                <div id="matrixClassFilterWrapper" style="display: flex; align-items: center; gap: 10px;">
                    <label style="font-weight: 700; font-size: 0.85rem; color: #475569;">Filter Kelas Matriks:</label>
                    <select id="matrixClassSelect" class="form-field-input" style="width: 200px; padding: 6px 12px; border-radius: 10px;" onchange="filterMatrixByClass()">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelases as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- ===================================================================
                 MATRIX SCHEDULE VIEW (DRAG & DROP TIMETABLE GRID)
                 =================================================================== -->
            <!-- ===================================================================
                 MATRIX SCHEDULE VIEW (GROUPED BY KELAS)
                 =================================================================== -->
            @php
                $totalStuckCount = 0;
                $stuckDetails = [];
                foreach($allJadwal as $chkJadwal) {
                    $chkKat = ($chkJadwal->hari === 'Jumat') ? 'Jumat' : 'Senin-Kamis';
                    $chkSlot = $jamPelajarans->where('hari_kategori', $chkKat)->where('jam_ke', $chkJadwal->jam_ke)->first();
                    $chkApplies = !$chkSlot || !$chkSlot->berlaku_hari || ($chkSlot->berlaku_hari === 'Semua Hari') || ($chkSlot->berlaku_hari === $chkJadwal->hari);
                    if ($chkSlot && (!$chkSlot->bisa_diisi_mapel || $chkSlot->is_istirahat || $chkJadwal->jam_ke == 0) && $chkApplies) {
                        $totalStuckCount++;
                        $stuckDetails[] = [
                            'id' => $chkJadwal->id_jadwal,
                            'kelas' => optional($kelases->firstWhere('id_kelas', $chkJadwal->id_kelas)),
                            'mapel' => optional($chkJadwal->mapel)->nama_mapel ?? '-',
                            'guru' => optional($chkJadwal->guru)->nama_guru ?? '-',
                            'hari' => $chkJadwal->hari,
                            'jam_ke' => $chkJadwal->jam_ke,
                            'slot_label' => $chkSlot->keterangan ?? ($chkSlot->is_istirahat ? 'Istirahat' : 'Non-KBM'),
                        ];
                    }
                }
            @endphp

            @if($totalStuckCount > 0)
                <div id="stuckBanner" style="margin-bottom: 16px; background: #fff7ed; border: 1px solid #fed7aa; border-left: 5px solid #ea580c; border-radius: 14px; box-shadow: 0 2px 8px rgba(234, 88, 12, 0.08); overflow: hidden;">
                    {{-- Banner Header (always visible) --}}
                    <div style="padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="font-size: 1.4rem; color: #ea580c; display: flex; align-items: center;">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 0.92rem; color: #9a3412;">
                                    Perhatian: Terdapat {{ $totalStuckCount }} Jadwal Mata Pelajaran Tertimpa Slot Non-KBM
                                </div>
                                <div style="font-size: 0.78rem; color: #c2410c; margin-top: 2px;">
                                    Beberapa slot jam pelajaran diubah menjadi Non-KBM namun masih menyimpan data jadwal.
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="toggleStuckDetail()" id="stuckToggleBtn" style="background: #ea580c; color: #fff; border: none; padding: 7px 16px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; white-space: nowrap; transition: background 0.2s ease;">
                            <svg id="stuckToggleIcon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="transition: transform 0.3s ease;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            <span id="stuckToggleText">Lihat Detail</span>
                        </button>
                    </div>

                    {{-- Collapsible Detail Panel --}}
                    <div id="stuckDetailPanel" style="max-height: 0; overflow: hidden; transition: max-height 0.4s ease, opacity 0.3s ease; opacity: 0;">
                        <div style="padding: 0 18px 16px 18px; border-top: 1px solid #fed7aa;">
                            <div style="margin-top: 12px; overflow-x: auto;">
                                <table style="width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.82rem;">
                                    <thead>
                                        <tr style="background: #fdba741a;">
                                            <th style="padding: 8px 12px; text-align: left; font-weight: 800; color: #9a3412; border-bottom: 2px solid #fed7aa; white-space: nowrap;">No</th>
                                            <th style="padding: 8px 12px; text-align: left; font-weight: 800; color: #9a3412; border-bottom: 2px solid #fed7aa; white-space: nowrap;">Kelas</th>
                                            <th style="padding: 8px 12px; text-align: left; font-weight: 800; color: #9a3412; border-bottom: 2px solid #fed7aa; white-space: nowrap;">Hari</th>
                                            <th style="padding: 8px 12px; text-align: left; font-weight: 800; color: #9a3412; border-bottom: 2px solid #fed7aa; white-space: nowrap;">Jam Ke</th>
                                            <th style="padding: 8px 12px; text-align: left; font-weight: 800; color: #9a3412; border-bottom: 2px solid #fed7aa; white-space: nowrap;">Mata Pelajaran</th>
                                            <th style="padding: 8px 12px; text-align: left; font-weight: 800; color: #9a3412; border-bottom: 2px solid #fed7aa; white-space: nowrap;">Guru</th>
                                            <th style="padding: 8px 12px; text-align: left; font-weight: 800; color: #9a3412; border-bottom: 2px solid #fed7aa; white-space: nowrap;">Slot Saat Ini</th>
                                            <th style="padding: 8px 12px; text-align: center; font-weight: 800; color: #9a3412; border-bottom: 2px solid #fed7aa; white-space: nowrap;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stuckDetails as $idx => $stuck)
                                            <tr style="border-bottom: 1px solid #fde68a; {{ $idx % 2 === 0 ? 'background: #fffbeb;' : 'background: #fff;' }}">
                                                <td style="padding: 8px 12px; font-weight: 700; color: #92400e;">{{ $idx + 1 }}</td>
                                                <td style="padding: 8px 12px; font-weight: 700; color: #1e293b;">
                                                    @if($stuck['kelas'])
                                                        {{ $stuck['kelas']->tingkat ?? '' }} {{ optional($stuck['kelas']->jurusan)->kode_jurusan ?? '' }} {{ $stuck['kelas']->rombel ?? '' }}
                                                    @else
                                                        <span style="color: #94a3b8;">-</span>
                                                    @endif
                                                </td>
                                                <td style="padding: 8px 12px; color: #475569; font-weight: 600;">{{ $stuck['hari'] }}</td>
                                                <td style="padding: 8px 12px; color: #475569; font-weight: 600;">{{ $stuck['jam_ke'] }}</td>
                                                <td style="padding: 8px 12px; font-weight: 700; color: #dc2626;">{{ $stuck['mapel'] }}</td>
                                                <td style="padding: 8px 12px; color: #475569; font-weight: 600;">{{ $stuck['guru'] }}</td>
                                                <td style="padding: 8px 12px;">
                                                    <span style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">{{ $stuck['slot_label'] }}</span>
                                                </td>
                                                <td style="padding: 8px 12px; text-align: center;">
                                                    <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                                        <button type="button" class="action-btn-icon edit" title="Edit / Pindahkan" onclick="scrollToStuckCard({{ $stuck['id'] }})" style="width: 28px; height: 28px; border-radius: 6px; padding: 0;">
                                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <circle cx="11" cy="11" r="8"></circle>
                                                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                            </svg>
                                                        </button>
                                                        <button type="button" class="action-btn-icon delete" title="Hapus Jadwal" onclick="deleteJadwalAjax({{ $stuck['id'] }})" style="width: 28px; height: 28px; border-radius: 6px; padding: 0;">
                                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="margin-top: 10px; font-size: 0.75rem; color: #c2410c; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                Klik ikon <strong>cari</strong> untuk scroll ke kartu jadwal di matrix, atau <strong>hapus</strong> untuk menghapus jadwal yang tertimpa.
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div id="matrixViewCard" style="margin-bottom: 24px;">
                @foreach($kelases as $kelas)
                    <div class="kelas-matrix-block" id="matrix-kelas-block-{{ $kelas->id_kelas }}" style="margin-bottom: 24px; background: #ffffff; border: 1px solid var(--dash-border-subtle); border-radius: 18px; padding: 20px; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
                        <!-- Class Header Banner -->
                        <div style="background: linear-gradient(135deg, var(--dash-navy), #3b82f6); color: #ffffff; border-radius: 12px; padding: 12px 18px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    <path d="M3 9h18M9 21V9"></path>
                                </svg>
                                <div>
                                    <div style="font-weight: 800; font-size: 1.05rem;">Kelas {{ $kelas->tingkat }} {{ optional($kelas->jurusan)->kode_jurusan }} {{ $kelas->rombel }}</div>
                                    <small style="opacity: 0.85; font-weight: 600;">Wali Kelas: {{ $kelas->wali_kelas ?: (optional($kelas->waliKelas)->nama_guru ?? 'Belum Diatur') }}</small>
                                </div>
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 700; background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px;">{{ $allJadwal->where('id_kelas', $kelas->id_kelas)->count() }} Mapel Terjadwal</span>
                        </div>

                        <div style="overflow-x: auto;">
                            <table class="matrix-grid-table" style="width: 100%; border-collapse: separate; border-spacing: 8px;">
                                <thead>
                                    <tr>
                                        <th style="width: 130px; background: #334155; color: #fff; border-radius: 10px; padding: 10px; font-weight: 800; font-size: 0.85rem;">Jam \ Hari</th>
                                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hName)
                                            <th style="background: #334155; color: #fff; border-radius: 10px; padding: 10px; font-weight: 800; font-size: 0.85rem;">{{ $hName }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // === BUILD UNIFIED ROW LIST ===
                                        $slotsSK = $jamPelajarans->where('hari_kategori', 'Senin-Kamis')->sortBy('jam_mulai');
                                        $slotsJM = $jamPelajarans->where('hari_kategori', 'Jumat')->sortBy('jam_mulai');

                                        $matrixRows = collect();

                                        // 1) KBM rows: group by jam_ke (>0), match SK & JM by jam_ke
                                        $allJamKe = $jamPelajarans->where('jam_ke', '>', 0)->pluck('jam_ke')->unique()->sort();
                                        foreach ($allJamKe as $jk) {
                                            $sk = $slotsSK->where('jam_ke', $jk)->first();
                                            $jm = $slotsJM->where('jam_ke', $jk)->first();
                                            $sortTime = $sk ? $sk->jam_mulai : ($jm ? $jm->jam_mulai : '99:99');
                                            $matrixRows->push([
                                                'jam_ke' => $jk, 'sk' => $sk, 'jm' => $jm, 
                                                'time' => $sortTime, 'is_break' => false
                                            ]);
                                        }

                                        // 2) Break/Istirahat rows: jam_ke=0, pair SK & JM by position
                                        $breaksSK = $slotsSK->where('jam_ke', 0)->values();
                                        $breaksJM = $slotsJM->where('jam_ke', 0)->values();
                                        $maxBreaks = max($breaksSK->count(), $breaksJM->count());
                                        for ($bi = 0; $bi < $maxBreaks; $bi++) {
                                            $bsk = $breaksSK->get($bi);
                                            $bjm = $breaksJM->get($bi);
                                            $sortTime = $bsk ? $bsk->jam_mulai : ($bjm ? $bjm->jam_mulai : '99:99');
                                            $matrixRows->push([
                                                'jam_ke' => 0, 'sk' => $bsk, 'jm' => $bjm,
                                                'time' => $sortTime, 'is_break' => true,
                                                'label' => ($bsk ? $bsk->keterangan : ($bjm ? $bjm->keterangan : null)) ?? 'Istirahat'
                                            ]);
                                        }

                                        // Sort all rows by time
                                        $matrixRows = $matrixRows->sortBy('time')->values();
                                    @endphp

                                    @foreach($matrixRows as $row)
                                        @php
                                            $slotSK = $row['sk'];
                                            $slotJM = $row['jm'];
                                            $isBreakRow = $row['is_break'];
                                            $rowJamKe = $row['jam_ke'];

                                            $skRange = $slotSK ? (\Carbon\Carbon::parse($slotSK->jam_mulai)->format('H.i') . '-' . \Carbon\Carbon::parse($slotSK->jam_selesai)->format('H.i')) : '-';
                                            $jmRange = $slotJM ? (\Carbon\Carbon::parse($slotJM->jam_mulai)->format('H.i') . '-' . \Carbon\Carbon::parse($slotJM->jam_selesai)->format('H.i')) : '-';

                                            // Row header label
                                            $rowLabel = $isBreakRow 
                                                ? ($row['label'] ?? 'Istirahat')
                                                : 'Jam ' . $rowJamKe;
                                        @endphp
                                        <tr>
                                            {{-- Row header (Jam label + time ranges) --}}
                                            <td style="background: {{ $isBreakRow ? '#fef3c7' : '#f8fafc' }}; border-radius: 10px; padding: 8px; vertical-align: middle; text-align: center; border: 1px solid {{ $isBreakRow ? '#fde68a' : '#e2e8f0' }};">
                                                <div style="font-weight: 800; font-size: 0.85rem; color: {{ $isBreakRow ? '#92400e' : 'var(--dash-navy)' }};">{{ $rowLabel }}</div>
                                                <div style="font-size: 0.68rem; color: #64748b; font-weight: 600;">S-K: {{ $skRange }}</div>
                                                <div style="font-size: 0.68rem; color: #0284c7; font-weight: 700;">Jmt: {{ $jmRange }}</div>
                                            </td>

                                            {{-- Day columns --}}
                                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hName)
                                                @php
                                                    // Pick correct slot per day
                                                    $slot = ($hName === 'Jumat') ? $slotJM : $slotSK;

                                                    // Check if Non-KBM setting applies to this specific day
                                                    $appliesToThisDay = !$slot || !$slot->berlaku_hari || ($slot->berlaku_hari === 'Semua Hari') || ($slot->berlaku_hari === $hName);

                                                    // Break row = always Non-KBM
                                                    // Normal row = check slot's bisa_diisi_mapel + berlaku_hari
                                                    $isNonKbm = $isBreakRow 
                                                        || ($slot && (!$slot->bisa_diisi_mapel || $slot->is_istirahat) && $appliesToThisDay);
                                                    $noSlot = !$slot;

                                                    // Jadwal lookup (only for KBM cells)
                                                    $jItem = (!$noSlot && !$isNonKbm && $rowJamKe > 0) 
                                                        ? $allJadwal->where('id_kelas', $kelas->id_kelas)->where('hari', $hName)->where('jam_ke', $rowJamKe)->first() 
                                                        : null;

                                                    // Stuck schedule lookup (for Non-KBM cells where DB still has a schedule entry)
                                                    $stuckJadwal = ($isNonKbm && $rowJamKe > 0)
                                                        ? $allJadwal->where('id_kelas', $kelas->id_kelas)->where('hari', $hName)->where('jam_ke', $rowJamKe)->first()
                                                        : null;
                                                @endphp

                                                @if($noSlot)
                                                    {{-- No slot for this day --}}
                                                    <td style="background: #f1f5f9; border: 1px dashed #cbd5e1; border-radius: 10px; padding: 6px; text-align: center; vertical-align: middle;">
                                                        <div style="font-size: 0.72rem; color: #94a3b8; font-weight: 600;">—</div>
                                                    </td>
                                                @elseif($isNonKbm)
                                                    @if($stuckJadwal)
                                                        {{-- Non-KBM cell WITH stuck schedule warning --}}
                                                        <td style="background: #fff7ed; border: 1.5px dashed #f97316; border-radius: 10px; padding: 6px; text-align: center; vertical-align: middle; position: relative;">
                                                            <div style="font-size: 0.76rem; font-weight: 800; color: #9a3412; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                                                                <span>{{ $slot->keterangan ?? ($isBreakRow ? 'Istirahat' : 'Non-KBM') }}</span>
                                                            </div>
                                                            <div style="font-size: 0.65rem; color: #ea580c; font-weight: 700; margin-bottom: 4px;">(Slot Non-KBM)</div>

                                                            {{-- Warning Draggable Card --}}
                                                            <div class="matrix-drag-box" 
                                                                 id="drag-jadwal-{{ $stuckJadwal->id_jadwal }}"
                                                                 draggable="true" 
                                                                 ondragstart="onDragStartJadwal(event, {{ $stuckJadwal->id_jadwal }})"
                                                                 style="background-color: #fef2f2; border: 1px solid #fca5a5; border-left: 4px solid #dc2626; text-align: left; cursor: grab;"
                                                                 title="Geser (drag & drop) untuk memindahkan ke slot KBM kosong">
                                                                
                                                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 4px;">
                                                                    <span style="font-size: 0.68rem; font-weight: 800; color: #dc2626; background: #fee2e2; padding: 1px 5px; border-radius: 4px; display: inline-flex; align-items: center; gap: 3px;">
                                                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                                                        <span>Tertimpa</span>
                                                                    </span>
                                                                    <div style="display: flex; gap: 3px;">
                                                                        <button type="button" class="action-btn-icon edit" title="Pindahkan / Edit Jadwal" onclick="openEditModal({{ $stuckJadwal->id_jadwal }}, '{{ $stuckJadwal->id_kelas }}', '{{ $stuckJadwal->hari }}', '{{ $stuckJadwal->jam_ke }}', '{{ $stuckJadwal->id_guru }}', '{{ $stuckJadwal->id_mapel }}', '{{ addslashes($stuckJadwal->ruangan ?? '') }}')" style="width: 22px; height: 22px; border-radius: 4px; padding: 0;">
                                                                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                                            </svg>
                                                                        </button>
                                                                        <button type="button" class="action-btn-icon delete" title="Hapus Jadwal" onclick="deleteJadwalAjax({{ $stuckJadwal->id_jadwal }})" style="width: 22px; height: 22px; border-radius: 4px; padding: 0;">
                                                                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div style="font-size: 0.8rem; font-weight: 800; color: #991b1b; margin-top: 4px;">{{ optional($stuckJadwal->mapel)->nama_mapel ?? '-' }}</div>
                                                                <div style="font-size: 0.68rem; color: #7f1d1d; font-weight: 700; margin-top: 2px;">{{ optional($stuckJadwal->guru)->nama_guru ?? '-' }}</div>
                                                            </div>
                                                        </td>
                                                    @else
                                                        {{-- Standard Non-KBM cell --}}
                                                        <td style="background: #fef3c7; border: 1px dashed #fcd34d; border-radius: 10px; padding: 6px; text-align: center; vertical-align: middle;">
                                                            <div style="font-size: 0.78rem; font-weight: 800; color: #92400e; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                                                                <span>{{ $slot->keterangan ?? ($isBreakRow ? 'Istirahat' : 'Non-KBM') }}</span>
                                                            </div>
                                                            <div style="font-size: 0.65rem; color: #b45309; font-weight: 700;">(Tidak Ada Mapel)</div>
                                                        </td>
                                                    @endif
                                                @else
                                                    {{-- Normal KBM slot --}}
                                                    <td class="matrix-cell-slot" 
                                                        id="cell-{{ $kelas->id_kelas }}-{{ $hName }}-{{ $rowJamKe }}"
                                                        data-kelas="{{ $kelas->id_kelas }}"
                                                        data-hari="{{ $hName }}" 
                                                        data-jam="{{ $rowJamKe }}"
                                                        ondragover="allowDropJadwal(event)" 
                                                        ondragleave="leaveDropJadwal(event)" 
                                                        ondrop="onDropJadwal(event, {{ $kelas->id_kelas }}, '{{ $hName }}', {{ $rowJamKe }})">
                                                        
                                                        @if($jItem)
                                                            <div class="matrix-drag-box" 
                                                                 id="drag-jadwal-{{ $jItem->id_jadwal }}"
                                                                 draggable="true" 
                                                                 ondragstart="onDragStartJadwal(event, {{ $jItem->id_jadwal }})"
                                                                 style="background-color: #e0f2fe; border-left-color: #0284c7;">
                                                                
                                                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                                                    <span style="font-size: 0.85rem; font-weight: 800; color: #1e2538;">{{ optional($jItem->mapel)->nama_mapel ?? '-' }}</span>
                                                                    <div style="display: flex; gap: 4px;">
                                                                        <button type="button" class="action-btn-icon edit" title="Edit Jadwal" onclick="openEditModal({{ $jItem->id_jadwal }}, '{{ $jItem->id_kelas }}', '{{ $jItem->hari }}', '{{ $jItem->jam_ke }}', '{{ $jItem->id_guru }}', '{{ $jItem->id_mapel }}', '{{ addslashes($jItem->ruangan ?? '') }}')" style="width: 24px; height: 24px; border-radius: 6px; padding: 0;">
                                                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                                            </svg>
                                                                        </button>
                                                                        <button type="button" class="action-btn-icon delete" title="Hapus Jadwal" onclick="deleteJadwalAjax({{ $jItem->id_jadwal }})" style="width: 24px; height: 24px; border-radius: 6px; padding: 0;">
                                                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 6px;">
                                                                    <span style="font-size: 0.72rem; color: #475569; font-weight: 700;">{{ optional($jItem->guru)->nama_guru ?? '-' }}</span>
                                                                    @if($jItem->ruangan)
                                                                        <span style="font-size: 0.68rem; font-weight: 800; color: #059669; background: #ffffff; border: 1px solid #a7f3d0; padding: 1px 6px; border-radius: 8px;">{{ $jItem->ruangan }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="matrix-empty-target" onclick="openCreateModalPrefilled({{ $kelas->id_kelas }}, '{{ $hName }}', {{ $rowJamKe }})" title="Klik untuk menambah jadwal di slot kosong ini">
                                                                <span>+ Isi Slot</span>
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- ---------------------------------------------------------------
                 Split Layout Card: Left Timeline ("Jadwal Hari Ini") vs Right Table (List View)
                 --------------------------------------------------------------- -->
            <div class="jadwal-card-container" id="listViewCard" style="display: none;">
                <!-- Navy Header Title -->
                <div class="jadwal-card-header">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Jadwal Hari ini ({{ $todayDayName }})</span>
                </div>

                <!-- Main Split Body -->
                <div class="jadwal-card-body">
                    
                    <!-- LEFT COLUMN: Real-Time Timeline Schedule -->
                    <div class="jadwal-timeline-column">
                        @if($todayJadwal->count() > 0)
                            <div class="timeline-container">
                                <div class="timeline-line"></div>
                                @php
                                    $borderColors = ['border-red', 'border-amber', 'border-green', 'border-cyan', 'border-purple'];
                                    $roomColors   = ['room-red', 'room-amber', 'room-green', 'room-cyan', 'room-purple'];
                                @endphp

                                @foreach($todayJadwal as $idx => $tItem)
                                    @php
                                        $cIndex = $idx % count($borderColors);
                                        $timeStr = '07.30 - 08.15';
                                        if ($tItem->jamPelajaran) {
                                            $timeStr = \Carbon\Carbon::parse($tItem->jamPelajaran->jam_mulai)->format('H.i') . ' - ' . \Carbon\Carbon::parse($tItem->jamPelajaran->jam_selesai)->format('H.i');
                                        }
                                        $isTMapelDel = !$tItem->mapel || $tItem->mapel->trashed();
                                        $isTKelasDel = !$tItem->kelas || $tItem->kelas->trashed();
                                        $isTGuruDel  = !$tItem->guru || $tItem->guru->trashed();
                                    @endphp
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-time-str">{{ $timeStr }}</div>
                                        
                                        <div class="timeline-card-box {{ $borderColors[$cIndex] }}">
                                            <div class="timeline-card-title">
                                                @if($isTMapelDel)
                                                    <span class="badge-warning-deleted" title="Mata pelajaran ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>
                                                @else
                                                    {{ $tItem->mapel->nama_mapel }}
                                                @endif
                                            </div>
                                            <div class="timeline-card-meta">
                                                <span>
                                                    Kelas: 
                                                    @if($isTKelasDel)
                                                        <span class="badge-warning-deleted" title="Kelas ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>
                                                    @else
                                                        {{ $tItem->kelas->tingkat }} {{ optional($tItem->kelas->jurusan)->kode_jurusan }} {{ $tItem->kelas->rombel }}
                                                    @endif
                                                </span>
                                                <span>
                                                    Guru: 
                                                    @if($isTGuruDel)
                                                        <span class="badge-warning-deleted" title="Guru ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>
                                                    @else
                                                        {{ $tItem->guru->nama_guru }}
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="timeline-card-room {{ $roomColors[$cIndex] }}">
                                                {{ $tItem->ruangan ?: '-' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Real-time Empty State when today has no schedule -->
                            <div class="timeline-empty-box">
                                <svg width="48" height="48" fill="none" stroke="#94a3b8" stroke-width="1.8" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <div>
                                    @if(in_array($todayDayName, ['Sabtu', 'Minggu']))
                                        <div style="font-weight: 800; font-size: 1rem; color: #334155; margin-bottom: 4px;">Hari Libur Akhir Pekan</div>
                                        <small style="color: #64748b; font-weight: 600;">Hari ini ({{ $todayDayName }}) tidak ada kegiatan belajar mengajar.</small>
                                    @else
                                        <div style="font-weight: 800; font-size: 1rem; color: #334155; margin-bottom: 4px;">Tidak Ada Jadwal Hari Ini</div>
                                        <small style="color: #64748b; font-weight: 600;">Hari ini ({{ $todayDayName }}) belum ada jadwal pelajaran berlangsung.</small>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- RIGHT COLUMN: Full Schedule Data Table & AJAX Filters -->
                    <div class="jadwal-table-column" data-ajax-pagination="main">
                        <!-- Table Filter Dropdown Bar (No Page Refresh) -->
                        <div class="table-filter-bar">
                            <!-- Filter Hari -->
                            <div style="position: relative;">
                                <button type="button" class="btn-jadwal-filter-pill" onclick="toggleDropdown('filterHariMenu')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                    </svg>
                                    <span id="filterHariLabel">{{ request('hari') ? 'Hari ' . request('hari') : 'Pilih Hari' }}</span>
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                                <div id="filterHariMenu" style="display: none; position: absolute; right: 0; top: 40px; background: #ffffff; border: 1px solid var(--dash-cream-border); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 8px; width: 140px; z-index: 50;">
                                    <button type="button" onclick="selectFilter('hari', '', 'Pilih Hari')" style="width: 100%; text-align: left; background: none; border: none; padding: 6px 10px; font-size: 0.8rem; font-weight: 600; color: #334155; cursor: pointer; border-radius: 6px;">Semua Hari</button>
                                    @foreach($hariList as $h)
                                        <button type="button" onclick="selectFilter('hari', '{{ $h }}', 'Hari {{ $h }}')" style="width: 100%; text-align: left; background: none; border: none; padding: 6px 10px; font-size: 0.8rem; font-weight: 600; color: #334155; cursor: pointer; border-radius: 6px;">{{ $h }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Filter Kelas -->
                            <div style="position: relative;">
                                <button type="button" class="btn-jadwal-filter-pill" onclick="toggleDropdown('filterKelasMenu')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                    </svg>
                                    <span id="filterKelasLabel">Pilih Kelas</span>
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                                <div id="filterKelasMenu" style="display: none; position: absolute; right: 0; top: 40px; background: #ffffff; border: 1px solid var(--dash-cream-border); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 8px; width: 160px; z-index: 50; max-height: 200px; overflow-y: auto;">
                                    <button type="button" onclick="selectFilter('id_kelas', '', 'Pilih Kelas')" style="width: 100%; text-align: left; background: none; border: none; padding: 6px 10px; font-size: 0.8rem; font-weight: 600; color: #334155; cursor: pointer; border-radius: 6px;">Semua Kelas</button>
                                    @foreach($kelases as $k)
                                        <button type="button" onclick="selectFilter('id_kelas', '{{ $k->id_kelas }}', '{{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}')" style="width: 100%; text-align: left; background: none; border: none; padding: 6px 10px; font-size: 0.8rem; font-weight: 600; color: #334155; cursor: pointer; border-radius: 6px;">{{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Filter Mapel -->
                            <div style="position: relative;">
                                <button type="button" class="btn-jadwal-filter-pill" onclick="toggleDropdown('filterMapelMenu')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                    <span id="filterMapelLabel">Pilih Mapel</span>
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                                <div id="filterMapelMenu" style="display: none; position: absolute; right: 0; top: 40px; background: #ffffff; border: 1px solid var(--dash-cream-border); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 8px; width: 180px; z-index: 50; max-height: 200px; overflow-y: auto;">
                                    <button type="button" onclick="selectFilter('id_mapel', '', 'Pilih Mapel')" style="width: 100%; text-align: left; background: none; border: none; padding: 6px 10px; font-size: 0.8rem; font-weight: 600; color: #334155; cursor: pointer; border-radius: 6px;">Semua Mapel</button>
                                    @foreach($mapels as $mp)
                                        <button type="button" onclick="selectFilter('id_mapel', '{{ $mp->id_mapel }}', '{{ $mp->nama_mapel }}')" style="width: 100%; text-align: left; background: none; border: none; padding: 6px 10px; font-size: 0.8rem; font-weight: 600; color: #334155; cursor: pointer; border-radius: 6px;">{{ $mp->nama_mapel }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Data Table -->
                        <div class="table-responsive-clean">
                            <table class="jadwal-table" id="jadwalMainTable">
                                <thead>
                                    <tr>
                                        <th style="width: 18%;">Waktu</th>
                                        <th style="width: 20%;">Mapel</th>
                                        <th style="width: 15%;">Kelas</th>
                                        <th style="width: 25%;">Guru</th>
                                        <th style="width: 12%;">Ruangan</th>
                                        <th style="width: 10%; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jadwal as $j)
                                        @php
                                            $jamObj = $j->jamPelajaran;
                                            if (!$jamObj && $j->jam_ke) {
                                                $kat = ($j->hari === 'Jumat') ? 'Jumat' : 'Senin-Kamis';
                                                $jamObj = $jamPelajarans->where('hari_kategori', $kat)->where('jam_ke', $j->jam_ke)->first();
                                            }
                                            $waktuRange = '-';
                                            if ($jamObj) {
                                                $waktuRange = \Carbon\Carbon::parse($jamObj->jam_mulai)->format('H.i') . '-' . \Carbon\Carbon::parse($jamObj->jam_selesai)->format('H.i');
                                            }
                                            $isMapelDel = !$j->mapel || $j->mapel->trashed();
                                            $isKelasDel = !$j->kelas || $j->kelas->trashed();
                                            $isGuruDel  = !$j->guru || $j->guru->trashed();
                                        @endphp
                                        <tr id="row-jadwal-{{ $j->id_jadwal }}">
                                            <td>{{ $waktuRange }}</td>
                                            <td style="font-weight: 700;">
                                                @if($isMapelDel)
                                                    <span class="badge-warning-deleted" title="Mata pelajaran ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>
                                                @else
                                                    {{ $j->mapel->nama_mapel }}
                                                @endif
                                            </td>
                                            <td style="font-weight: 700;">
                                                @if($isKelasDel)
                                                    <span class="badge-warning-deleted" title="Kelas ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>
                                                @else
                                                    {{ $j->kelas->tingkat }} {{ optional($j->kelas->jurusan)->kode_jurusan }} {{ $j->kelas->rombel }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($isGuruDel)
                                                    <span class="badge-warning-deleted" title="Guru ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>
                                                @else
                                                    {{ $j->guru->nama_guru }}
                                                @endif
                                            </td>
                                            <td style="font-weight: 700; color: #475569;">{{ $j->ruangan ?: '-' }}</td>
                                            <td>
                                                <div class="action-icons-cell">
                                                    <!-- View Detail Action -->
                                                    <button type="button" class="action-btn-icon view" title="Lihat Detail Jadwal" onclick="openViewModal({{ $j->id_jadwal }})">
                                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </button>

                                                    <!-- Edit Action -->
                                                    <button type="button" class="action-btn-icon edit" title="Edit Jadwal" onclick="openEditModal({{ $j->id_jadwal }}, '{{ $j->id_kelas }}', '{{ $j->hari }}', '{{ $j->jam_ke }}', '{{ $j->id_guru }}', '{{ $j->id_mapel }}', '{{ addslashes($j->ruangan ?? '') }}')">
                                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                    </button>

                                                    <!-- Delete Action (AJAX) -->
                                                    <button type="button" class="action-btn-icon delete" title="Hapus Jadwal" onclick="deleteJadwalAjax({{ $j->id_jadwal }})">
                                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 30px; color: #847e73;">
                                                Belum ada data jadwal pelajaran.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer Pagination Row -->
                        <div class="table-pagination-row">
                            <span class="pagination-summary-text" id="paginationSummary">
                                Menampilkan {{ $jadwal->firstItem() ?? 0 }} - {{ $jadwal->lastItem() ?? 0 }} dari {{ $jadwal->total() }} data
                            </span>

                            <div class="pagination-nav-group" id="paginationLinks">
                                {{ $jadwal->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </main>

    </div>

    <!-- ===================================================================
         Create Jadwal Modal Popup (AJAX Submit)
         =================================================================== -->
    <div class="modal-overlay" id="createModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Tambah Jadwal Pelajaran</h3>
                <button type="button" class="btn-close-modal" onclick="closeCreateModal()">&times;</button>
            </div>

            <div id="createModalAlert"></div>

            <form id="createJadwalForm" class="modal-form-grid">
                @csrf
                <div class="form-field-group">
                    <label for="create_id_kelas">Kelas</label>
                    <select name="id_kelas" id="create_id_kelas" class="form-field-input" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelases as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="create_hari">Hari</label>
                    <select name="hari" id="create_hari" class="form-field-input" required>
                        <option value="">-- Pilih Hari --</option>
                        @foreach($hariList as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="create_jam_ke">Jam Pelajaran</label>
                    <select name="jam_ke" id="create_jam_ke" class="form-field-input" required>
                        <option value="">-- Pilih Hari Terlebih Dahulu --</option>
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="create_id_mapel">Mata Pelajaran</label>
                    <select name="id_mapel" id="create_id_mapel" class="form-field-input" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($mapels as $mp)
                            <option value="{{ $mp->id_mapel }}">{{ $mp->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="create_id_guru">Guru Pengajar</label>
                    <select name="id_guru" id="create_id_guru" class="form-field-input" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($gurus as $g)
                            <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="create_ruangan">Ruangan (Opsional)</label>
                    <input type="text" name="ruangan" id="create_ruangan" class="form-field-input" placeholder="Contoh: Lab 1, R. 12 (kosongkan jika tidak ada)">
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeCreateModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================
         Edit Jadwal Modal Popup (AJAX Submit)
         =================================================================== -->
    <div class="modal-overlay" id="editModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Edit Jadwal Pelajaran</h3>
                <button type="button" class="btn-close-modal" onclick="closeEditModal()">&times;</button>
            </div>

            <div id="editModalAlert"></div>

            <form id="editJadwalForm" class="modal-form-grid">
                @csrf
                <input type="hidden" id="edit_id_jadwal" name="id_jadwal">

                <div class="form-field-group">
                    <label for="edit_id_kelas">Kelas</label>
                    <select name="id_kelas" id="edit_id_kelas" class="form-field-input" required>
                        @foreach($kelases as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="edit_hari">Hari</label>
                    <select name="hari" id="edit_hari" class="form-field-input" required>
                        @foreach($hariList as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="edit_jam_ke">Jam Pelajaran</label>
                    <select name="jam_ke" id="edit_jam_ke" class="form-field-input" required>
                        <option value="">-- Pilih Jam Pelajaran --</option>
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="edit_id_mapel">Mata Pelajaran</label>
                    <select name="id_mapel" id="edit_id_mapel" class="form-field-input" required>
                        @foreach($mapels as $mp)
                            <option value="{{ $mp->id_mapel }}">{{ $mp->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="edit_id_guru">Guru Pengajar</label>
                    <select name="id_guru" id="edit_id_guru" class="form-field-input" required>
                        @foreach($gurus as $g)
                            <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="edit_ruangan">Ruangan</label>
                    <input type="text" name="ruangan" id="edit_ruangan" class="form-field-input">
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Update Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================
         View Detail Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="viewModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Detail Jadwal Pelajaran</h3>
                <button type="button" class="btn-close-modal" onclick="closeViewModal()">&times;</button>
            </div>

            <div class="modal-form-grid">
                <div class="form-field-group">
                    <label>Hari & Jam:</label>
                    <div id="view_hari_jam" style="font-weight: 800; font-size: 1.05rem; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Kelas:</label>
                    <div id="view_nama_kelas" style="font-weight: 700; color: var(--dash-navy);">-</div>
                </div>
                <div class="form-field-group">
                    <label>Mata Pelajaran:</label>
                    <div id="view_nama_mapel" style="font-weight: 700; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Guru Pengajar:</label>
                    <div id="view_nama_guru" style="font-weight: 700; color: #334155;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Ruangan:</label>
                    <div id="view_ruangan" style="font-weight: 800; color: #059669;">-</div>
                </div>
            </div>

            <div class="modal-actions-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeViewModal()" style="width: 100%;">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Script for Full AJAX Operations (No Page Refresh) -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentFilters = { search: '', hari: '', id_kelas: '', id_mapel: '' };

        function toggleSubmenu(id) {
            const el = document.getElementById(id);
            if (el) el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'flex' : 'none';
        }

        function toggleDropdown(id) {
            const el = document.getElementById(id);
            if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }

        function selectFilter(type, val, label) {
            currentFilters[type] = val;

            if (type === 'hari') {
                const labelEl = document.getElementById('filterHariLabel');
                if (labelEl) labelEl.innerText = label || 'Pilih Hari';
                const menuEl = document.getElementById('filterHariMenu');
                if (menuEl) menuEl.style.display = 'none';
            } else if (type === 'id_kelas') {
                const labelEl = document.getElementById('filterKelasLabel');
                if (labelEl) labelEl.innerText = label || 'Pilih Kelas';
                const menuEl = document.getElementById('filterKelasMenu');
                if (menuEl) menuEl.style.display = 'none';
            } else if (type === 'id_mapel') {
                const labelEl = document.getElementById('filterMapelLabel');
                if (labelEl) labelEl.innerText = label || 'Pilih Mapel';
                const menuEl = document.getElementById('filterMapelMenu');
                if (menuEl) menuEl.style.display = 'none';
            }

            reloadJadwalTable();
        }

        window.addEventListener('click', function(e) {
            const dropdownIds = ['filterHariMenu', 'filterKelasMenu', 'filterMapelMenu'];
            dropdownIds.forEach(id => {
                const menu = document.getElementById(id);
                if (!menu) return;
                const btn = menu.previousElementSibling;
                if (menu.style.display === 'block' && !menu.contains(e.target) && (!btn || !btn.contains(e.target))) {
                    menu.style.display = 'none';
                }
            });
        });

        function showToast(message, type = 'success') {
            const container = document.getElementById('ajaxAlertContainer');
            if (!container) return;

            const bg = type === 'success' ? '#ecfdf5' : '#fef2f2';
            const border = type === 'success' ? '#a7f3d0' : '#fecaca';
            const color = type === 'success' ? '#065f46' : '#991b1b';
            const iconSvg = type === 'success' 
                ? '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>'
                : '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>';

            const toast = document.createElement('div');
            toast.className = 'flash-alert';
            toast.style.cssText = `background-color: ${bg}; border: 1px solid ${border}; color: ${color}; padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 600; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; transition: opacity 0.5s ease, transform 0.5s ease;`;
            toast.innerHTML = `${iconSvg} <span>${message}</span>`;

            container.prepend(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        /* ---- View Switcher (Matrix vs List) ---- */
        function switchJadwalView(mode) {
            const btnMatrix = document.getElementById('btnViewMatrix');
            const btnList   = document.getElementById('btnViewList');
            const cardMatrix= document.getElementById('matrixViewCard');
            const cardList  = document.getElementById('listViewCard');
            const filterWrap= document.getElementById('matrixClassFilterWrapper');

            if (mode === 'matrix') {
                btnMatrix.classList.add('active');
                btnList.classList.remove('active');
                cardMatrix.style.display = 'block';
                cardList.style.display = 'none';
                if (filterWrap) filterWrap.style.display = 'flex';
            } else {
                btnList.classList.add('active');
                btnMatrix.classList.remove('active');
                cardList.style.display = 'block';
                cardMatrix.style.display = 'none';
                if (filterWrap) filterWrap.style.display = 'none';
            }
        }

        /* ---- Matrix Class Filter ---- */
        function filterMatrixByClass() {
            const classId = document.getElementById('matrixClassSelect').value;
            const blocks  = document.querySelectorAll('.kelas-matrix-block');
            blocks.forEach(b => {
                if (!classId || b.id === 'matrix-kelas-block-' + classId) {
                    b.style.display = 'block';
                } else {
                    b.style.display = 'none';
                }
            });
        }

        /* ---- Dynamic Jam Pelajaran Options Filter ---- */
        const allJamSlots = @json($jamPelajarans);

        function populateJamOptions(hariSelectId, jamSelectId, targetJamKe = null) {
            const hariSelect = document.getElementById(hariSelectId);
            const jamSelect  = document.getElementById(jamSelectId);
            if (!jamSelect) return;

            const hariVal = hariSelect ? hariSelect.value : null;

            jamSelect.innerHTML = '';

            if (!hariVal) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = '-- Pilih Hari Terlebih Dahulu --';
                jamSelect.appendChild(opt);
                return;
            }

            const kat = (hariVal === 'Jumat') ? 'Jumat' : 'Senin-Kamis';

            // Filter slots based on day category and whether it is a valid KBM slot for this day
            const filtered = allJamSlots.filter(s => {
                if (s.hari_kategori !== kat) return false;

                // Check if Non-KBM setting applies to this specific day
                const appliesToThisDay = !s.berlaku_hari || s.berlaku_hari === 'Semua Hari' || s.berlaku_hari === hariVal;
                const isNonKbmForThisDay = (s.bisa_diisi_mapel == 0 || s.is_istirahat == 1) && appliesToThisDay;

                // Keep slot if it is a valid KBM slot on this day (or if targetJamKe matches)
                return (s.jam_ke > 0 && !isNonKbmForThisDay) || (targetJamKe !== null && Number(s.jam_ke) === Number(targetJamKe));
            });

            // Remove duplicate jam_ke entries
            const seen = new Set();
            const uniqueSlots = [];
            filtered.forEach(s => {
                const num = Number(s.jam_ke);
                if (!seen.has(num)) {
                    seen.add(num);
                    uniqueSlots.push(s);
                }
            });

            // Sort by jam_ke
            uniqueSlots.sort((a, b) => Number(a.jam_ke) - Number(b.jam_ke));

            if (uniqueSlots.length === 0) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Tidak Ada Jam Pelajaran KBM pada hari ini';
                jamSelect.appendChild(opt);
                return;
            }

            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = '-- Pilih Jam Pelajaran --';
            jamSelect.appendChild(defaultOpt);

            uniqueSlots.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.jam_ke;

                let start = s.jam_mulai ? s.jam_mulai.substring(0, 5).replace(':', '.') : '';
                let end   = s.jam_selesai ? s.jam_selesai.substring(0, 5).replace(':', '.') : '';
                let rangeStr = (start && end) ? ` (${start} - ${end})` : '';

                const appliesToThisDay = !s.berlaku_hari || s.berlaku_hari === 'Semua Hari' || s.berlaku_hari === hariVal;
                const isNonKbmForThisDay = (s.bisa_diisi_mapel == 0 || s.is_istirahat == 1) && appliesToThisDay;
                let nonKbmTag = isNonKbmForThisDay ? ' [Non-KBM]' : '';

                opt.textContent = `Jam Ke-${s.jam_ke}${rangeStr}${nonKbmTag}`;

                if (targetJamKe !== null && Number(s.jam_ke) === Number(targetJamKe)) {
                    opt.selected = true;
                }
                jamSelect.appendChild(opt);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const createHariEl = document.getElementById('create_hari');
            if (createHariEl) {
                createHariEl.addEventListener('change', function() {
                    populateJamOptions('create_hari', 'create_jam_ke');
                });
            }
            const editHariEl = document.getElementById('edit_hari');
            if (editHariEl) {
                editHariEl.addEventListener('change', function() {
                    populateJamOptions('edit_hari', 'edit_jam_ke');
                });
            }
        });

        /* ---- Open Create Modal Prefilled with Kelas, Hari & Jam ---- */
        function openCreateModalPrefilled(idKelas, hari, jamKe) {
            openCreateModal();
            const inputKelas = document.getElementById('create_id_kelas');
            const inputHari  = document.getElementById('create_hari');
            if (inputKelas) inputKelas.value = idKelas;
            if (inputHari)  inputHari.value  = hari;
            populateJamOptions('create_hari', 'create_jam_ke', jamKe);
        }

        /* ---- Stuck Schedule Banner Toggle & Scroll ---- */
        function toggleStuckDetail() {
            const panel = document.getElementById('stuckDetailPanel');
            const icon = document.getElementById('stuckToggleIcon');
            const text = document.getElementById('stuckToggleText');
            const btn = document.getElementById('stuckToggleBtn');
            if (!panel) return;

            const isOpen = panel.style.maxHeight && panel.style.maxHeight !== '0px';
            if (isOpen) {
                panel.style.maxHeight = '0';
                panel.style.opacity = '0';
                icon.style.transform = 'rotate(0deg)';
                text.textContent = 'Lihat Detail';
                btn.style.background = '#ea580c';
            } else {
                panel.style.maxHeight = panel.scrollHeight + 'px';
                panel.style.opacity = '1';
                icon.style.transform = 'rotate(180deg)';
                text.textContent = 'Tutup Detail';
                btn.style.background = '#9a3412';
            }
        }

        function scrollToStuckCard(idJadwal) {
            const card = document.getElementById('drag-jadwal-' + idJadwal);
            if (card) {
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // Flash highlight animation
                card.style.transition = 'box-shadow 0.3s ease, transform 0.3s ease';
                card.style.boxShadow = '0 0 0 3px #ea580c, 0 4px 16px rgba(234, 88, 12, 0.3)';
                card.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    card.style.boxShadow = '';
                    card.style.transform = '';
                }, 2000);
            }
        }

        /* ---- Drag & Drop Event Handlers ---- */
        function onDragStartJadwal(event, idJadwal) {
            event.dataTransfer.setData('text/plain', idJadwal.toString());
            event.dataTransfer.effectAllowed = 'move';
        }

        function allowDropJadwal(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            event.currentTarget.classList.add('drop-target-active');
        }

        function leaveDropJadwal(event) {
            event.currentTarget.classList.remove('drop-target-active');
        }

        function onDropJadwal(event, targetKelasId, targetHari, targetJamKe) {
            event.preventDefault();
            event.currentTarget.classList.remove('drop-target-active');

            const idJadwal = event.dataTransfer.getData('text/plain');
            if (!idJadwal) return;

            const formData = new FormData();
            formData.append('hari', targetHari);
            formData.append('jam_ke', targetJamKe);
            if (targetKelasId) formData.append('id_kelas', targetKelasId);

            fetch('/jadwal/' + idJadwal + '/move', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.error || 'Gagal memindahkan jadwal.', 'error');
                } else {
                    showToast(data.success || 'Jadwal berhasil dipindahkan.');
                    window.location.reload();
                }
            })
            .catch(() => {
                showToast('Terjadi kesalahan koneksi server.', 'error');
            });
        }

        /* ---- Modal Control Functions ---- */
        function openCreateModal() {
            document.getElementById('createModalAlert').innerHTML = '';
            document.getElementById('createJadwalForm').reset();
            populateJamOptions('create_hari', 'create_jam_ke');
            document.getElementById('createModal').style.display = 'flex';
        }

        function closeCreateModal() {
            document.getElementById('createModal').style.display = 'none';
        }

        function openEditModal(id, idKelas, hari, jamKe, idGuru, idMapel, ruangan) {
            document.getElementById('editModalAlert').innerHTML = '';
            document.getElementById('edit_id_jadwal').value = id;
            document.getElementById('edit_id_kelas').value = idKelas;
            document.getElementById('edit_hari').value = hari;
            populateJamOptions('edit_hari', 'edit_jam_ke', jamKe);
            document.getElementById('edit_id_guru').value = idGuru;
            document.getElementById('edit_id_mapel').value = idMapel;
            document.getElementById('edit_ruangan').value = ruangan || '';
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function openViewModal(id) {
            fetch('/jadwal/' + id, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('view_hari_jam').innerText = data.hari + ' • Jam Ke-' + data.jam_ke + ' (' + data.waktu + ')';
                document.getElementById('view_nama_kelas').innerText = data.nama_kelas;
                document.getElementById('view_nama_mapel').innerText = data.nama_mapel;
                document.getElementById('view_nama_guru').innerText = data.nama_guru;
                document.getElementById('view_ruangan').innerText = data.ruangan;
                document.getElementById('viewModal').style.display = 'flex';
            });
        }

        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }

        /* ---- Submit Create Form (AJAX) ---- */
        const createJadwalFormEl = document.getElementById('createJadwalForm');
        if (createJadwalFormEl) {
            createJadwalFormEl.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const alertBox = document.getElementById('createModalAlert');

                fetch('/jadwal', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        const errMsg = data.error || (data.errors ? Object.values(data.errors).flat().join('<br>') : 'Gagal menyimpan jadwal.');
                        if (alertBox) alertBox.innerHTML = `<div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> <span>${errMsg}</span></div>`;
                    } else {
                        if (alertBox) alertBox.innerHTML = '';
                        closeCreateModal();
                        showToast(data.success || 'Jadwal pelajaran berhasil ditambahkan.');
                        setTimeout(() => window.location.reload(), 400);
                    }
                })
                .catch(() => {
                    if (alertBox) alertBox.innerHTML = `<div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> <span>Terjadi kesalahan koneksi server.</span></div>`;
                });
            });
        }

        /* ---- Submit Edit Form (AJAX) ---- */
        const editJadwalFormEl = document.getElementById('editJadwalForm');
        if (editJadwalFormEl) {
            editJadwalFormEl.addEventListener('submit', function(e) {
                e.preventDefault();
                const id = document.getElementById('edit_id_jadwal').value;
                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                const alertBox = document.getElementById('editModalAlert');

                fetch('/jadwal/' + id, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        const errMsg = data.error || (data.errors ? Object.values(data.errors).flat().join('<br>') : 'Gagal memperbarui data.');
                        if (alertBox) alertBox.innerHTML = `<div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> <span>${errMsg}</span></div>`;
                    } else {
                        if (alertBox) alertBox.innerHTML = '';
                        closeEditModal();
                        showToast(data.success || 'Jadwal pelajaran berhasil diperbarui.');
                        setTimeout(() => window.location.reload(), 400);
                    }
                })
                .catch(() => {
                    if (alertBox) alertBox.innerHTML = `<div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> <span>Terjadi kesalahan koneksi server.</span></div>`;
                });
            });
        }

        /* ---- Delete Jadwal via AJAX ---- */
        function deleteJadwalAjax(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus jadwal pelajaran ini?')) return;

            fetch('/jadwal/' + id, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                showToast(data.success || 'Jadwal pelajaran berhasil dihapus.');
                setTimeout(() => window.location.reload(), 400);
            });
        }

        /* ---- Reload Main Jadwal Table via AJAX ---- */
        function reloadJadwalTable() {
            const params = new URLSearchParams();
            if (currentFilters.search) params.append('search', currentFilters.search);
            if (currentFilters.hari) params.append('hari', currentFilters.hari);
            if (currentFilters.id_kelas) params.append('id_kelas', currentFilters.id_kelas);
            if (currentFilters.id_mapel) params.append('id_mapel', currentFilters.id_mapel);

            fetch('/jadwal?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(resData => {
                const tbody = document.querySelector('.jadwal-table tbody');
                if (!tbody) return;

                if (!resData.data || resData.data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #847e73;">
                                Tidak ada data jadwal pelajaran yang sesuai dengan filter.
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = resData.data.map(j => `
                        <tr id="row-jadwal-${j.id_jadwal}">
                            <td>${j.waktu}</td>
                            <td style="font-weight: 700;">
                                ${j.is_mapel_deleted ? '<span class="badge-warning-deleted" title="Mata pelajaran ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>' : (j.mapel || j.nama_mapel || '-')}
                            </td>
                            <td style="font-weight: 700;">
                                ${j.is_kelas_deleted ? '<span class="badge-warning-deleted" title="Kelas ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>' : (j.kelas || j.nama_kelas || '-')}
                            </td>
                            <td>
                                ${j.is_guru_deleted ? '<span class="badge-warning-deleted" title="Guru ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>' : (j.guru || j.nama_guru || '-')}
                            </td>
                            <td style="font-weight: 700; color: #475569;">${j.ruangan}</td>
                            <td>
                                <div class="action-icons-cell">
                                    <button type="button" class="action-btn-icon view" title="Lihat Detail Jadwal" onclick="openViewModal(${j.id_jadwal})">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                    <button type="button" class="action-btn-icon edit" title="Edit Jadwal" onclick="openEditModal(${j.id_jadwal}, '${j.id_kelas}', '${j.hari}', '${j.jam_ke}', '${j.id_guru}', '${j.id_mapel}', '${j.ruangan.replace(/'/g, "\\'")}')">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" class="action-btn-icon delete" title="Hapus Jadwal" onclick="deleteJadwalAjax(${j.id_jadwal})">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }

                const pag = resData.pagination;
                const summaryEl = document.querySelector('.pagination-summary-text');
                const linksEl = document.getElementById('paginationLinks');
                if (summaryEl && pag) summaryEl.innerText = `Menampilkan ${pag.first} - ${pag.last} dari ${pag.total} data`;
                if (linksEl && resData.pagination_html) linksEl.innerHTML = resData.pagination_html;
            });
        }

        /* ---- Search Filter Setup ---- */
        (function() {
            const searchInput = document.getElementById('jadwalSearchInput');
            if (searchInput) {
                let searchTimer;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        currentFilters.search = this.value.trim();
                        reloadJadwalTable();
                    }, 300);
                });
            }
        })();

        /* ---- Auto-fade Session Flash Alerts ---- */
        setTimeout(function() {
            document.querySelectorAll('.flash-alert').forEach(function(el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 500);
            });
        }, 3000);
    </script>
    <script src="/js/ajax-pagination.js"></script>
    <script src="/js/live-clock.js"></script>
</body>
</html>