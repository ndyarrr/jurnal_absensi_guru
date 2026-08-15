<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data - Kelas | Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Modular Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
</head>
<body class="dashboard-body">

    <div class="dash-layout">

        <!-- ===================================================================
             Left Sidebar Navigation
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

                <!-- Master Data Category (Expanded) -->
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
                        <!-- Kelas Active Sub Link -->
                        <li>
                            <a href="{{ route('kelas.index') }}" class="dash-sub-link" style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #ffffff;">
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
                    <ul class="dash-sub-menu" id="akademikSub">
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
                    <h1 class="dash-header-title">Master Data - Kelas & Jurusan</h1>
                    <p class="dash-header-subtitle">Pengelolaan rombongan belajar dan kompetensi keahlian</p>
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

            <!-- Flash Alerts -->
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
                 Controls Bar: Search & Action Buttons (Form Hide/Unhide & Tambah Jurusan)
                 --------------------------------------------------------------- -->
            <div class="kelas-controls-row">
                <!-- Search Form -->
                <form action="{{ route('kelas.index') }}" method="GET" class="kelas-search-box">
                    @if(request('tingkat')) <input type="hidden" name="tingkat" value="{{ request('tingkat') }}"> @endif
                    @if(request('id_jurusan')) <input type="hidden" name="id_jurusan" value="{{ request('id_jurusan') }}"> @endif

                    <svg class="kelas-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="search" id="kelasSearchInput" class="kelas-search-input" placeholder="Cari Kelas/Wali/Jurusan..." value="{{ request('search') }}" autocomplete="off">
                </form>

                <!-- Action Controls Group -->
                <div class="kelas-action-group">
                    <!-- Toggle Hide / Unhide Form Tambah Kelas Button -->
                    <button type="button" class="btn-toggle-form" id="btnToggleFormPanel" onclick="toggleFormPanel()">
                        <svg id="iconToggleForm" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span id="textToggleForm">Tambah Kelas</span>
                    </button>

                    <!-- + Tambah Jurusan Button -->
                    <button type="button" class="btn-jurusan-tambah" onclick="openCreateJurusanModal()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Tambah Jurusan</span>
                    </button>

                    <!-- Filter Tingkat -->
                    <div style="position: relative;">
                        <button type="button" class="btn-filter-pill" onclick="toggleDropdown('tingkatMenu')">
                            <span>{{ request('tingkat') ? 'Tingkat ' . request('tingkat') : 'Tingkat' }}</span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div id="tingkatMenu" style="display: none; position: absolute; right: 0; top: 48px; background: #ffffff; border: 1px solid var(--dash-cream-border); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 8px; width: 140px; z-index: 50;">
                            <a href="{{ route('kelas.index', array_merge(request()->except('tingkat'), [])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Semua</a>
                            @foreach($tingkatList as $t)
                                <a href="{{ route('kelas.index', array_merge(request()->except('tingkat'), ['tingkat' => $t])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Tingkat {{ $t }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Filter Jurusan -->
                    <div style="position: relative;">
                        <button type="button" class="btn-filter-pill" onclick="toggleDropdown('jurusanMenu')">
                            <span>
                                @if(request('id_jurusan'))
                                    {{ optional($jurusanList->firstWhere('id_jurusan', request('id_jurusan')))->kode_jurusan ?? 'Jurusan' }}
                                @else
                                    Jurusan
                                @endif
                            </span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div id="jurusanMenu" style="display: none; position: absolute; right: 0; top: 48px; background: #ffffff; border: 1px solid var(--dash-cream-border); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 8px; width: 180px; z-index: 50;">
                            <a href="{{ route('kelas.index', array_merge(request()->except('id_jurusan'), [])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Semua Jurusan</a>
                            @foreach($jurusanList as $j)
                                <a href="{{ route('kelas.index', array_merge(request()->except('id_jurusan'), ['id_jurusan' => $j->id_jurusan])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">{{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Filter Rombel -->
                    <div style="position: relative;">
                        <button type="button" class="btn-filter-pill" onclick="toggleDropdown('rombelMenu')">
                            <span>{{ request('rombel') ? 'Rombel ' . request('rombel') : 'Rombel' }}</span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div id="rombelMenu" style="display: none; position: absolute; right: 0; top: 48px; background: #ffffff; border: 1px solid var(--dash-cream-border); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 8px; width: 140px; z-index: 50;">
                            <a href="{{ route('kelas.index', array_merge(request()->except('rombel'), [])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Semua Rombel</a>
                            @foreach($rombelList as $r)
                                <a href="{{ route('kelas.index', array_merge(request()->except('rombel'), ['rombel' => $r])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Rombel {{ $r }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Total Count Badge -->
                    <div class="badge-kelas-count" title="Total Kelas / Jurusan">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <path d="M3 9h18M9 21V9"></path>
                        </svg>
                        <span>{{ $totalKelasCount }} Kelas ({{ $totalJurusanCount }} Jurusan)</span>
                    </div>
                </div>
            </div>

            <!-- ---------------------------------------------------------------
                 Collapsible Inline Form Panel: Tambah Kelas Baru (Hide / Unhide)
                 --------------------------------------------------------------- -->
            <div class="collapsible-form-panel" id="formTambahKelasPanel" style="display: none;">
                <div class="collapsible-form-header">
                    <h3 class="collapsible-form-title">
                        <svg width="20" height="20" fill="none" stroke="var(--dash-navy)" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14"></path>
                        </svg>
                        <span>Form Tambah Kelas Baru</span>
                    </h3>
                    <button type="button" class="btn-close-modal" onclick="toggleFormPanel()" title="Sembunyikan Form">&times;</button>
                </div>

                <form action="{{ route('kelas.store') }}" method="POST" class="form-grid-inline">
                    @csrf
                    <div class="form-field-group" style="margin-bottom: 0;">
                        <label for="create_tingkat">Tingkat Kelas</label>
                        <select name="tingkat" id="create_tingkat" class="form-field-input" required>
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="X">X (Sepuluh)</option>
                            <option value="XI">XI (Sebelas)</option>
                            <option value="XII">XII (Dua Belas)</option>
                        </select>
                    </div>

                    <div class="form-field-group" style="margin-bottom: 0;">
                        <label for="create_id_jurusan">Jurusan</label>
                        <select name="id_jurusan" id="create_id_jurusan" class="form-field-input" required>
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusanList as $j)
                                <option value="{{ $j->id_jurusan }}">{{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-field-group" style="margin-bottom: 0;">
                        <label for="create_rombel">Rombel (Angka)</label>
                        <input type="number" name="rombel" id="create_rombel" class="form-field-input" placeholder="Contoh: 1" min="1" required>
                    </div>

                    <div class="form-field-group" style="margin-bottom: 0;">
                        <label>Wali Kelas</label>
                        <input type="hidden" name="id_guru_wali" id="create_id_guru_wali" value="" required>
                        <div class="searchable-select" id="create_wali_ss">
                            <input type="text" class="form-field-input ss-input" id="create_wali_input" placeholder="Ketik untuk cari" autocomplete="off" onclick="openWaliDropdown('create')" onkeyup="filterWaliDropdown('create')" required>
                            <div class="ss-dropdown" id="create_wali_dropdown">
                                @foreach($guruList as $g)
                                    @php
                                        $isAssigned = isset($assignedWaliMap[$g->id_guru]);
                                        $assignedKelasName = $isAssigned ? $assignedWaliMap[$g->id_guru]['kelas_name'] : '';
                                    @endphp
                                    @if($isAssigned)
                                        <div class="ss-option" data-value="" data-disabled="true" style="opacity: 0.5; background-color: #f8fafc; cursor: not-allowed;" title="Guru ini sudah menjadi Wali Kelas di {{ $assignedKelasName }}">
                                            <strong>{{ $g->nama_guru }}</strong>
                                            <small style="color: #ef4444; font-weight: 700;">Menjadi Wali Kelas di {{ $assignedKelasName }}</small>
                                        </div>
                                    @else
                                        <div class="ss-option" data-value="{{ $g->id_guru }}" onclick="pickWaliGuru('create','{{ $g->id_guru }}','{{ addslashes($g->nama_guru) }} (NUPTK: {{ $g->nuptk ?? '-' }})')">
                                            <strong>{{ $g->nama_guru }}</strong>
                                            <small style="color: #64748b;">NUPTK: {{ $g->nuptk ?? '-' }}</small>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-field-group" style="margin-bottom: 0;">
                        <label for="create_jumlah_siswa">Jumlah Siswa</label>
                        <input type="number" name="jumlah_siswa" id="create_jumlah_siswa" class="form-field-input" placeholder="Contoh: 36" min="0" value="36">
                    </div>

                    <div>
                        <button type="submit" class="btn-modal-submit" style="width: 100%; padding: 11px;">Simpan Kelas</button>
                    </div>
                </form>
            </div>

            <!-- ---------------------------------------------------------------
                 Data Table Card Component (Matching Mockup Design)
                 --------------------------------------------------------------- -->
            <div class="kelas-table-card">
                <div class="table-responsive-clean">
                    <table class="kelas-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 30%;">Kelas</th>
                                <th style="width: 30%;">Wali Kelas</th>
                                <th style="width: 15%;">Jumlah Siswa</th>
                                <th style="width: 20%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelas as $index => $k)
                                <tr>
                                    <td class="td-no">{{ $loop->iteration + ($kelas->currentPage() - 1) * $kelas->perPage() }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <span class="jurusan-code-badge">{{ optional($k->jurusan)->kode_jurusan ?? '-' }}</span>
                                            <div>
                                                <div style="font-weight: 800; font-size: 1.05rem; color: #1e2538;">{{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan ?? '' }} {{ $k->rombel }}</div>
                                                <small style="color: #64748b; font-weight: 600;">{{ optional($k->jurusan)->nama_jurusan ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #1e2538;">
                                            @if($k->waliKelasGuru && $k->waliKelasGuru->trashed())
                                                <span class="badge-warning-deleted" title="Wali kelas ini telah dihapus">⚠️ -</span>
                                            @else
                                                {{ $k->wali_kelas ?: (optional($k->waliKelasGuru)->nama_guru ?? 'Belum Ada Wali Kelas') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-status-aktif" style="background-color: #f1f5f9; color: #334155; border-color: #cbd5e1;">
                                            👥 {{ $k->jumlah_siswa ?? 0 }} Siswa
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-icons-cell">
                                            <!-- View Detail Action -->
                                            <button type="button" class="action-btn-icon view" title="Lihat Detail Kelas" onclick="openViewModal({{ $k->id_kelas }})">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </button>

                                            <!-- Edit Action -->
                                            <button type="button" class="action-btn-icon edit" title="Edit Data Kelas" onclick="openEditModal({{ $k->id_kelas }}, '{{ $k->tingkat }}', '{{ $k->id_jurusan }}', '{{ $k->rombel }}', '{{ $k->id_guru_wali }}', '{{ $k->jumlah_siswa }}')">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>

                                            <!-- Delete Action -->
                                            <form action="{{ route('kelas.destroy', $k) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kelas Tingkat {{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn-icon delete" title="Hapus Kelas">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                                    <td colspan="5" style="text-align: center; padding: 40px; color: #847e73;">
                                        Belum ada data kelas. Silakan gunakan form di atas atau klik Tambah Jurusan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination Row -->
                <div class="table-pagination-row">
                    <span class="pagination-summary-text">
                        Menampilkan {{ $kelas->firstItem() ?? 0 }} - {{ $kelas->lastItem() ?? 0 }} dari {{ $kelas->total() }} data
                    </span>

                    <div class="pagination-nav-group">
                        {{ $kelas->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>

        </main>

    </div>

    <!-- ===================================================================
         Create Jurusan Modal Popup (Form Tambah Jurusan Baru)
         =================================================================== -->
    <div class="modal-overlay" id="createJurusanModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Tambah Jurusan Baru</h3>
                <button type="button" class="btn-close-modal" onclick="closeCreateJurusanModal()">&times;</button>
            </div>

            <form action="{{ route('jurusan.store') }}" method="POST" class="modal-form-grid">
                @csrf
                <div class="form-field-group">
                    <label for="create_kode_jurusan">Kode Singkatan Jurusan</label>
                    <input type="text" name="kode_jurusan" id="create_kode_jurusan" class="form-field-input" placeholder="Contoh: RPL, TKJ, DKV, AKL" maxlength="10" required style="text-transform: uppercase;">
                </div>

                <div class="form-field-group">
                    <label for="create_nama_jurusan">Nama Lengkap Jurusan</label>
                    <input type="text" name="nama_jurusan" id="create_nama_jurusan" class="form-field-input" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeCreateJurusanModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Simpan Jurusan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================
         Edit Kelas Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="editModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Edit Data Kelas</h3>
                <button type="button" class="btn-close-modal" onclick="closeEditModal()">&times;</button>
            </div>

            <form id="editForm" method="POST" class="modal-form-grid">
                @csrf
                @method('PUT')

                <div class="form-field-group">
                    <label for="edit_tingkat">Tingkat Kelas</label>
                    <select name="tingkat" id="edit_tingkat" class="form-field-input" required>
                        <option value="X">X (Sepuluh)</option>
                        <option value="XI">XI (Sebelas)</option>
                        <option value="XII">XII (Dua Belas)</option>
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="edit_id_jurusan">Jurusan</label>
                    <select name="id_jurusan" id="edit_id_jurusan" class="form-field-input" required>
                        @foreach($jurusanList as $j)
                            <option value="{{ $j->id_jurusan }}">{{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="edit_rombel">Rombel (Angka)</label>
                    <input type="number" name="rombel" id="edit_rombel" class="form-field-input" min="1" required>
                </div>

                <div class="form-field-group">
                    <label>Wali Kelas</label>
                    <input type="hidden" name="id_guru_wali" id="edit_id_guru_wali" value="" required>
                    <div class="searchable-select" id="edit_wali_ss">
                        <input type="text" class="form-field-input ss-input" id="edit_wali_input" placeholder="🔍 Ketik untuk cari nama/NUPTK wali..." autocomplete="off" onclick="openWaliDropdown('edit')" onkeyup="filterWaliDropdown('edit')" required>
                        <div class="ss-dropdown" id="edit_wali_dropdown">
                            <!-- Populated via JS openEditModal -->
                        </div>
                    </div>
                </div>

                <div class="form-field-group">
                    <label for="edit_jumlah_siswa">Jumlah Siswa</label>
                    <input type="number" name="jumlah_siswa" id="edit_jumlah_siswa" class="form-field-input" min="0">
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Update Kelas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================
         View Kelas Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="viewModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Detail Data Kelas</h3>
                <button type="button" class="btn-close-modal" onclick="closeViewModal()">&times;</button>
            </div>

            <div class="modal-form-grid">
                <div class="form-field-group">
                    <label>Nama Rombongan Belajar:</label>
                    <div id="view_nama_kelas" style="font-weight: 800; font-size: 1.1rem; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Jurusan / Keahlian:</label>
                    <div id="view_nama_jurusan" style="font-weight: 700; color: var(--dash-navy);">-</div>
                </div>
                <div class="form-field-group">
                    <label>Wali Kelas:</label>
                    <div id="view_wali_kelas" style="font-weight: 700; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Jumlah Terdaftar Siswa:</label>
                    <div id="view_jumlah_siswa" style="font-weight: 700; color: #059669;">-</div>
                </div>
            </div>

            <div class="modal-actions-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeViewModal()" style="width: 100%;">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Toggle & Modal Scripts -->
    <script>
        function toggleSubmenu(id) {
            const el = document.getElementById(id);
            if (el.style.display === 'none' || el.style.display === '') {
                el.style.display = 'flex';
            } else {
                el.style.display = 'none';
            }
        }

        function toggleDropdown(id) {
            const el = document.getElementById(id);
            if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }

        /* ---- Toggle Form Panel Tambah Kelas (Hide / Unhide) ---- */
        let isFormPanelVisible = false;
        function toggleFormPanel() {
            isFormPanelVisible = !isFormPanelVisible;
            const panel = document.getElementById('formTambahKelasPanel');
            const icon = document.getElementById('iconToggleForm');
            const text = document.getElementById('textToggleForm');

            if (isFormPanelVisible) {
                panel.style.display = 'block';
                text.innerText = 'Sembunyikan Form Kelas';
                icon.innerHTML = '<circle cx="12" cy="12" r="3"></circle><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>';
            } else {
                panel.style.display = 'none';
                text.innerText = 'Tambah Kelas';
                icon.innerHTML = '<line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>';
            }
        }

        /* ---- Tambah Jurusan Modal ---- */
        function openCreateJurusanModal() {
            document.getElementById('createJurusanModal').style.display = 'flex';
        }

        function closeCreateJurusanModal() {
            document.getElementById('createJurusanModal').style.display = 'none';
        }

        /* ---- Guru & Wali Kelas Data for JS lookup ---- */
        const GURU_WALI_DATA = [
            @foreach($guruList as $g)
                {
                    id: '{{ $g->id_guru }}',
                    name: '{{ addslashes($g->nama_guru) }}',
                    nuptk: '{{ $g->nuptk ?? '-' }}',
                    label: '{{ addslashes($g->nama_guru) }} (NUPTK: {{ $g->nuptk ?? '-' }})',
                    assigned_kelas_name: '{{ isset($assignedWaliMap[$g->id_guru]) ? addslashes($assignedWaliMap[$g->id_guru]['kelas_name']) : '' }}',
                    assigned_id_kelas: '{{ isset($assignedWaliMap[$g->id_guru]) ? $assignedWaliMap[$g->id_guru]['id_kelas'] : '' }}'
                },
            @endforeach
        ];

        /* ---- Searchable Select for Wali Kelas ---- */
        function openWaliDropdown(prefix) {
            const input = document.getElementById(prefix + '_wali_input');
            const dd = document.getElementById(prefix + '_wali_dropdown');
            const rect = input.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceAbove = rect.top;
            const dropdownHeight = 220;

            dd.classList.remove('ss-up', 'ss-down');
            if (spaceBelow < dropdownHeight && spaceAbove > spaceBelow) {
                dd.classList.add('ss-up');
            } else {
                dd.classList.add('ss-down');
            }

            dd.classList.add('ss-open');
            filterWaliDropdown(prefix);
        }

        function filterWaliDropdown(prefix) {
            const query = document.getElementById(prefix + '_wali_input').value.toLowerCase();
            const dd = document.getElementById(prefix + '_wali_dropdown');
            const items = dd.querySelectorAll('.ss-option');
            items.forEach(item => {
                const txt = item.textContent.toLowerCase();
                if (txt.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
            dd.classList.add('ss-open');
        }

        function pickWaliGuru(prefix, value, label) {
            document.getElementById(prefix + '_id_guru_wali').value = value;
            document.getElementById(prefix + '_wali_input').value = value ? label : '';
            document.getElementById(prefix + '_wali_dropdown').classList.remove('ss-open');
        }

        /* Close dropdown on outside click */
        document.addEventListener('click', function(e) {
            ['create', 'edit'].forEach(prefix => {
                const ss = document.getElementById(prefix + '_wali_ss');
                const dd = document.getElementById(prefix + '_wali_dropdown');
                if (ss && dd && !ss.contains(e.target)) {
                    dd.classList.remove('ss-open');
                }
            });
        });

        /* ---- Edit & View Kelas Modal ---- */
        function openEditModal(id, tingkat, idJurusan, rombel, idGuruWali, jumlahSiswa) {
            document.getElementById('editForm').action = '/kelas/' + id;
            document.getElementById('edit_tingkat').value = tingkat;
            document.getElementById('edit_id_jurusan').value = idJurusan;
            document.getElementById('edit_rombel').value = rombel;
            document.getElementById('edit_id_guru_wali').value = idGuruWali || '';
            document.getElementById('edit_jumlah_siswa').value = jumlahSiswa || 0;

            // Populate edit dropdown options dynamically considering current class assignment
            const editDd = document.getElementById('edit_wali_dropdown');
            let htmlOptions = '';
            let currentSelectedLabel = '';

            GURU_WALI_DATA.forEach(g => {
                const isCurrentClassWali = (g.id == idGuruWali);
                const isAssignedToOtherClass = g.assigned_id_kelas && g.assigned_id_kelas != id;

                if (isCurrentClassWali) {
                    currentSelectedLabel = g.label;
                    htmlOptions += `<div class="ss-option" data-value="${g.id}" onclick="pickWaliGuru('edit','${g.id}','${g.label.replace(/'/g, "\\'")}')">
                        <strong>${g.name}</strong> <small style="color: #059669; font-weight:700;">✓ Wali Kelas Saat Ini</small>
                    </div>`;
                } else if (isAssignedToOtherClass) {
                    htmlOptions += `<div class="ss-option" data-value="" style="opacity: 0.5; background-color: #f8fafc; cursor: not-allowed;" title="Sudah Wali Kelas di ${g.assigned_kelas_name}">
                        <strong>${g.name}</strong> <small style="color: #ef4444; font-weight:700;">Menjadi Wali Kelas di ${g.assigned_kelas_name}</small>
                    </div>`;
                } else {
                    htmlOptions += `<div class="ss-option" data-value="${g.id}" onclick="pickWaliGuru('edit','${g.id}','${g.label.replace(/'/g, "\\'")}')">
                        <strong>${g.name}</strong> <small style="color: #64748b;">NUPTK: ${g.nuptk}</small>
                    </div>`;
                }
            });

            editDd.innerHTML = htmlOptions;
            document.getElementById('edit_wali_input').value = currentSelectedLabel;

            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function openViewModal(id) {
            fetch('/kelas/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('view_nama_kelas').innerText = data.nama_kelas;
                    document.getElementById('view_nama_jurusan').innerText = data.kode_jurusan + ' - ' + data.nama_jurusan;
                    document.getElementById('view_wali_kelas').innerText = data.wali_kelas;
                    document.getElementById('view_jumlah_siswa').innerText = data.jumlah_siswa + ' Siswa';
                    document.getElementById('viewModal').style.display = 'flex';
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }
        /* ---- Real-time Client-side Search (No Refresh) ---- */
        (function() {
            const input = document.getElementById('kelasSearchInput');
            if (!input) return;

            const tbody = document.querySelector('.kelas-table tbody');
            if (!tbody) return;

            const rows = Array.from(tbody.querySelectorAll('tr'));

            input.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();

                rows.forEach(function(row) {
                    const text = row.innerText.toLowerCase();
                    if (q === '' || text.includes(q)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Prevent form submit on Enter key
            input.closest('form').addEventListener('submit', function(e) {
                e.preventDefault();
            });
        })();

        /* ---- Auto-fade Flash Feedback Alerts after 3 seconds ---- */
        setTimeout(function() {
            document.querySelectorAll('.flash-alert').forEach(function(el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 500);
            });
        }, 3000);
    </script>
    <script src="/js/live-clock.js"></script>
</body>
</html>