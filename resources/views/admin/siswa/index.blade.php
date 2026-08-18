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
</head>
<body class="dashboard-body">

    <div class="dash-layout">

        <!-- ===================================================================
             Left Sidebar Navigation
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
                        <!-- Siswa Active Sub Link -->
                        <li>
                            <a href="{{ route('siswa.index') }}" class="dash-sub-link" style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #ffffff;">
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
                    <h1 class="dash-header-title">Master Data - Siswa</h1>
                    <p class="dash-header-subtitle">Pengelolaan siswa</p>
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
                    <button type="button" class="btn-export-pill" onclick="exportSiswaCsv()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Export</span>
                    </button>

                    <!-- + Tambah Button -->
                    <button type="button" class="btn-siswa-tambah" onclick="openCreateModal()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Tambah</span>
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
                                <th style="width: 5%;">No</th>
                                <th style="width: 18%;">NISN</th>
                                <th style="width: 32%;">Nama Siswa</th>
                                <th style="width: 18%;">Kelas</th>
                                <th style="width: 12%; text-align: center;">Status</th>
                                <th style="width: 15%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $index => $s)
                                <tr id="row-siswa-{{ $s->id_siswa }}">
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
                                            <button type="button" class="action-btn-icon edit" title="Edit Data Siswa" onclick="startInlineEditSiswa({{ $s->id_siswa }}, '{{ addslashes($s->nisn) }}', '{{ addslashes($s->nama_siswa) }}', '{{ $s->id_kelas }}')">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>

                                            <!-- Delete Action -->
                                            <form action="{{ route('siswa.destroy', $s) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa {{ $s->nama_siswa }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn-icon delete" title="Hapus Siswa">
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
                                    <td colspan="6" style="text-align: center; padding: 40px; color: #847e73;">
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
            const dropdowns = ['tingkatMenu', 'jurusanMenu', 'rombelMenu'];
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

        /* ---- Inline Table Row Edit for Siswa ---- */
        function startInlineEditSiswa(id, nisn, nama, idKelas) {
            if (editingSiswaId && editingSiswaId !== id) {
                cancelInlineEditSiswa(editingSiswaId);
            }
            editingSiswaId = id;

            const row = document.getElementById('row-siswa-' + id);
            if (!row) return;

            const tdNisn = row.querySelector('.td-siswa-nisn');
            const tdNama = row.querySelector('.td-siswa-nama');
            const tdKelas = row.querySelector('.td-siswa-kelas');
            const tdAksi = row.querySelector('.action-icons-cell');

            if (tdNisn && tdNama && tdKelas && tdAksi) {
                tdNisn.innerHTML = `<input type="text" id="inline-nisn-${id}" class="form-field-input" value="${nisn}" maxlength="10" inputmode="numeric" oninput="this.value=this.value.replace(/\\D/g,'')" style="padding: 4px 8px; font-size: 0.825rem; font-family: monospace; width: 100%; border-radius: 6px; border: 1.5px solid var(--dash-navy);" autocomplete="off">`;
                
                tdNama.innerHTML = `<input type="text" id="inline-nama-${id}" class="form-field-input" value="${nama.replace(/"/g, '&quot;')}" style="padding: 4px 8px; font-size: 0.85rem; font-weight: 700; width: 100%; border-radius: 6px; border: 1.5px solid var(--dash-navy);" autocomplete="off">`;

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
            formData.append('id_kelas', idKelas);
            formData.append('_method', 'PUT');

            fetch('/siswa/' + id, {
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
                    document.getElementById('view_kelas_str').innerText = data.kelas_str;
                    document.getElementById('viewModal').style.display = 'flex';
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }
        /* ---- Real-time Client-side Search (No Refresh) ---- */
        (function() {
            const input = document.getElementById('siswaSearchInput');
            if (!input) return;

            const tbody = document.querySelector('.siswa-table tbody');
            if (!tbody) return;

            const rows = Array.from(tbody.querySelectorAll('tr'));

            input.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();

                rows.forEach(function(row) {
                    const nisn = (row.querySelector('.td-siswa-nisn') || {}).textContent || '';
                    const nama = (row.querySelector('.td-siswa-nama') || {}).textContent || '';
                    const kelas = (row.querySelector('.td-siswa-kelas') || {}).textContent || '';
                    const text = (nisn + ' ' + nama + ' ' + kelas).toLowerCase();

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
    <script src="/js/live-clock.js"></script>
</body>
</html>