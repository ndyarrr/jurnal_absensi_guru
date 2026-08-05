@extends('layouts.app')

@section('content')
    <h2>Jurnal Mengajar</h2>
    <a href="{{ route('jurnal.create') }}">+ Tambah Jurnal</a>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-top:15px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kelas</th>
                <th>Mapel</th>
                <th>Guru</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnal as $j)
                <tr>
                    <td>{{ $j->tanggal }}</td>
                    <td>{{ $j->jadwal->kelas->tingkat ?? '-' }} {{ $j->jadwal->kelas->jurusan->kode_jurusan ?? '' }} {{ $j->jadwal->kelas->rombel ?? '' }}</td>
                    <td>{{ $j->jadwal->mapel->nama_mapel ?? '-' }}</td>
                    <td>{{ $j->jadwal->guru->nama_guru ?? '-' }}</td>
                    <td>{{ $j->status_kehadiran }}</td>
                    <td>
                        <a href="{{ route('jurnal.show', $j) }}">Detail</a> |
                        <a href="{{ route('jurnal.edit', $j) }}">Edit</a> |
                        <form action="{{ route('jurnal.destroy', $j) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada data jurnal.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection