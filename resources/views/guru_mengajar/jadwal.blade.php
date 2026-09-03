@extends('layouts.guru_mengajar')

@section('title', 'Jadwal Mengajar')
@section('page-title', 'Jadwal Mengajar')
@section('page-subtitle', 'Matriks jadwal mengajar mingguan Anda')

@section('content')

    <section class="gm-stats-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="gm-stat-card">
            <div class="gm-stat-icon navy"><i class="fa-solid fa-calendar-week"></i></div>
            <div>
                <div class="gm-stat-value">{{ $totalJamMengajar }}</div>
                <div class="gm-stat-label">Total Jam Mengajar / Minggu</div>
            </div>
        </div>
        <div class="gm-stat-card">
            <div class="gm-stat-icon blue"><i class="fa-solid fa-chalkboard"></i></div>
            <div>
                <div class="gm-stat-value">{{ $totalKelasDiampu }}</div>
                <div class="gm-stat-label">Total Kelas Diampu</div>
            </div>
        </div>
        <div class="gm-stat-card">
            <div class="gm-stat-icon emerald"><i class="fa-solid fa-book"></i></div>
            <div>
                <div class="gm-stat-value">{{ $totalMapelDiampu }}</div>
                <div class="gm-stat-label">Mata Pelajaran Diampu</div>
            </div>
        </div>
    </section>

    <div class="gm-card">
        <div class="gm-card-header">
            <h3 class="gm-card-title"><i class="fa-solid fa-calendar-days" style="color: var(--dash-navy);"></i> Matriks Jadwal Mengajar Mingguan</h3>
        </div>
        <div class="gm-card-body">
            <div class="gm-week-grid">
                @foreach($daysList as $dayName)
                    @php $daySchedules = $weeklySchedules->get($dayName, collect()); @endphp
                    <div class="gm-day-column">
                        <div class="gm-day-column-header">
                            <h4>{{ $dayName }}</h4>
                            <span class="gm-day-count-pill">{{ $daySchedules->count() }} Jam</span>
                        </div>

                        @forelse($daySchedules as $sch)
                            @php
                                $kName = $sch->kelas ? ($sch->kelas->tingkat . ' ' . optional($sch->kelas->jurusan)->kode_jurusan . ' ' . $sch->kelas->rombel) : '-';
                                $mName = optional($sch->mapel)->nama_mapel ?? '-';
                                $waktu = optional($sch->jamPelajaran)->jam_mulai ? (\Carbon\Carbon::parse($sch->jamPelajaran->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($sch->jamPelajaran->jam_selesai)->format('H:i')) : ('Jam ' . $sch->jam_ke);
                            @endphp
                            <div class="gm-day-item">
                                <div class="gm-day-item-top">
                                    <span><i class="fa-regular fa-clock"></i> {{ $waktu }}</span>
                                    <span>{{ $sch->ruangan ?? '-' }}</span>
                                </div>
                                <div class="gm-day-item-kelas">{{ $kName }}</div>
                                <div class="gm-day-item-mapel">{{ $mName }}</div>
                            </div>
                        @empty
                            <div class="gm-day-empty">Tidak ada jadwal mengajar</div>
                        @endforelse
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection