@extends('layouts.satpam')

@section('title', 'Dashboard Pemantauan Satpam')
@section('page-title', 'Dashboard Pemantauan Satpam')
@section('page-subtitle', 'Memantau status surat dispensasi dan siswa yang diizinkan keluar oleh Wakasek')

@section('content')

    {{-- Ringkasan status surat, bukan wewenang persetujuan Satpam --}}
    <section class="sp-stats-grid">
        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['total_dispen'] }}</div>
            <div class="sp-stat-label">Surat Berlaku Hari Ini</div>
        </div>

        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['disetujui'] }}</div>
            <div class="sp-stat-label success" style="color: #16a34a;">
                Disetujui Wakasek
            </div>
        </div>

        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['pending'] }}</div>
            <div class="sp-stat-label warn">Menunggu Keputusan</div>
        </div>

        <div class="sp-stat-card">
            <div class="sp-stat-value">{{ $stats['ditolak'] }}</div>
            <div class="sp-stat-label danger">Ditolak Wakasek</div>
        </div>
    </section>

    <section class="sp-content-grid">

        {{-- Riwayat pemantauan --}}
        <div class="sp-card">
            <div class="sp-card-header">
                <h3 class="sp-card-title">
                    <i class="fa-solid fa-timeline"
                       style="color: var(--dash-navy);"></i>
                    Pemantauan Surat Hari Ini
                </h3>
            </div>

            <div class="sp-card-body">
                @forelse($aktivitasGerbang as $item)
                    <div class="sp-activity-item">
                        <div>
                            <div class="sp-activity-name">
                                {{ $item['nama_siswa'] }}
                            </div>

                            <div class="sp-activity-meta">
                                Kelas {{ $item['kelas'] }}
                                &middot;
                                {{ $item['keterangan'] }}
                            </div>

                            <div class="sp-activity-meta">
                                Status surat: {{ ucfirst($item['status'] ?? 'belum diketahui') }}
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                            <div class="sp-activity-time">
                                {{ $item['waktu'] }}
                            </div>

                            @if($item['status'] === 'disetujui')
                                <span style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 12px; font-weight: 800; font-size: 0.68rem;">
                                    Disetujui Wakasek
                                </span>
                            @elseif($item['status'] === 'pending')
                                <span style="background: #fefce8; color: #ca8a04; border: 1px solid #fef08a; padding: 2px 8px; border-radius: 12px; font-weight: 800; font-size: 0.68rem;">
                                    Menunggu Keputusan
                                </span>
                            @else
                                <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 2px 8px; border-radius: 12px; font-weight: 800; font-size: 0.68rem;">
                                    Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="sp-empty-state">
                        <div class="sp-empty-icon">
                            <i class="fa-regular fa-clock"></i>
                        </div>

                        <div class="sp-empty-title">
                            Belum Ada Surat Dispensasi Hari Ini
                        </div>

                        <p>
                            Informasi surat dispensasi yang berlaku hari ini
                            akan muncul di sini.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 20px;">

            {{-- Akses hanya ke halaman pemeriksaan --}}
            <div class="sp-card">
                <div class="sp-card-header">
                    <h3 class="sp-card-title">
                        <i class="fa-solid fa-shield-halved"
                           style="color: var(--dash-navy);"></i>
                        Pemeriksaan Gerbang
                    </h3>
                </div>

                <div class="sp-card-body sp-quick-actions">
                    <a href="{{ route('satpam.cek-izin') }}"
                       class="sp-quick-btn navy">
                        <i class="fa-solid fa-user-check"></i>

                        <div>
                            Periksa Surat Dispensasi
                            <span class="sp-quick-btn-sub">
                                Lihat nama siswa dan status persetujuan Wakasek
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Daftar surat yang sudah disetujui --}}
            <div class="sp-card">
                <div class="sp-card-header">
                    <h3 class="sp-card-title">
                        <i class="fa-solid fa-user-check"
                           style="color: var(--dash-navy);"></i>
                        Siswa dengan Izin Disetujui
                    </h3>
                </div>

                <div class="sp-card-body">
                    @forelse($siswaIzinKeluarHariIni as $dispen)
                        @php
                            $daftarSiswa = $dispen->siswaList
                                ->map(fn ($detail) => $detail->siswa)
                                ->filter()
                                ->unique('id_siswa')
                                ->values();

                            if ($daftarSiswa->isEmpty() && $dispen->siswa) {
                                $daftarSiswa = collect([$dispen->siswa]);
                            }

                            $jMulai = $dispen->jam_mulai
                                ? \Carbon\Carbon::parse($dispen->jam_mulai)->format('H:i')
                                : '-';

                            $jSelesai = $dispen->jam_selesai
                                ? \Carbon\Carbon::parse($dispen->jam_selesai)->format('H:i')
                                : '-';
                        @endphp

                        <div style="padding: 10px 0; border-bottom: 1px dashed #e2e8f0;">
                            @forelse($daftarSiswa as $s)
                                @php
                                    $kelas = $s->kelas
                                        ? trim(
                                            ($s->kelas->tingkat ?? '') . ' ' .
                                            (optional($s->kelas->jurusan)->kode_jurusan ?? '') . ' ' .
                                            ($s->kelas->rombel ?? '')
                                        )
                                        : '-';
                                @endphp

                                <div style="margin-bottom: 10px;">
                                    <div class="sp-activity-name"
                                         style="font-size: 0.875rem; font-weight: 800;">
                                        {{ $s->nama_siswa }}
                                    </div>

                                    <div class="sp-activity-meta"
                                         style="font-size: 0.775rem;">
                                        Kelas {{ $kelas }}
                                    </div>
                                </div>
                            @empty
                                <div class="sp-activity-name">
                                    Data siswa belum ditemukan
                                </div>
                            @endforelse

                            <div class="sp-activity-meta">
                                {{ $dispen->nama_kegiatan ?? 'Dispensasi keluar' }}
                            </div>

                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 4px;">
                                <i class="fa-regular fa-clock"></i>
                                Jam izin:
                                <strong>{{ $jMulai }}</strong>
                                s/d
                                <strong>{{ $jSelesai }}</strong>
                            </div>

                            <div style="font-size: 0.75rem; color: #16a34a; font-weight: 800; margin-top: 6px;">
                                <i class="fa-solid fa-circle-check"></i>
                                Status: Disetujui Wakasek
                            </div>
                        </div>
                    @empty
                        <div class="sp-empty-state" style="padding: 24px 12px;">
                            <p style="font-size: 0.825rem;">
                                Belum ada siswa dengan surat dispensasi
                                yang disetujui dan berlaku hari ini.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

@endsection
