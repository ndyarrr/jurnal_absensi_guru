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
        <select name="id_jadwal" required>
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

        <button type="submit">Simpan</button>
        <a href="{{ route('jurnal.index') }}">Batal</a>
    </form>
@endsection