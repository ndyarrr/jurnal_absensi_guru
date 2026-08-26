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
                        <h1 class="dash-header-title">Master Data - Kelas & Jurusan</h1>
                        <p class="dash-header-subtitle">Pengelolaan rombongan belajar dan kompetensi keahlian</p>
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
                 Inline Detail Panel: Daftar Siswa Per Kelas (Tanpa Pop-up Overlay)
                 --------------------------------------------------------------- -->
            <div id="inlineDetailPanel" style="display: none; margin-bottom: 24px; background: #ffffff; border: 1px solid var(--dash-border-subtle); border-radius: 18px; padding: 20px; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 12px; background: #e0f2fe; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="22" height="22" fill="none" stroke="#0284c7" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 id="inline_nama_kelas" style="font-size: 1.15rem; font-weight: 800; color: #1e2538; margin: 0;">Detail Kelas</h3>
                            <small id="inline_sub_info" style="color: #64748b; font-weight: 600;">Memuat info kelas...</small>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span id="inline_siswa_badge" style="font-size: 0.8rem; background: #e0f2fe; color: #0369a1; padding: 5px 14px; border-radius: 20px; font-weight: 700;">0 Siswa</span>
                        <button type="button" onclick="closeInlineDetailPanel()" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; width: 32px; height: 32px; font-size: 1.2rem; cursor: pointer; color: #475569; display: flex; align-items: center; justify-content: center;" title="Tutup Detail Kelas">&times;</button>
                    </div>
                </div>

                <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                        <thead>
                            <tr style="background: var(--dash-navy); color: #ffffff;">
                                <th style="padding: 11px 16px; text-align: left; width: 60px; font-weight: 700; border-top-left-radius: 10px;">No</th>
                                <th style="padding: 11px 16px; text-align: left; width: 140px; font-weight: 700;">NISN</th>
                                <th style="padding: 11px 16px; text-align: left; font-weight: 700;">Nama Siswa</th>
                                <th style="padding: 11px 16px; text-align: center; width: 120px; font-weight: 700; border-top-right-radius: 10px;">Jenis Kelamin</th>
                            </tr>
                        </thead>
                        <tbody id="inline_siswa_list_body">
                            <tr><td colspan="4" style="text-align: center; padding: 20px; color: #94a3b8;">Memuat data siswa...</td></tr>
                        </tbody>
                    </table>
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
                        <label for="create_jumlah_siswa">Pagu Kelas (Kapasitas Maksimal)</label>
                        <input type="number" name="jumlah_siswa" id="create_jumlah_siswa" class="form-field-input" placeholder="Contoh: 36" min="1" value="36">
                    </div>

                    <div>
                        <button type="submit" class="btn-modal-submit" style="width: 100%; padding: 11px;">Simpan Kelas</button>
                    </div>
                </form>
            </div>

            <!-- ---------------------------------------------------------------
                 Data Table Card Component (Matching Mockup Design)
                 --------------------------------------------------------------- -->
            <div class="kelas-table-card" data-ajax-pagination="main">
                <div class="table-responsive-clean">
                    <table class="kelas-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 30%;">Kelas</th>
                                <th style="width: 30%;">Wali Kelas</th>
                                <th style="width: 15%;">Siswa / Pagu</th>
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
                                                <a href="javascript:void(0)" onclick="openViewModal({{ $k->id_kelas }})" style="font-weight: 800; font-size: 1.05rem; color: #1e2538; text-decoration: none; cursor: pointer;" onmouseover="this.style.color='var(--dash-navy)'; this.style.textDecoration='underline'" onmouseout="this.style.color='#1e2538'; this.style.textDecoration='none'" title="Klik untuk lihat daftar siswa kelas ini">
                                                    {{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan ?? '' }} {{ $k->rombel }}
                                                </a>
                                                <div><small style="color: #64748b; font-weight: 600;">{{ optional($k->jurusan)->nama_jurusan ?? '-' }}</small></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #1e2538;">
                                            @if($k->waliKelasGuru && $k->waliKelasGuru->trashed())
                                                <span class="badge-warning-deleted" title="Wali kelas ini telah dihapus"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="16" x2="12.01" y2="17"></line></svg> -</span>
                                            @else
                                                {{ $k->wali_kelas ?: (optional($k->waliKelasGuru)->nama_guru ?? 'Belum Ada Wali Kelas') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-status-aktif" style="background-color: #f1f5f9; color: #334155; border-color: #cbd5e1; font-weight: 700;" title="Jumlah Siswa Terdaftar / Pagu Maksimal">
                                            {{ $k->siswa_count ?? $k->siswa()->count() }}/{{ $k->jumlah_siswa ?? 36 }} Siswa
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

            <!-- ---------------------------------------------------------------
                 Data Jurusan Table Card
                 --------------------------------------------------------------- -->
            <div class="kelas-table-card" style="margin-top: 24px;">
                <div class="collapsible-form-header" style="margin-bottom: 4px; padding-bottom: 16px;">
                    <h3 class="collapsible-form-title">
                        <svg width="20" height="20" fill="none" stroke="var(--dash-navy)" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        <span>Daftar Jurusan / Kompetensi Keahlian</span>
                    </h3>
                    <span class="badge-kelas-count" style="padding: 8px 14px; font-size: 0.8rem;">{{ $totalJurusanCount }} Jurusan</span>
                </div>

                <div class="table-responsive-clean">
                    <table class="kelas-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 15%;">Kode</th>
                                <th style="width: 45%;">Nama Jurusan</th>
                                <th style="width: 15%;">Jumlah Kelas</th>
                                <th style="width: 20%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jurusanList as $j)
                                <tr>
                                    <td class="td-no">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="jurusan-code-badge">{{ $j->kode_jurusan }}</span>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #1e2538;">{{ $j->nama_jurusan }}</div>
                                    </td>
                                    <td>
                                        <span class="badge-status-aktif" style="background-color: #f1f5f9; color: #334155; border-color: #cbd5e1;">
                                            {{ $j->kelas_count }} Kelas
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-icons-cell">
                                            <button type="button" class="action-btn-icon view" title="Lihat Detail Jurusan" onclick="openViewJurusanModal({{ $j->id_jurusan }})">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </button>

                                            <button type="button" class="action-btn-icon edit" title="Edit Data Jurusan"
                                                data-id="{{ $j->id_jurusan }}"
                                                data-kode="{{ $j->kode_jurusan }}"
                                                data-nama="{{ $j->nama_jurusan }}"
                                                onclick="openEditJurusanModal(this)">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>

                                            <form action="{{ route('jurusan.destroy', $j) }}" method="POST" style="display: inline;"
                                                onsubmit="return confirm(@if($j->kelas_count > 0)'Jurusan {{ $j->kode_jurusan }} masih memiliki {{ $j->kelas_count }} kelas terkait dan tidak dapat dihapus.'@else'Apakah Anda yakin ingin menghapus jurusan {{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}?'@endif)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn-icon delete" title="{{ $j->kelas_count > 0 ? 'Tidak dapat dihapus — masih ada kelas terkait' : 'Hapus Jurusan' }}" @if($j->kelas_count > 0) disabled @endif>
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
                                        Belum ada data jurusan. Klik tombol <strong>Tambah Jurusan</strong> untuk menambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
         Edit Jurusan Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="editJurusanModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Edit Data Jurusan</h3>
                <button type="button" class="btn-close-modal" onclick="closeEditJurusanModal()">&times;</button>
            </div>

            <form id="editJurusanForm" method="POST" class="modal-form-grid">
                @csrf
                @method('PUT')

                <div class="form-field-group">
                    <label for="edit_kode_jurusan">Kode Singkatan Jurusan</label>
                    <input type="text" name="kode_jurusan" id="edit_kode_jurusan" class="form-field-input" placeholder="Contoh: RPL, TKJ, DKV, AKL" maxlength="10" required style="text-transform: uppercase;">
                </div>

                <div class="form-field-group">
                    <label for="edit_nama_jurusan">Nama Lengkap Jurusan</label>
                    <input type="text" name="nama_jurusan" id="edit_nama_jurusan" class="form-field-input" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditJurusanModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Update Jurusan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================
         View Jurusan Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="viewJurusanModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Detail Data Jurusan</h3>
                <button type="button" class="btn-close-modal" onclick="closeViewJurusanModal()">&times;</button>
            </div>

            <div class="modal-form-grid">
                <div class="form-field-group">
                    <label>Kode Jurusan:</label>
                    <div id="view_jurusan_kode" style="font-weight: 800; font-size: 1.1rem; color: var(--dash-navy);">-</div>
                </div>
                <div class="form-field-group">
                    <label>Nama Lengkap Jurusan:</label>
                    <div id="view_jurusan_nama" style="font-weight: 700; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Jumlah Kelas Terkait:</label>
                    <div id="view_jurusan_kelas_count" style="font-weight: 700; color: #059669;">-</div>
                </div>
            </div>

            <div class="modal-actions-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeViewJurusanModal()" style="width: 100%;">Tutup</button>
            </div>
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
                    <label for="edit_jumlah_siswa">Pagu Kelas (Kapasitas Maksimal)</label>
                    <input type="number" name="jumlah_siswa" id="edit_jumlah_siswa" class="form-field-input" min="1" placeholder="Default: 36">
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Update Kelas</button>
                </div>
            </form>
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

        /* ---- Edit Jurusan Modal ---- */
        function openEditJurusanModal(btn) {
            document.getElementById('editJurusanForm').action = '/jurusan/' + btn.dataset.id;
            document.getElementById('edit_kode_jurusan').value = btn.dataset.kode;
            document.getElementById('edit_nama_jurusan').value = btn.dataset.nama;
            document.getElementById('editJurusanModal').style.display = 'flex';
        }

        function closeEditJurusanModal() {
            document.getElementById('editJurusanModal').style.display = 'none';
        }

        /* ---- View Jurusan Modal ---- */
        function openViewJurusanModal(id) {
            fetch('/jurusan/' + id, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('view_jurusan_kode').innerText = data.kode_jurusan;
                    document.getElementById('view_jurusan_nama').innerText = data.nama_jurusan;
                    document.getElementById('view_jurusan_kelas_count').innerText = data.jumlah_kelas + ' Kelas';
                    document.getElementById('viewJurusanModal').style.display = 'flex';
                });
        }

        function closeViewJurusanModal() {
            document.getElementById('viewJurusanModal').style.display = 'none';
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
            const panel = document.getElementById('inlineDetailPanel');
            panel.style.display = 'block';

            document.getElementById('inline_nama_kelas').innerText = 'Memuat Data Kelas...';
            document.getElementById('inline_sub_info').innerText = 'Harap tunggu...';
            document.getElementById('inline_siswa_list_body').innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px; color: #64748b;">Memuat data siswa...</td></tr>';

            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            fetch('/kelas/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('inline_nama_kelas').innerText = 'Daftar Siswa - ' + data.nama_kelas;
                    document.getElementById('inline_sub_info').innerText = 'Jurusan: ' + data.kode_jurusan + ' • Wali Kelas: ' + data.wali_kelas + ' • Status Pagu: ' + data.jumlah_siswa;
                    document.getElementById('inline_siswa_badge').innerText = (data.siswa ? data.siswa.length : 0) + '/' + data.pagu + ' Siswa';

                    const tbody = document.getElementById('inline_siswa_list_body');
                    tbody.innerHTML = '';
                    if (data.siswa && data.siswa.length > 0) {
                        data.siswa.forEach((s, idx) => {
                            const tr = document.createElement('tr');
                            tr.style.borderBottom = '1px solid #e2e8f0';
                            tr.style.backgroundColor = (idx % 2 === 0) ? '#ffffff' : '#f8fafc';
                            tr.innerHTML = `
                                <td style="padding: 10px 16px; font-weight: 700; color: #475569;">${idx + 1}</td>
                                <td style="padding: 10px 16px; color: #334155; font-weight: 600;">${s.nisn}</td>
                                <td style="padding: 10px 16px; font-weight: 700; color: #1e2538;">${s.nama_siswa}</td>
                                <td style="padding: 10px 16px; text-align: center; color: #334155; font-weight: 600;">${s.jenis_kelamin}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 24px; color: #94a3b8; font-weight: 600;">Belum ada siswa terdaftar di kelas ini.</td></tr>';
                    }
                });
        }

        function closeInlineDetailPanel() {
            document.getElementById('inlineDetailPanel').style.display = 'none';
        }
        /* ---- Real-time Client-side Search (No Refresh) ---- */
        (function() {
            const input = document.getElementById('kelasSearchInput');
            if (!input) return;

            const listSection = document.querySelector('[data-ajax-pagination="main"]');
            if (!listSection) return;

            const tbody = listSection.querySelector('table tbody');
            if (!tbody) return;

            input.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const rows = tbody.querySelectorAll('tr');

                rows.forEach(function(row) {
                    const text = row.innerText.toLowerCase();
                    if (q === '' || text.includes(q)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

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
    <script src="/js/ajax-pagination.js"></script>
    <script src="/js/sidebar-toggle.js"></script>
    <script src="/js/live-clock.js"></script>
</body>
</html>