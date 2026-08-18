<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data - Ruangan | Admin</title>

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

        <!-- Sidebar Navigation -->
        <aside class="dash-sidebar">
            @include('partials.dash-brand')

            <ul class="dash-menu">
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
                        <li>
                            <a href="{{ route('ruangan.index') }}" class="dash-sub-link" style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #ffffff;">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                <span>Ruangan</span>
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
                            <a href="{{ route('jam.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span>Jam Pelajaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jadwal.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span>Jadwal Pelajaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jurnal.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                                <span>Jurnal Mengajar</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Bottom User Profile Sub-Widget -->
            @include('partials.dash-user-widget')
        </aside>

        <!-- Main Content Area -->
        <main class="dash-main">
            <!-- Header Bar -->
            <header class="dash-header">
                <div class="dash-header-left">
                    <h1 class="dash-header-title">Master Data - Ruangan</h1>
                    <p class="dash-header-subtitle">Kelola daftar ruangan kelas, laboratorium, dan fasilitas sekolah.</p>
                </div>

                <div class="dash-header-right">
                    <button type="button" class="btn-create-item" onclick="openCreateModal()">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Tambah Ruangan</span>
                    </button>
                </div>
            </header>

            <!-- Alerts -->
            <div id="flashAlertContainer">
                @if(session('success'))
                    <div class="dash-alert success" style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="dash-alert error" style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            <!-- Content Card -->
            <div class="dash-content-card">
                <div class="table-toolbar">
                    <div class="search-box-wrap">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari nama ruangan atau keterangan..." value="{{ request('search') }}" onkeyup="debounceSearch()">
                    </div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #64748b;">
                        Total: <span id="totalBadge" style="color: var(--dash-navy);">{{ $ruangan->total() }}</span> Ruangan
                    </div>
                </div>

                <div class="table-responsive-clean">
                    <table class="clean-data-table">
                        <thead>
                            <tr>
                                <th style="width: 8%;">No</th>
                                <th style="width: 35%;">Nama Ruangan</th>
                                <th style="width: 42%;">Keterangan</th>
                                <th style="width: 15%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="ruanganTableBody">
                            @forelse($ruangan as $idx => $r)
                                <tr>
                                    <td>{{ $ruangan->firstItem() + $idx }}</td>
                                    <td>
                                        <strong style="color: var(--dash-navy); font-size: 0.95rem;">{{ $r->nama_ruangan }}</strong>
                                    </td>
                                    <td>
                                        <span style="color: #64748b;">{{ $r->keterangan ?: '-' }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                            <button type="button" class="action-btn-icon edit" title="Edit Ruangan" onclick="openEditModal({{ $r->id_ruangan }}, '{{ addslashes($r->nama_ruangan) }}', '{{ addslashes($r->keterangan ?? '') }}')">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" class="action-btn-icon delete" title="Hapus Ruangan" onclick="deleteRuanganAjax({{ $r->id_ruangan }}, '{{ addslashes($r->nama_ruangan) }}')">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">
                                        Belum ada data ruangan tersimpan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination -->
                <div class="pagination-footer" id="paginationFooter">
                    @if($ruangan->hasPages())
                        <div class="pagination-info">
                            Menampilkan {{ $ruangan->firstItem() }} - {{ $ruangan->lastItem() }} dari {{ $ruangan->total() }} Ruangan
                        </div>
                        <div class="pagination-links">
                            {{ $ruangan->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>

    </div>

    <!-- Create Modal -->
    <div class="modal-overlay" id="createModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Tambah Data Ruangan</h3>
                <button type="button" class="btn-close-modal" onclick="closeCreateModal()">&times;</button>
            </div>
            <form id="createForm" method="POST" action="{{ route('ruangan.store') }}" class="modal-form-grid">
                @csrf
                <div id="createModalAlert"></div>

                <div class="form-field-group">
                    <label for="create_nama_ruangan">Nama Ruangan <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="nama_ruangan" id="create_nama_ruangan" class="form-field-input" placeholder="Contoh: R. 57, Lab. RPL 1, Aula" required>
                </div>

                <div class="form-field-group">
                    <label for="create_keterangan">Keterangan / Lokasi (Opsional)</label>
                    <input type="text" name="keterangan" id="create_keterangan" class="form-field-input" placeholder="Contoh: Lantai 2 Gedung Utama">
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeCreateModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Simpan Ruangan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal-overlay" id="editModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Edit Data Ruangan</h3>
                <button type="button" class="btn-close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editForm" method="POST" class="modal-form-grid">
                @csrf
                @method('PUT')
                <div id="editModalAlert"></div>
                <input type="hidden" id="edit_id_ruangan">

                <div class="form-field-group">
                    <label for="edit_nama_ruangan">Nama Ruangan <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="nama_ruangan" id="edit_nama_ruangan" class="form-field-input" required>
                </div>

                <div class="form-field-group">
                    <label for="edit_keterangan">Keterangan / Lokasi (Opsional)</label>
                    <input type="text" name="keterangan" id="edit_keterangan" class="form-field-input">
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Update Ruangan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSubmenu(id) {
            var el = document.getElementById(id);
            if (el) el.style.display = (el.style.display === 'none' || !el.style.display) ? 'flex' : 'none';
        }

        function openCreateModal() {
            document.getElementById('createModalAlert').innerHTML = '';
            document.getElementById('createForm').reset();
            document.getElementById('createModal').style.display = 'flex';
        }

        function closeCreateModal() {
            document.getElementById('createModal').style.display = 'none';
        }

        function openEditModal(id, nama, ket) {
            document.getElementById('editModalAlert').innerHTML = '';
            document.getElementById('edit_id_ruangan').value = id;
            document.getElementById('edit_nama_ruangan').value = nama;
            document.getElementById('edit_keterangan').value = ket || '';
            document.getElementById('editForm').action = '/ruangan/' + id;
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function showToast(msg, type) {
            var container = document.getElementById('flashAlertContainer');
            if (!container) return;
            var isSuccess = type === 'success';
            var iconSvg = isSuccess 
                ? '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>'
                : '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
            
            var alertDiv = document.createElement('div');
            alertDiv.className = 'dash-alert ' + (isSuccess ? 'success' : 'error');
            alertDiv.style.cssText = 'display: flex; align-items: center; gap: 8px; margin-bottom: 16px;';
            alertDiv.innerHTML = iconSvg + '<span>' + msg + '</span>';
            container.innerHTML = '';
            container.appendChild(alertDiv);

            setTimeout(function() {
                alertDiv.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                alertDiv.style.opacity = '0';
                alertDiv.style.transform = 'translateY(-8px)';
                setTimeout(function() { alertDiv.remove(); }, 500);
            }, 3000);
        }

        var searchTimer;
        function debounceSearch() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(fetchRuanganAjax, 300);
        }

        function fetchRuanganAjax(page) {
            var q = document.getElementById('searchInput').value;
            var url = '/ruangan?search=' + encodeURIComponent(q);
            if (page) url += '&page=' + page;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {
                renderTable(res.data, res.pagination);
            });
        }

        function renderTable(data, pagination) {
            var tbody = document.getElementById('ruanganTableBody');
            tbody.innerHTML = '';

            document.getElementById('totalBadge').innerText = pagination.total;

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">Tidak ada data ruangan ditemukan.</td></tr>';
                document.getElementById('paginationFooter').innerHTML = '';
                return;
            }

            data.forEach(function(r, i) {
                var rowNo = pagination.first + i;
                var tr = document.createElement('tr');
                tr.innerHTML = '<td>' + rowNo + '</td>' +
                    '<td><strong style="color: var(--dash-navy); font-size: 0.95rem;">' + escapeHtml(r.nama_ruangan) + '</strong></td>' +
                    '<td><span style="color: #64748b;">' + escapeHtml(r.keterangan || '-') + '</span></td>' +
                    '<td style="text-align: center;">' +
                        '<div style="display: flex; align-items: center; justify-content: center; gap: 8px;">' +
                            '<button type="button" class="action-btn-icon edit" title="Edit Ruangan" onclick="openEditModal(' + r.id_ruangan + ', \'' + escapeJs(r.nama_ruangan) + '\', \'' + escapeJs(r.keterangan || '') + '\')">' +
                                '<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
                            '</button>' +
                            '<button type="button" class="action-btn-icon delete" title="Hapus Ruangan" onclick="deleteRuanganAjax(' + r.id_ruangan + ', \'' + escapeJs(r.nama_ruangan) + '\')">' +
                                '<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>' +
                            '</button>' +
                        '</div>' +
                    '</td>';
                tbody.appendChild(tr);
            });
        }

        function escapeHtml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
        function escapeJs(str) {
            return String(str).replace(/'/g, "\\'").replace(/"/g, '\\"');
        }

        document.getElementById('createForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var form = this;
            var alertDiv = document.getElementById('createModalAlert');
            alertDiv.innerHTML = '';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(res => res.json().then(data => ({ status: res.status, data })))
            .then(res => {
                if (res.status === 422) {
                    var errors = res.data.errors;
                    var errHtml = '<div class="dash-alert error" style="margin-bottom: 16px;"><ul>';
                    for (var k in errors) {
                        errHtml += '<li>' + errors[k][0] + '</li>';
                    }
                    errHtml += '</ul></div>';
                    alertDiv.innerHTML = errHtml;
                } else if (res.data.success) {
                    closeCreateModal();
                    showToast(res.data.success, 'success');
                    fetchRuanganAjax();
                }
            });
        });

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var form = this;
            var alertDiv = document.getElementById('editModalAlert');
            alertDiv.innerHTML = '';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(res => res.json().then(data => ({ status: res.status, data })))
            .then(res => {
                if (res.status === 422) {
                    var errors = res.data.errors;
                    var errHtml = '<div class="dash-alert error" style="margin-bottom: 16px;"><ul>';
                    for (var k in errors) {
                        errHtml += '<li>' + errors[k][0] + '</li>';
                    }
                    errHtml += '</ul></div>';
                    alertDiv.innerHTML = errHtml;
                } else if (res.data.success) {
                    closeEditModal();
                    showToast(res.data.success, 'success');
                    fetchRuanganAjax();
                }
            });
        });

        function deleteRuanganAjax(id, nama) {
            if (!confirm('Apakah Anda yakin ingin menghapus ruangan "' + nama + '"?')) return;

            fetch('/ruangan/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({ '_method': 'DELETE' })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.success, 'success');
                    fetchRuanganAjax();
                } else if (data.error) {
                    showToast(data.error, 'error');
                }
            });
        }
    </script>
</body>
</html>
