@extends('layouts.app')

@section('title', 'Data Jurusan')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2>🎓 Data Jurusan</h2>
            <p>Daftar program keahlian & jurusan yang tersedia di sekolah.</p>
        </div>
        <a href="{{ route('jurusan.create') }}" class="btn btn-primary">
            <span><i class="fa-solid fa-plus"></i></span> Tambah Jurusan Baru
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Kode Jurusan</th>
                        <th style="width: 50%;">Nama Jurusan</th>
                        <th style="width: 25%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurusan as $j)
                        <tr>
                            <td>
                                <span class="jurusan-code-badge">{{ $j->kode_jurusan }}</span>
                            </td>
                            <td>
                                <span class="jurusan-title">{{ $j->nama_jurusan }}</span>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="{{ route('jurusan.show', $j) }}" class="btn btn-sm btn-action-show" title="Detail"> Detail</a>
                                    <a href="{{ route('jurusan.edit', $j) }}" class="btn btn-sm btn-action-edit" title="Edit"> Edit</a>
                                    <form action="{{ route('jurusan.destroy', $j) }}" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Yakin hapus jurusan ini?')">
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
                                    <div class="empty-state-icon">🎓</div>
                                    <h4>Belum Ada Data Jurusan</h4>
                                    <p>Silakan klik tombol <strong>+ Tambah Jurusan Baru</strong> untuk menambahkan data.</p>
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