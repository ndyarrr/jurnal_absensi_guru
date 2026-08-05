@extends('layouts.app')

@section('content')
    <h2>Tambah Jurusan</h2>

    @if($errors->any())
        <ul style="color:red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('jurusan.store') }}" method="POST">
        @csrf

        <label>Kode Jurusan</label><br>
        <input type="text" name="kode_jurusan" value="{{ old('kode_jurusan') }}" required><br><br>

        <label>Nama Jurusan</label><br>
        <input type="text" name="nama_jurusan" value="{{ old('nama_jurusan') }}" required><br><br>

        <button type="submit">Simpan</button>
        <a href="{{ route('jurusan.index') }}">Batal</a>
    </form>
@endsection