@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2> Jadwal Pelajaran</h2>
            <p>Jadwal mata pelajaran dan alokasi jam mengajar guru.</p>
        </div>
        <a href="{{ route('jadwal.create') }}" class="btn btn-primary">
            <span><i class="fa-solid fa-plus"></i></span> Tambah Jadwal Baru
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Hari & Jam</th>
                        <th style="width: 20%;">Kelas</th>
                        <th style="width: 30%;">Mata Pelajaran</th>
                        <th style="width: 20%;">Guru Pengampu</th>
                        <th style="width: 15%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $j)
                        <tr>
                            <td>
                                <span class="jadwal-hari-badge">{{ $j->hari }}</span>
                                <div style="margin-top: 4px;">
                                    <span class="jadwal-jam-pill">Jam ke-{{ $j->jam_ke }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="kelas-badge-group">
                                    <span class="badge-item tingkat">{{ $j->kelas->tingkat ?? '-' }}</span>
                                    <span class="badge-item jurusan">{{ $j->kelas->jurusan->kode_jurusan ?? '-' }}</span>
                                    <span class="badge-item rombel">{{ $j->kelas->rombel ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="mapel-name-tag">{{ $j->mapel->nama_mapel ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="guru-name">{{ $j->guru->nama_guru ?? '-' }}</span>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="{{ route('jadwal.show', $j) }}" class="btn btn-sm btn-action-show" title="Detail"> Detail</a>
                                    <a href="{{ route('jadwal.edit', $j) }}" class="btn btn-sm btn-action-edit" title="Edit">Edit</a>
                                    <form action="{{ route('jadwal.destroy', $j) }}" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-action-delete" title="Hapus">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon"></div>
                                    <h4>Belum Ada Jadwal Pelajaran</h4>
                                    <p>Silakan klik tombol <strong>+ Tambah Jadwal Baru</strong> untuk mengatur jadwal mengajar.</p>
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