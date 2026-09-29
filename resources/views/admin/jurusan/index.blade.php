@extends('layouts.app')

@section('title', 'Data Jurusan')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2>🎓 Data Jurusan</h2>
            <p>Daftar program keahlian & jurusan yang tersedia di sekolah.</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="openCreateModal()">
            <span><i class="fa-solid fa-plus"></i></span> Tambah Jurusan Baru
        </button>
    </div>

    <div id="jurusanAlertContainer"></div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Kode Jurusan</th>
                        <th style="width: 50%;">Nama Jurusan</th>
                        <th style="width: 25%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="jurusanTableBody">
                    @forelse($jurusan as $j)
                        <tr id="jurusan-row-{{ $j->id_jurusan }}">
                            <td>
                                <span class="jurusan-code-badge">{{ $j->kode_jurusan }}</span>
                            </td>
                            <td>
                                <span class="jurusan-title">{{ $j->nama_jurusan }}</span>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="{{ route('jurusan.show', $j) }}" class="btn btn-sm btn-action-show" title="Detail"> Detail</a>
                                    <button type="button" class="btn btn-sm btn-action-edit" title="Edit" onclick="openEditModal({{ $j->id_jurusan }}, '{{ addslashes($j->kode_jurusan) }}', '{{ addslashes($j->nama_jurusan) }}')"> Edit</button>
                                    <button type="button" class="btn btn-sm btn-action-delete" title="Hapus" onclick="deleteJurusanAjax({{ $j->id_jurusan }}, '{{ addslashes($j->kode_jurusan) }}', '{{ addslashes($j->nama_jurusan) }}')">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="jurusanEmptyRow">
                            <td colspan="3">
                                <div class="empty-state">
                                    <div class="empty-state-icon">🎓</div>
                                    <h4>Belum Ada Data Jurusan</h4>
                                    <p>Silakan klik tombol <strong>+ Tambah Jurusan Baru</strong> untuk menambahkan data.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal-overlay" id="createModal" style="display: none;">
    <div class="modal-content-card">
        <div class="modal-header-bar">
            <h3 class="modal-title-text">Tambah Jurusan</h3>
            <button type="button" class="btn-close-modal" onclick="closeCreateModal()">&times;</button>
        </div>
        <form id="createForm" method="POST" action="{{ route('jurusan.store') }}" class="modal-form-grid">
            @csrf
            <div id="createModalAlert"></div>

            <div class="form-field-group">
                <label for="create_kode_jurusan">Kode Jurusan <span style="color:#dc2626;">*</span></label>
                <input type="text" name="kode_jurusan" id="create_kode_jurusan" class="form-field-input" placeholder="Contoh: RPL, TKJ, AKL" maxlength="10" required>
            </div>

            <div class="form-field-group">
                <label for="create_nama_jurusan">Nama Jurusan <span style="color:#dc2626;">*</span></label>
                <input type="text" name="nama_jurusan" id="create_nama_jurusan" class="form-field-input" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
            </div>

            <div class="modal-actions-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeCreateModal()">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Jurusan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal" style="display: none;">
    <div class="modal-content-card">
        <div class="modal-header-bar">
            <h3 class="modal-title-text">Edit Data Jurusan</h3>
            <button type="button" class="btn-close-modal" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm" method="POST" class="modal-form-grid">
            @csrf
            @method('PUT')
            <div id="editModalAlert"></div>

            <div class="form-field-group">
                <label for="edit_kode_jurusan">Kode Jurusan <span style="color:#dc2626;">*</span></label>
                <input type="text" name="kode_jurusan" id="edit_kode_jurusan" class="form-field-input" maxlength="10" required>
            </div>

            <div class="form-field-group">
                <label for="edit_nama_jurusan">Nama Jurusan <span style="color:#dc2626;">*</span></label>
                <input type="text" name="nama_jurusan" id="edit_nama_jurusan" class="form-field-input" required>
            </div>

            <div class="modal-actions-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn-modal-submit">Update Jurusan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createModalAlert').innerHTML = '';
        document.getElementById('createForm').reset();
        document.getElementById('createModal').style.display = 'flex';
    }
    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
    }

    function openEditModal(id, kode, nama) {
        document.getElementById('editModalAlert').innerHTML = '';
        document.getElementById('edit_kode_jurusan').value = kode;
        document.getElementById('edit_nama_jurusan').value = nama;
        document.getElementById('editForm').action = '/jurusan/' + id;
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function showJurusanToast(msg, type) {
        var container = document.getElementById('jurusanAlertContainer');
        if (!container) return;
        var alertDiv = document.createElement('div');
        alertDiv.className = 'alert ' + (type === 'success' ? 'alert-success' : 'alert-danger');
        alertDiv.innerHTML = '<span>' + msg + '</span>';
        container.innerHTML = '';
        container.appendChild(alertDiv);
        setTimeout(function () {
            alertDiv.style.transition = 'opacity 0.5s ease';
            alertDiv.style.opacity = '0';
            setTimeout(function () { alertDiv.remove(); }, 500);
        }, 3000);
    }

    function renderErrors(alertElId, errors) {
        var alertDiv = document.getElementById(alertElId);
        var html = '<div class="alert alert-danger"><ul style="margin:0; padding-left: 18px;">';
        for (var k in errors) { html += '<li>' + errors[k][0] + '</li>'; }
        html += '</ul></div>';
        alertDiv.innerHTML = html;
    }

    document.getElementById('createForm').addEventListener('submit', function (e) {
        e.preventDefault();
        var form = this;
        document.getElementById('createModalAlert').innerHTML = '';

        showConfirmModal({
            type: 'create',
            title: 'Konfirmasi Tambah Jurusan',
            message: 'Apakah Anda yakin ingin menambahkan jurusan baru ini?',
            onConfirm: () => {

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
                renderErrors('createModalAlert', res.data.errors);
            } else if (res.data.success) {
                closeCreateModal();
                showJurusanToast(res.data.success, 'success');
                setTimeout(() => window.location.reload(), 600);
            }
        });
            }
        });
    });

    document.getElementById('editForm').addEventListener('submit', function (e) {
        e.preventDefault();
        var form = this;
        document.getElementById('editModalAlert').innerHTML = '';

        showConfirmModal({
            type: 'update',
            title: 'Konfirmasi Simpan Perubahan',
            message: 'Apakah Anda yakin ingin menyimpan perubahan data jurusan ini?',
            onConfirm: () => {

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
                renderErrors('editModalAlert', res.data.errors);
            } else if (res.data.success) {
                closeEditModal();
                showJurusanToast(res.data.success, 'success');
                setTimeout(() => window.location.reload(), 600);
            }
        });
            }
        });
    });

    function deleteJurusanAjax(id, kode, nama) {
        showConfirmModal({
            type: 'delete',
            title: 'Hapus Jurusan',
            message: 'Apakah Anda yakin ingin menghapus jurusan <strong>' + kode + ' - ' + nama + '</strong>?',
            onConfirm: function () {
                fetch('/jurusan/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({ '_method': 'DELETE' })
                })
                .then(res => res.json().then(data => ({ status: res.status, data })))
                .then(res => {
                    if (res.data.success) {
                        showJurusanToast(res.data.success, 'success');
                        setTimeout(() => window.location.reload(), 600);
                    } else if (res.data.error) {
                        showJurusanToast(res.data.error, 'error');
                    }
                });
            }
        });
    }
</script>

@endsection