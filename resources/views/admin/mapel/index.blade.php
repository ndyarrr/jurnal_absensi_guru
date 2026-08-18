<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data - Mapel | Admin</title>

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
             Left Sidebar Navigation (Fixed Position)
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
                        <li>
                            <a href="{{ route('kelas.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    <path d="M3 9h18M9 21V9"></path>
                                </svg>
                                <span>Kelas</span>
                            </a>
                        </li>
                        <!-- Active Mapel Sub Link -->
                        <li>
                            <a href="{{ route('mapel.index') }}" class="dash-sub-link" style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #ffffff;">
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

            @include('partials.dash-sidebar-footer')
        </aside>

        <!-- ===================================================================
             Main Content Region
             =================================================================== -->
        <main class="dash-main">

            <!-- Top Header Bar -->
            <header class="dash-top-bar">
                <div>
                    <h1 class="dash-header-title">Master Data - Mapel</h1>
                    <p class="dash-header-subtitle">Pengelolaan mapel</p>
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

            <!-- Container for AJAX Toast Alerts -->
            <div id="ajaxAlertContainer"></div>

            <!-- Auto-fading Flash Alerts -->
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
                 Controls Bar: Real-time Search & Top Summary Stat Badges
                 --------------------------------------------------------------- -->
            <div class="mapel-controls-row">
                <!-- Search Input -->
                <div style="position: relative; width: 340px;">
                    <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #94a3b8; pointer-events: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="mapelSearchInput" class="form-field-input" style="padding-left: 42px; border-radius: 14px;" placeholder="Cari Nama Mapel" value="{{ request('search') }}" autocomplete="off">
                </div>

                <!-- Top Summary Stat Badges -->
                <div style="display: flex; align-items: center; gap: 14px;">
                    <!-- Stat Card 1: Total Mapel -->
                    <div class="mapel-stat-box">
                        <svg class="mapel-stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <div class="mapel-stat-info">
                            <span class="mapel-stat-label">Mapel</span>
                            <span class="mapel-stat-value" id="statTotalMapel">{{ $totalMapel }}</span>
                        </div>
                    </div>

                    <!-- Stat Card 2: Total Pengampu -->
                    <div class="mapel-stat-box">
                        <svg class="mapel-stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <div class="mapel-stat-info">
                            <span class="mapel-stat-label">Pengampu</span>
                            <span class="mapel-stat-value" id="statTotalPengampu">{{ $totalPengampu }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---------------------------------------------------------------
                 Two-Column Main Grid Layout: Left Table vs Right Panel Cards
                 --------------------------------------------------------------- -->
            <div class="mapel-grid-layout">
                
                <!-- ===========================================================
                     LEFT COLUMN: "Daftar Mapel" Card & Table
                     =========================================================== -->
                <div class="mapel-card-container" data-ajax-pagination="main">
                    <!-- Card Header Bar with Collapsible Form Toggle Button -->
                    <div class="mapel-card-header-bar">
                        <div class="mapel-card-title">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <span>Daftar Mapel</span>
                        </div>

                        <!-- Toggle Form Button (Hide / Unhide Panel Form) -->
                        <button type="button" class="btn-mapel-tambah-navy" id="btnToggleFormMapel" onclick="toggleTambahMapelForm()">
                            <svg id="toggleMapelIcon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span id="toggleMapelText">Tambah Mapel</span>
                        </button>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive-clean">
                        <table class="mapel-table" id="mapelMainTable">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">No</th>
                                    <th style="width: 50%;">Nama Mapel (Klik untuk Detail)</th>
                                    <th style="width: 25%;">Jumlah Pengampu</th>
                                    <th style="width: 15%; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mapel as $index => $m)
                                    <tr id="row-mapel-{{ $m->id_mapel }}" class="{{ (isset($defaultMapel) && $defaultMapel && $m->id_mapel == $defaultMapel->id_mapel) ? 'selected-active-row' : '' }}">
                                        <td class="td-no">{{ $loop->iteration + ($mapel->currentPage() - 1) * $mapel->perPage() }}</td>
                                        <td style="font-weight: 700; color: #1e2538; cursor: pointer;" class="td-nama-mapel" title="Klik untuk melihat detail mapel" onclick="loadMapelDetail({{ $m->id_mapel }})">
                                            {{ $m->nama_mapel }}
                                        </td>
                                        <td style="cursor: pointer;" onclick="loadMapelDetail({{ $m->id_mapel }})">
                                            <span class="badge-pengampu-pill">{{ $m->jumlah_pengampu }} Pengampu</span>
                                        </td>
                                        <td>
                                            <div class="action-icons-cell">
                                                <!-- Edit Button (Inline Row Edit) -->
                                                <button type="button" class="action-btn-icon edit" title="Edit Mapel" onclick="startInlineEditMapel({{ $m->id_mapel }}, '{{ addslashes($m->nama_mapel) }}')">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                </button>

                                                <!-- Trash Delete Button -->
                                                <button type="button" class="action-btn-icon delete" title="Hapus Mapel" onclick="deleteMapelAjax({{ $m->id_mapel }})">
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
                                        <td colspan="4" style="text-align: center; padding: 30px; color: #847e73;">
                                            Belum ada data mata pelajaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Pagination Row -->
                    <div class="table-pagination-row">
                        <span class="pagination-summary-text" id="paginationSummaryText">
                            Menampilkan {{ $mapel->firstItem() ?? 0 }} - {{ $mapel->lastItem() ?? 0 }} dari {{ $mapel->total() }} data
                        </span>

                        <div class="pagination-nav-group">
                            {{ $mapel->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>

                <!-- ===========================================================
                     RIGHT COLUMN: Stacked Panel Cards ("Tambah Mapel" & "Detail Mapel")
                     =========================================================== -->
                <div class="mapel-right-stack">
                    
                    <!-- Panel Card 1: Form "Tambah Mapel" (Collapsible Hide/Unhide) -->
                    <div class="mapel-panel-card" id="tambahMapelPanelCard" style="display: flex;">
                        <div class="mapel-panel-title" style="justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                <span>Tambah Mapel</span>
                            </div>
                            <button type="button" onclick="toggleTambahMapelForm()" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: #94a3b8; line-height: 1;" title="Sembunyikan Form">&times;</button>
                        </div>

                        <div id="createMapelAlert"></div>

                        <form id="createMapelForm" style="display: flex; flex-direction: column; gap: 14px;">
                            @csrf
                            <div class="form-field-group" style="margin-bottom: 0;">
                                <label for="input_nama_mapel">Nama Mapel</label>
                                <input type="text" name="nama_mapel" id="input_nama_mapel" class="form-field-input" placeholder="Masukkan Nama Mapel" required autocomplete="off">
                            </div>

                            <button type="submit" class="btn-modal-submit" style="width: 100%; padding: 11px; margin-top: 4px;">
                                Simpan Mapel
                            </button>
                        </form>
                    </div>

                    <!-- Panel Card 2: "Detail Mapel" Card (Triggered on Row Click) -->
                    <div class="mapel-panel-card" id="detailMapelPanelCard">
                        <div class="mapel-panel-title" style="justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                <span>Detail Mapel</span>
                            </div>
                            <button type="button" onclick="closeDetailMapelCard()" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: #94a3b8; line-height: 1;" title="Sembunyikan Detail">&times;</button>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            <!-- Detail Row 1: Nama Mapel -->
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                                <span style="font-size: 0.85rem; font-weight: 700; color: #64748b;">Nama Mapel</span>
                                <span class="badge-mapel-tan" id="detailNamaMapel">
                                    {{ $defaultMapel->nama_mapel ?? 'Bahasa Inggris' }}
                                </span>
                            </div>

                            <!-- Detail Row 2: Jumlah Pengampu -->
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                                <span style="font-size: 0.85rem; font-weight: 700; color: #64748b;">Jumlah Pengampu</span>
                                <span class="badge-mapel-pink" id="detailJumlahPengampu">
                                    {{ $defaultTeachers->count() }} Pengampu
                                </span>
                            </div>

                            <!-- Detail Section 3: Daftar Pengampu List -->
                            <div style="margin-top: 6px; display: flex; flex-direction: column; gap: 10px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-size: 0.85rem; font-weight: 800; color: #1e2538;">Daftar Pengampu</span>
                                    <button type="button" class="btn-lihat-semua-pill" onclick="alert('Menampilkan seluruh daftar pengampu...')">Lihat Semua</button>
                                </div>

                                <div id="detailTeacherList" style="display: flex; flex-direction: column; gap: 10px; max-height: 180px; overflow-y: auto;">
                                    @forelse($defaultTeachers as $gt)
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="teacher-avatar-circle"></div>
                                            <span style="font-size: 0.85rem; font-weight: 700; color: #334155;">{{ $gt->nama_guru }}</span>
                                        </div>
                                    @empty
                                        <div style="font-size: 0.825rem; color: #94a3b8; font-weight: 600;">Belum ada guru pengampu terdaftar.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </main>

    </div>

    <!-- (Pop-up modal removed in favor of direct inline table row edit) -->

    <!-- Full Single-Page AJAX Scripts -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let editingMapelId = null;

        function toggleSubmenu(id) {
            const el = document.getElementById(id);
            if (el) el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'flex' : 'none';
        }

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

        /* ---- Toggle (Hide / Unhide) Form Panel ---- */
        function toggleTambahMapelForm() {
            const panel = document.getElementById('tambahMapelPanelCard');
            const btnText = document.getElementById('toggleMapelText');
            if (!panel) return;

            if (panel.style.display === 'none') {
                panel.style.display = 'flex';
                if (btnText) btnText.innerText = '- Sembunyikan Form';
                const input = document.getElementById('input_nama_mapel');
                if (input) input.focus();
            } else {
                panel.style.display = 'none';
                if (btnText) btnText.innerText = 'Tambah Mapel';
            }
        }

        /* ---- Close Detail Panel Card ---- */
        function closeDetailMapelCard() {
            const card = document.getElementById('detailMapelPanelCard');
            if (card) card.style.display = 'none';
        }

        /* ---- Load Mapel Detail into Right Panel via AJAX on Row Click ---- */
        function loadMapelDetail(id) {
            if (editingMapelId === id) return;

            const detailCard = document.getElementById('detailMapelPanelCard');
            if (detailCard) detailCard.style.display = 'flex';

            document.querySelectorAll('#mapelMainTable tbody tr').forEach(r => r.classList.remove('selected-active-row'));
            const selectedRow = document.getElementById('row-mapel-' + id);
            if (selectedRow) selectedRow.classList.add('selected-active-row');

            fetch('/mapel/' + id, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('detailNamaMapel').innerText = data.nama_mapel;
                document.getElementById('detailJumlahPengampu').innerText = data.jumlah_pengampu + ' Pengampu';

                const listEl = document.getElementById('detailTeacherList');
                if (listEl) {
                    if (!data.gurus || data.gurus.length === 0) {
                        listEl.innerHTML = `<div style="font-size: 0.825rem; color: #94a3b8; font-weight: 600;">Belum ada guru pengampu terdaftar.</div>`;
                    } else {
                        listEl.innerHTML = data.gurus.map(g => `
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="teacher-avatar-circle"></div>
                                <span style="font-size: 0.85rem; font-weight: 700; color: #334155;">${g.nama_guru}</span>
                            </div>
                        `).join('');
                    }
                }
            });
        }

        /* ---- Direct Inline Table Row Edit ---- */
        function startInlineEditMapel(id, currentName) {
            if (editingMapelId && editingMapelId !== id) {
                cancelInlineEditMapel(editingMapelId);
            }

            editingMapelId = id;

            const row = document.getElementById('row-mapel-' + id);
            if (!row) return;

            const tdName = row.querySelector('.td-nama-mapel');
            const tdAction = row.querySelector('.action-icons-cell');

            if (tdName && tdAction) {
                tdName.onclick = null;
                tdName.style.cursor = 'default';
                tdName.innerHTML = `
                    <input type="text" id="inline-input-mapel-${id}" 
                        class="form-field-input" 
                        value="${currentName.replace(/"/g, '&quot;')}" 
                        style="padding: 6px 12px; font-size: 0.875rem; height: 36px; border-radius: 8px; border: 1.5px solid var(--dash-navy); width: 100%; box-sizing: border-box;"
                        onkeydown="handleInlineKeydown(event, ${id})"
                        autocomplete="off">
                `;

                tdAction.innerHTML = `
                    <button type="button" class="action-btn-icon" style="background-color: #dcfce7; color: #15803d; border: 1px solid #86efac;" title="Simpan (Enter)" onclick="saveInlineEditMapel(${id})">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </button>
                    <button type="button" class="action-btn-icon" style="background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5;" title="Batal (Esc)" onclick="cancelInlineEditMapel(${id})">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                `;

                const input = document.getElementById(`inline-input-mapel-${id}`);
                if (input) {
                    input.focus();
                    input.select();
                }
            }
        }

        function handleInlineKeydown(event, id) {
            if (event.key === 'Enter') {
                event.preventDefault();
                saveInlineEditMapel(id);
            } else if (event.key === 'Escape') {
                event.preventDefault();
                cancelInlineEditMapel(id);
            }
        }

        function cancelInlineEditMapel(id) {
            editingMapelId = null;
            reloadMapelTable();
        }

        function saveInlineEditMapel(id) {
            const input = document.getElementById(`inline-input-mapel-${id}`);
            if (!input) return;

            const newName = input.value.trim();
            if (!newName) {
                showToast('Nama mata pelajaran tidak boleh kosong.', 'error');
                input.focus();
                return;
            }

            const formData = new FormData();
            formData.append('nama_mapel', newName);
            formData.append('_method', 'PUT');

            fetch('/mapel/' + id, {
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
                    showToast(errMsg, 'error');
                } else {
                    editingMapelId = null;
                    showToast(data.success || 'Mata pelajaran berhasil diperbarui.');
                    reloadMapelTable();
                    loadMapelDetail(id);
                }
            })
            .catch(() => {
                showToast('Terjadi kesalahan koneksi server.', 'error');
            });
        }

        /* ---- Reload Main Mapel Table via AJAX ---- */
        function reloadMapelTable() {
            editingMapelId = null;
            const input = document.getElementById('mapelSearchInput');
            const q = input ? input.value.trim() : '';
            const params = new URLSearchParams();
            if (q) params.append('search', q);

            fetch('/mapel?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(resData => {
                const tbody = document.querySelector('#mapelMainTable tbody');
                if (!tbody) return;

                if (!resData.data || resData.data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px; color: #847e73;">
                                Belum ada data mata pelajaran.
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = resData.data.map((m, i) => `
                        <tr id="row-mapel-${m.id_mapel}">
                            <td class="td-no">${i + 1}</td>
                            <td style="font-weight: 700; color: #1e2538; cursor: pointer;" class="td-nama-mapel" title="Klik untuk melihat detail mapel" onclick="loadMapelDetail(${m.id_mapel})">${m.nama_mapel}</td>
                            <td style="cursor: pointer;" onclick="loadMapelDetail(${m.id_mapel})">
                                <span class="badge-pengampu-pill">${m.jumlah_pengampu} Pengampu</span>
                            </td>
                            <td>
                                <div class="action-icons-cell">
                                    <button type="button" class="action-btn-icon edit" title="Edit Mapel" onclick="startInlineEditMapel(${m.id_mapel}, '${m.nama_mapel.replace(/'/g, "\\'")}')">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" class="action-btn-icon delete" title="Hapus Mapel" onclick="deleteMapelAjax(${m.id_mapel})">
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
                const pagText = document.getElementById('paginationSummaryText');
                const statMapel = document.getElementById('statTotalMapel');
                if (pagText && pag) pagText.innerText = `Menampilkan ${pag.first} - ${pag.last} dari ${pag.total} data`;
                if (statMapel && pag) statMapel.innerText = pag.total;
            });
        }

        /* ---- Real-Time Search (No Refresh) ---- */
        (function() {
            const input = document.getElementById('mapelSearchInput');
            if (!input) return;

            input.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const tbody = document.querySelector('#mapelMainTable tbody');
                if (!tbody) return;

                const rows = Array.from(tbody.querySelectorAll('tr'));
                rows.forEach(function(row) {
                    const name = (row.querySelector('.td-nama-mapel') || {}).textContent || '';
                    if (q === '' || name.toLowerCase().includes(q)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        })();

        /* ---- Submit Create Mapel Form (AJAX) ---- */
        const createForm = document.getElementById('createMapelForm');
        if (createForm) {
            createForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const alertBox = document.getElementById('createMapelAlert');

                fetch('/mapel', {
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
                        const errMsg = data.error || (data.errors ? Object.values(data.errors).flat().join('<br>') : 'Gagal menyimpan mata pelajaran.');
                        if (alertBox) alertBox.innerHTML = `<div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> <span>${errMsg}</span></div>`;
                    } else {
                        if (alertBox) alertBox.innerHTML = '';
                        this.reset();
                        showToast(data.success || 'Mata pelajaran berhasil ditambahkan.');
                        reloadMapelTable();
                        if (data.mapel && data.mapel.id_mapel) {
                            loadMapelDetail(data.mapel.id_mapel);
                        }
                    }
                })
                .catch(() => {
                    if (alertBox) alertBox.innerHTML = `<div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> <span>Terjadi kesalahan koneksi server.</span></div>`;
                });
            });
        }

        /* ---- Delete Mapel via AJAX ---- */
        function deleteMapelAjax(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')) return;

            fetch('/mapel/' + id, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                showToast(data.success || 'Mata pelajaran berhasil dihapus.');
                reloadMapelTable();
            });
        }

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