@extends('layouts.guru_mengajar')

@section('title', 'Jurnal Harian')
@section('page-title', 'Jurnal Harian')
@section('page-subtitle', 'Isi & kelola jurnal mengajar serta presensi siswa')

@section('content')

    <div class="gm-card">
        <div class="gm-card-header">
            <h3 class="gm-card-title"><i class="fa-solid fa-list-check" style="color: var(--dash-navy);"></i> Riwayat Jurnal Mengajar Saya</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('guru-mengajar.export-csv', request()->query()) }}" class="gm-btn gm-btn-tan">
                    <i class="fa-solid fa-file-csv"></i> Ekspor CSV
                </a>
            </div>
        </div>

        <div class="gm-card-body">
            <!-- Filter Controls -->
            <form method="GET" action="{{ route('guru-mengajar.jurnal') }}" class="gm-filter-bar" style="margin-bottom: 20px;">
                <input type="text" name="search" class="gm-input" placeholder="Cari materi / mapel..." value="{{ request('search') }}" style="min-width: 220px;">
                <input type="date" name="tanggal" class="gm-input" value="{{ request('tanggal') }}">

                <select name="id_kelas" class="gm-select">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($allKelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}
                        </option>
                    @endforeach
                </select>

                <select name="id_mapel" class="gm-select">
                    <option value="">-- Semua Mapel --</option>
                    @foreach($allMapel as $m)
                        <option value="{{ $m->id_mapel }}" {{ request('id_mapel') == $m->id_mapel ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="gm-btn gm-btn-navy"><i class="fa-solid fa-filter"></i> Filter</button>
                <a href="{{ route('guru-mengajar.jurnal') }}" class="gm-btn gm-btn-outline"><i class="fa-solid fa-rotate-left"></i> Reset</a>
            </form>

            <!-- Table -->
            <div class="gm-table-responsive">
                <table class="gm-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Materi Pembelajaran</th>
                            <th>Presensi Siswa</th>
                            <th>Catatan KBM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jurnalHistory as $jurnal)
                            @php
                                $kelasObj = optional($jurnal->jadwal)->kelas;
                                $kelasStr = $kelasObj ? ($kelasObj->tingkat . ' ' . optional($kelasObj->jurusan)->kode_jurusan . ' ' . $kelasObj->rombel) : '-';
                                $mapelStr = optional(optional($jurnal->jadwal)->mapel)->nama_mapel ?? '-';
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight: 800; color: var(--dash-navy);">{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') }}</div>
                                    <div style="font-size: 0.775rem; color: var(--dash-text-muted);">{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l') }}</div>
                                </td>
                                <td><span style="font-weight: 700; color: var(--dash-navy);">{{ $kelasStr }}</span></td>
                                <td><span style="font-weight: 700; color: var(--dash-navy);">{{ $mapelStr }}</span></td>
                                <td><div style="max-width: 250px; white-space: normal; line-height: 1.4;">{{ $jurnal->materi ?? '-' }}</div></td>
                                <td>
                                    <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                                        <span class="gm-status-badge filled">Hadir: {{ $jurnal->jumlah_hadir ?? 0 }}</span>
                                        @if(($jurnal->jumlah_tidak_hadir ?? 0) > 0)
                                            <span style="background: #fee2e2; color: #dc2626; padding: 3px 10px; border-radius: 20px; font-weight: 800; font-size: 0.7rem;">
                                                Absen: {{ $jurnal->jumlah_tidak_hadir }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td><div style="max-width: 200px; font-size: 0.8rem; color: var(--dash-text-muted);">{{ $jurnal->catatan ? \Illuminate\Support\Str::limit($jurnal->catatan, 50) : '-' }}</div></td>
                                <td>
                                    <a href="{{ route('guru-mengajar.jurnal.input', ['id_jadwal' => optional($jurnal->jadwal)->id_jadwal, 'tanggal' => $jurnal->tanggal]) }}" class="gm-btn gm-btn-outline" style="padding: 6px 12px; font-size: 0.8rem; text-decoration: none;">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="gm-empty-state">
                                        <div class="gm-empty-icon"><i class="fa-regular fa-folder-open"></i></div>
                                        <div class="gm-empty-title">Belum Ada Riwayat Jurnal</div>
                                        <p>Jurnal yang Anda isi dari jadwal harian akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($jurnalHistory->hasPages())
                <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                    {{ $jurnalHistory->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Auto-filter on page load using today's date
    document.addEventListener('DOMContentLoaded', function() {
        const todayInput = document.getElementById('pilih_tanggal');
        if (todayInput && todayInput.value) {
            filterJadwalByHari(todayInput.value);
        }
    });
</script>
@endpush