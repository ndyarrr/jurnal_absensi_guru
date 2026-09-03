@extends('layouts.guru_mengajar')

@section('title', 'Absensi Siswa')
@section('page-title', 'Absensi Siswa')
@section('page-subtitle', 'Rekap kehadiran siswa dari jurnal yang telah Anda isi')

@section('content')

    <div class="gm-card">
        <div class="gm-card-header">
            <h3 class="gm-card-title"><i class="fa-solid fa-user-check" style="color: var(--dash-navy);"></i> Rekap Kehadiran Siswa</h3>

            <form method="GET" action="{{ route('guru-mengajar.absensi') }}" class="gm-filter-bar">
                <select name="id_kelas" class="gm-select" onchange="this.form.submit()">
                    @forelse($allKelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ (string) $selectedKelasId === (string) $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}
                        </option>
                    @empty
                        <option value="">Belum ada kelas diampu</option>
                    @endforelse
                </select>
            </form>
        </div>

        <div class="gm-card-body">
            @if(!$selectedKelas)
                <div class="gm-empty-state">
                    <div class="gm-empty-icon"><i class="fa-regular fa-face-frown"></i></div>
                    <div class="gm-empty-title">Anda Belum Mengampu Kelas Manapun</div>
                    <p>Rekap absensi akan tampil di sini setelah Anda memiliki jadwal mengajar.</p>
                </div>
            @else
                <div style="margin-bottom: 18px; font-size: 0.85rem; color: var(--dash-text-muted); font-weight: 600;">
                    Kelas <strong style="color: var(--dash-navy);">{{ $selectedKelas->tingkat . ' ' . optional($selectedKelas->jurusan)->kode_jurusan . ' ' . $selectedKelas->rombel }}</strong>
                    &middot; Total pertemuan tercatat: <strong style="color: var(--dash-navy);">{{ $totalPertemuan }}</strong>
                </div>

                <div class="gm-table-responsive">
                    <table class="gm-table">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th style="text-align: center;">Hadir</th>
                                <th style="text-align: center;">Sakit</th>
                                <th style="text-align: center;">Izin</th>
                                <th style="text-align: center;">Alpa</th>
                                <th style="text-align: center;">Persentase Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaList as $siswa)
                                <tr>
                                    <td>
                                        <div style="font-weight: 800; color: var(--dash-navy);">{{ $siswa->nama_siswa }}</div>
                                        <div style="font-size: 0.775rem; color: var(--dash-text-muted);">NISN: {{ $siswa->nisn }}</div>
                                    </td>
                                    <td style="text-align: center;"><span class="gm-day-badge hadir">{{ $siswa->rekap_hadir }}</span></td>
                                    <td style="text-align: center;"><span class="gm-day-badge sakit">{{ $siswa->rekap_sakit }}</span></td>
                                    <td style="text-align: center;"><span class="gm-day-badge izin">{{ $siswa->rekap_izin }}</span></td>
                                    <td style="text-align: center;"><span class="gm-day-badge alpa">{{ $siswa->rekap_alpa }}</span></td>
                                    <td style="text-align: center; font-weight: 800; color: {{ $siswa->rekap_persentase >= 90 ? '#059669' : ($siswa->rekap_persentase >= 75 ? '#b45309' : '#dc2626') }};">
                                        {{ $siswa->rekap_persentase }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="gm-empty-state">
                                            <div class="gm-empty-icon"><i class="fa-regular fa-folder-open"></i></div>
                                            <div class="gm-empty-title">Belum Ada Data Siswa</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection