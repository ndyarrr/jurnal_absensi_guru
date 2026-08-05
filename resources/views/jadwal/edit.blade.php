@extends('layouts.app')

@section('content')
    <h2>Edit Jurnal Mengajar</h2>

    @if($errors->any())
        <ul style="color:red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('jurnal.update', $jurnal) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Jadwal</label><br>
        <select name="id_jadwal" id="id_jadwal" required>
            @foreach($jadwal as $j)
                <option value="{{ $j->id_jadwal }}" {{ old('id_jadwal', $jurnal->id_jadwal) == $j->id_jadwal ? 'selected' : '' }}>
                    {{ $j->kelas->tingkat }} {{ $j->kelas->jurusan->kode_jurusan ?? '' }} {{ $j->kelas->rombel }}
                    - {{ $j->mapel->nama_mapel }} ({{ $j->hari }}, jam ke-{{ $j->jam_ke }}, {{ $j->guru->nama_guru }})
                </option>
            @endforeach
        </select><br><br>

        <label>Tanggal</label><br>
        <input type="date" name="tanggal" value="{{ old('tanggal', $jurnal->tanggal) }}" required><br><br>

        <label>Status Kehadiran Guru</label><br>
        <select name="status_kehadiran" required>
            @foreach(['Hadir','Izin','Sakit','Tanpa Keterangan'] as $status)
                <option value="{{ $status }}" {{ old('status_kehadiran', $jurnal->status_kehadiran) == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select><br><br>

        <label>Guru Pengganti</label><br>
        <select name="id_guru_pengganti">
            <option value="">-- Tidak ada --</option>
            @foreach($guru as $g)
                <option value="{{ $g->id_guru }}" {{ old('id_guru_pengganti', $jurnal->id_guru_pengganti) == $g->id_guru ? 'selected' : '' }}>
                    {{ $g->nama_guru }}
                </option>
            @endforeach
        </select><br><br>

        <label>Materi</label><br>
        <input type="text" name="materi" value="{{ old('materi', $jurnal->materi) }}" style="width:100%;"><br><br>

        <label>Jumlah Hadir</label><br>
        <input type="number" name="jumlah_hadir" value="{{ old('jumlah_hadir', $jurnal->jumlah_hadir) }}" min="0"><br><br>

        <label>Jumlah Tidak Hadir</label><br>
        <input type="number" name="jumlah_tidak_hadir" value="{{ old('jumlah_tidak_hadir', $jurnal->jumlah_tidak_hadir) }}" min="0"><br><br>

        <label>Catatan</label><br>
        <textarea name="catatan" style="width:100%;">{{ old('catatan', $jurnal->catatan) }}</textarea><br><br>

        <hr>
        <h3>Siswa Tidak Hadir</h3>
        <p style="font-size:13px; color:#666;">Data lama sudah ditampilkan. Kalau ganti Jadwal ke kelas lain, baris di bawah akan dikosongkan.</p>

        <table border="1" cellpadding="6" cellspacing="0" style="width:100%;">
            <thead>
                <tr>
                    <th style="width:60%;">Siswa</th>
                    <th style="width:25%;">Status</th>
                    <th style="width:15%;"></th>
                </tr>
            </thead>
            <tbody id="detail-rows"></tbody>
        </table>
        <br>
        <button type="button" id="add-row">+ Tambah Siswa Tidak Hadir</button>
        <br><br>

        <button type="submit">Update</button>
        <a href="{{ route('jurnal.index') }}">Batal</a>
    </form>

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
                    <select name="detail[${rowIndex}][id_siswa]" required style="width:100%;">
                        <option value="">-- Pilih Siswa --</option>
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <select name="detail[${rowIndex}][status]" required style="width:100%;">
                        <option value="S" ${selectedStatus == 'S' ? 'selected' : ''}>Sakit (S)</option>
                        <option value="I" ${selectedStatus == 'I' ? 'selected' : ''}>Izin (I)</option>
                        <option value="A" ${selectedStatus == 'A' ? 'selected' : ''}>Alpa (A)</option>
                    </select>
                </td>
                <td>
                    <button type="button" onclick="this.closest('tr').remove()">Hapus</button>
                </td>
            `;
            document.getElementById('detail-rows').appendChild(tr);
            rowIndex++;
        }

        function tambahBaris() {
            const kelasId = getKelasIdTerpilih();
            if (!kelasId) {
                alert('Pilih Jadwal terlebih dahulu.');
                return;
            }
            const filtered = siswaList.filter(s => s.id_kelas == kelasId);
            if (filtered.length === 0) {
                alert('Tidak ada siswa terdaftar di kelas ini.');
                return;
            }
            buatBaris();
        }

        document.getElementById('add-row').addEventListener('click', tambahBaris);

        document.getElementById('id_jadwal').addEventListener('change', function () {
            document.getElementById('detail-rows').innerHTML = '';
            rowIndex = 0;
        });

        // prefill data lama saat halaman dibuka
        window.addEventListener('DOMContentLoaded', function () {
            existingDetail.forEach(function (d) {
                buatBaris(d.id_siswa, d.status);
            });
        });
    </script>
@endsection