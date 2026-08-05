@extends('layouts.app')

@section('content')
    <h2>Detail Jurnal Mengajar</h2>

    <p><strong>Tanggal:</strong> {{ $jurnal->tanggal }}</p>
    <p><strong>Kelas:</strong> {{ $jurnal->jadwal->kelas->tingkat ?? '-' }} {{ $jurnal->jadwal->kelas->jurusan->kode_jurusan ?? '' }} {{ $jurnal->jadwal->kelas->rombel ?? '' }}</p>
    <p><strong>Mapel:</strong> {{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}</p>
    <p><strong>Guru:</strong> {{ $jurnal->jadwal->guru->nama_guru ?? '-' }}</p>
    <p><strong>Status Kehadiran:</strong> {{ $jurnal->status_kehadiran }}</p>
    <p><strong>Guru Pengganti:</strong> {{ $jurnal->guruPengganti->nama_guru ?? '-' }}</p>
    <p><strong>Materi:</strong> {{ $jurnal->materi ?? '-' }}</p>
    <p><strong>Jumlah Hadir:</strong> {{ $jurnal->jumlah_hadir ?? 0 }}</p>
    <p><strong>Jumlah Tidak Hadir:</strong> {{ $jurnal->jumlah_tidak_hadir ?? 0 }}</p>
    <p><strong>Catatan:</strong> {{ $jurnal->catatan ?? '-' }}</p>

    <h3>Siswa Tidak Hadir</h3>
    <ul>
        @forelse($jurnal->detailKetidakhadiran as $d)
            <li>
                {{ $d->siswa->nama_siswa ?? '(siswa tidak ditemukan)' }} — {{ $d->status }}
                @if($d->siswa && $d->siswa->trashed())
                    <span style="color:#999; font-size:12px;">(sudah dihapus dari data siswa)</span>
                @endif
            </li>
        @empty
            <li>Tidak ada data.</li>
        @endforelse
    </ul>

    <a href="{{ route('jurnal.index') }}">← Kembali</a>
@endsection