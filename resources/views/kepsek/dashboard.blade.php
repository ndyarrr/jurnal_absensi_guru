@extends('layouts.kepsek')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Kepala Sekolah')
@section('page-subtitle', 'Halo, ' . auth()->user()->name . ' - ringkasan izin dan kehadiran guru')

@section('content')
    {{-- ===== Statistik izin ===== --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #fff7ed; color: #ea580c;"><i class="fa-solid fa-hourglass-half"></i></div>
            <div>
                <div class="stat-val">{{ $stat['menunggu_saya'] }}</div>
                <div class="stat-label">Menunggu Keputusan Anda</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <div class="stat-val">{{ $stat['disetujui_bulan_ini'] }}</div>
                <div class="stat-label">Anda Setujui Bulan Ini</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fef2f2; color: #dc2626;"><i class="fa-solid fa-circle-xmark"></i></div>
            <div>
                <div class="stat-val">{{ $stat['ditolak_bulan_ini'] }}</div>
                <div class="stat-label">Anda Tolak Bulan Ini</div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        {{-- ===== Perlu keputusan ===== --}}
        <div class="content-card">
            <div class="card-head">
                <div class="card-title"><i class="fa-solid fa-bell" style="color:#ea580c;"></i> Perlu Keputusan Anda</div>
                <a class="card-link" href="{{ route('kepsek.izin.index', ['status' => 'perlu']) }}">Lihat semua</a>
            </div>
            @forelse($perluKeputusan as $izin)
                <div class="list-row">
                    <div>
                        <div class="list-main">{{ $izin->guru->nama_guru ?? 'Guru' }}</div>
                        <div class="list-sub">
                            {{ $izin->kategori_label }} &middot;
                            {{ $izin->tanggal_mulai->format('d/m/Y') }}@if(! $izin->tanggal_mulai->isSameDay($izin->tanggal_selesai)) - {{ $izin->tanggal_selesai->format('d/m/Y') }}@endif
                            ({{ $izin->durasi_hari }} hari)
                        </div>
                    </div>
                    <div style="display:flex; gap:6px; align-items:center;">
                        <a href="{{ route('kepsek.izin.show', $izin->id_izin_guru) }}" class="btn btn-light btn-sm">Detail</a>
                        <button type="button" class="btn btn-success btn-sm"
                                data-open-setujui
                                data-action="{{ route('approver.izin.approve', $izin->id_izin_guru) }}"
                                data-guru="{{ $izin->guru->nama_guru ?? 'Guru' }}"
                                data-periode="{{ $izin->tanggal_mulai->format('d/m/Y') }}{{ $izin->tanggal_mulai->isSameDay($izin->tanggal_selesai) ? '' : ' - ' . $izin->tanggal_selesai->format('d/m/Y') }}">
                            Setujui
                        </button>
                        <button type="button" class="btn btn-danger btn-sm"
                                data-open-tolak
                                data-action="{{ route('approver.izin.reject', $izin->id_izin_guru) }}"
                                data-guru="{{ $izin->guru->nama_guru ?? 'Guru' }}"
                                data-periode="{{ $izin->tanggal_mulai->format('d/m/Y') }}{{ $izin->tanggal_mulai->isSameDay($izin->tanggal_selesai) ? '' : ' - ' . $izin->tanggal_selesai->format('d/m/Y') }}">
                            Tolak
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-note"><i class="fa-solid fa-circle-check"></i>Tidak ada izin yang menunggu keputusan Anda.</div>
            @endforelse
        </div>

        {{-- ===== Guru izin hari ini ===== --}}
        <div class="content-card">
            <div class="card-head">
                <div class="card-title"><i class="fa-solid fa-user-clock" style="color:#2563eb;"></i> Guru Izin Hari Ini</div>
                <span class="badge badge-blue">{{ $izinHariIni->count() }} guru</span>
            </div>
            @forelse($izinHariIni as $izin)
                <div class="list-row">
                    <div>
                        <div class="list-main">{{ $izin->guru->nama_guru ?? 'Guru' }}</div>
                        <div class="list-sub">{{ $izin->kategori_label }} &middot; sampai {{ $izin->tanggal_selesai->format('d/m/Y') }}</div>
                    </div>
                    <a href="{{ route('kepsek.izin.show', $izin->id_izin_guru) }}" class="card-link">Detail</a>
                </div>
            @empty
                <div class="empty-note"><i class="fa-solid fa-user-check"></i>Tidak ada guru yang izin hari ini.</div>
            @endforelse
        </div>
    </div>

    {{-- ===== Kehadiran / jurnal hari ini ===== --}}
    <div class="content-card">
        <div class="card-head">
            <div class="card-title"><i class="fa-solid fa-chalkboard-user" style="color:#7c3aed;"></i> Kehadiran Guru &amp; Jurnal Mengajar Hari Ini</div>
            @if($hariIni)
                <span class="badge badge-gray">{{ $hariIni }}</span>
            @endif
        </div>
        <div class="card-body">
            @if(! $hariIni)
                <div class="empty-note" style="padding: 12px;"><i class="fa-solid fa-mug-hot"></i>Hari ini hari Minggu, tidak ada jadwal mengajar.</div>
            @elseif($kehadiran['sesi_total'] === 0)
                <div class="empty-note" style="padding: 12px;"><i class="fa-solid fa-calendar-xmark"></i>Belum ada jadwal pelajaran untuk hari {{ $hariIni }}.</div>
            @else
                <div style="display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:14px;">
                    <div>
                        <div class="kpi-big">{{ $kehadiran['persen_terisi'] }}%</div>
                        <div class="list-sub">jurnal terisi ({{ $kehadiran['sesi_terisi'] }} dari {{ $kehadiran['sesi_total'] }} sesi)</div>
                    </div>
                    <div class="list-sub" style="text-align:right;">
                        {{ $kehadiran['guru_terjadwal'] }} guru terjadwal hari ini<br>
                        {{ $kehadiran['guru_izin'] }} di antaranya sedang izin
                    </div>
                </div>

                @php
                    $t = max($kehadiran['sesi_total'], 1);
                    $w = fn ($n) => round(($n / $t) * 100, 2);
                @endphp
                <div class="meter" aria-label="Komposisi sesi mengajar hari ini">
                    <span style="width: {{ $w($kehadiran['sesi_terisi']) }}%; background:#10b981;"></span>
                    <span style="width: {{ $w($kehadiran['sesi_izin']) }}%; background:#3b82f6;"></span>
                    <span style="width: {{ $w($kehadiran['sesi_belum']) }}%; background:#ef4444;"></span>
                    <span style="width: {{ $w($kehadiran['sesi_akan_datang']) }}%; background:#cbd5e1;"></span>
                </div>
                <div class="meter-legend">
                    <span><i class="fa-solid fa-circle" style="color:#10b981;"></i>Jurnal terisi ({{ $kehadiran['sesi_terisi'] }})</span>
                    <span><i class="fa-solid fa-circle" style="color:#3b82f6;"></i>Guru izin ({{ $kehadiran['sesi_izin'] }})</span>
                    <span><i class="fa-solid fa-circle" style="color:#ef4444;"></i>Belum diisi ({{ $kehadiran['sesi_belum'] }})</span>
                    <span><i class="fa-solid fa-circle" style="color:#cbd5e1;"></i>Belum berlangsung ({{ $kehadiran['sesi_akan_datang'] }})</span>
                </div>

                <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:16px;">
                    <span class="badge badge-green">Hadir: {{ $statusJurnal['Hadir'] }}</span>
                    <span class="badge badge-blue">Izin: {{ $statusJurnal['Izin'] }}</span>
                    <span class="badge badge-amber">Sakit: {{ $statusJurnal['Sakit'] }}</span>
                    <span class="badge badge-red">Tanpa Keterangan: {{ $statusJurnal['Tanpa Keterangan'] }}</span>
                    <span class="list-sub" style="align-self:center;">&larr; status kehadiran guru dari jurnal yang sudah masuk</span>
                </div>
            @endif
        </div>
    </div>

    <div class="grid-2-eq">
        {{-- ===== Jurnal belum diisi ===== --}}
        <div class="content-card">
            <div class="card-head">
                <div class="card-title"><i class="fa-solid fa-triangle-exclamation" style="color:#dc2626;"></i> Jurnal Belum Diisi</div>
                <span class="badge {{ $jurnalBelumDiisi->count() ? 'badge-red' : 'badge-green' }}">{{ $jurnalBelumDiisi->count() }} sesi</span>
            </div>
            <div style="max-height: 440px; overflow-y: auto;">
            @forelse($jurnalBelumDiisi as $sesi)
                <div class="list-row">
                    <div>
                        <div class="list-main">{{ $sesi->guru->nama_guru ?? 'Guru' }}</div>
                        <div class="list-sub">
                            {{ $sesi->mapel->nama_mapel ?? '-' }}
                            &middot; {{ optional($sesi->kelas)->nama_lengkap ?: '-' }}
                        </div>
                    </div>
                    <span class="badge badge-gray">
                        {{ $sesi->jamPelajaran ? \Carbon\Carbon::parse($sesi->jamPelajaran->jam_mulai)->format('H:i') . '-' . \Carbon\Carbon::parse($sesi->jamPelajaran->jam_selesai)->format('H:i') : 'Jam ke-' . $sesi->jam_ke }}
                    </span>
                </div>
            @empty
                <div class="empty-note"><i class="fa-solid fa-circle-check"></i>Semua sesi yang sudah lewat sudah terisi jurnalnya.</div>
            @endforelse
            </div>
        </div>

        {{-- ===== Tren izin ===== --}}
        <div class="content-card">
            <div class="card-head">
                <div class="card-title"><i class="fa-solid fa-chart-column" style="color:#2563eb;"></i> Tren Izin Guru (6 Bulan)</div>
                <div class="list-sub">
                    <i class="fa-solid fa-square" style="color:#93c5fd;"></i> Diajukan
                    &nbsp;<i class="fa-solid fa-square" style="color:#10b981;"></i> Disetujui
                </div>
            </div>
            <div class="card-body">
                @php $maks = max(collect($tren)->max('total'), 1); @endphp
                <div class="chart">
                    @foreach($tren as $bulan)
                        <div class="chart-col">
                            <div class="chart-bars">
                                <div class="chart-bar total" style="height: {{ round(($bulan['total'] / $maks) * 100) }}%;" title="Diajukan: {{ $bulan['total'] }}"></div>
                                <div class="chart-bar ok" style="height: {{ round(($bulan['disetujui'] / $maks) * 100) }}%;" title="Disetujui: {{ $bulan['disetujui'] }}"></div>
                            </div>
                            <div class="chart-val">{{ $bulan['total'] }}/{{ $bulan['disetujui'] }}</div>
                            <div class="chart-label">{{ $bulan['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection