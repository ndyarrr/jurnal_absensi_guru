@extends('layouts.app')

@section('title', 'Detail Jurnal Mengajar')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2> Detail Jurnal Mengajar</h2>
            <p>Informasi rincian jurnal pembelajaran dan ketidakhadiran siswa.</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('jurnal.edit', $jurnal) }}" class="btn btn-action-edit"> Edit Data</a>
            <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive" style="margin-bottom: 24px;">
            <table class="custom-table">
                <tbody>
                    <tr>
                        <th style="width: 30%;">Tanggal Pembelajaran</th>
                        <td><span class="jurnal-tanggal"> {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}</span></td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>
                            <span class="siswa-kelas-tag">
                                {{ $jurnal->jadwal->kelas->tingkat ?? '-' }} {{ $jurnal->jadwal->kelas->jurusan->kode_jurusan ?? '' }} {{ $jurnal->jadwal->kelas->rombel ?? '' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <td><strong style="font-size: 1.05rem;">{{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <th>Guru Pengampu</th>
                        <td><strong class="guru-name">{{ $jurnal->jadwal->guru->nama_guru ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <th>Status Kehadiran Guru</th>
                        <td>
                            @if($jurnal->status_kehadiran == 'Hadir')
                                <span class="badge-chip badge-success">Hadir</span>
                            @elseif($jurnal->status_kehadiran == 'Izin')
                                <span class="badge-chip badge-warning">Izin</span>
                            @elseif($jurnal->status_kehadiran == 'Sakit')
                                <span class="badge-chip badge-info">Sakit</span>
                            @else
                                <span class="badge-chip badge-danger">{{ $jurnal->status_kehadiran }}</span>
                            @endif
                        </td>
                    </tr>
                    @if($jurnal->guruPengganti)
                        <tr>
                            <th>Guru Pengganti</th>
                            <td><strong class="guru-name">👤 {{ $jurnal->guruPengganti->nama_guru }}</strong></td>
                        </tr>
                    @endif
                    <tr>
                        <th>Materi Pembelajaran</th>
                        <td>{{ $jurnal->materi ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Statistik Kehadiran Siswa</th>
                        <td>
                            <span class="badge-chip badge-success">Hadir: {{ $jurnal->jumlah_hadir ?? 0 }}</span>
                            <span class="badge-chip badge-danger">Tidak Hadir: {{ $jurnal->jumlah_tidak_hadir ?? 0 }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Catatan KBM</th>
                        <td>{{ $jurnal->catatan ?: '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Student Absence Detail Table -->
        <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 12px; color: var(--text-main);">
            👥 Daftar Siswa Tidak Hadir
        </h3>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">No</th>
                        <th style="width: 60%;">Nama Siswa</th>
                        <th style="width: 30%;">Status Ketidakhadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurnal->detailKetidakhadiran as $index => $detail)
                        <tr>
                            <td><span class="badge-chip badge-neutral">{{ $index + 1 }}</span></td>
                            <td><strong class="siswa-name-cell">{{ $detail->siswa->nama_siswa ?? 'Siswa tidak ditemukan' }}</strong></td>
                            <td>
                                @if($detail->status == 'S')
                                    <span class="badge-chip badge-info">😷 Sakit (S)</span>
                                @elseif($detail->status == 'I')
                                    <span class="badge-chip badge-warning">Izin (I)</span>
                                @else
                                    <span class="badge-chip badge-danger">Alpa (A)</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty-state" style="padding: 20px;">
                                    <p>✨ Seluruh siswa hadir dalam pembelajaran ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection