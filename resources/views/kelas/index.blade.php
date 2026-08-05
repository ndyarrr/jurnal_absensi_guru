@extends('layouts.app')

@section('content')
    <h2>Data Kelas</h2>
    <a href="{{ route('kelas.create') }}">+ Tambah Kelas</a>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-top:15px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Tingkat</th>
                <th>Jurusan</th>
                <th>Rombel</th>
                <th>Wali Kelas</th>
                <th>Jumlah Siswa</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kelas as $k)
                <tr>
                    <td>{{ $k->tingkat }}</td>
                    <td>{{ $k->jurusan->nama_jurusan ?? '-' }}</td>
                    <td>{{ $k->rombel }}</td>
                    <td>{{ $k->wali_kelas ?? '-' }}</td>
                    <td>{{ $k->jumlah_siswa ?? 0 }}</td>
                    <td>
                        <a href="{{ route('kelas.show', $k) }}">Detail</a> |
                        <a href="{{ route('kelas.edit', $k) }}">Edit</a> |
                        <form action="{{ route('kelas.destroy', $k) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada data kelas.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection