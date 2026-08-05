@extends('layouts.app')

@section('content')
    <h2>Data Mata Pelajaran</h2>
    <a href="{{ route('mapel.create') }}">+ Tambah Mapel</a>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-top:15px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Nama Mapel</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mapel as $m)
                <tr>
                    <td>{{ $m->nama_mapel }}</td>
                    <td>
                        <a href="{{ route('mapel.show', $m) }}">Detail</a> |
                        <a href="{{ route('mapel.edit', $m) }}">Edit</a> |
                        <form action="{{ route('mapel.destroy', $m) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="2">Belum ada data mapel.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection