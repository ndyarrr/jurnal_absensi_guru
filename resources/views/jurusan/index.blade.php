@extends('layouts.app')

@section('content')
    <h2>Data Jurusan</h2>
    <a href="{{ route('jurusan.create') }}">+ Tambah Jurusan</a>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-top:15px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Jurusan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurusan as $j)
                <tr>
                    <td>{{ $j->kode_jurusan }}</td>
                    <td>{{ $j->nama_jurusan }}</td>
                    <td>
                        <a href="{{ route('jurusan.show', $j) }}">Detail</a> |
                        <a href="{{ route('jurusan.edit', $j) }}">Edit</a> |
                        <form action="{{ route('jurusan.destroy', $j) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">Belum ada data jurusan.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection