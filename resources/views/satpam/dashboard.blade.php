@extends('layouts.satpam')

@section('title', 'Dashboard Satpam')
@section('page-title', 'Dashboard Satpam')
@section('page-subtitle', 'Monitor siswa yang mendapat dispensasi keluar sekolah hari ini')

@section('content')

    <section class="sp-stats-grid">
        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['total_dispen'] }}</div>
            <div class="sp-stat-label">Total Dispensasi Hari Ini</div>
        </div>
        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['disetujui'] }}</div>
            <div class="sp-stat-label success" style="color: #16a34a;">Disetujui</div>
        </div>
        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['pending'] }}</div>
            <div class="sp-stat-label warn">Menunggu Approval</div>
        </div>
        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['ditolak'] }}</div>
            <div class="sp-stat-label danger">Ditolak</div>
        </div>
    </section>

    <section class="sp-content-grid">

        <div class="sp-card">
            <div class="sp-card-header">
                <h3 class="sp-card-title"><i class="fa-solid fa-timeline" style="color: var(--dash-navy);"></i> Aktivitas Dispensasi Hari Ini</h3>
            </div>
            <div class="sp-card-body">
                @forelse($aktivitasGerbang as $item)
                    <div class="sp-activity-item">
                        <div>
                            <div class="sp-activity-name">{{ $item['nama_siswa'] }} - {{ $item['kelas'] }}</div>
                            <div class="sp-activity-meta">{{ $item['aktivitas'] }} &middot; {{ $item['keterangan'] }}</div>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                            <div class="sp-activity-time">{{ $item['waktu'] }}</div>
                            @if($item['status'] === 'disetujui')
                                <span style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 12px; font-weight: 800; font-size: 0.68rem;">Disetujui</span>
                            @elseif($item['status'] === 'pending')
                                <span style="background: #fefce8; color: #ca8a04; border: 1px solid #fef08a; padding: 2px 8px; border-radius: 12px; font-weight: 800; font-size: 0.68rem;">Menunggu</span>
                            @else
                                <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 2px 8px; border-radius: 12px; font-weight: 800; font-size: 0.68rem;">Ditolak</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="sp-empty-state">
                        <div class="sp-empty-icon"><i class="fa-regular fa-clock"></i></div>
                        <div class="sp-empty-title">Belum Ada Pengajuan Dispensasi Hari Ini</div>
                        <p>Daftar permohonan dispensasi siswa hari ini akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 20px;">

            <div class="sp-card">
                <div class="sp-card-header">
                    <h3 class="sp-card-title"><i class="fa-solid fa-bolt" style="color: var(--dash-navy);"></i> Akses Cepat</h3>
                </div>
                <div class="sp-card-body sp-quick-actions">
                    <a href="{{ route('satpam.cek-izin') }}" class="sp-quick-btn navy">
                        <i class="fa-solid fa-user-check"></i>
                        <div>
                            Cek Dispensasi Siswa
                            <span class="sp-quick-btn-sub">Monitor status dispensasi siswa</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="sp-card">
                <div class="sp-card-header">
                    <h3 class="sp-card-title"><i class="fa-solid fa-user-check" style="color: var(--dash-navy);"></i> Siswa Diizinkan Keluar Hari Ini</h3>
                </div>
                <div class="sp-card-body">
                    @forelse($siswaIzinKeluarHariIni as $dispen)
                        @php
                            $s = $dispen->siswa;
                            $kStr = $s && $s->kelas ? ($s->kelas->tingkat . ' ' . optional($s->kelas->jurusan)->kode_jurusan . ' ' . $s->kelas->rombel) : '-';
                            $jMulai = $dispen->jam_mulai ? \Carbon\Carbon::parse($dispen->jam_mulai)->format('H:i') : '-';
                            $jSelesai = $dispen->jam_selesai ? \Carbon\Carbon::parse($dispen->jam_selesai)->format('H:i') : '-';
                        @endphp
                        <div class="sp-verify-item" style="padding: 10px 0; border-bottom: 1px dashed #e2e8f0;">
                            <div>
                                <div class="sp-activity-name" style="font-size: 0.875rem; font-weight: 800;">{{ optional($s)->nama_siswa ?? '-' }}</div>
                                <div class="sp-activity-meta" style="font-size: 0.775rem;">Kelas {{ $kStr }} &middot; {{ $dispen->nama_kegiatan ?? 'Dispensasi' }}</div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">
                                    <i class="fa-regular fa-clock"></i> Jam: <strong>{{ $jMulai }}</strong> s/d <strong>{{ $jSelesai }}</strong>
                                </div>
                            </div>
                            <div style="font-size: 0.75rem; color: #16a34a; font-weight: 800; flex-shrink: 0; background: #f0fdf4; padding: 4px 10px; border-radius: 8px; border: 1px solid #bbf7d0;">
                                <i class="fa-solid fa-circle-check"></i> Disetujui
                            </div>
                        </div>
                    @empty
                        <div class="sp-empty-state" style="padding: 24px 12px;">
                            <p style="font-size: 0.825rem;">Tidak ada siswa yang diizinkan dispensasi keluar hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

@endsection