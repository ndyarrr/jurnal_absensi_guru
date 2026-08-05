@extends('layouts.app')

@section('content')
    <h2>Jadwal Pelajaran</h2>
    <a href="{{ route('jadwal.create') }}">+ Tambah Jadwal</a>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-top:15px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Hari</th>
                <th>Jam Ke</th>
                <th>Kelas</th>
                <th>Mapel</th>
                <th>Guru</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwal as $j)
                <tr>
                    <td>{{ $j->hari }}</td>
                    <td>{{ $j->jam_ke }}</td>
                    <td>{{ $j->kelas->tingkat ?? '-' }} {{ $j->kelas->jurusan->kode_jurusan ?? '' }} {{ $j->kelas->rombel ?? '' }}</td>
                    <td>{{ $j->mapel->nama_mapel ?? '-' }}</td>
                    <td>{{ $j->guru->nama_guru ?? '-' }}</td>
                    <td>
                        <a href="{{ route('jadwal.show', $j) }}">Detail</a> |
                        <a href="{{ route('jadwal.edit', $j) }}">Edit</a> |
                        <form action="{{ route('jadwal.destroy', $j) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada data jadwal.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection