@extends('layouts.app')

@section('title', 'Tambah Jurnal Mengajar')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Tambah Jurnal Mengajar</h2>
            <p>Catat aktivitas pembelajaran, absensi guru, dan daftar siswa tidak hadir.</p>
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

        <form action="{{ route('jurnal.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group col-8">
                    <label for="id_jadwal">Jadwal Pelajaran <span class="required">*</span></label>
                    <select name="id_jadwal" id="id_jadwal" class="form-control" required>
                        <option value="">-- Pilih Jadwal Pelajaran --</option>
                        @foreach($jadwal as $j)
                            <option value="{{ $j->id_jadwal }}" {{ old('id_jadwal') == $j->id_jadwal ? 'selected' : '' }}>
                                {{ $j->kelas->tingkat }} {{ $j->kelas->jurusan->kode_jurusan ?? '' }} {{ $j->kelas->rombel }}
                                - {{ $j->mapel->nama_mapel }} ({{ $j->hari }}, Jam Ke-{{ $j->jam_ke }} | {{ $j->guru->nama_guru }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-4">
                    <label for="tanggal">Tanggal Pembelajaran <span class="required">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                </div>

                <div class="form-group col-6">
                    <label for="status_kehadiran">Status Kehadiran Guru <span class="required">*</span></label>
                    <select name="status_kehadiran" id="status_kehadiran" class="form-control" required>
                        <option value="">-- Pilih Status Guru --</option>
                        <option value="Hadir" {{ old('status_kehadiran', 'Hadir') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Izin" {{ old('status_kehadiran') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Sakit" {{ old('status_kehadiran') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Tanpa Keterangan" {{ old('status_kehadiran') == 'Tanpa Keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                    </select>
                </div>

                <div class="form-group col-6">
                    <label for="id_guru_pengganti">Guru Pengganti</label>
                    <select name="id_guru_pengganti" id="id_guru_pengganti" class="form-control">
                        <option value="">-- Tidak Ada Guru Pengganti --</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->id_guru }}" {{ old('id_guru_pengganti') == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                    <span class="help-text">Diisi jika guru utama berhalangan hadir.</span>
                </div>

                <div class="form-group col-12">
                    <label for="materi">Materi Pembelajaran</label>
                    <input type="text" name="materi" id="materi" class="form-control" value="{{ old('materi') }}" placeholder="Pokok bahasan / topik materi hari ini">
                </div>

                <div class="form-group col-6">
                    <label for="jumlah_hadir">Jumlah Siswa Hadir</label>
                    <input type="number" name="jumlah_hadir" id="jumlah_hadir" class="form-control" value="{{ old('jumlah_hadir', 0) }}" min="0">
                </div>

                <div class="form-group col-6">
                    <label for="jumlah_tidak_hadir">Jumlah Siswa Tidak Hadir</label>
                    <input type="number" name="jumlah_tidak_hadir" id="jumlah_tidak_hadir" class="form-control" value="{{ old('jumlah_tidak_hadir', 0) }}" min="0">
                </div>

                <div class="form-group col-12">
                    <label for="catatan">Catatan / Keterangan Tambahan</label>
                    <textarea name="catatan" id="catatan" class="form-control" placeholder="Catatan jalannya KBM atau kendala saat mengajar">{{ old('catatan') }}</textarea>
                </div>

                <!-- Dynamic Student Absence Table -->
                <div class="col-12" style="margin-top: 15px;">
                    <div class="form-section-title">
                        <span>👥 Daftar Siswa Tidak Hadir (Opsional)</span>
                    </div>
                    <p class="help-text" style="margin-top: 6px;">Pilih Jadwal di atas terlebih dahulu, kemudian klik <strong>+ Tambah Siswa Tidak Hadir</strong>.</p>

                    <div class="detail-table-wrapper">
                        <table class="detail-table">
                            <thead>
                                <tr>
                                    <th style="width: 55%;">Nama Siswa</th>
                                    <th style="width: 30%;">Status Ketidakhadiran</th>
                                    <th style="width: 15%; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detail-rows">
                                <tr>
                                    <td colspan="3" class="empty-table-notice">Belum ada data siswa tidak hadir ditambahkan.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" id="add-row" class="btn-add">+ Tambah Siswa Tidak Hadir</button>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Jurnal</button>
                <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    const siswaList = @json($siswa->map(fn($s) => ['id' => $s->id_siswa, 'nama' => $s->nama_siswa, 'id_kelas' => $s->id_kelas]));
    const jadwalKelasMap = @json($jadwalKelasMap);

    let rowIndex = 0;

    function getKelasIdTerpilih() {
        const jadwalId = document.getElementById('id_jadwal').value;
        return jadwalKelasMap[jadwalId] || null;
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

        const emptyNotice = document.querySelector('.empty-table-notice');
        if (emptyNotice) {
            emptyNotice.closest('tr').remove();
        }

        const optionsHtml = filtered.map(s => `<option value="${s.id}">${s.nama}</option>`).join('');

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
                    <option value="S">Sakit (S)</option>
                    <option value="I">Izin (I)</option>
                    <option value="A">Alpa (A)</option>
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
</script>
@endsection