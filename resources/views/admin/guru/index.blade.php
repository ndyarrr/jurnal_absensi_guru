<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data - Guru | Admin</title>

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

        <!-- ===================================================================
             Left Sidebar Navigation
             =================================================================== -->
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
                        <h1 class="dash-header-title">Master Data - Guru</h1>
                        <p class="dash-header-subtitle">Pengelolaan guru fleksibel</p>
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
                 Controls Bar: Search & Action Buttons (Matching Mockup)
                 --------------------------------------------------------------- -->
            <div class="guru-controls-row">
                <!-- Search Form -->
                <form action="{{ route('guru.index') }}" method="GET" class="guru-search-box">
                    @if(request('id_mapel'))
                        <input type="hidden" name="id_mapel" value="{{ request('id_mapel') }}">
                    @endif
                    <svg class="guru-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="search" id="guruSearchInput" class="guru-search-input" placeholder="Cari Guru NUPTK/NAMA..." value="{{ request('search') }}" autocomplete="off">
                </form>

                <!-- Action Controls Group -->
                <div class="guru-action-group">
                    <button type="button" class="btn-export-pill" onclick="exportGuruCsv()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Export</span>
                    </button>

                    <!-- + Tambah Button -->
                    <button type="button" class="btn-guru-tambah" onclick="openCreateModal()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Tambah Guru</span>
                    </button>

                    <!-- Filter Mapel Dropdown Pill -->
                    <div style="position: relative;">
                        <button type="button" class="btn-filter-pill" onclick="toggleFilterMenu()">
                            <span>
                                @if(request('id_mapel'))
                                    {{ optional($mapelList->firstWhere('id_mapel', request('id_mapel')))->nama_mapel ?? 'Semua' }}
                                @else
                                    Semua
                                @endif
                            </span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div id="filterMapelMenu" style="display: none; position: absolute; right: 0; top: 48px; background: #ffffff; border: 1px solid var(--dash-cream-border); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 8px; width: 200px; z-index: 50;">
                            <a href="{{ route('guru.index', array_merge(request()->except('id_mapel'), [])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Semua Mapel</a>
                            @foreach($mapelList as $mp)
                                <a href="{{ route('guru.index', array_merge(request()->except('id_mapel'), ['id_mapel' => $mp->id_mapel])) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">{{ $mp->nama_mapel }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Total Count Badge -->
                    <div class="badge-guru-count">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                            <line x1="4" y1="22" x2="4" y2="15"></line>
                        </svg>
                        <span>{{ $totalGuruCount }}</span>
                    </div>
                </div>
            </div>

            <!-- ---------------------------------------------------------------
                 Data Table Card Component (Matching Mockup)
                 --------------------------------------------------------------- -->
            <div class="guru-table-card" data-ajax-pagination="main">
                <div class="table-responsive-clean">
                    <table class="guru-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 22%;">NUPTK</th>
                                <th style="width: 28%;">Nama</th>
                                <th style="width: 20%;">Mapel Diampu</th>
                                <th style="width: 15%;">No Telp</th>
                                <th style="width: 10%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guru as $index => $g)
                                <tr>
                                    <td class="td-guru-no">{{ $loop->iteration + ($guru->currentPage() - 1) * $guru->perPage() }}</td>
                                    <td class="td-guru-nuptk">
                                        <span class="nuptk-badge">{{ $g->nuptk ?? '-' }}</span>
                                    </td>
                                    <td class="td-guru-nama">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div class="avatar-initial" style="background: linear-gradient(135deg, #059669, #10b981);">
                                                {{ strtoupper(substr($g->nama_guru, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 700; color: #1e2538;">{{ $g->nama_guru }}</div>
                                                <small style="color: #64748b; font-weight: 500;">
                                                    {{ optional($g->user)->email ? optional($g->user)->email : 'ID: GUR-' . str_pad($g->id_guru, 3, '0', STR_PAD_LEFT) }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-guru-mapel">
                                        @forelse($g->mapel as $m)
                                            <span class="badge-tag-guru" style="margin-right: 4px; margin-bottom: 4px; display: inline-block;">
                                                {{ $m->nama_mapel }}
                                            </span>
                                        @empty
                                            <span style="color: #94a3b8; font-size: 0.85rem;">Bahasa Inggris</span>
                                        @endforelse
                                    </td>
                                    <td class="td-guru-telp">
                                        {{ $g->no_hp ?? '08123456789' }}
                                    </td>
                                    <td>
                                        <div class="action-icons-cell">
                                            <!-- View Action -->
                                            <button type="button" class="action-btn-icon view" title="Lihat Detail Guru" onclick="openViewModal({{ $g->id_guru }})">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </button>

                                            <!-- Edit Action -->
                                            <button type="button" class="action-btn-icon edit" title="Edit Data Guru" onclick="openEditModal({{ $g->id_guru }}, '{{ addslashes($g->nuptk ?? '') }}', '{{ addslashes($g->nama_guru) }}', '{{ addslashes($g->no_hp ?? '') }}')">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>

                                            <!-- Delete Action -->
                                            <form action="{{ route('guru.destroy', $g) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru {{ $g->nama_guru }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn-icon delete" title="Hapus Guru">
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
                                        Belum ada data guru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination Row -->
                <div class="table-pagination-row">
                    <span class="pagination-summary-text">
                        Menampilkan {{ $guru->firstItem() ?? 0 }} - {{ $guru->lastItem() ?? 0 }} dari {{ $guru->total() }} data
                    </span>

                    <div class="pagination-nav-group">
                        {{ $guru->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>

        </main>

    </div>

    <!-- ===================================================================
         Create Guru Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="createModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Tambah Data Guru Baru</h3>
                <button type="button" class="btn-close-modal" onclick="closeCreateModal()">&times;</button>
            </div>

            <form action="{{ route('guru.store') }}" method="POST" class="modal-form-grid">
                @csrf
                <div class="form-field-group">
                    <label for="create_nuptk">NUPTK / NIP</label>
                    <input type="text" name="nuptk" id="create_nuptk" class="form-field-input" placeholder="Masukkan 16 digit NUPTK atau 18 digit NIP" maxlength="18" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')" required>
                </div>

                <div class="form-field-group">
                    <label for="create_nama_guru">Nama Lengkap Guru (Gelar)</label>
                    <input type="text" name="nama_guru" id="create_nama_guru" class="form-field-input" placeholder="Contoh: Agus Prasetyo, S.T" required>
                </div>

                <div class="form-field-group">
                    <label for="create_no_hp">No Telepon / WhatsApp</label>
                    <input type="text" name="no_hp" id="create_no_hp" class="form-field-input" placeholder="Contoh: 08123456789">
                </div>

                <div class="form-field-group">
                    <label>Mata Pelajaran Diampu</label>
                    <input type="text" id="create_mapel_search" class="form-field-input" placeholder="Cari mapel..." style="margin-bottom: 8px;" oninput="filterMapelCheckboxList(this, 'createMapelList')">
                    <div id="createMapelList" style="max-height: 140px; overflow-y: auto; border: 1px solid var(--dash-cream-border); border-radius: 12px; padding: 10px; background: #ffffff;">
                        @foreach($mapelList as $m)
                            <label class="mapel-cb-label" style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.85rem; color: #1e2538; padding: 4px 0; cursor: pointer;">
                                <input type="checkbox" name="mapel[]" value="{{ $m->id_mapel }}">
                                <span>{{ $m->nama_mapel }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeCreateModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Simpan Guru</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================
         Edit Guru Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="editModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Edit Data Guru</h3>
                <button type="button" class="btn-close-modal" onclick="closeEditModal()">&times;</button>
            </div>

            <form id="editForm" method="POST" class="modal-form-grid">
                @csrf
                @method('PUT')

                <div class="form-field-group">
                    <label for="edit_nuptk">NUPTK / NIP</label>
                    <input type="text" name="nuptk" id="edit_nuptk" class="form-field-input" placeholder="Masukkan 16 digit NUPTK atau 18 digit NIP" maxlength="18" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')" required>
                </div>

                <div class="form-field-group">
                    <label for="edit_nama_guru">Nama Lengkap Guru</label>
                    <input type="text" name="nama_guru" id="edit_nama_guru" class="form-field-input" required>
                </div>

                <div class="form-field-group">
                    <label for="edit_no_hp">No Telepon / WhatsApp</label>
                    <input type="text" name="no_hp" id="edit_no_hp" class="form-field-input">
                </div>

                <div class="form-field-group">
                    <label>Mata Pelajaran Diampu</label>
                    <input type="text" id="edit_mapel_search" class="form-field-input" placeholder="Cari mapel..." style="margin-bottom: 8px;" oninput="filterMapelCheckboxList(this, 'editMapelList')">
                    <div id="editMapelList" style="max-height: 140px; overflow-y: auto; border: 1px solid var(--dash-cream-border); border-radius: 12px; padding: 10px; background: #ffffff;">
                        @foreach($mapelList as $m)
                            <label class="mapel-cb-label" style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.85rem; color: #1e2538; padding: 4px 0; cursor: pointer;">
                                <input type="checkbox" name="mapel[]" class="edit-mapel-cb" value="{{ $m->id_mapel }}">
                                <span>{{ $m->nama_mapel }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Update Guru</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================
         View Guru Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="viewModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Detail Data Guru</h3>
                <button type="button" class="btn-close-modal" onclick="closeViewModal()">&times;</button>
            </div>

            <div class="modal-form-grid">
                <div class="form-field-group">
                    <label>NUPTK / NIP:</label>
                    <div id="view_nuptk" style="font-family: monospace; font-weight: 700; font-size: 1rem; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Nama Lengkap Guru:</label>
                    <div id="view_nama_guru" style="font-weight: 700; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>No. Telepon / WA:</label>
                    <div id="view_no_hp" style="font-weight: 600; color: #334155;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Mapel Diampu:</label>
                    <div id="view_mapel_names" style="font-weight: 700; color: var(--dash-navy);">-</div>
                </div>
                <div class="form-field-group">
                    <label>Akun Pengguna Terelasi:</label>
                    <div id="view_user_info" style="font-weight: 600; color: #475569;">-</div>
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

        function toggleFilterMenu() {
            const el = document.getElementById('filterMapelMenu');
            if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }

        function exportGuruCsv() {
            const params = new URLSearchParams(window.location.search);
            const searchVal = document.getElementById('guruSearchInput')?.value?.trim() || '';

            if (searchVal) {
                params.set('search', searchVal);
            } else {
                params.delete('search');
            }

            window.location.href = '{{ route('guru.export-csv') }}' + (params.toString() ? '?' + params.toString() : '');
        }

        function filterMapelCheckboxList(inputEl, containerId) {
            const q = inputEl.value.toLowerCase().trim();
            const container = document.getElementById(containerId);
            if (!container) return;

            container.querySelectorAll('.mapel-cb-label').forEach(function(label) {
                const text = label.textContent.toLowerCase();
                label.style.display = (q === '' || text.includes(q)) ? 'flex' : 'none';
            });
        }

        function openCreateModal() {
            const cbs = document.querySelectorAll('#createModal input[name="mapel[]"]');
            cbs.forEach(cb => { cb.checked = false; });

            const searchEl = document.getElementById('create_mapel_search');
            if (searchEl) { searchEl.value = ''; filterMapelCheckboxList(searchEl, 'createMapelList'); }

            document.getElementById('createModal').style.display = 'flex';
        }

        function closeCreateModal() {
            document.getElementById('createModal').style.display = 'none';
        }

        function openEditModal(id, nuptk, nama, noHp) {
            document.getElementById('editForm').action = '/guru/' + id;
            document.getElementById('edit_nuptk').value = nuptk;
            document.getElementById('edit_nama_guru').value = nama;
            document.getElementById('edit_no_hp').value = noHp;

            const searchEl = document.getElementById('edit_mapel_search');
            if (searchEl) { searchEl.value = ''; filterMapelCheckboxList(searchEl, 'editMapelList'); }

            const cbs = document.querySelectorAll('.edit-mapel-cb');
            cbs.forEach(cb => { cb.checked = false; });

            fetch('/guru/' + id)
                .then(response => response.json())
                .then(data => {
                    const mapelIds = (data && data.mapel_ids) ? data.mapel_ids.map(Number) : [];
                    cbs.forEach(cb => {
                        cb.checked = mapelIds.includes(Number(cb.value));
                    });
                    document.getElementById('editModal').style.display = 'flex';
                })
                .catch(err => {
                    console.error('Error fetching guru detail:', err);
                    document.getElementById('editModal').style.display = 'flex';
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function openViewModal(id) {
            fetch('/guru/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('view_nuptk').innerText = data.nuptk;
                    document.getElementById('view_nama_guru').innerText = data.nama_guru;
                    document.getElementById('view_no_hp').innerText = data.no_hp;
                    document.getElementById('view_mapel_names').innerText = data.mapel_names;
                    document.getElementById('view_user_info').innerText = data.user_email + ' (' + data.user_role + ')';
                    document.getElementById('viewModal').style.display = 'flex';
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }
        /* ---- Real-time Client-side Search (No Refresh) ---- */
        (function() {
            const input = document.getElementById('guruSearchInput');
            if (!input) return;

            const tbody = document.querySelector('.guru-table tbody');
            if (!tbody) return;

            const rows = Array.from(tbody.querySelectorAll('tr'));

            input.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();

                rows.forEach(function(row) {
                    const nuptk = (row.querySelector('.td-guru-nuptk') || {}).textContent || '';
                    const nama = (row.querySelector('.td-guru-nama') || {}).textContent || '';
                    const mapel = (row.querySelector('.td-guru-mapel') || {}).textContent || '';
                    const telp = (row.querySelector('.td-guru-telp') || {}).textContent || '';
                    const text = (nuptk + ' ' + nama + ' ' + mapel + ' ' + telp).toLowerCase();

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

        /* ---- Real-time NUPTK/NIP Digit Counter & Validator ---- */
        function setupNuptkDigitListener(inputId) {
            const el = document.getElementById(inputId);
            if (!el) return;

            const badge = document.createElement('span');
            badge.style.cssText = 'font-size: 0.72rem; font-weight: 800; padding: 2px 6px; border-radius: 6px; margin-left: 8px; font-family: monospace; transition: all 0.2s ease;';
            const label = el.parentNode.querySelector('label');
            if (label) label.appendChild(badge);

            function updateBadge() {
                const len = el.value.length;
                if (len === 16) {
                    badge.textContent = '16 / 16 (NUPTK Pas)';
                    badge.style.background = '#dcfce7';
                    badge.style.color = '#15803d';
                } else if (len === 18) {
                    badge.textContent = '18 / 18 (NIP Pas)';
                    badge.style.background = '#dcfce7';
                    badge.style.color = '#15803d';
                } else {
                    badge.textContent = len + ' / 16 atau 18 digit';
                    badge.style.background = '#fee2e2';
                    badge.style.color = '#b91c1c';
                }
            }
            el.addEventListener('input', updateBadge);
            updateBadge();

            const form = el.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const len = el.value.trim().length;
                    if (len !== 16 && len !== 18) {
                        e.preventDefault();
                        alert('NUPTK harus berisi tepat 16 digit atau NIP 18 digit angka.');
                        el.focus();
                    }
                });
            }
        }

        setupNuptkDigitListener('create_nuptk');
        setupNuptkDigitListener('edit_nuptk');

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
