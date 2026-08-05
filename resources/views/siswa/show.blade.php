@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2>Detail Siswa</h2>
            <p>Rincian data siswa terdaftar.</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('siswa.edit', $siswa) }}" class="btn btn-action-edit"> Edit Data</a>
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <tbody>
                    <tr>
                        <th style="width: 30%;">NISN / NIS</th>
                        <td><span class="siswa-nisn-badge">{{ $siswa->nisn }}</span></td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td><strong style="font-size: 1.05rem;">{{ $siswa->nama_siswa }}</strong></td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>
                            <span class="siswa-kelas-tag">
                                {{ $siswa->kelas->tingkat ?? '-' }} {{ $siswa->kelas->jurusan->kode_jurusan ?? '' }} {{ $siswa->kelas->rombel ?? '' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection