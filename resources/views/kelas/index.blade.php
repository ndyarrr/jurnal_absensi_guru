@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2>Data Kelas</h2>
            <p>Daftar rombongan belajar dan wali kelas.</p>
        </div>
        <a href="{{ route('kelas.create') }}" class="btn btn-primary">
            <span><i class="fa-solid fa-plus"></i></span> Tambah Kelas Baru
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">Tingkat</th>
                        <th style="width: 30%;">Nama Jurusan</th>
                        <th style="width: 10%;">Rombel</th>
                        <th style="width: 20%;">Wali Kelas</th>
                        <th style="width: 12%;">Jumlah Siswa</th>
                        <th style="width: 18%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $k)
                        <tr>
                            <td>
                                <span class="badge-chip badge-primary" style="font-weight: 700;">{{ $k->tingkat }}</span>
                            </td>
                            <td>
                                <span class="jurusan-code-badge">{{ $k->jurusan->kode_jurusan ?? '-' }}</span>
                                <span class="jurusan-title" style="margin-left: 6px;">{{ $k->jurusan->nama_jurusan ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge-chip badge-neutral" style="font-weight: 700;">{{ $k->rombel }}</span>
                            </td>
                            <td>
                                <span class="wali-kelas-tag">{{ $k->wali_kelas ?: '-' }}</span>
                            </td>
                            <td>
                                <span class="badge-chip badge-neutral">👥 {{ $k->jumlah_siswa ?? 0 }} Siswa</span>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="{{ route('kelas.show', $k) }}" class="btn btn-sm btn-action-show" title="Detail"> Detail</a>
                                    <a href="{{ route('kelas.edit', $k) }}" class="btn btn-sm btn-action-edit" title="Edit">Edit</a>
                                    <form action="{{ route('kelas.destroy', $k) }}" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Yakin hapus data kelas ini?')">
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
                                    <div class="empty-state-icon">🏫</div>
                                    <h4>Belum Ada Data Kelas</h4>
                                    <p>Silakan klik tombol <strong>+ Tambah Kelas Baru</strong> untuk mendaftarkan kelas.</p>
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