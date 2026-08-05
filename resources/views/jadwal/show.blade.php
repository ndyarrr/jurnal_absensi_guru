@extends('layouts.app')

@section('content')
    <h2>Detail Jadwal</h2>

    <p><strong>Kelas:</strong> {{ $jadwal->kelas->tingkat ?? '-' }} {{ $jadwal->kelas->rombel ?? '' }}</p>
    <p><strong>Hari:</strong> {{ $jadwal->hari }}</p>
    <p><strong>Jam Ke:</strong> {{ $jadwal->jam_ke }}</p>
    <p><strong>Mapel:</strong> {{ $jadwal->mapel->nama_mapel ?? '-' }}</p>
    <p><strong>Guru:</strong> {{ $jadwal->guru->nama_guru ?? '-' }}</p>

    <a href="{{ route('jadwal.index') }}">← Kembali</a>
@endsection