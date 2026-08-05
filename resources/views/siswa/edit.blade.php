@extends('layouts.app')

@section('content')
    <h2>Edit Siswa</h2>

    @if($errors->any())
        <ul style="color:red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('siswa.update', $siswa) }}" method="POST">
        @csrf
        @method('PUT')

        <label>NISN</label><br>
        <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" required><br><br>

        <label>Nama Siswa</label><br>
        <input type="text" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" required><br><br>

        <label>Kelas</label><br>
        <select name="id_kelas" required>
            @foreach($kelas as $k)
                <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $siswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                    {{ $k->tingkat }} {{ $k->rombel }} - {{ $k->jurusan->kode_jurusan ?? '' }}
                </option>
            @endforeach
        </select><br><br>

        <button type="submit">Update</button>
        <a href="{{ route('siswa.index') }}">Batal</a>
    </form>
@endsection