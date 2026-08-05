@extends('layouts.app')

@section('content')
    <h2>Tambah Siswa</h2>

    @if($errors->any())
        <ul style="color:red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('siswa.store') }}" method="POST">
        @csrf

        <label>NISN</label><br>
        <input type="text" name="nisn" value="{{ old('nisn') }}" required><br><br>

        <label>Nama Siswa</label><br>
        <input type="text" name="nama_siswa" value="{{ old('nama_siswa') }}" required><br><br>

        <label>Kelas</label><br>
        <select name="id_kelas" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach($kelas as $k)
                <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                    {{ $k->tingkat }} {{ $k->rombel }} - {{ $k->jurusan->kode_jurusan ?? '' }}
                </option>
            @endforeach
        </select><br><br>

        <button type="submit">Simpan</button>
        <a href="{{ route('siswa.index') }}">Batal</a>
    </form>
@endsection