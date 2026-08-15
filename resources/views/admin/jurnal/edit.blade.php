@extends('layouts.app')

@section('title', 'Edit Jurnal Mengajar')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Edit Jurnal Mengajar</h2>
            <p>Perbarui catatan mengajar, materi, atau absensi siswa.</p>
        </div>
    </div>

    <div class="form-card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <span>⚠️</span>
                <div>
                    <strong>Terjadi kesalahan input:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('jurnal.update', $jurnal) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group col-8">
                    <label for="id_jadwal">Jadwal Pelajaran <span class="required">*</span></label>
                    <select name="id_jadwal" id="id_jadwal" class="form-control" required>
                        @foreach($jadwal as $j)
                            <option value="{{ $j->id_jadwal }}" {{ old('id_jadwal', $jurnal->id_jadwal) == $j->id_jadwal ? 'selected' : '' }}>
                                {{ $j->kelas->tingkat }} {{ $j->kelas->jurusan->kode_jurusan ?? '' }} {{ $j->kelas->rombel }}
                                - {{ $j->mapel->nama_mapel }} ({{ $j->hari }}, Jam Ke-{{ $j->jam_ke }} | {{ $j->guru->nama_guru }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-4">
                    <label for="tanggal">Tanggal Pembelajaran <span class="required">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $jurnal->tanggal) }}" required>
                </div>

                <div class="form-group col-6">
                    <label for="status_kehadiran">Status Kehadiran Guru <span class="required">*</span></label>
                    <select name="status_kehadiran" id="status_kehadiran" class="form-control" required>
                        @foreach(['Hadir','Izin','Sakit','Tanpa Keterangan'] as $status)
                            <option value="{{ $status }}" {{ old('status_kehadiran', $jurnal->status_kehadiran) == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-6">
                    <label for="id_guru_pengganti">Guru Pengganti</label>
                    <select name="id_guru_pengganti" id="id_guru_pengganti" class="form-control">
                        <option value="">-- Tidak Ada Guru Pengganti --</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->id_guru }}" {{ old('id_guru_pengganti', $jurnal->id_guru_pengganti) == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-12">
                    <label for="materi">Materi Pembelajaran</label>
                    <input type="text" name="materi" id="materi" class="form-control" value="{{ old('materi', $jurnal->materi) }}">
                </div>

                <div class="form-group col-6">
                    <label for="jumlah_hadir">Jumlah Siswa Hadir</label>
                    <input type="number" name="jumlah_hadir" id="jumlah_hadir" class="form-control" value="{{ old('jumlah_hadir', $jurnal->jumlah_hadir) }}" min="0">
                </div>

                <div class="form-group col-6">
                    <label for="jumlah_tidak_hadir">Jumlah Siswa Tidak Hadir</label>
                    <input type="number" name="jumlah_tidak_hadir" id="jumlah_tidak_hadir" class="form-control" value="{{ old('jumlah_tidak_hadir', $jurnal->jumlah_tidak_hadir) }}" min="0">
                </div>

                <div class="form-group col-12">
                    <label for="catatan">Catatan / Keterangan Tambahan</label>
                    <textarea name="catatan" id="catatan" class="form-control">{{ old('catatan', $jurnal->catatan) }}</textarea>
                </div>

                <!-- Dynamic Student Absence Table -->
                <div class="col-12" style="margin-top: 15px;">
                    <div class="form-section-title">
                        <span>👥 Daftar Siswa Tidak Hadir</span>
                    </div>

                    <div class="detail-table-wrapper">
                        <table class="detail-table">
                            <thead>
                                <tr>
                                    <th style="width: 55%;">Nama Siswa</th>
                                    <th style="width: 30%;">Status Ketidakhadiran</th>
                                    <th style="width: 15%; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detail-rows"></tbody>
                        </table>
                    </div>

                    <button type="button" id="add-row" class="btn-add">+ Tambah Siswa Tidak Hadir</button>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">🔄 Perbarui Jurnal</button>
                <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    const siswaList = @json($siswa->map(fn($s) => ['id' => $s->id_siswa, 'nama' => $s->nama_siswa, 'id_kelas' => $s->id_kelas]));
    const jadwalKelasMap = @json($jadwalKelasMap);
    const existingDetail = @json($jurnal->detailKetidakhadiran->map(fn($d) => ['id_siswa' => $d->id_siswa, 'status' => $d->status]));

    let rowIndex = 0;

    function getKelasIdTerpilih() {
        const jadwalId = document.getElementById('id_jadwal').value;
        return jadwalKelasMap[jadwalId] || null;
    }

    function buatBaris(selectedSiswaId = '', selectedStatus = 'S') {
        const kelasId = getKelasIdTerpilih();
        if (!kelasId) return;

        const filtered = siswaList.filter(s => s.id_kelas == kelasId);
        const optionsHtml = filtered.map(s =>
            `<option value="${s.id}" ${s.id == selectedSiswaId ? 'selected' : ''}>${s.nama}</option>`
        ).join('');

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="detail[${rowIndex}][id_siswa]" class="form-control" required>
                    <option value="">-- Pilih Siswa --</option>
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <select name="detail[${rowIndex}][status]" class="form-control" required>
                    <option value="S" ${selectedStatus == 'S' ? 'selected' : ''}>Sakit (S)</option>
                    <option value="I" ${selectedStatus == 'I' ? 'selected' : ''}>Izin (I)</option>
                    <option value="A" ${selectedStatus == 'A' ? 'selected' : ''}>Alpa (A)</option>
                </select>
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn btn-danger-light" onclick="hapusBaris(this)">Hapus</button>
            </td>
        `;
        document.getElementById('detail-rows').appendChild(tr);
        rowIndex++;
        updateJumlahTidakHadir();
    }

    function tambahBaris() {
        const kelasId = getKelasIdTerpilih();
        if (!kelasId) {
            alert('Silakan pilih Jadwal Pelajaran terlebih dahulu.');
            return;
        }
        const filtered = siswaList.filter(s => s.id_kelas == kelasId);
        if (filtered.length === 0) {
            alert('Tidak ada siswa terdaftar di kelas untuk jadwal ini.');
            return;
        }
        buatBaris();
    }

    function hapusBaris(button) {
        button.closest('tr').remove();
        const rows = document.querySelectorAll('#detail-rows tr');
        if (rows.length === 0) {
            document.getElementById('detail-rows').innerHTML = `
                <tr>
                    <td colspan="3" class="empty-table-notice">Belum ada data siswa tidak hadir ditambahkan.</td>
                </tr>
            `;
        }
        updateJumlahTidakHadir();
    }

    function updateJumlahTidakHadir() {
        const count = document.querySelectorAll('#detail-rows tr:not(:has(.empty-table-notice))').length;
        document.getElementById('jumlah_tidak_hadir').value = count;
    }

    document.getElementById('add-row').addEventListener('click', tambahBaris);

    document.getElementById('id_jadwal').addEventListener('change', function () {
        document.getElementById('detail-rows').innerHTML = `
            <tr>
                <td colspan="3" class="empty-table-notice">Belum ada data siswa tidak hadir ditambahkan.</td>
            </tr>
        `;
        rowIndex = 0;
        updateJumlahTidakHadir();
    });

    window.addEventListener('DOMContentLoaded', function () {
        if (existingDetail.length > 0) {
            existingDetail.forEach(function (d) {
                buatBaris(d.id_siswa, d.status);
            });
        } else {
            document.getElementById('detail-rows').innerHTML = `
                <tr>
                    <td colspan="3" class="empty-table-notice">Belum ada data siswa tidak hadir ditambahkan.</td>
                </tr>
            `;
        }
    });
</script>
@endsection