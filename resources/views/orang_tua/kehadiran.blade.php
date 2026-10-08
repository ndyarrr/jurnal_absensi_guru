@extends('layouts.orang_tua')

@section('title', 'Rekap Kehadiran')
@section('page-title', 'Rekap Kehadiran')
@section('page-subtitle', 'Riwayat kehadiran anak per bulan')

@section('content')
<div class="ot-page">
@if(! $anak)
    @include('orang_tua._kosong')
@else
    @include('orang_tua._anak', ['routeName' => 'orang-tua.kehadiran'])

    <div class="ot-card">
        <div class="ot-card-header">
            <div>
                <h3 class="ot-card-title"><i class="fa-solid fa-calendar-check" style="color: var(--dash-navy);"></i> Ringkasan {{ $monthLabel }}</h3>
                <div class="ot-card-subtitle">Dihitung per jam pelajaran yang sudah diisi jurnalnya oleh guru</div>
            </div>
            <div class="ot-month-nav">
                <a class="ot-month-btn" href="{{ route('orang-tua.kehadiran', $prevParams) }}" title="Bulan sebelumnya"><i class="fa-solid fa-chevron-left"></i></a>
                <span class="ot-month-label">{{ $monthLabel }}</span>
                <a class="ot-month-btn" href="{{ route('orang-tua.kehadiran', $nextParams) }}" title="Bulan berikutnya"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
        </div>
        <div style="padding: 18px 22px;">
            <section class="ot-stats-grid six">
                <div class="ot-stat-card">
                    <div class="ot-stat-value">{{ is_null($rekap['persen']) ? '-' : $rekap['persen'] . '%' }}</div>
                    <div class="ot-stat-label">Kehadiran</div>
                </div>
                <div class="ot-stat-card">
                    <div class="ot-stat-value">{{ $rekap['hadir'] }}<span style="font-size:0.9rem; color:var(--dash-text-muted);"> / {{ $rekap['total_pertemuan'] }}</span></div>
                    <div class="ot-stat-label">Jam Hadir</div>
                </div>
                <div class="ot-stat-card">
                    <div class="ot-stat-value">{{ $rekap['sakit'] }}</div>
                    <div class="ot-stat-label">Sakit</div>
                </div>
                <div class="ot-stat-card">
                    <div class="ot-stat-value">{{ $rekap['izin'] }}</div>
                    <div class="ot-stat-label">Izin</div>
                </div>
                <div class="ot-stat-card">
                    <div class="ot-stat-value">{{ $rekap['dispensasi'] }}</div>
                    <div class="ot-stat-label">Dispensasi</div>
                </div>
                <div class="ot-stat-card">
                    <div class="ot-stat-value" style="{{ $rekap['alpa'] > 0 ? 'color:#dc2626;' : '' }}">{{ $rekap['alpa'] }}</div>
                    <div class="ot-stat-label">Alpa</div>
                </div>
            </section>
        </div>
    </div>

    <div class="ot-card">
        <div class="ot-card-header">
            <h3 class="ot-card-title"><i class="fa-solid fa-list-check" style="color: var(--dash-navy);"></i> Catatan Ketidakhadiran</h3>
        </div>
        <div class="ot-card-body">
            @forelse($riwayatPerTanggal as $hari)
                <div class="ot-day-head">{{ $hari['tanggal']->translatedFormat('l, d F Y') }}</div>
                @foreach($hari['items'] as $item)
                    <div class="ot-row">
                        <div>
                            <div class="ot-row-title">{{ $item['mapel'] }}</div>
                            <div class="ot-row-meta">{{ $item['jam'] }}@if($item['catatan']) &middot; {{ $item['catatan'] }}@endif</div>
                        </div>
                        <span class="ot-badge {{ $item['status']['key'] }}">{{ $item['status']['label'] }}</span>
                    </div>
                @endforeach
            @empty
                <div class="ot-empty">
                    <div class="ot-empty-icon"><i class="fa-solid fa-circle-check" style="color:#86efac;"></i></div>
                    <div class="ot-empty-title">
                        @if($rekap['total_pertemuan'] > 0)
                            Tidak ada ketidakhadiran di bulan ini
                        @else
                            Belum ada data kehadiran di bulan ini
                        @endif
                    </div>
                    <p>
                        @if($rekap['total_pertemuan'] > 0)
                            Anak Anda tercatat hadir di semua jam pelajaran.
                        @else
                            Data muncul setelah guru mengisi jurnal mengajar.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
    </div>
@endif
</div>
@endsection