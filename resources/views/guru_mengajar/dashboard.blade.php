@extends('layouts.guru_mengajar')

@section('title', 'Beranda Guru')
@section('page-title', 'Beranda Guru')
@section('page-subtitle', $now->translatedFormat('l, d F Y'))

@section('content')

    <!-- Welcome Banner -->
    <header class="gm-header-card">
        <div>
            <h2>Selamat Datang, {{ $guru->nama_guru ?? auth()->user()->name }} </h2>
            <p>Kelola KBM, isi jurnal mengajar, dan rekap absensi siswa secara real-time.</p>
        </div>
    </header>

    <!-- Stats Overview -->
    <section class="gm-stats-grid">
        <div class="gm-stat-card">
            <div class="gm-stat-icon navy"><i class="fa-solid fa-calendar-check"></i></div>
            <div>
                <div class="gm-stat-value">{{ $stats['total_jadwal_hari_ini'] }}</div>
                <div class="gm-stat-label">Jadwal Mengajar Hari Ini</div>
            </div>
        </div>

        <div class="gm-stat-card">
            <div class="gm-stat-icon tan"><i class="fa-solid fa-file-signature"></i></div>
            <div>
                <div class="gm-stat-value">{{ $stats['jurnal_terisi_hari_ini'] }} / {{ $stats['total_jadwal_hari_ini'] }}</div>
                <div class="gm-stat-label">Jurnal Terisi Hari Ini</div>
            </div>
        </div>

        <div class="gm-stat-card">
            <div class="gm-stat-icon emerald"><i class="fa-solid fa-book-open-reader"></i></div>
            <div>
                <div class="gm-stat-value">{{ $stats['jurnal_bulan_ini'] }}</div>
                <div class="gm-stat-label">Jurnal Terisi Bulan Ini</div>
            </div>
        </div>

        <div class="gm-stat-card">
            <div class="gm-stat-icon blue"><i class="fa-solid fa-calendar-week"></i></div>
            <div>
                <div class="gm-stat-value">{{ $stats['total_jam_minggu'] }}</div>
                <div class="gm-stat-label">Total Jam Mengajar / Minggu</div>
            </div>
        </div>
    </section>

    <!-- Main Grid: Jadwal Hari Ini + Side Panel -->
    <section class="gm-content-grid">

        <!-- Jadwal Hari Ini -->
        <div class="gm-card">
            <div class="gm-card-header">
                <h3 class="gm-card-title">
                    <i class="fa-solid fa-clock-rotate-left" style="color: var(--dash-navy);"></i>
                    Jadwal Mengajar Hari Ini ({{ $hariIniName }})
                </h3>
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--dash-text-muted);">
                    Progres: <span style="color: var(--dash-navy); font-weight: 800;">{{ $stats['persentase_hari_ini'] }}% Selesai</span>
                </div>
            </div>
            <div class="gm-card-body">
                @if($jadwalHariIni->isEmpty())
                    <div class="gm-empty-state">
                        <div class="gm-empty-icon"><i class="fa-regular fa-calendar-xmark"></i></div>
                        <div class="gm-empty-title">Tidak Ada Jadwal Mengajar Hari Ini</div>
                        <p>Anda tidak memiliki agenda jam mengajar untuk hari {{ $hariIniName }}. Cek jadwal mingguan Anda.</p>
                    </div>
                @else
                    <div class="gm-schedule-grid">
                            @foreach($jadwalHariIni as $jadwal)
                            @php
                                $kelasName = $jadwal->kelas ? ($jadwal->kelas->tingkat . ' ' . optional($jadwal->kelas->jurusan)->kode_jurusan . ' ' . $jadwal->kelas->rombel) : 'Kelas -';
                                $mapelName = optional($jadwal->mapel)->nama_mapel ?? 'Mata Pelajaran';
                                $jamStr = optional($jadwal->jamPelajaran)->keterangan ?? ('Jam Ke-' . $jadwal->jam_ke);
                                $waktuStr = optional($jadwal->jamPelajaran)->jam_mulai ? (\Carbon\Carbon::parse($jadwal->jamPelajaran->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($jadwal->jamPelajaran->jam_selesai)->format('H:i')) : '-';
                                $todayStr = $now->toDateString();
                            @endphp
                            <div class="gm-schedule-card {{ $jadwal->is_filled ? 'filled' : '' }}">
                                <div class="gm-schedule-top">
                                    <div class="gm-time-badge"><i class="fa-regular fa-clock"></i> {{ $waktuStr }} ({{ $jamStr }})</div>
                                    @if($jadwal->is_filled)
                                        <div class="gm-status-badge filled"><i class="fa-solid fa-circle-check"></i> Terisi</div>
                                    @else
                                        <div class="gm-status-badge pending"><i class="fa-solid fa-clock"></i> Belum Diisi</div>
                                    @endif
                                </div>
                                <h4 class="gm-class-title">{{ $kelasName }}</h4>
                                <div class="gm-mapel-name"><i class="fa-solid fa-book"></i> {{ $mapelName }}</div>
                                <div class="gm-schedule-meta">
                                    <div class="gm-meta-item"><i class="fa-solid fa-door-open"></i> {{ $jadwal->ruangan ?? 'Ruang Kelas' }}</div>
                                    <div class="gm-meta-item"><i class="fa-solid fa-users"></i> {{ $jadwal->kelas ? ($jadwal->kelas->siswa_count ?? $jadwal->kelas->jumlah_siswa_real) : 0 }} Siswa</div>
                                </div>
                                <a href="{{ route('guru-mengajar.jurnal.input', ['id_jadwal' => $jadwal->id_jadwal, 'tanggal' => $todayStr]) }}"
                                    class="gm-btn {{ $jadwal->is_filled ? 'gm-btn-outline' : 'gm-btn-navy' }}" style="width: 100%; text-decoration: none;">
                                    <i class="fa-solid {{ $jadwal->is_filled ? 'fa-pen-to-square' : 'fa-plus' }}"></i>
                                    {{ $jadwal->is_filled ? 'Edit Jurnal & Absensi' : 'Isi Jurnal & Absensi' }}
                                </a>
                            </div>
                            @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Side Panel -->
        <div style="display: flex; flex-direction: column; gap: 20px;">

            <!-- Jadwal Jam Berikutnya -->
            <div class="gm-card">
                <div class="gm-card-header">
                    <h3 class="gm-card-title"><i class="fa-regular fa-calendar-check" style="color: var(--dash-navy);"></i> Jadwal Jam Berikutnya</h3>
                </div>
                <div class="gm-card-body">
                    @if($jadwalBerikutnya)
                        @php
                            $kelasNextName = $jadwalBerikutnya->kelas ? ($jadwalBerikutnya->kelas->tingkat . ' ' . optional($jadwalBerikutnya->kelas->jurusan)->kode_jurusan . ' ' . $jadwalBerikutnya->kelas->rombel) : 'Kelas -';
                            $mapelNextName = optional($jadwalBerikutnya->mapel)->nama_mapel ?? '-';
                        @endphp
                        <div class="gm-next-card">
                            <div>
                                <div class="gm-next-kelas">{{ $kelasNextName }}</div>
                                <div class="gm-next-meta">{{ $mapelNextName }} · {{ $jadwalBerikutnya->ruangan ?? 'Ruang Kelas' }}</div>
                            </div>
                            <div class="gm-next-time">{{ \Carbon\Carbon::parse($jadwalBerikutnya->jamPelajaran->jam_mulai)->format('H:i') }}</div>
                        </div>
                    @else
                        <div class="gm-next-empty">
                            <i class="fa-regular fa-clock" style="font-size: 1.4rem; margin-bottom: 8px; display: block;"></i>
                            Tidak ada jadwal mengajar berikutnya hari ini.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>

@endsection