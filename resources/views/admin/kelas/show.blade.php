@extends('layouts.app')

@section('title', 'Detail Kelas')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2>Detail Kelas</h2>
            <p>Rincian informasi rombongan belajar.</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('kelas.edit', $kelas) }}" class="btn btn-action-edit">Edit Data</a>
            <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <tbody>
                    <tr>
                        <th style="width: 30%;">Format Kelas</th>
                        <td>
                            <div class="kelas-badge-group">
                                <span class="badge-item tingkat">{{ $kelas->tingkat }}</span>
                                <span class="badge-item jurusan">{{ $kelas->jurusan->kode_jurusan ?? '-' }}</span>
                                <span class="badge-item rombel">{{ $kelas->rombel }}</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Tingkat Kelas</th>
                        <td><span class="badge-chip badge-primary">{{ $kelas->tingkat }}</span></td>
                    </tr>
                    <tr>
                        <th>Jurusan</th>
                        <td>
                            <span class="jurusan-code-badge">{{ $kelas->jurusan->kode_jurusan ?? '-' }}</span>
                            <span>{{ $kelas->jurusan->nama_jurusan ?? '' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Rombel</th>
                        <td><span class="badge-chip badge-neutral">{{ $kelas->rombel }}</span></td>
                    </tr>
                    <tr>
                        <th>Wali Kelas</th>
                        <td><span class="wali-kelas-tag">{{ $kelas->wali_kelas ?: '-' }}</span></td>
                    </tr>
                    <tr>
                        <th>Jumlah Siswa</th>
                        <td><span class="badge-chip badge-neutral">👥 {{ $kelas->jumlah_siswa ?? 0 }} Siswa</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection