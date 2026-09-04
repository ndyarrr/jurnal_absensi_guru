<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akademik - Jurnal Mengajar | Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Modular Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <script src="/js/sidebar-toggle.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <h1 class="dash-header-title">Akademik - Jurnal Mengajar</h1>
                        <p class="dash-header-subtitle">Catatan kegiatan mengajar guru setiap pertemuan</p>
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
                 Controls Bar: Search & Action Buttons
                 --------------------------------------------------------------- -->
            <div class="jurnal-controls-row">
                <div class="jurnal-search-box">
                    <svg class="jurnal-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="jurnalSearchInput" class="jurnal-search-input" placeholder="Cari Mata Pelajaran / Guru / Kelas..." value="{{ request('search') }}" autocomplete="off">
                </div>

                <div class="jurnal-action-group">
                    <button type="button" class="btn-export-pill" onclick="exportJurnalCsv()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Export</span>
                    </button>
                </div>
            </div>

            <!-- ---------------------------------------------------------------
                 Card Container: Daftar Jurnal Mengajar
                 --------------------------------------------------------------- -->
            <div class="jurnal-card-container" data-ajax-pagination="main">
                <div class="jurnal-card-header">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1"></rect>
                    </svg>
                    Daftar Jurnal Mengajar
                </div>

                <div class="jurnal-card-body">

                    <!-- Filter Bar -->
                    <div class="jurnal-filter-bar">
                        <div class="jurnal-date-pill">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <input type="date" id="filterDateFrom" onchange="reloadJurnalTable()" style="border: none; background: none; font-size: 0.85rem; font-weight: 600; color: #334155; outline: none; width: 110px;" value="{{ request('date_from') }}">
                            <span style="color: #94a3b8;">-</span>
                            <input type="date" id="filterDateTo" onchange="reloadJurnalTable()" style="border: none; background: none; font-size: 0.85rem; font-weight: 600; color: #334155; outline: none; width: 110px;" value="{{ request('date_to') }}">
                        </div>

                        <select id="filterGuru" class="jurnal-filter-select" onchange="reloadJurnalTable()">
                            <option value="">Semua Guru</option>
                            @foreach($gurus as $g)
                                <option value="{{ $g->id_guru }}" {{ request('id_guru') == $g->id_guru ? 'selected' : '' }}>{{ $g->nama_guru }}</option>
                            @endforeach
                        </select>

                        <select id="filterKelas" class="jurnal-filter-select" onchange="reloadJurnalTable()">
                            <option value="">Semua Kelas</option>
                            @foreach($kelases as $k)
                                <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}</option>
                            @endforeach
                        </select>

                        <select id="filterMapel" class="jurnal-filter-select" onchange="reloadJurnalTable()">
                            <option value="">Semua Mapel</option>
                            @foreach($mapels as $m)
                                <option value="{{ $m->id_mapel }}" {{ request('id_mapel') == $m->id_mapel ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>

                        <button type="button" class="btn-jurnal-filter" onclick="reloadJurnalTable()">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            Filter
                        </button>
                    </div>

                    <!-- Stat Cards -->
                    <div class="jurnal-stats-grid">
                        <div class="jurnal-stat-box">
                            <div class="jurnal-stat-icon stat-icon-purple">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                            <div class="jurnal-stat-info">
                                <span class="jurnal-stat-label">Total Pertemuan</span>
                                <div class="jurnal-stat-val-group">
                                    <span class="jurnal-stat-val" id="statTotal">{{ $totalPertemuan }}</span>
                                    <span class="jurnal-stat-unit">Pertemuan</span>
                                </div>
                            </div>
                        </div>

                        <div class="jurnal-stat-box">
                            <div class="jurnal-stat-icon stat-icon-green">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </div>
                            <div class="jurnal-stat-info">
                                <span class="jurnal-stat-label">Terlaksana</span>
                                <div class="jurnal-stat-val-group">
                                    <span class="jurnal-stat-val" id="statTerlaksana">{{ $terlaksana }}</span>
                                    <span class="jurnal-stat-unit">Pertemuan</span>
                                </div>
                            </div>
                        </div>

                        <div class="jurnal-stat-box">
                            <div class="jurnal-stat-icon stat-icon-amber">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </div>
                            <div class="jurnal-stat-info">
                                <span class="jurnal-stat-label">Belum Terlaksana</span>
                                <div class="jurnal-stat-val-group">
                                    <span class="jurnal-stat-val" id="statBelum">{{ $belumTerlaksana }}</span>
                                    <span class="jurnal-stat-unit">Pertemuan</span>
                                </div>
                            </div>
                        </div>

                        <div class="jurnal-stat-box">
                            <div class="jurnal-stat-icon stat-icon-blue">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div class="jurnal-stat-info">
                                <span class="jurnal-stat-label">Guru</span>
                                <div class="jurnal-stat-val-group">
                                    <span class="jurnal-stat-val" id="statGuru">{{ $totalGuruAktif }}</span>
                                    <span class="jurnal-stat-unit">Guru</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive-clean">
                        <table class="jurnal-table" id="jurnalMainTable">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">Tanggal</th>
                                    <th style="width: 15%;">Mata Pelajaran</th>
                                    <th style="width: 10%;">Kelas</th>
                                    <th style="width: 15%;">Guru</th>
                                    <th style="width: 18%;">Materi</th>
                                    <th style="width: 10%;">Murid Hadir</th>
                                    <th style="width: 12%;">Status</th>
                                    <th style="width: 10%; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jurnal as $j)
                                    @php
                                        $isMapelDel = !optional($j->jadwal)->mapel || optional(optional($j->jadwal)->mapel)->trashed();
                                        $isKelasDel = !optional($j->jadwal)->kelas || optional(optional($j->jadwal)->kelas)->trashed();
                                        $isGuruDel  = !optional($j->jadwal)->guru || optional(optional($j->jadwal)->guru)->trashed();

                                        $jumlahSiswa = 0;
                                        if ($j->jadwal && $j->jadwal->kelas) {
                                            $jumlahSiswa = $j->jadwal->kelas->jumlah_siswa_real;
                                        }
                                    @endphp
                                    <tr id="row-jurnal-{{ $j->id_jurnal }}">
                                        <td>
                                            <div class="date-block-flex">
                                                <span class="date-day-num">{{ \Carbon\Carbon::parse($j->tanggal)->format('d') }}</span>
                                                <div>
                                                    <div class="date-month-year">{{ strtoupper(\Carbon\Carbon::parse($j->tanggal)->translatedFormat('M')) }}</div>
                                                    <div class="date-month-year">{{ \Carbon\Carbon::parse($j->tanggal)->format('Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="font-weight: 700;">
                                            @if($isMapelDel)
                                                <span class="badge-warning-deleted" title="Mata pelajaran ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>
                                            @else
                                                {{ $j->jadwal->mapel->nama_mapel }}
                                            @endif
                                        </td>
                                        <td style="font-weight: 700;">
                                            @if($isKelasDel)
                                                <span class="badge-warning-deleted" title="Kelas ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>
                                            @else
                                                {{ optional($j->jadwal)->kelas->tingkat }} {{ optional(optional($j->jadwal)->kelas->jurusan)->kode_jurusan }} {{ optional($j->jadwal)->kelas->rombel }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($isGuruDel)
                                                <span class="badge-warning-deleted" title="Guru ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>
                                            @else
                                                {{ $j->jadwal->guru->nama_guru }}
                                            @endif
                                        </td>
                                        <td>
                                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $j->materi ?? '-' }}">
                                                {{ $j->materi ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $hadir = $j->jumlah_hadir ?? 0;
                                                $total = $jumlahSiswa;
                                                $pct = $total > 0 ? round(($hadir / $total) * 100) : 0;
                                                $color = $pct >= 80 ? '#15803d' : ($pct >= 60 ? '#b45309' : '#dc2626');
                                                $bg    = $pct >= 80 ? '#dcfce7' : ($pct >= 60 ? '#fef3c7' : '#fee2e2');
                                            @endphp
                                            <span style="
                                                font-weight: 800; font-size: 0.9rem;
                                                color: {{ $color }};
                                                background: {{ $bg }};
                                                padding: 3px 10px; border-radius: 20px;
                                                white-space: nowrap;
                                            ">{{ $hadir }}/{{ $total }}</span>
                                        </td>
                                        <td>
                                            @if($j->status_kehadiran == 'Hadir')
                                                <span class="badge-status-terlaksana">Terlaksana</span>
                                            @else
                                                <span class="badge-status-belum">Belum Terlaksana</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-icons-cell">
                                                <button type="button" class="action-btn-icon view" title="Lihat Detail" onclick="openViewModal({{ $j->id_jurnal }})">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </button>
                                                <button type="button" class="action-btn-icon delete" title="Hapus Jurnal" onclick="deleteJurnalAjax({{ $j->id_jurnal }})">
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
                                        <td colspan="8" style="text-align: center; padding: 40px; color: #847e73;">
                                            Belum ada data jurnal mengajar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Pagination Row -->
                    <div class="table-pagination-row">
                        <span class="pagination-summary-text" id="paginationSummary">
                            Menampilkan {{ $jurnal->firstItem() ?? 0 }} - {{ $jurnal->lastItem() ?? 0 }} dari {{ $jurnal->total() }} data
                        </span>

                        <div class="pagination-nav-group" id="paginationLinks">
                            {{ $jurnal->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>

        </main>

    </div>

    <!-- ===================================================================
         View Detail Modal
         =================================================================== -->
    <div id="viewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.45); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: #ffffff; border-radius: 20px; padding: 32px; width: 500px; max-width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.2); position: relative;">
            <button type="button" onclick="closeViewModal()" style="position: absolute; top: 16px; right: 16px; background: none; border: none; cursor: pointer; color: #64748b; font-size: 1.2rem;">✕</button>

            <h3 style="font-size: 1.15rem; font-weight: 800; color: #1e2538; margin-bottom: 20px;">📋 Detail Jurnal Mengajar</h3>

            <div style="display: grid; gap: 14px;">
                <div><span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Tanggal</span><div id="view_tanggal" style="font-weight: 700; color: #1e2538; margin-top: 2px;">-</div></div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div><span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Hari & Jam</span><div id="view_hari_jam" style="font-weight: 700; color: #1e2538; margin-top: 2px;">-</div></div>
                    <div><span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Status</span><div id="view_status" style="font-weight: 700; color: #1e2538; margin-top: 2px;">-</div></div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div><span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Kelas</span><div id="view_kelas" style="font-weight: 700; color: #1e2538; margin-top: 2px;">-</div></div>
                    <div><span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Mata Pelajaran</span><div id="view_mapel" style="font-weight: 700; color: #1e2538; margin-top: 2px;">-</div></div>
                </div>
                <div><span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Guru Pengajar</span><div id="view_guru" style="font-weight: 700; color: #1e2538; margin-top: 2px;">-</div></div>
                <div><span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Materi</span><div id="view_materi" style="font-weight: 700; color: #1e2538; margin-top: 2px;">-</div></div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div><span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Jumlah Hadir</span><div id="view_hadir" style="font-weight: 700; color: #1e2538; margin-top: 2px;">0</div></div>
                    <div><span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Jumlah Tidak Hadir</span><div id="view_tidak_hadir" style="font-weight: 700; color: #1e2538; margin-top: 2px;">0</div></div>
                </div>
                <div><span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Catatan</span><div id="view_catatan" style="font-weight: 700; color: #1e2538; margin-top: 2px;">-</div></div>
            </div>
        </div>
    </div>

    <!-- ===================================================================
         JavaScript
         =================================================================== -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        /* ---- Helper: Escape HTML ---- */
        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        /* ---- Toast Alert Function ---- */
        function showToast(message, type = 'success') {
            const container = document.getElementById('ajaxAlertContainer');
            if (!container) return;

            const bgColor = type === 'success' ? '#ecfdf5' : '#fef2f2';
            const borderColor = type === 'success' ? '#a7f3d0' : '#fecaca';
            const textColor = type === 'success' ? '#065f46' : '#991b1b';
            const iconSvg = type === 'success' 
                ? '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>'
                : '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>';

            const alertEl = document.createElement('div');
            alertEl.className = 'flash-alert';
            alertEl.style.cssText = `background-color: ${bgColor}; border: 1px solid ${borderColor}; color: ${textColor}; padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 600; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;`;
            alertEl.innerHTML = `${iconSvg} <span>${escapeHtml(message)}</span>`;

            container.appendChild(alertEl);

            setTimeout(() => {
                alertEl.style.opacity = '0';
                alertEl.style.transform = 'translateY(-10px)';
                setTimeout(() => alertEl.remove(), 500);
            }, 3000);
        }

        /* ---- Auto-fade Session Flash Alerts ---- */
        setTimeout(function() {
            document.querySelectorAll('.flash-alert').forEach(function(el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 500);
            });
        }, 3000);

        function exportJurnalCsv() {
            const params = new URLSearchParams();
            const searchVal = document.getElementById('jurnalSearchInput')?.value || '';
            const dateFrom = document.getElementById('filterDateFrom')?.value || '';
            const dateTo = document.getElementById('filterDateTo')?.value || '';
            const guruId = document.getElementById('filterGuru')?.value || '';
            const kelasId = document.getElementById('filterKelas')?.value || '';
            const mapelId = document.getElementById('filterMapel')?.value || '';

            if (searchVal) params.append('search', searchVal);
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);
            if (guruId) params.append('id_guru', guruId);
            if (kelasId) params.append('id_kelas', kelasId);
            if (mapelId) params.append('id_mapel', mapelId);

            window.location.href = '{{ route('jurnal.export-csv') }}' + (params.toString() ? '?' + params.toString() : '');
        }

        /* ---- Reload Main Jurnal Table via AJAX ---- */
        function reloadJurnalTable() {
            const searchVal = document.getElementById('jurnalSearchInput')?.value || '';
            const dateFrom = document.getElementById('filterDateFrom')?.value || '';
            const dateTo = document.getElementById('filterDateTo')?.value || '';
            const guruId = document.getElementById('filterGuru')?.value || '';
            const kelasId = document.getElementById('filterKelas')?.value || '';
            const mapelId = document.getElementById('filterMapel')?.value || '';

            const params = new URLSearchParams();
            if (searchVal) params.append('search', searchVal);
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);
            if (guruId) params.append('id_guru', guruId);
            if (kelasId) params.append('id_kelas', kelasId);
            if (mapelId) params.append('id_mapel', mapelId);

            fetch('/jurnal?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(resData => {
                const tbody = document.querySelector('#jurnalMainTable tbody');
                if (!tbody) return;

                if (!resData.data || resData.data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #847e73;">
                                Belum ada data jurnal mengajar.
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = resData.data.map(j => {
                        const mapelHtml = j.is_mapel_deleted 
                            ? `<span class="badge-warning-deleted" title="Mata pelajaran ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>`
                            : escapeHtml(j.mapel);

                        const kelasHtml = j.is_kelas_deleted
                            ? `<span class="badge-warning-deleted" title="Kelas ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>`
                            : escapeHtml(j.kelas);

                        const guruHtml = j.is_guru_deleted
                            ? `<span class="badge-warning-deleted" title="Guru ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> -</span>`
                            : escapeHtml(j.guru);

                        const statusBadge = j.status_kehadiran === 'Hadir'
                            ? `<span class="badge-status-terlaksana">Terlaksana</span>`
                            : `<span class="badge-status-belum">Belum Terlaksana</span>`;

                        return `
                            <tr id="row-jurnal-${j.id_jurnal}">
                                <td>
                                    <div class="date-block-flex">
                                        <span class="date-day-num">${j.tanggal_day}</span>
                                        <div>
                                            <div class="date-month-year">${j.tanggal_month}</div>
                                            <div class="date-month-year">${j.tanggal_year}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-weight: 700;">${mapelHtml}</td>
                                <td style="font-weight: 700;">${kelasHtml}</td>
                                <td>${guruHtml}</td>
                                <td>
                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${escapeHtml(j.materi)}">
                                        ${escapeHtml(j.materi)}
                                    </div>
                                </td>
                                <td>
                                    ${(() => {
                                        const parts = (j.pertemuan || '0/0').split('/');
                                        const h = parseInt(parts[0]) || 0;
                                        const t = parseInt(parts[1]) || 1;
                                        const pct = Math.round((h / t) * 100);
                                        const color = pct >= 80 ? '#15803d' : (pct >= 60 ? '#b45309' : '#dc2626');
                                        const bg    = pct >= 80 ? '#dcfce7' : (pct >= 60 ? '#fef3c7' : '#fee2e2');
                                        return `<span style="font-weight:800;font-size:0.9rem;color:${color};background:${bg};padding:3px 10px;border-radius:20px;white-space:nowrap;">${h}/${t}</span>`;
                                    })()}
                                </td>
                                <td>${statusBadge}</td>
                                <td>
                                    <div class="action-icons-cell">
                                        <button type="button" class="action-btn-icon view" title="Lihat Detail" onclick="openViewModal(${j.id_jurnal})">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>
                                        <button type="button" class="action-btn-icon delete" title="Hapus Jurnal" onclick="deleteJurnalAjax(${j.id_jurnal})">
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

                // Update Stat Cards
                if (resData.stats) {
                    const statTotal = document.getElementById('statTotal');
                    const statTerlaksana = document.getElementById('statTerlaksana');
                    const statBelum = document.getElementById('statBelum');
                    const statGuru = document.getElementById('statGuru');

                    if (statTotal) statTotal.innerText = resData.stats.total_pertemuan;
                    if (statTerlaksana) statTerlaksana.innerText = resData.stats.terlaksana;
                    if (statBelum) statBelum.innerText = resData.stats.belum_terlaksana;
                    if (statGuru) statGuru.innerText = resData.stats.total_guru_aktif;
                }

                // Update Pagination Summary Text
                if (resData.pagination) {
                    const pagSummary = document.getElementById('paginationSummary');
                    if (pagSummary) {
                        pagSummary.innerText = `Menampilkan ${resData.pagination.first} - ${resData.pagination.last} dari ${resData.pagination.total} data`;
                    }
                }
            })
            .catch(err => console.error('Error reloading jurnal table:', err));
        }

        /* ---- Real-Time Search Debouncing ---- */
        let searchTimeout = null;
        const searchInput = document.getElementById('jurnalSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    reloadJurnalTable();
                }, 300);
            });
        }

        /* ---- Open View Modal via AJAX ---- */
        function openViewModal(id) {
            fetch('/jurnal/' + id, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('view_tanggal').innerText = data.tanggal || '-';
                document.getElementById('view_hari_jam').innerText = (data.hari || '-') + ' / Jam ke-' + (data.jam_ke || '-');
                document.getElementById('view_status').innerText = data.status_kehadiran || '-';
                document.getElementById('view_kelas').innerText = data.kelas || '-';
                document.getElementById('view_mapel').innerText = data.mapel || '-';
                document.getElementById('view_guru').innerText = data.guru || '-';
                document.getElementById('view_materi').innerText = data.materi || '-';
                document.getElementById('view_hadir').innerText = data.jumlah_hadir ?? 0;
                document.getElementById('view_tidak_hadir').innerText = data.jumlah_tidak_hadir ?? 0;
                document.getElementById('view_catatan').innerText = data.catatan || '-';

                const modal = document.getElementById('viewModal');
                if (modal) {
                    modal.style.display = 'flex';
                }
            })
            .catch(err => {
                console.error('Error fetching detail jurnal:', err);
                showToast('Gagal memuat detail jurnal.', 'error');
            });
        }

        /* ---- Close View Modal ---- */
        function closeViewModal() {
            const modal = document.getElementById('viewModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        /* ---- Close View Modal when Clicking Outside ---- */
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('viewModal');
            if (e.target === modal) {
                closeViewModal();
            }
        });

        /* ---- Delete Jurnal via AJAX ---- */
        function deleteJurnalAjax(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus data jurnal mengajar ini?')) {
                return;
            }

            fetch('/jurnal/' + id, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                showToast(data.success || 'Jurnal mengajar berhasil dihapus.', 'success');
                reloadJurnalTable();
            })
            .catch(err => {
                console.error('Error deleting jurnal:', err);
                showToast('Gagal menghapus jurnal mengajar.', 'error');
            });
        }
    </script>
    <script src="/js/ajax-pagination.js"></script>
    <script src="/js/sidebar-toggle.js"></script>
    <script src="/js/live-clock.js"></script>
</body>
</html>