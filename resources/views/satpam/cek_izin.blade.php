@extends('layouts.satpam')

@section('title', 'Cek Dispensasi Siswa')
@section('page-title', 'Cek Dispensasi Siswa')
@section('page-subtitle', 'Monitor status surat dispensasi keluar siswa hari ini')

@section('content')

    <div class="sp-card">
        <div class="sp-card-header">
            <h3 class="sp-card-title"><i class="fa-solid fa-user-check" style="color: var(--dash-navy);"></i> Daftar Surat Dispensasi Siswa</h3>
        </div>

        <div class="sp-card-body">
            <form method="GET" action="{{ route('satpam.cek-izin') }}" class="sp-filter-bar" style="margin-bottom: 18px;">
                <input type="text" name="search" class="sp-input" placeholder="Cari nama siswa, kelas, atau kegiatan..." value="{{ request('search') }}" style="min-width: 260px; flex: 1;">

                <a href="{{ route('satpam.cek-izin', array_merge(request()->except('status'), ['status' => 'semua'])) }}" class="sp-filter-pill {{ !request('status') || request('status') === 'semua' ? 'active' : '' }}">Semua</a>
                <a href="{{ route('satpam.cek-izin', array_merge(request()->except('status'), ['status' => 'disetujui'])) }}" class="sp-filter-pill {{ request('status') === 'disetujui' ? 'active' : '' }}">Disetujui</a>
                <a href="{{ route('satpam.cek-izin', array_merge(request()->except('status'), ['status' => 'pending'])) }}" class="sp-filter-pill {{ request('status') === 'pending' ? 'active' : '' }}">Menunggu</a>
                <a href="{{ route('satpam.cek-izin', array_merge(request()->except('status'), ['status' => 'ditolak'])) }}" class="sp-filter-pill {{ request('status') === 'ditolak' ? 'active' : '' }}">Ditolak</a>

                <button type="submit" class="sp-btn sp-btn-navy"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
            </form>

            <div class="sp-table-responsive">
                <table class="sp-table">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Kegiatan & Alasan</th>
                            <th>Jam Keluar</th>
                            <th>Est. Kembali</th>
                            <th style="text-align: center;">Status Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftarIzin as $dispen)
                            @php
                                $siswa = $dispen->siswa;
                                $initial = $siswa ? strtoupper(substr($siswa->nama_siswa, 0, 1)) : '-';
                                $kelasStr = optional($siswa)->kelas ? ($siswa->kelas->tingkat . ' ' . optional($siswa->kelas->jurusan)->kode_jurusan . ' ' . $siswa->kelas->rombel) : '-';
                                $jMulai = $dispen->jam_mulai ? \Carbon\Carbon::parse($dispen->jam_mulai)->format('H:i') : '-';
                                $jSelesai = $dispen->jam_selesai ? \Carbon\Carbon::parse($dispen->jam_selesai)->format('H:i') : '-';
                            @endphp
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                                        <span class="sp-avatar-initial" style="flex-shrink: 0; margin-right: 0;">{{ $initial }}</span>
                                        <div style="min-width: 0;">
                                            <strong style="display: block; line-height: 1.3;">{{ optional($siswa)->nama_siswa ?? '-' }}</strong>
                                            <div style="font-size: 0.75rem; color: var(--dash-text-muted);">{{ $kelasStr }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #1e293b;">{{ $dispen->nama_kegiatan ?? 'Dispensasi' }}</div>
                                    <div style="max-width: 260px; font-size: 0.8rem; color: #64748b; white-space: normal; line-height: 1.4;">{{ $dispen->alasan_dispensasi ?? '-' }}</div>
                                </td>
                                <td><span style="font-weight: 700; color: #1e293b;">{{ $jMulai }}</span></td>
                                <td><span style="font-weight: 700; color: #1e293b;">{{ $jSelesai }}</span></td>
                                <td style="text-align: center;">
                                    @if($dispen->status_approval === 'disetujui')
                                        <span style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; padding: 4px 12px; border-radius: 20px; font-weight: 800; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fa-solid fa-circle-check"></i> Disetujui (Boleh Keluar)
                                        </span>
                                    @elseif($dispen->status_approval === 'pending')
                                        <span style="background: #fefce8; color: #ca8a04; border: 1px solid #fef08a; padding: 4px 12px; border-radius: 20px; font-weight: 800; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fa-solid fa-clock"></i> Menunggu Approval
                                        </span>
                                    @else
                                        <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 4px 12px; border-radius: 20px; font-weight: 800; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fa-solid fa-circle-xmark"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="sp-empty-state">
                                        <div class="sp-empty-icon"><i class="fa-regular fa-folder-open"></i></div>
                                        <div class="sp-empty-title">Belum Ada Data Surat Dispensasi Siswa</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($daftarIzin->hasPages())
                <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                    {{ $daftarIzin->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection