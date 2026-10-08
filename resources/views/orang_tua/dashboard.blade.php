@extends('layouts.orang_tua')

@section('title', 'Dashboard Orang Tua')
@section('page-title', 'Dashboard Orang Tua')
@section('page-subtitle', 'Pantau kehadiran dan aktivitas sekolah anak Anda')

@section('content')
<div class="ot-page">
@if(! $anak)
    @include('orang_tua._kosong')
@else
    @include('orang_tua._anak', ['routeName' => 'orang-tua.dashboard'])

    <section class="ot-stats-grid">
        <div class="ot-stat-card">
            <div class="ot-stat-value small"><span class="ot-badge {{ $statusHariIni['key'] }}">{{ $statusHariIni['label'] }}</span></div>
            <div class="ot-stat-label">Status Hari Ini</div>
            <div class="ot-stat-sub">{{ $statusHariIni['detail'] }}</div>
        </div>
        <div class="ot-stat-card">
            <div class="ot-stat-value">{{ is_null($rekapBulanIni['persen']) ? '-' : $rekapBulanIni['persen'] . '%' }}</div>
            <div class="ot-stat-label">Kehadiran Bulan {{ $now->translatedFormat('F') }}</div>
            <div class="ot-stat-sub">{{ $rekapBulanIni['hadir'] }} dari {{ $rekapBulanIni['total_pertemuan'] }} jam pelajaran</div>
        </div>
        <div class="ot-stat-card">
            <div class="ot-stat-value">{{ $rekapBulanIni['sakit'] + $rekapBulanIni['izin'] + $rekapBulanIni['dispensasi'] }}</div>
            <div class="ot-stat-label">Sakit / Izin / Dispensasi</div>
            <div class="ot-stat-sub">{{ $rekapBulanIni['sakit'] }} sakit &middot; {{ $rekapBulanIni['izin'] }} izin &middot; {{ $rekapBulanIni['dispensasi'] }} dispensasi</div>
        </div>
        <div class="ot-stat-card">
            <div class="ot-stat-value" style="{{ $rekapBulanIni['alpa'] > 0 ? 'color:#dc2626;' : '' }}">{{ $rekapBulanIni['alpa'] }}</div>
            <div class="ot-stat-label">Alpa (Tanpa Keterangan)</div>
            <div class="ot-stat-sub">Bulan {{ $now->translatedFormat('F Y') }}</div>
        </div>
    </section>

    <section class="ot-content-grid">
        <div class="ot-card">
            <div class="ot-card-header">
                <div>
                    <h3 class="ot-card-title"><i class="fa-solid fa-book-open" style="color: var(--dash-navy);"></i> Pelajaran Hari Ini</h3>
                    <div class="ot-card-subtitle">{{ $now->translatedFormat('l, d F Y') }}</div>
                </div>
            </div>
            <div class="ot-card-body">
                @forelse($pelajaranHariIni as $p)
                    <div class="ot-row">
                        <div>
                            <div class="ot-row-title">{{ $p['mapel'] }}</div>
                            <div class="ot-row-meta">{{ $p['guru'] }}@if($p['materi']) &middot; Materi: {{ $p['materi'] }}@endif</div>
                        </div>
                        <div class="ot-row-end">
                            <div class="ot-row-time">{{ $p['jam'] }}</div>
                            <span class="ot-badge {{ $p['status']['key'] }}">{{ $p['status']['label'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="ot-empty">
                        <div class="ot-empty-icon"><i class="fa-regular fa-calendar"></i></div>
                        <div class="ot-empty-title">Tidak ada jadwal pelajaran hari {{ $namaHari }}</div>
                        <p>Jadwal akan tampil di sini pada hari sekolah.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="ot-card">
            <div class="ot-card-header">
                <h3 class="ot-card-title"><i class="fa-solid fa-timeline" style="color: var(--dash-navy);"></i> Aktivitas Terbaru</h3>
            </div>
            <div class="ot-card-body">
                @forelse($aktivitasTerbaru as $a)
                    <div class="ot-row">
                        <div>
                            <div class="ot-row-title">{{ $a['judul'] }}</div>
                            <div class="ot-row-meta">{{ \Illuminate\Support\Str::limit($a['detail'], 90) }}</div>
                        </div>
                        <div class="ot-row-end">
                            <div class="ot-row-time">{{ $a['waktu']->translatedFormat('d M') }}</div>
                            <span class="ot-badge {{ $a['badge'] }}">{{ $a['badge_label'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="ot-empty">
                        <div class="ot-empty-icon"><i class="fa-regular fa-clock"></i></div>
                        <div class="ot-empty-title">Belum ada aktivitas</div>
                        <p>Catatan ketidakhadiran dan surat izin anak akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endif
</div>
@endsection