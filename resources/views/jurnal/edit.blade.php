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
        <select name="id_jadwal" required>
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

        <button type="submit">Update</button>
        <a href="{{ route('jurnal.index') }}">Batal</a>
    </form>
@endsection