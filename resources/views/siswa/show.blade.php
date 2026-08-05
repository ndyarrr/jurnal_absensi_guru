@extends('layouts.app')

@section('content')
    <h2>Detail Siswa</h2>

    <p><strong>NISN:</strong> {{ $siswa->nisn }}</p>
    <p><strong>Nama:</strong> {{ $siswa->nama_siswa }}</p>
    <p><strong>Kelas:</strong> {{ $siswa->kelas->tingkat ?? '-' }} {{ $siswa->kelas->rombel ?? '' }}</p>

    <a href="{{ route('siswa.index') }}">← Kembali</a>
@endsection