@extends('layouts.approver')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard ' . $tahap['label'])
@section('page-subtitle', 'Halo, ' . auth()->user()->name . ' - ringkasan izin guru, izin siswa, dan kehadiran guru')

@section('content')
    {{-- ===== Statistik ===== --}}
    <div class="stats-grid">
        <a href="{{ route('approver.izin-guru.index', ['status' => 'perlu']) }}" class="stat-card" style="text-decoration:none;">
            <div class="stat-icon" style="background: #fff7ed; color: #ea580c;"><i class="fa-solid fa-chalkboard-user"></i></div>
            <div>
                <div class="stat-val">{{ $stat['izin_guru_menunggu'] }}</div>
                <div class="stat-label">Izin Guru Menunggu Anda</div>
            </div>
        </a>
        <a href="{{ route('approver.izin-siswa.index', ['status' => 'perlu']) }}" class="stat-card" style="text-decoration:none;">
            <div class="stat-icon" style="background: #eff6ff; color: #2563eb;"><i class="fa-solid fa-user-graduate"></i></div>
            <div>
                <div class="stat-val">{{ $stat['izin_siswa_menunggu'] }}</div>
                <div class="stat-label">Izin Siswa Menunggu Anda</div>
            </div>
        </a>
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

    <div class="grid-2-eq">
        {{-- ===== Izin guru perlu keputusan ===== --}}
        <div class="content-card">
            <div class="card-head">
                <div class="card-title"><i class="fa-solid fa-bell" style="color:#ea580c;"></i> Izin Guru Perlu Keputusan</div>
                <a class="card-link" href="{{ route('approver.izin-guru.index', ['status' => 'perlu']) }}">Lihat semua</a>
            </div>
            @forelse($izinGuruPerlu as $izin)
                @php
                    $periode = $izin->tanggal_mulai->format('d/m/Y') . ($izin->tanggal_mulai->isSameDay($izin->tanggal_selesai) ? '' : ' - ' . $izin->tanggal_selesai->format('d/m/Y'));
                @endphp
                <div class="list-row">
                    <div>
                        <div class="list-main">{{ $izin->guru->nama_guru ?? 'Guru' }}</div>
                        <div class="list-sub">{{ $izin->kategori_label }} &middot; {{ $periode }} ({{ $izin->durasi_hari }} hari)</div>
                    </div>
                    <div style="display:flex; gap:6px; align-items:center;">
                        <a href="{{ route('approver.izin-guru.show', $izin->id_izin_guru) }}" class="btn btn-light btn-sm">Detail</a>
                        <button type="button" class="btn btn-success btn-sm" data-open-setujui
                                data-jenis="izin guru" data-jenis-title="Izin Guru"
                                data-action="{{ route('approver.izin.approve', $izin->id_izin_guru) }}"
                                data-guru="{{ $izin->guru->nama_guru ?? 'Guru' }}" data-periode="{{ $periode }}">Setujui</button>
                        <button type="button" class="btn btn-danger btn-sm" data-open-tolak
                                data-jenis="izin guru" data-jenis-title="Izin Guru"
                                data-action="{{ route('approver.izin.reject', $izin->id_izin_guru) }}"
                                data-guru="{{ $izin->guru->nama_guru ?? 'Guru' }}" data-periode="{{ $periode }}">Tolak</button>
                    </div>
                </div>
            @empty
                <div class="empty-note"><i class="fa-solid fa-circle-check"></i>Tidak ada izin guru yang menunggu keputusan Anda.</div>
            @endforelse
        </div>

        {{-- ===== Izin siswa perlu keputusan ===== --}}
        <div class="content-card">
            <div class="card-head">
                <div class="card-title"><i class="fa-solid fa-bell" style="color:#2563eb;"></i> Izin Siswa Perlu Keputusan</div>
                <a class="card-link" href="{{ route('approver.izin-siswa.index', ['status' => 'perlu']) }}">Lihat semua</a>
            </div>
            @forelse($izinSiswaPerlu as $dispen)
                <div class="list-row">
                    <div>
                        <div class="list-main">{{ $dispen->ringkas_siswa }}</div>
                        <div class="list-sub">{{ $dispen->nama_kegiatan ?? '-' }} &middot; {{ $dispen->periode_label }}</div>
                    </div>
                    <div style="display:flex; gap:6px; align-items:center;">
                        <a href="{{ route('approver.izin-siswa.show', $dispen->id_dispen) }}" class="btn btn-light btn-sm">Detail</a>
                        <button type="button" class="btn btn-success btn-sm" data-open-setujui
                                data-jenis="dispensasi siswa" data-jenis-title="Izin Siswa"
                                data-action="{{ route('approver.dispensasi.approve', $dispen->id_dispen) }}"
                                data-guru="{{ $dispen->ringkas_siswa }}" data-periode="{{ $dispen->periode_label }}">Setujui</button>
                        <button type="button" class="btn btn-danger btn-sm" data-open-tolak
                                data-jenis="dispensasi siswa" data-jenis-title="Izin Siswa"
                                data-action="{{ route('approver.dispensasi.reject', $dispen->id_dispen) }}"
                                data-guru="{{ $dispen->ringkas_siswa }}" data-periode="{{ $dispen->periode_label }}">Tolak</button>
                    </div>
                </div>
            @empty
                <div class="empty-note"><i class="fa-solid fa-circle-check"></i>Tidak ada izin siswa yang menunggu keputusan Anda.</div>
            @endforelse
        </div>
    </div>

    <div class="grid-2-eq">
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
                    <a href="{{ route('approver.izin-guru.show', $izin->id_izin_guru) }}" class="card-link">Detail</a>
                </div>
            @empty
                <div class="empty-note"><i class="fa-solid fa-user-check"></i>Tidak ada guru yang izin hari ini.</div>
            @endforelse
        </div>

        {{-- ===== Siswa dispensasi hari ini ===== --}}
        <div class="content-card">
            <div class="card-head">
                <div class="card-title"><i class="fa-solid fa-person-walking-arrow-right" style="color:#7c3aed;"></i> Siswa Izin / Dispensasi Hari Ini</div>
                <span class="badge badge-blue">{{ $dispensasiHariIni->count() }} surat</span>
            </div>
            <div style="max-height: 360px; overflow-y: auto;">
            @forelse($dispensasiHariIni as $dispen)
                <div class="list-row">
                    <div>
                        <div class="list-main">{{ $dispen->ringkas_siswa }}</div>
                        <div class="list-sub">{{ $dispen->nama_kegiatan ?? '-' }} &middot; {{ $dispen->jam_label }}</div>
                    </div>
                    <a href="{{ route('approver.izin-siswa.show', $dispen->id_dispen) }}" class="card-link">Detail</a>
                </div>
            @empty
                <div class="empty-note"><i class="fa-solid fa-user-graduate"></i>Tidak ada siswa yang izin / dispensasi hari ini.</div>
            @endforelse
            </div>
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
                    $tot = max($kehadiran['sesi_total'], 1);
                    $w = fn ($n) => round(($n / $tot) * 100, 2);
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
                        <div class="list-sub">{{ $sesi->mapel->nama_mapel ?? '-' }} &middot; {{ optional($sesi->kelas)->nama_lengkap ?: '-' }}</div>
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

        {{-- ===== Tren pengajuan ===== --}}
        <div class="content-card">
            <div class="card-head">
                <div class="card-title"><i class="fa-solid fa-chart-column" style="color:#2563eb;"></i> Tren Pengajuan (6 Bulan)</div>
                <div class="list-sub">
                    <i class="fa-solid fa-square" style="color:#93c5fd;"></i> Izin guru
                    &nbsp;<i class="fa-solid fa-square" style="color:#10b981;"></i> Izin siswa
                </div>
            </div>
            <div class="card-body">
                @php $maks = max(collect($tren)->max('guru'), collect($tren)->max('siswa'), 1); @endphp
                <div class="chart">
                    @foreach($tren as $bulan)
                        <div class="chart-col">
                            <div class="chart-bars">
                                <div class="chart-bar total" style="height: {{ round(($bulan['guru'] / $maks) * 100) }}%;" title="Izin guru: {{ $bulan['guru'] }}"></div>
                                <div class="chart-bar ok" style="height: {{ round(($bulan['siswa'] / $maks) * 100) }}%;" title="Izin siswa: {{ $bulan['siswa'] }}"></div>
                            </div>
                            <div class="chart-val">{{ $bulan['guru'] }}/{{ $bulan['siswa'] }}</div>
                            <div class="chart-label">{{ $bulan['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection