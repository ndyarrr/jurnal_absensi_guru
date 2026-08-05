@extends('layouts.app')

@section('content')
    <h2>Edit Jurusan</h2>

    @if($errors->any())
        <ul style="color:red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('jurusan.update', $jurusan) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Kode Jurusan</label><br>
        <input type="text" name="kode_jurusan" value="{{ old('kode_jurusan', $jurusan->kode_jurusan) }}" required><br><br>

        <label>Nama Jurusan</label><br>
        <input type="text" name="nama_jurusan" value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}" required><br><br>

        <button type="submit">Update</button>
        <a href="{{ route('jurusan.index') }}">Batal</a>
    </form>
@endsection