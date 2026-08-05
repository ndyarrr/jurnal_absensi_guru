@extends('layouts.app')

@section('title', 'Data Mata Pelajaran')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2> Data Mata Pelajaran</h2>
            <p>Daftar mata pelajaran dalam kurikulum sekolah.</p>
        </div>
        <a href="{{ route('mapel.create') }}" class="btn btn-primary">
            <span><i class="fa-solid fa-plus"></i></span> Tambah Mapel Baru
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">No</th>
                        <th style="width: 60%;">Nama Mata Pelajaran</th>
                        <th style="width: 25%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mapel as $index => $m)
                        <tr>
                            <td><span class="badge-chip badge-neutral">{{ $index + 1 }}</span></td>
                            <td><span class="mapel-name-tag">{{ $m->nama_mapel }}</span></td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="{{ route('mapel.show', $m) }}" class="btn btn-sm btn-action-show" title="Detail"> Detail</a>
                                    <a href="{{ route('mapel.edit', $m) }}" class="btn btn-sm btn-action-edit" title="Edit"> Edit</a>
                                    <form action="{{ route('mapel.destroy', $m) }}" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Yakin hapus mapel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-action-delete" title="Hapus">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <div class="empty-state-icon"></div>
                                    <h4>Belum Ada Data Mata Pelajaran</h4>
                                    <p>Silakan klik tombol <strong>+ Tambah Mapel Baru</strong> untuk mendaftarkan mata pelajaran.</p>
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