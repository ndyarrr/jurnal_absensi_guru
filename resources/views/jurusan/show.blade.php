@extends('layouts.app')

@section('content')
    <h2>Detail Jurusan</h2>

    <p><strong>Kode:</strong> {{ $jurusan->kode_jurusan }}</p>
    <p><strong>Nama:</strong> {{ $jurusan->nama_jurusan }}</p>

    <a href="{{ route('jurusan.index') }}">← Kembali</a>
@endsection