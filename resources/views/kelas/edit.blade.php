@extends('layouts.app')

@section('content')
    <h2>Edit Kelas</h2>

    @if($errors->any())
        <div style="color:red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kelas.update', $kelas) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Tingkat</label><br>
        <select name="tingkat" required>
            <option value="X" {{ old('tingkat', $kelas->tingkat) == 'X' ? 'selected' : '' }}>X</option>
            <option value="XI" {{ old('tingkat', $kelas->tingkat) == 'XI' ? 'selected' : '' }}>XI</option>
            <option value="XII" {{ old('tingkat', $kelas->tingkat) == 'XII' ? 'selected' : '' }}>XII</option>
        </select><br><br>

        <label>Jurusan</label><br>
        <select name="id_jurusan" required>
            @foreach($jurusan as $j)
                <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan', $kelas->id_jurusan) == $j->id_jurusan ? 'selected' : '' }}>
                    {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                </option>
            @endforeach
        </select><br><br>

        <label>Rombel</label><br>
        <input type="number" name="rombel" value="{{ old('rombel', $kelas->rombel) }}" min="1" required><br><br>

        <label>Wali Kelas</label><br>
        <input type="text" name="wali_kelas" value="{{ old('wali_kelas', $kelas->wali_kelas) }}"><br><br>

        <label>Jumlah Siswa</label><br>
        <input type="number" name="jumlah_siswa" value="{{ old('jumlah_siswa', $kelas->jumlah_siswa) }}" min="0"><br><br>

        <button type="submit">Update</button>
        <a href="{{ route('kelas.index') }}">Batal</a>
    </form>
@endsection