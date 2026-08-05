@extends('layouts.app')

@section('title', 'Detail Jadwal Pelajaran')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2> Detail Jadwal Pelajaran</h2>
            <p>Rincian alokasi jam mengajar guru.</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('jadwal.edit', $jadwal) }}" class="btn btn-action-edit"> Edit Data</a>
            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <tbody>
                    <tr>
                        <th style="width: 30%;">Hari & Jam Pelajaran</th>
                        <td>
                            <span class="jadwal-hari-badge">{{ $jadwal->hari }}</span>
                            <span class="jadwal-jam-pill">Jam ke-{{ $jadwal->jam_ke }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>
                            <span class="siswa-kelas-tag">
                                {{ $jadwal->kelas->tingkat ?? '-' }} {{ $jadwal->kelas->jurusan->kode_jurusan ?? '' }} {{ $jadwal->kelas->rombel ?? '' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <td><strong style="font-size: 1.05rem;">{{ $jadwal->mapel->nama_mapel ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <th>Guru Pengampu</th>
                        <td><strong class="guru-name">{{ $jadwal->guru->nama_guru ?? '-' }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection