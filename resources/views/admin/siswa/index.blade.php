<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data - Siswa | Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Modular Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <script src="/js/sidebar-toggle.js"></script>
</head>
<body class="dashboard-body">

    <div class="dash-layout">

        @include('partials.dash-sidebar')

        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- ===================================================================
             Main Content Region
             =================================================================== -->
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
                        <h1 class="dash-header-title">Master Data - Siswa</h1>
                        <p class="dash-header-subtitle">Pengelolaan siswa</p>
                    </div>
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

                    @include('partials.dash-user-widget')
                </div>
            </header>

            <!-- Flash Alerts -->
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

            @if(session('import_summary'))
                <div class="flash-alert persistent-alert" id="importSummaryCard" style="background-color: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: 0 4px 14px rgba(0,0,0,0.06); padding: 16px 20px; border-radius: 14px; font-size: 0.88rem; margin-bottom: 16px; position: relative;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <div style="font-weight: 700; color: #0f172a; font-size: 0.98rem; display: flex; align-items: center; gap: 8px;">
                            <svg width="20" height="20" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            <span>Ringkasan Hasil Import Siswa</span>
                        </div>
                        <button type="button" onclick="document.getElementById('importSummaryCard').remove()" style="background: #2563eb; color: #ffffff; border: none; padding: 6px 18px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 5px rgba(37,99,235,0.2);">
                            Oke
                        </button>
                    </div>
                    
                    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px;">
                        <span style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 6px 14px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 4px;">
                            ✓ {{ session('import_summary')['success'] }} Siswa Baru
                        </span>
                        <span style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; padding: 6px 14px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 4px;">
                            ↻ {{ session('import_summary')['updated'] }} Ditimpa/Diperbarui
                        </span>
                        @if(session('import_summary')['skipped'] > 0)
                            <span style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; padding: 6px 14px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 4px;">
                                ⚠ {{ session('import_summary')['skipped'] }} Dilewati / Gagal
                            </span>
                        @endif
                    </div>

                    @if(session('import_errors') && count(session('import_errors')) > 0)
                        <div style="margin-top: 12px; border-top: 1px solid #e2e8f0; padding-top: 10px; color: #92400e;">
                            <strong style="font-size: 0.85rem; display: block; margin-bottom: 6px; color: #78350f;">Detail Peringatan / Error per Baris:</strong>
                            <ul style="margin: 0; padding-left: 18px; font-size: 0.83rem; max-height: 150px; overflow-y: auto; background: #fffbe8; border: 1px solid #fef08a; border-radius: 8px; padding-top: 8px; padding-bottom: 8px;">
                                @foreach(session('import_errors') as $err)
                                    <li style="margin-bottom: 3px;">{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div style="display: flex; justify-content: flex-end; margin-top: 12px;">
                        <button type="button" onclick="document.getElementById('importSummaryCard').remove()" style="background: #059669; color: #ffffff; border: none; padding: 8px 24px; border-radius: 8px; font-weight: 600; font-size: 0.88rem; cursor: pointer; box-shadow: 0 2px 6px rgba(5,150,105,0.25);">
                            Oke, Mengerti
                        </button>
                    </div>
                </div>
            @elseif(session('import_errors') && count(session('import_errors')) > 0)
                <div class="flash-alert persistent-alert" id="importErrorCard" style="background-color: #fffbebfb; border: 1px solid #fde68a; color: #92400e; padding: 14px 18px; border-radius: 12px; font-size: 0.85rem; margin-bottom: 12px; position: relative;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <strong style="font-weight: 700; color: #78350f;">Catatan/Peringatan Baris Dilewati saat Import:</strong>
                        <button type="button" onclick="document.getElementById('importErrorCard').remove()" style="background: #d97706; color: #fff; border: none; padding: 4px 14px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; cursor: pointer;">
                            Oke
                        </button>
                    </div>
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach(session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ---------------------------------------------------------------
                 Controls Bar: Search & Action Buttons (Matching Mockup)
                 --------------------------------------------------------------- -->
            <div class="siswa-controls-row">
                <!-- Search Form -->
                <form action="{{ route('siswa.index') }}" method="GET" class="siswa-search-box">
                    @if(request('tingkat')) <input type="hidden" name="tingkat" value="{{ request('tingkat') }}"> @endif
                    @if(request('id_jurusan')) <input type="hidden" name="id_jurusan" value="{{ request('id_jurusan') }}"> @endif
                    @if(request('rombel')) <input type="hidden" name="rombel" value="{{ request('rombel') }}"> @endif

                    <svg class="siswa-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="search" id="siswaSearchInput" class="siswa-search-input" placeholder="Cari Siswa NISN/NAMA..." value="{{ request('search') }}" autocomplete="off">
                </form>

                <!-- Action Controls Group -->
                <div class="siswa-action-group">
                    <!-- Import Button -->
                    <button type="button" class="btn-export-pill" onclick="openImportModal()" style="background-color: #f0fdf4; color: #16a34a; border-color: #bbf7d0;" title="Import Siswa dari file CSV atau XLSX">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <span>Import</span>
                    </button>

                    <button type="button" class="btn-export-pill" onclick="exportSiswaCsv()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Export</span>
                    </button>

                    <!-- Bulk Delete Button -->
                    <button type="button" id="btnBulkDeleteSiswa" class="btn-bulk-delete" style="display: none; background: #dc2626; color: white; border: none; padding: 0 16px; height: 42px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer; align-items: center; gap: 8px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s;" onclick="executeBulkDeleteSiswa()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        <span>Hapus Terpilih (<span id="selectedCountTextSiswa">0</span>)</span>
                    </button>

                    <!-- + Tambah Button -->
                    <button type="button" class="btn-siswa-tambah" onclick="openCreateModal()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Tambah</span>
                    </button>

                    <!-- Filter Jenis Kelamin -->
                    <div style="position: relative;">
                        <button type="button" class="btn-filter-pill" onclick="toggleDropdown('jkMenu')">
                            <span>
                                @if(request('jenis_kelamin') === 'L') Laki-laki (L)
                                @elseif(request('jenis_kelamin') === 'P') Perempuan (P)
                                @else L/P @endif
                            </span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div id="jkMenu" style="display: none; position: absolute; right: 0; top: 48px; background: #ffffff; border: 1px solid var(--dash-cream-border); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 8px; width: 150px; z-index: 50;">
                            <a href="{{ route('siswa.index', array_merge(request()->except('jenis_kelamin'), [])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Semua Gender</a>
                            <a href="{{ route('siswa.index', array_merge(request()->except('jenis_kelamin'), ['jenis_kelamin' => 'L'])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Laki-laki (L)</a>
                            <a href="{{ route('siswa.index', array_merge(request()->except('jenis_kelamin'), ['jenis_kelamin' => 'P'])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Perempuan (P)</a>
                        </div>
                    </div>

                    <!-- Filter Tingkat -->
                    <div style="position: relative;">
                        <button type="button" class="btn-filter-pill" onclick="toggleDropdown('tingkatMenu')">
                            <span>{{ request('tingkat') ? 'Tingkat ' . request('tingkat') : 'Tingkat' }}</span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div id="tingkatMenu" style="display: none; position: absolute; right: 0; top: 48px; background: #ffffff; border: 1px solid var(--dash-cream-border); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 8px; width: 140px; z-index: 50;">
                            <a href="{{ route('siswa.index', array_merge(request()->except('tingkat'), [])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Semua</a>
                            @foreach($tingkatList as $t)
                                <a href="{{ route('siswa.index', array_merge(request()->except('tingkat'), ['tingkat' => $t])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Tingkat {{ $t }}</a>
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
                            <a href="{{ route('siswa.index', array_merge(request()->except('id_jurusan'), [])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Semua Jurusan</a>
                            @foreach($jurusanList as $j)
                                <a href="{{ route('siswa.index', array_merge(request()->except('id_jurusan'), ['id_jurusan' => $j->id_jurusan])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">{{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})</a>
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
                            <a href="{{ route('siswa.index', array_merge(request()->except('rombel'), [])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Semua Rombel</a>
                            @foreach($rombelList as $r)
                                <a href="{{ route('siswa.index', array_merge(request()->except('rombel'), ['rombel' => $r])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Rombel {{ $r }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Total Count Badge -->
                    <div class="badge-siswa-count">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        <span>{{ $totalSiswaCount }}</span>
                    </div>
                </div>
            </div>

            <!-- ---------------------------------------------------------------
                 Data Table Card Component (Matching Mockup)
                 --------------------------------------------------------------- -->
            <div class="siswa-table-card" data-ajax-pagination="main">
                <div class="table-responsive-clean">
                    <table class="siswa-table">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;">
                                    <input type="checkbox" id="selectAllSiswa" onclick="toggleSelectAllSiswa(this)" style="width: 16px; height: 16px; cursor: pointer; accent-color: #dc2626;" title="Pilih Semua di Halaman Ini">
                                </th>
                                <th style="width: 5%;">No</th>
                                <th style="width: 14%;">NISN</th>
                                <th style="width: 22%;">Nama Siswa</th>
                                <th style="width: 8%; text-align: center;">L/P</th>
                                <th style="width: 14%;">No. Telepon</th>
                                <th style="width: 14%;">Kelas</th>
                                <th style="width: 9%; text-align: center;">Status</th>
                                <th style="width: 14%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $index => $s)
                                <tr id="row-siswa-{{ $s->id_siswa }}">
                                    <td style="text-align: center;">
                                        <input type="checkbox" class="siswa-row-checkbox" value="{{ $s->id_siswa }}" onchange="onSiswaCheckboxChange(this)" style="width: 16px; height: 16px; cursor: pointer; accent-color: #dc2626;">
                                    </td>
                                    <td class="td-siswa-no">{{ $loop->iteration + ($siswa->currentPage() - 1) * $siswa->perPage() }}</td>
                                    <td class="td-siswa-nisn">
                                        <span class="nisn-badge">{{ $s->nisn }}</span>
                                    </td>
                                    <td class="td-siswa-nama">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div class="avatar-initial">
                                                {{ strtoupper(substr($s->nama_siswa, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 700; color: #1e2538;" class="siswa-name-title">{{ $s->nama_siswa }}</div>
                                                <small style="color: #64748b; font-weight: 500;">ID: SIS-{{ str_pad($s->id_siswa, 4, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-siswa-jk" style="text-align: center;">
                                        @if($s->jenis_kelamin === 'L')
                                            <span style="background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 800;" title="Laki-laki">L</span>
                                        @elseif($s->jenis_kelamin === 'P')
                                            <span style="background: #fce7f3; color: #be185d; padding: 2px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 800;" title="Perempuan">P</span>
                                        @else
                                            <span style="color: #94a3b8; font-weight: 600;">-</span>
                                        @endif
                                    </td>
                                    <td class="td-siswa-telepon" style="font-size: 0.85rem; font-weight: 600; color: #334155;">
                                        {{ $s->no_telepon ?? '-' }}
                                    </td>
                                    <td class="td-siswa-kelas">
                                        @if($s->kelas && !$s->kelas->trashed())
                                            <span class="badge-tag-guru">{{ $s->kelas->tingkat }} {{ optional($s->kelas->jurusan)->kode_jurusan }} {{ $s->kelas->rombel }}</span>
                                        @else
                                            <span class="badge-warning-deleted" title="Kelas ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge-status-aktif">
                                            <span class="dot-green"></span> Aktif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-icons-cell">
                                            <!-- View Action -->
                                            <button type="button" class="action-btn-icon view" title="Lihat Detail Siswa" onclick="openViewModal({{ $s->id_siswa }})">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </button>

                                            <!-- Edit Action (Inline Row Edit) -->
                                            <button type="button" class="action-btn-icon edit" title="Edit Data Siswa" onclick="startInlineEditSiswa({{ $s->id_siswa }}, '{{ addslashes($s->nisn) }}', '{{ addslashes($s->nama_siswa) }}', '{{ $s->jenis_kelamin ?? '' }}', '{{ addslashes($s->no_telepon ?? '') }}', '{{ $s->id_kelas }}')">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>

                                            <!-- Delete Action (AJAX - No Page Refresh) -->
                                            <button type="button" class="action-btn-icon delete" title="Hapus Siswa" onclick="deleteSiswaAjax({{ $s->id_siswa }}, '{{ addslashes($s->nama_siswa) }}')">
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
                                    <td colspan="9" style="text-align: center; padding: 40px; color: #847e73;">
                                        Belum ada data siswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination Row -->
                <div class="table-pagination-row">
                    <span class="pagination-summary-text">
                        Menampilkan {{ $siswa->firstItem() ?? 0 }} - {{ $siswa->lastItem() ?? 0 }} dari {{ $siswa->total() }} data
                    </span>

                    <div class="pagination-nav-group">
                        {{ $siswa->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>

        </main>

    </div>

    <!-- ===================================================================
         Create Siswa Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="createModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Tambah Data Siswa Baru</h3>
                <button type="button" class="btn-close-modal" onclick="closeCreateModal()">&times;</button>
            </div>

            <form action="{{ route('siswa.store') }}" method="POST" class="modal-form-grid">
                @csrf
                <div class="form-field-group">
                    <label for="create_nisn">NISN (10 Digit)</label>
                    <input type="text" name="nisn" id="create_nisn" class="form-field-input" placeholder="Masukkan 10 digit NISN" maxlength="10" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')" required>
                </div>

                <div class="form-field-group">
                    <label for="create_nama_siswa">Nama Lengkap Siswa</label>
                    <input type="text" name="nama_siswa" id="create_nama_siswa" class="form-field-input" placeholder="Masukkan nama lengkap siswa" required>
                </div>

                <div class="form-field-group">
                    <label for="create_jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="create_jenis_kelamin" class="form-field-input">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>

                <div class="form-field-group">
                    <label for="create_no_telepon">No. Telepon</label>
                    <input type="text" name="no_telepon" id="create_no_telepon" class="form-field-input" placeholder="Contoh: 081234567890" maxlength="20" inputmode="tel">
                </div>

                <div class="form-field-group">
                    <label for="create_id_kelas">Kelas</label>
                    <select name="id_kelas" id="create_id_kelas" class="form-field-input" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}">
                                {{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeCreateModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Simpan Siswa</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================
         Import Siswa Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="importModal" style="display: none;">
        <div class="modal-content-card" style="max-width: 520px;">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Import Data Siswa (CSV / XLSX)</h3>
                <button type="button" class="btn-close-modal" onclick="closeImportModal()">&times;</button>
            </div>

            <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data" class="modal-form-grid">
                @csrf

                <div class="form-field-group">
                    <label for="import_file">File Spreadsheet (CSV / XLSX) <span style="color: #dc2626;">*</span></label>
                    <input type="file" name="file" id="import_file" class="form-field-input" accept=".csv,.xlsx,.xls,.txt" required>
                    <small style="color: #64748b; margin-top: 4px;">Format yang didukung: <strong>.csv</strong>, <strong>.xlsx</strong>, <strong>.xls</strong> (Maksimal 10MB)</small>
                </div>

                <div class="form-field-group">
                    <label for="import_id_kelas">Pilih Kelas Default (Opsional)</label>
                    <select name="id_kelas" id="import_id_kelas" class="form-field-input">
                        <option value="">-- Otomatis Deteksi dari Kolom File --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}">
                                {{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #64748b; margin-top: 4px;">Digunakan jika kolom Kelas pada file kosong atau nama kelas tidak terdeteksi.</small>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 0.8rem; color: #334155; display: flex; flex-direction: column; gap: 8px;">
                    <div style="font-weight: 700; color: #0f172a; display: flex; align-items: center; justify-content: space-between;">
                        <span>💡 Deteksi Otomatis Indikator:</span>
                        <a href="{{ route('siswa.template') }}" style="font-size: 0.78rem; font-weight: 700; color: #2563eb; text-decoration: underline; display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            <span>Download Template CSV</span>
                        </a>
                    </div>
                    <ul style="margin: 0; padding-left: 18px; line-height: 1.5; color: #475569;">
                        <li><strong>Header Kolom:</strong> Otomatis mendeteksi kolom (<code>NISN</code>, <code>Nama</code>, <code>L/P</code>, <code>No HP</code>, <code>Kelas</code>).</li>
                        <li><strong>Jenis Kelamin (L/P):</strong> Terdeteksi otomatis dari huruf besar/kecil (<code>L</code> / <code>l</code> / <code>P</code> / <code>p</code> / <em>Laki-laki</em> / <em>Perempuan</em>).</li>
                        <li><strong>NISN:</strong> Harus tepat 10 digit angka.</li>
                        <li><strong>Kelas:</strong> Dapat berisi nama kelas seperti <em>X RPL 1</em> atau ID kelas.</li>
                    </ul>
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeImportModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit" style="background: linear-gradient(135deg, #10b981, #059669);">Upload & Import Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- (Pop-up modal removed in favor of direct inline table row edit) -->

    <!-- ===================================================================
         View Siswa Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="viewModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Detail Data Siswa</h3>
                <button type="button" class="btn-close-modal" onclick="closeViewModal()">&times;</button>
            </div>

            <div class="modal-form-grid">
                <div class="form-field-group">
                    <label>NISN:</label>
                    <div id="view_nisn" style="font-family: monospace; font-weight: 700; font-size: 1rem; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Nama Lengkap Siswa:</label>
                    <div id="view_nama_siswa" style="font-weight: 700; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Jenis Kelamin:</label>
                    <div id="view_jenis_kelamin" style="font-weight: 700; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>No. Telepon:</label>
                    <div id="view_no_telepon" style="font-weight: 700; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Kelas:</label>
                    <div id="view_kelas_str" style="font-weight: 700; color: var(--dash-navy);">-</div>
                </div>
            </div>

            <div class="modal-actions-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeViewModal()" style="width: 100%;">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Toggle & Modal Scripts -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';
        let editingSiswaId = null;

        const KELAS_OPTIONS = [
            @foreach($kelasList as $k)
                { id: '{{ $k->id_kelas }}', label: '{{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}' },
            @endforeach
        ];

        function toggleSubmenu(id) {
            const el = document.getElementById(id);
            if (el.style.display === 'none' || el.style.display === '') {
                el.style.display = 'flex';
            } else {
                el.style.display = 'none';
            }
        }

        function toggleDropdown(id) {
            const dropdowns = ['jkMenu', 'tingkatMenu', 'jurusanMenu', 'rombelMenu'];
            dropdowns.forEach(dId => {
                if (dId !== id) {
                    const el = document.getElementById(dId);
                    if (el) el.style.display = 'none';
                }
            });
            const el = document.getElementById(id);
            if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }

        function exportSiswaCsv() {
            const params = new URLSearchParams(window.location.search);
            const searchVal = document.getElementById('siswaSearchInput')?.value?.trim() || '';

            if (searchVal) {
                params.set('search', searchVal);
            } else {
                params.delete('search');
            }

            window.location.href = '{{ route('siswa.export-csv') }}' + (params.toString() ? '?' + params.toString() : '');
        }

        function openCreateModal() {
            document.getElementById('createModal').style.display = 'flex';
        }

        function closeCreateModal() {
            document.getElementById('createModal').style.display = 'none';
        }

        function openImportModal() {
            document.getElementById('importModal').style.display = 'flex';
        }

        function closeImportModal() {
            document.getElementById('importModal').style.display = 'none';
        }

        /* ---- Inline Table Row Edit for Siswa ---- */
        function startInlineEditSiswa(id, nisn, nama, jk, noTelp, idKelas) {
            if (editingSiswaId && editingSiswaId !== id) {
                cancelInlineEditSiswa(editingSiswaId);
            }
            editingSiswaId = id;

            const row = document.getElementById('row-siswa-' + id);
            if (!row) return;

            const tdNisn = row.querySelector('.td-siswa-nisn');
            const tdNama = row.querySelector('.td-siswa-nama');
            const tdJk = row.querySelector('.td-siswa-jk');
            const tdTelp = row.querySelector('.td-siswa-telepon');
            const tdKelas = row.querySelector('.td-siswa-kelas');
            const tdAksi = row.querySelector('.action-icons-cell');

            if (tdNisn && tdNama && tdKelas && tdAksi) {
                tdNisn.innerHTML = `<input type="text" id="inline-nisn-${id}" class="form-field-input" value="${nisn}" maxlength="10" inputmode="numeric" oninput="this.value=this.value.replace(/\\D/g,'')" style="padding: 4px 8px; font-size: 0.825rem; font-family: monospace; width: 100%; border-radius: 6px; border: 1.5px solid var(--dash-navy);" autocomplete="off">`;
                
                tdNama.innerHTML = `<input type="text" id="inline-nama-${id}" class="form-field-input" value="${nama.replace(/"/g, '&quot;')}" style="padding: 4px 8px; font-size: 0.85rem; font-weight: 700; width: 100%; border-radius: 6px; border: 1.5px solid var(--dash-navy);" autocomplete="off">`;

                if (tdJk) {
                    tdJk.innerHTML = `
                        <select id="inline-jk-${id}" class="form-field-input" style="padding: 4px; font-size: 0.825rem; width: 100%; border-radius: 6px; border: 1.5px solid var(--dash-navy);">
                            <option value="">-</option>
                            <option value="L" ${jk === 'L' ? 'selected' : ''}>L</option>
                            <option value="P" ${jk === 'P' ? 'selected' : ''}>P</option>
                        </select>
                    `;
                }

                if (tdTelp) {
                    tdTelp.innerHTML = `<input type="text" id="inline-telp-${id}" class="form-field-input" value="${(noTelp || '').replace(/"/g, '&quot;')}" placeholder="08..." style="padding: 4px 8px; font-size: 0.825rem; width: 100%; border-radius: 6px; border: 1.5px solid var(--dash-navy);" autocomplete="off">`;
                }

                let selectOptionsHtml = '<option value="">-- Pilih Kelas --</option>';
                KELAS_OPTIONS.forEach(k => {
                    const selected = (k.id == idKelas) ? 'selected' : '';
                    selectOptionsHtml += `<option value="${k.id}" ${selected}>${k.label}</option>`;
                });
                tdKelas.innerHTML = `<select id="inline-kelas-${id}" class="form-field-input" style="padding: 4px 8px; font-size: 0.825rem; width: 100%; border-radius: 6px; border: 1.5px solid var(--dash-navy);">${selectOptionsHtml}</select>`;

                tdAksi.innerHTML = `
                    <button type="button" class="action-btn-icon" style="background-color: #dcfce7; color: #15803d; border: 1px solid #86efac;" title="Simpan" onclick="saveInlineEditSiswa(${id})">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </button>
                    <button type="button" class="action-btn-icon" style="background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5;" title="Batal" onclick="cancelInlineEditSiswa(${id})">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                `;

                const input = document.getElementById(`inline-nama-${id}`);
                if (input) input.focus();
            }
        }

        function cancelInlineEditSiswa(id) {
            editingSiswaId = null;
            window.location.reload();
        }

        function saveInlineEditSiswa(id) {
            const nisn = document.getElementById(`inline-nisn-${id}`).value.trim();
            const nama = document.getElementById(`inline-nama-${id}`).value.trim();
            const jk = document.getElementById(`inline-jk-${id}`) ? document.getElementById(`inline-jk-${id}`).value : '';
            const noTelp = document.getElementById(`inline-telp-${id}`) ? document.getElementById(`inline-telp-${id}`).value.trim() : '';
            const idKelas = document.getElementById(`inline-kelas-${id}`).value;

            if (!nisn || !nama || !idKelas) {
                alert('Mohon lengkapi NISN, Nama Siswa, dan Kelas.');
                return;
            }

            if (nisn.length !== 10) {
                alert('NISN harus berisi tepat 10 digit angka (saat ini ' + nisn.length + ' digit).');
                return;
            }

            const formData = new FormData();
            formData.append('nisn', nisn);
            formData.append('nama_siswa', nama);
            formData.append('jenis_kelamin', jk);
            formData.append('no_telepon', noTelp);
            formData.append('id_kelas', idKelas);
            formData.append('_method', 'PUT');

            fetch('/siswa/' + id, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfTokenSiswa
                }
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) {
                    alert(data.error || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Gagal memperbarui data siswa.'));
                } else {
                    editingSiswaId = null;
                    window.location.reload();
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan koneksi server.');
            });
        }

        function openViewModal(id) {
            fetch('/siswa/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('view_nisn').innerText = data.nisn;
                    document.getElementById('view_nama_siswa').innerText = data.nama_siswa;
                    document.getElementById('view_jenis_kelamin').innerText = data.jk_label || '-';
                    document.getElementById('view_no_telepon').innerText = data.no_telepon || '-';
                    document.getElementById('view_kelas_str').innerText = data.kelas_str;
                    document.getElementById('viewModal').style.display = 'flex';
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }
        /* ---- Server-side Search with Debounce & AJAX Pagination ---- */
        (function() {
            const input = document.getElementById('siswaSearchInput');
            if (!input) return;

            let searchTimer = null;

            function performSearch() {
                const q = input.value.trim();
                const container = input.closest('[data-ajax-pagination]') || document.querySelector('[data-ajax-pagination="main"]');
                const form = input.closest('form');
                const url = new URL(form ? form.action : window.location.href, window.location.origin);

                if (q) {
                    url.searchParams.set('search', q);
                } else {
                    url.searchParams.delete('search');
                }

                // Preserve active filters
                const paramsToKeep = ['tingkat', 'id_jurusan', 'rombel', 'jenis_kelamin'];
                paramsToKeep.forEach(p => {
                    const el = form ? form.querySelector(`input[name="${p}"]`) : null;
                    if (el && el.value) {
                        url.searchParams.set(p, el.value);
                    } else {
                        const pageUrl = new URL(window.location.href);
                        if (pageUrl.searchParams.has(p)) {
                            url.searchParams.set(p, pageUrl.searchParams.get(p));
                        }
                    }
                });

                if (typeof window.loadPaginatedContent === 'function' && container) {
                    window.loadPaginatedContent(url.toString(), container).catch(function() {
                        window.location.href = url.toString();
                    });
                } else {
                    window.location.href = url.toString();
                }
            }

            input.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(performSearch, 350);
            });

            if (input.closest('form')) {
                input.closest('form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    clearTimeout(searchTimer);
                    performSearch();
                });
            }
        })();

        /* ---- Real-time NISN Digit Counter & Validator ---- */
        const createNisnEl = document.getElementById('create_nisn');
        if (createNisnEl) {
            const badge = document.createElement('span');
            badge.className = 'digit-badge-nisn';
            badge.style.cssText = 'font-size: 0.72rem; font-weight: 800; padding: 2px 6px; border-radius: 6px; margin-left: 8px; font-family: monospace; transition: all 0.2s ease;';
            const label = createNisnEl.parentNode.querySelector('label');
            if (label) label.appendChild(badge);

            function updateNisnBadge() {
                const len = createNisnEl.value.length;
                badge.textContent = len + ' / 10 digit';
                if (len === 10) {
                    badge.style.background = '#dcfce7';
                    badge.style.color = '#15803d';
                } else {
                    badge.style.background = '#fee2e2';
                    badge.style.color = '#b91c1c';
                }
            }
            createNisnEl.addEventListener('input', updateNisnBadge);
            updateNisnBadge();

            createNisnEl.closest('form').addEventListener('submit', function(e) {
                if (createNisnEl.value.trim().length !== 10) {
                    e.preventDefault();
                    alert('NISN harus berisi tepat 10 digit angka.');
                    createNisnEl.focus();
                }
            });
        }

        /* ---- Multi-Select & Bulk Delete with Page Slide Persistence (Siswa) ---- */
        const selectedSiswaIds = new Set();
        const csrfTokenSiswa = '{{ csrf_token() }}';

        function onSiswaCheckboxChange(cb) {
            const val = parseInt(cb.value);
            if (cb.checked) {
                selectedSiswaIds.add(val);
            } else {
                selectedSiswaIds.delete(val);
            }
            updateBulkDeleteUISiswa();
        }

        function toggleSelectAllSiswa(masterCb) {
            const rowCbs = document.querySelectorAll('.siswa-row-checkbox:not(:disabled)');
            rowCbs.forEach(cb => {
                cb.checked = masterCb.checked;
                const val = parseInt(cb.value);
                if (masterCb.checked) {
                    selectedSiswaIds.add(val);
                } else {
                    selectedSiswaIds.delete(val);
                }
            });
            updateBulkDeleteUISiswa();
        }

        function updateBulkDeleteUISiswa() {
            const btn = document.getElementById('btnBulkDeleteSiswa');
            const countText = document.getElementById('selectedCountTextSiswa');
            const masterCb = document.getElementById('selectAllSiswa');
            const totalRowCbs = document.querySelectorAll('.siswa-row-checkbox:not(:disabled)');

            if (countText) countText.textContent = selectedSiswaIds.size;

            if (btn) {
                btn.style.display = selectedSiswaIds.size > 0 ? 'inline-flex' : 'none';
            }

            if (masterCb && totalRowCbs.length > 0) {
                const checkedCount = Array.from(totalRowCbs).filter(cb => cb.checked).length;
                masterCb.checked = checkedCount === totalRowCbs.length;
            }
        }

        function syncCheckboxesWithSetSiswa() {
            const rowCbs = document.querySelectorAll('.siswa-row-checkbox');
            rowCbs.forEach(cb => {
                const val = parseInt(cb.value);
                cb.checked = selectedSiswaIds.has(val);
            });
            updateBulkDeleteUISiswa();
        }

        document.addEventListener('ajaxPagination:updated', function() {
            syncCheckboxesWithSetSiswa();
        });

        /* ---- Single Delete via AJAX (Siswa - No Web Refresh) ---- */
        function deleteSiswaAjax(id, name) {
            showConfirmModal({
                type: 'delete',
                title: 'Hapus Data Siswa',
                message: 'Apakah Anda yakin ingin menghapus data siswa <strong>"' + name + '"</strong>?',
                onConfirm: function() {
                    fetch('/siswa/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfTokenSiswa
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.success, 'success');
                            selectedSiswaIds.delete(id);
                            const row = document.getElementById('row-siswa-' + id);
                            if (row) {
                                row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                                row.style.opacity = '0';
                                row.style.transform = 'translateY(-10px)';
                                setTimeout(() => {
                                    row.remove();
                                    updateBulkDeleteUISiswa();
                                    const container = document.querySelector('[data-ajax-pagination="main"]');
                                    if (container && typeof window.loadPaginatedContent === 'function') {
                                        window.loadPaginatedContent(window.location.href, container);
                                    }
                                }, 300);
                            } else {
                                updateBulkDeleteUISiswa();
                            }
                        } else if (data.error) {
                            showToast(data.error, 'error');
                        }
                    })
                    .catch((err) => {
                        console.error(err);
                        showToast('Gagal menghapus data siswa.', 'error');
                    });
                }
            });
        }

        /* ---- Bulk Delete via AJAX (Siswa - No Web Refresh) ---- */
        function executeBulkDeleteSiswa() {
            if (selectedSiswaIds.size === 0) return;

            const count = selectedSiswaIds.size;
            showConfirmModal({
                type: 'delete',
                title: 'Hapus Multiple Data Siswa',
                message: 'Apakah Anda yakin ingin menghapus <strong>' + count + ' data siswa</strong> terpilih?',
                onConfirm: function() {
                    fetch('{{ route("siswa.bulk-delete") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfTokenSiswa
                        },
                        body: JSON.stringify({ ids: Array.from(selectedSiswaIds) })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.success, 'success');
                            selectedSiswaIds.clear();
                            const masterCb = document.getElementById('selectAllSiswa');
                            if (masterCb) masterCb.checked = false;
                            updateBulkDeleteUISiswa();

                            const container = document.querySelector('[data-ajax-pagination="main"]');
                            if (container && typeof window.loadPaginatedContent === 'function') {
                                window.loadPaginatedContent(window.location.href, container);
                            } else {
                                window.location.reload();
                            }
                        } else if (data.error) {
                            showToast(data.error, 'error');
                        }
                    })
                    .catch((err) => {
                        console.error(err);
                        showToast('Gagal menghapus beberapa data siswa.', 'error');
                    });
                }
            });
        }

        /* ---- Auto-fade Flash Feedback Alerts after 3 seconds (excludes persistent summary alerts) ---- */
        setTimeout(function() {
            document.querySelectorAll('.flash-alert:not(.persistent-alert)').forEach(function(el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 500);
            });
        }, 3000);
    </script>
    <script src="/js/ajax-pagination.js"></script>
    <script src="/js/sidebar-toggle.js"></script>
    <script src="/js/live-clock.js"></script>
</body>
</html>