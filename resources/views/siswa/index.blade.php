@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2>Data Siswa</h2>
            <p>Daftar seluruh siswa terdaftar berdasarkan kelas.</p>
        </div>
        <a href="{{ route('siswa.create') }}" class="btn btn-primary">
            <span><i class="fa-solid fa-plus"></i></span> Tambah Siswa Baru
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">NISN / NIS</th>
                        <th style="width: 40%;">Nama Siswa</th>
                        <th style="width: 20%;">Kelas</th>
                        <th style="width: 15%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $s)
                        <tr>
                            <td>
                                <span class="siswa-nisn-badge">{{ $s->nisn }}</span>
                            </td>
                            <td>
                                <span class="siswa-name-cell">{{ $s->nama_siswa }}</span>
                            </td>
                            <td>
                                <div class="kelas-badge-group">
                                    <span class="badge-item tingkat">{{ $s->kelas->tingkat ?? '-' }}</span>
                                    <span class="badge-item jurusan">{{ $s->kelas->jurusan->kode_jurusan ?? '-' }}</span>
                                    <span class="badge-item rombel">{{ $s->kelas->rombel ?? '-' }}</span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="{{ route('siswa.show', $s) }}" class="btn btn-sm btn-action-show" title="Detail"> Detail</a>
                                    <a href="{{ route('siswa.edit', $s) }}" class="btn btn-sm btn-action-edit" title="Edit">Edit</a>
                                    <form action="{{ route('siswa.destroy', $s) }}" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Yakin hapus data siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-action-delete" title="Hapus">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-state-icon"></div>
                                    <h4>Belum Ada Data Siswa</h4>
                                    <p>Silakan klik tombol <strong>+ Tambah Siswa Baru</strong> untuk mendaftarkan siswa.</p>
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