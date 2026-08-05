@extends('layouts.app')

@section('content')
    <h2>Data Siswa</h2>
    <a href="{{ route('siswa.create') }}">+ Tambah Siswa</a>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-top:15px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa as $s)
                <tr>
                    <td>{{ $s->nisn }}</td>
                    <td>{{ $s->nama_siswa }}</td>
                    <<td>{{ $s->kelas->tingkat ?? '-' }} {{ $s->kelas->jurusan->kode_jurusan ?? '' }} {{ $s->kelas->rombel ?? '' }}</td>
                    <td>
                        <a href="{{ route('siswa.show', $s) }}">Detail</a> |
                        <a href="{{ route('siswa.edit', $s) }}">Edit</a> |
                        <form action="{{ route('siswa.destroy', $s) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">Belum ada data siswa.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection