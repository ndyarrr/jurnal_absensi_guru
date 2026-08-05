@extends('layouts.app')

@section('content')
    <h2>Tambah Jurnal Mengajar</h2>

    @if($errors->any())
        <ul style="color:red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('jurnal.store') }}" method="POST">
        @csrf

        <label>Jadwal</label><br>
        <select name="id_jadwal" id="id_jadwal" required>
            <option value="">-- Pilih Jadwal --</option>
            @foreach($jadwal as $j)
                <option value="{{ $j->id_jadwal }}" {{ old('id_jadwal') == $j->id_jadwal ? 'selected' : '' }}>
                    {{ $j->kelas->tingkat }} {{ $j->kelas->jurusan->kode_jurusan ?? '' }} {{ $j->kelas->rombel }}
                    - {{ $j->mapel->nama_mapel }} ({{ $j->hari }}, jam ke-{{ $j->jam_ke }}, {{ $j->guru->nama_guru }})
                </option>
            @endforeach
        </select><br><br>

        <label>Tanggal</label><br>
        <input type="date" name="tanggal" value="{{ old('tanggal') }}" required><br><br>

        <label>Status Kehadiran Guru</label><br>
        <select name="status_kehadiran" required>
            <option value="">-- Pilih Status --</option>
            <option value="Hadir" {{ old('status_kehadiran') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
            <option value="Izin" {{ old('status_kehadiran') == 'Izin' ? 'selected' : '' }}>Izin</option>
            <option value="Sakit" {{ old('status_kehadiran') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
            <option value="Tanpa Keterangan" {{ old('status_kehadiran') == 'Tanpa Keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
        </select><br><br>

        <label>Guru Pengganti (jika guru tidak hadir)</label><br>
        <select name="id_guru_pengganti">
            <option value="">-- Tidak ada --</option>
            @foreach($guru as $g)
                <option value="{{ $g->id_guru }}" {{ old('id_guru_pengganti') == $g->id_guru ? 'selected' : '' }}>
                    {{ $g->nama_guru }}
                </option>
            @endforeach
        </select><br><br>

        <label>Materi</label><br>
        <input type="text" name="materi" value="{{ old('materi') }}" style="width:100%;"><br><br>

        <label>Jumlah Hadir</label><br>
        <input type="number" name="jumlah_hadir" value="{{ old('jumlah_hadir', 0) }}" min="0"><br><br>

        <label>Jumlah Tidak Hadir</label><br>
        <input type="number" name="jumlah_tidak_hadir" value="{{ old('jumlah_tidak_hadir', 0) }}" min="0"><br><br>

        <label>Catatan</label><br>
        <textarea name="catatan" style="width:100%;">{{ old('catatan') }}</textarea><br><br>

        <hr>
        <h3>Siswa Tidak Hadir (opsional)</h3>
        <p style="font-size:13px; color:#666;">Pilih Jadwal dulu di atas, baru klik "Tambah Siswa" — daftar siswa akan otomatis sesuai kelas dari jadwal yang dipilih.</p>

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

        <button type="submit">Simpan</button>
        <a href="{{ route('jurnal.index') }}">Batal</a>
    </form>

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
                alert('Pilih Jadwal terlebih dahulu.');
                return;
            }

            const filtered = siswaList.filter(s => s.id_kelas == kelasId);
            if (filtered.length === 0) {
                alert('Tidak ada siswa terdaftar di kelas ini.');
                return;
            }

            const optionsHtml = filtered.map(s => `<option value="${s.id}">${s.nama}</option>`).join('');

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
                        <option value="S">Sakit (S)</option>
                        <option value="I">Izin (I)</option>
                        <option value="A">Alpa (A)</option>
                    </select>
                </td>
                <td>
                    <button type="button" onclick="this.closest('tr').remove()">Hapus</button>
                </td>
            `;
            document.getElementById('detail-rows').appendChild(tr);
            rowIndex++;
        }

        document.getElementById('add-row').addEventListener('click', tambahBaris);

        // kalau jadwal diganti, kosongkan baris siswa yang sudah ditambah (supaya tidak nyampur kelas)
        document.getElementById('id_jadwal').addEventListener('change', function () {
            document.getElementById('detail-rows').innerHTML = '';
            rowIndex = 0;
        });
    </script>
@endsection