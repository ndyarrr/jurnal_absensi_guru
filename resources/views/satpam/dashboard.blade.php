@extends('layouts.satpam')

@section('title', 'Dashboard Satpam')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Pantauan siswa keluar gerbang hari ini')

@section('content')

    <div class="sp-header-row">
        <div></div>
        <a href="{{ route('satpam.lapor-siswa') }}" class="sp-btn sp-btn-danger">
            <i class="fa-solid fa-bullhorn"></i> Buat Laporan Cepat
        </a>
    </div>

    <section class="sp-stats-grid">
        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['siswa_sudah_masuk'] }}</div>
            <div class="sp-stat-label">Siswa Sudah Masuk</div>
        </div>
        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['sedang_izin_keluar'] }}</div>
            <div class="sp-stat-label warn">Sedang Izin Keluar</div>
        </div>
        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['terlambat'] }}</div>
            <div class="sp-stat-label danger">Terlambat</div>
        </div>
        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['laporan_kejadian'] }}</div>
            <div class="sp-stat-label">Laporan Kejadian</div>
        </div>
    </section>

    <section class="sp-content-grid">

        <div class="sp-card">
            <div class="sp-card-header">
                <h3 class="sp-card-title"><i class="fa-solid fa-timeline" style="color: var(--dash-navy);"></i> Aktivitas Gerbang Terbaru</h3>
            </div>
            <div class="sp-card-body">
                @forelse($aktivitasGerbang as $item)
                    <div class="sp-activity-item">
                        <div>
                            <div class="sp-activity-name">{{ $item['nama_siswa'] }} - {{ $item['kelas'] }}</div>
                            <div class="sp-activity-meta">{{ $item['aktivitas'] }} &middot; {{ $item['keterangan'] }}</div>
                        </div>
                        <div class="sp-activity-time">{{ $item['waktu'] ?? '-' }}</div>
                    </div>
                @empty
                    <div class="sp-empty-state">
                        <div class="sp-empty-icon"><i class="fa-regular fa-clock"></i></div>
                        <div class="sp-empty-title">Belum Ada Aktivitas Hari Ini</div>
                        <p>Aktivitas siswa keluar/masuk gerbang akan muncul di sini.</p>
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
                            Cek Izin
                            <span class="sp-quick-btn-sub">Verifikasi status izin siswa</span>
                        </div>
                    </a>
                    <a href="{{ route('satpam.lapor-siswa') }}" class="sp-quick-btn tan">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <div>
                            Buat Laporan
                            <span class="sp-quick-btn-sub">Kirim laporan kejadian</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="sp-card">
                <div class="sp-card-header">
                    <h3 class="sp-card-title"><i class="fa-solid fa-hourglass-half" style="color: var(--dash-navy);"></i> Izin Aktif Butuh Verifikasi</h3>
                </div>
                <div class="sp-card-body">
                    @forelse($izinButuhVerifikasi as $izin)
                        <div class="sp-verify-item">
                            <div>
                                <div class="sp-activity-name" style="font-size: 0.85rem;">{{ optional($izin->siswa)->nama_siswa }}</div>
                                <div class="sp-activity-meta">Kelas {{ optional($izin->siswa)->kelas ? optional($izin->siswa)->kelas->tingkat . ' ' . optional($izin->siswa)->kelas->rombel : '-' }}</div>
                            </div>
                            <span class="sp-pending-badge">Menunggu konfirmasi</span>
                        </div>
                    @empty
                        <div class="sp-empty-state" style="padding: 24px 12px;">
                            <p style="font-size: 0.825rem;">Tidak ada izin yang perlu diverifikasi.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

@endsection