@extends('layouts.app')

@section('title', 'Jurnal Mengajar')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2> Jurnal Mengajar Guru</h2>
            <p>Rekapitulasi materi pembelajaran dan absensi guru & siswa.</p>
        </div>
        <a href="{{ route('jurnal.create') }}" class="btn btn-primary">
            <span><i class="fa-solid fa-plus"></i></span> Tambah Jurnal Baru
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 12%;">Tanggal</th>
                        <th style="width: 13%;">Hari & Jam</th>
                        <th style="width: 15%;">Kelas</th>
                        <th style="width: 20%;">Mata Pelajaran</th>
                        <th style="width: 13%;">Guru</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 17%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurnal as $j)
                        <tr>
                            <td>
                                <span class="jurnal-tanggal">{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}</span>
                            </td>
                            <td>
                                <span class="jadwal-hari-badge">{{ $j->jadwal->hari ?? '-' }}</span>
                                <div style="margin-top: 4px;">
                                    <span class="jadwal-jam-pill">Jam ke-{{ $j->jadwal->jam_ke ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="kelas-badge-group">
                                    <span class="badge-item tingkat">{{ $j->jadwal->kelas->tingkat ?? '-' }}</span>
                                    <span class="badge-item jurusan">{{ $j->jadwal->kelas->jurusan->kode_jurusan ?? '-' }}</span>
                                    <span class="badge-item rombel">{{ $j->jadwal->kelas->rombel ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="mapel-name-tag">{{ $j->jadwal->mapel->nama_mapel ?? '-' }}</span>
                                @if($j->materi)
                                    <div class="jurnal-materi-preview" title="{{ $j->materi }}">{{ $j->materi }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="guru-name">{{ $j->jadwal->guru->nama_guru ?? '-' }}</span>
                            </td>
                            <td>
                                @if($j->status_kehadiran == 'Hadir')
                                    <span class="badge-chip badge-success">Hadir</span>
                                @elseif($j->status_kehadiran == 'Izin')
                                    <span class="badge-chip badge-warning">Izin</span>
                                @elseif($j->status_kehadiran == 'Sakit')
                                    <span class="badge-chip badge-info">Sakit</span>
                                @else
                                    <span class="badge-chip badge-danger">{{ $j->status_kehadiran }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="{{ route('jurnal.show', $j) }}" class="btn btn-sm btn-action-show" title="Detail"> Detail</a>
                                    <a href="{{ route('jurnal.edit', $j) }}" class="btn btn-sm btn-action-edit" title="Edit">Edit</a>
                                    <form action="{{ route('jurnal.destroy', $j) }}" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Yakin hapus jurnal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-action-delete" title="Hapus">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon"></div>
                                    <h4>Belum Ada Data Jurnal Mengajar</h4>
                                    <p>Silakan klik tombol <strong>+ Tambah Jurnal Baru</strong> untuk menginput jurnal hari ini.</p>
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