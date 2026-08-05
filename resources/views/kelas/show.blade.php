@extends('layouts.app')

@section('content')
    <h2>Detail Kelas</h2>

    <p><strong>Tingkat:</strong> {{ $kelas->tingkat }}</p>
    <p><strong>Jurusan:</strong> {{ $kelas->jurusan->nama_jurusan ?? '-' }}</p>
    <p><strong>Rombel:</strong> {{ $kelas->rombel }}</p>
    <p><strong>Wali Kelas:</strong> {{ $kelas->wali_kelas ?? '-' }}</p>
    <p><strong>Jumlah Siswa:</strong> {{ $kelas->jumlah_siswa ?? 0 }}</p>

    <a href="{{ route('kelas.index') }}">← Kembali</a>
@endsection