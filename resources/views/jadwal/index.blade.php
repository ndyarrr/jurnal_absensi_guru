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
                <div class="flash-alert" style="background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 600; margin-bottom: 12px;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="flash-alert" style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 600; margin-bottom: 12px;">
                    ⚠️ {{ session('error') }}
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
                    <input type="text" id="jadwalSearchInput" class="form-field-input" style="padding-left: 42px; border-radius: 14px;" placeholder="Cari Nama/Wali kelas.." value="{{ request('search') }}" autocomplete="off">
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; align-items: center; gap: 12px;">
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

            <!-- ---------------------------------------------------------------
                 Split Layout Card: Left Timeline ("Jadwal Hari Ini") vs Right Table
                 --------------------------------------------------------------- -->
            <div class="jadwal-card-container">
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
                                                    <span class="badge-warning-deleted" title="Mata pelajaran ini telah dihapus">⚠️ -</span>
                                                @else
                                                    {{ $tItem->mapel->nama_mapel }}
                                                @endif
                                            </div>
                                            <div class="timeline-card-meta">
                                                <span>
                                                    Kelas: 
                                                    @if($isTKelasDel)
                                                        <span class="badge-warning-deleted" title="Kelas ini telah dihapus">⚠️ -</span>
                                                    @else
                                                        {{ $tItem->kelas->tingkat }} {{ optional($tItem->kelas->jurusan)->kode_jurusan }} {{ $tItem->kelas->rombel }}
                                                    @endif
                                                </span>
                                                <span>
                                                    Guru: 
                                                    @if($isTGuruDel)
                                                        <span class="badge-warning-deleted" title="Guru ini telah dihapus">⚠️ -</span>
                                                    @else
                                                        {{ $tItem->guru->nama_guru }}
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="timeline-card-room {{ $roomColors[$cIndex] }}">
                                                {{ $tItem->ruangan ?? 'R. 57' }}
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
                    <div class="jadwal-table-column">
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
                                            $waktuRange = '07.30-08.15';
                                            if ($j->jamPelajaran) {
                                                $waktuRange = \Carbon\Carbon::parse($j->jamPelajaran->jam_mulai)->format('H.i') . '-' . \Carbon\Carbon::parse($j->jamPelajaran->jam_selesai)->format('H.i');
                                            }
                                            $isMapelDel = !$j->mapel || $j->mapel->trashed();
                                            $isKelasDel = !$j->kelas || $j->kelas->trashed();
                                            $isGuruDel  = !$j->guru || $j->guru->trashed();
                                        @endphp
                                        <tr id="row-jadwal-{{ $j->id_jadwal }}">
                                            <td>{{ $waktuRange }}</td>
                                            <td style="font-weight: 700;">
                                                @if($isMapelDel)
                                                    <span class="badge-warning-deleted" title="Mata pelajaran ini telah dihapus">⚠️ -</span>
                                                @else
                                                    {{ $j->mapel->nama_mapel }}
                                                @endif
                                            </td>
                                            <td style="font-weight: 700;">
                                                @if($isKelasDel)
                                                    <span class="badge-warning-deleted" title="Kelas ini telah dihapus">⚠️ -</span>
                                                @else
                                                    {{ $j->kelas->tingkat }} {{ optional($j->kelas->jurusan)->kode_jurusan }} {{ $j->kelas->rombel }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($isGuruDel)
                                                    <span class="badge-warning-deleted" title="Guru ini telah dihapus">⚠️ -</span>
                                                @else
                                                    {{ $j->guru->nama_guru }}
                                                @endif
                                            </td>
                                            <td style="font-weight: 700; color: #475569;">{{ $j->ruangan ?? 'R. 57' }}</td>
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
                                                    <button type="button" class="action-btn-icon edit" title="Edit Jadwal" onclick="openEditModal({{ $j->id_jadwal }}, '{{ $j->id_kelas }}', '{{ $j->hari }}', '{{ $j->jam_ke }}', '{{ $j->id_guru }}', '{{ $j->id_mapel }}', '{{ addslashes($j->ruangan ?? 'R. 57') }}')">
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
                        <option value="">-- Pilih Jam Pelajaran --</option>
                        @foreach($jamPelajarans as $jp)
                            @php
                                $rangeStr = \Carbon\Carbon::parse($jp->jam_mulai)->format('H.i') . ' - ' . \Carbon\Carbon::parse($jp->jam_selesai)->format('H.i');
                            @endphp
                            <option value="{{ $jp->jam_ke }}">Jam Ke-{{ $jp->jam_ke }} ({{ $rangeStr }})</option>
                        @endforeach
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
                    <label for="create_ruangan">Ruangan</label>
                    <input type="text" name="ruangan" id="create_ruangan" class="form-field-input" placeholder="Contoh: R. 57, Lab. 1" value="R. 57">
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
                        @foreach($jamPelajarans as $jp)
                            @php
                                $rangeStr = \Carbon\Carbon::parse($jp->jam_mulai)->format('H.i') . ' - ' . \Carbon\Carbon::parse($jp->jam_selesai)->format('H.i');
                            @endphp
                            <option value="{{ $jp->jam_ke }}">Jam Ke-{{ $jp->jam_ke }} ({{ $rangeStr }})</option>
                        @endforeach
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

        /* ---- Toast Alert Function (Auto-hides in 3 seconds) ---- */
        function showToast(message, type = 'success') {
            const container = document.getElementById('ajaxAlertContainer');
            if (!container) return;

            const bg = type === 'success' ? '#ecfdf5' : '#fef2f2';
            const border = type === 'success' ? '#a7f3d0' : '#fecaca';
            const color = type === 'success' ? '#065f46' : '#991b1b';
            const icon = type === 'success' ? '✅' : '⚠️';

            const toast = document.createElement('div');
            toast.className = 'flash-alert';
            toast.style.cssText = `background-color: ${bg}; border: 1px solid ${border}; color: ${color}; padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 600; margin-bottom: 12px; transition: opacity 0.5s ease, transform 0.5s ease;`;
            toast.innerHTML = `${icon} ${message}`;

            container.prepend(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        /* ---- Fetch Table Data via AJAX (No Refresh) ---- */
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
                const tbody = document.querySelector('#jadwalMainTable tbody');
                if (!tbody) return;

                if (!resData.data || resData.data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #847e73;">
                                Belum ada data jadwal pelajaran.
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = resData.data.map(j => {
                        const mapelCell = j.is_mapel_deleted ? '<span class="badge-warning-deleted" title="Mata pelajaran ini telah dihapus">⚠️ -</span>' : j.mapel;
                        const kelasCell = j.is_kelas_deleted ? '<span class="badge-warning-deleted" title="Kelas ini telah dihapus">⚠️ -</span>' : j.kelas;
                        const guruCell  = j.is_guru_deleted  ? '<span class="badge-warning-deleted" title="Guru ini telah dihapus">⚠️ -</span>' : j.guru;

                        return `
                            <tr id="row-jadwal-${j.id_jadwal}">
                                <td>${j.waktu}</td>
                                <td style="font-weight: 700;">${mapelCell}</td>
                                <td style="font-weight: 700;">${kelasCell}</td>
                                <td>${guruCell}</td>
                                <td style="font-weight: 700; color: #475569;">${j.ruangan}</td>
                                <td>
                                    <div class="action-icons-cell">
                                        <button type="button" class="action-btn-icon view" title="Lihat Detail" onclick="openViewModal(${j.id_jadwal})">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>
                                        <button type="button" class="action-btn-icon edit" title="Edit Jadwal" onclick="openEditModal(${j.id_jadwal}, '${j.id_kelas}', '${j.hari}', '${j.jam_ke}', '${j.id_guru}', '${j.id_mapel}', '${(j.ruangan || 'R. 57').replace(/'/g, "\\'")}')">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>
                                        <button type="button" class="action-btn-icon delete" title="Hapus" onclick="deleteJadwalAjax(${j.id_jadwal})">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }).join('');
                }

                // Update pagination text
                const pag = resData.pagination;
                const pagEl = document.getElementById('paginationSummary');
                if (pagEl && pag) {
                    pagEl.innerText = `Menampilkan ${pag.first} - ${pag.last} dari ${pag.total} data`;
                }
            });
        }

        /* ---- Select Filter Without Refresh ---- */
        function selectFilter(key, value, label) {
            currentFilters[key] = value;
            if (key === 'hari') {
                document.getElementById('filterHariLabel').innerText = label;
                document.getElementById('filterHariMenu').style.display = 'none';
            } else if (key === 'id_kelas') {
                document.getElementById('filterKelasLabel').innerText = label;
                document.getElementById('filterKelasMenu').style.display = 'none';
            } else if (key === 'id_mapel') {
                document.getElementById('filterMapelLabel').innerText = label;
                document.getElementById('filterMapelMenu').style.display = 'none';
            }
            reloadJadwalTable();
        }

        /* ---- Search Input Real-Time AJAX ---- */
        document.getElementById('jadwalSearchInput').addEventListener('input', function() {
            currentFilters.search = this.value.trim();
            reloadJadwalTable();
        });

        /* ---- Modal Control Functions ---- */
        function openCreateModal() {
            document.getElementById('createModalAlert').innerHTML = '';
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
            document.getElementById('edit_jam_ke').value = jamKe;
            document.getElementById('edit_id_guru').value = idGuru;
            document.getElementById('edit_id_mapel').value = idMapel;
            document.getElementById('edit_ruangan').value = ruangan;
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function openViewModal(id) {
            fetch('/jadwal/' + id)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('view_hari_jam').innerText = data.hari + ', Jam Ke-' + data.jam_ke + ' (' + data.waktu + ')';
                    
                    document.getElementById('view_nama_kelas').innerHTML = data.is_kelas_deleted 
                        ? '<span class="badge-warning-deleted" title="Kelas ini telah dihapus">⚠️ - (Telah Dihapus)</span>' 
                        : data.nama_kelas;
                        
                    document.getElementById('view_nama_mapel').innerHTML = data.is_mapel_deleted 
                        ? '<span class="badge-warning-deleted" title="Mata pelajaran ini telah dihapus">⚠️ - (Telah Dihapus)</span>' 
                        : data.nama_mapel;
                        
                    document.getElementById('view_nama_guru').innerHTML = data.is_guru_deleted 
                        ? '<span class="badge-warning-deleted" title="Guru ini telah dihapus">⚠️ - (Telah Dihapus)</span>' 
                        : data.nama_guru;

                    document.getElementById('view_ruangan').innerText = data.ruangan;
                    document.getElementById('viewModal').style.display = 'flex';
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }

        /* ---- AJAX Submit Create Form ---- */
        document.getElementById('createJadwalForm').addEventListener('submit', function(e) {
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
                    const errMsg = data.error || (data.errors ? Object.values(data.errors).flat().join('<br>') : 'Gagal menyimpan data.');
                    alertBox.innerHTML = `<div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 14px; font-weight: 600;">⚠️ ${errMsg}</div>`;
                } else {
                    closeCreateModal();
                    this.reset();
                    showToast(data.success || 'Jadwal pelajaran berhasil ditambahkan.');
                    reloadJadwalTable();
                }
            })
            .catch(() => {
                alertBox.innerHTML = `<div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 14px; font-weight: 600;">⚠️ Terjadi kesalahan koneksi server.</div>`;
            });
        });

        /* ---- AJAX Submit Edit Form ---- */
        document.getElementById('editJadwalForm').addEventListener('submit', function(e) {
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
                    alertBox.innerHTML = `<div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 14px; font-weight: 600;">⚠️ ${errMsg}</div>`;
                } else {
                    closeEditModal();
                    showToast(data.success || 'Jadwal pelajaran berhasil diperbarui.');
                    reloadJadwalTable();
                }
            })
            .catch(() => {
                alertBox.innerHTML = `<div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 14px; font-weight: 600;">⚠️ Terjadi kesalahan koneksi server.</div>`;
            });
        });

        /* ---- AJAX Delete Function ---- */
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
                reloadJadwalTable();
            });
        }

        /* ---- Live Clock Widget ---- */
        function updateLiveClock() {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const now = new Date();
            const dayName = days[now.getDay()];
            const dateNum = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const dateEl = document.getElementById('live_date_str');
            const timeEl = document.getElementById('live_time_str');

            if (dateEl) dateEl.innerText = `${dayName}, ${dateNum} ${monthName} ${year}`;
            if (timeEl) timeEl.innerText = `${hours}:${minutes}:${seconds} WIB`;
        }

        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        /* ---- Auto-fade Session Flash Alerts ---- */
        setTimeout(function() {
            document.querySelectorAll('.flash-alert').forEach(function(el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 500);
            });
        }, 3000);
    </script>
</body>
</html>