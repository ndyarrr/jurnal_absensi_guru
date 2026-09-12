@extends('layouts.satpam')

@section('title', 'Cek Izin Siswa')
@section('page-title', 'Cek Izin Siswa')
@section('page-subtitle', 'Verifikasi status izin keluar siswa hari ini')

@section('content')

    <div class="sp-card">
        <div class="sp-card-header">
            <h3 class="sp-card-title"><i class="fa-solid fa-user-check" style="color: var(--dash-navy);"></i> Daftar Izin Siswa</h3>
        </div>

        <div class="sp-card-body">
            <form method="GET" action="{{ route('satpam.cek-izin') }}" class="sp-filter-bar" style="margin-bottom: 18px;">
                <input type="text" name="search" class="sp-input" placeholder="Cari nama siswa atau kelas..." value="{{ request('search') }}" style="min-width: 260px; flex: 1;">

                <a href="{{ route('satpam.cek-izin', array_merge(request()->except('status'), ['status' => 'semua'])) }}" class="sp-filter-pill {{ !request('status') || request('status') === 'semua' ? 'active' : '' }}">Semua</a>
                <a href="{{ route('satpam.cek-izin', array_merge(request()->except('status'), ['status' => 'disetujui'])) }}" class="sp-filter-pill {{ request('status') === 'disetujui' ? 'active' : '' }}">Disetujui</a>
                <a href="{{ route('satpam.cek-izin', array_merge(request()->except('status'), ['status' => 'menunggu'])) }}" class="sp-filter-pill {{ request('status') === 'menunggu' ? 'active' : '' }}">Menunggu</a>
                <a href="{{ route('satpam.cek-izin', array_merge(request()->except('status'), ['status' => 'kadaluwarsa'])) }}" class="sp-filter-pill {{ request('status') === 'kadaluwarsa' ? 'active' : '' }}">Kadaluwarsa</a>

                <button type="submit" class="sp-btn sp-btn-navy"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <div class="sp-table-responsive">
                <table class="sp-table">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Alasan Izin</th>
                            <th>Jam Keluar</th>
                            <th>Perkiraan Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftarIzin as $izin)
                            @php
                                $siswa = $izin->siswa;
                                $initial = $siswa ? strtoupper(substr($siswa->nama_siswa, 0, 1)) : '-';
                                $kelasStr = optional($siswa)->kelas ? ($siswa->kelas->tingkat . ' ' . $siswa->kelas->rombel) : '-';
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
                            
                                <td><div style="max-width: 220px; white-space: normal; line-height: 1.4;">{{ $izin->alasan }}</div></td>
                                <td>{{ $izin->jam_keluar ? \Carbon\Carbon::parse($izin->jam_keluar)->format('H:i') : '-' }}</td>
                                <td>{{ $izin->perkiraan_kembali ? \Carbon\Carbon::parse($izin->perkiraan_kembali)->format('H:i') : 'Tidak kembali' }}</td>
                                <td>
                                    <span class="sp-status-badge {{ $izin->status_gerbang }}">{{ $izin->status_gerbang_label }}</span>
                                </td>
                                <td>
                                    @if(!$izin->jam_keluar)
                                        <button type="button" class="sp-btn sp-btn-navy" style="padding: 6px 12px; font-size: 0.775rem;" onclick="openCatatKeluarModal({{ $izin->id_permohonan }})">
                                            <i class="fa-solid fa-right-from-bracket"></i> Catat Keluar
                                        </button>
                                    @elseif(!$izin->jam_kembali_aktual && $izin->perkiraan_kembali)
                                        <form action="{{ route('satpam.catat-kembali', $izin->id_permohonan) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="sp-btn sp-btn-outline" style="padding: 6px 12px; font-size: 0.775rem;">
                                                <i class="fa-solid fa-right-to-bracket"></i> Catat Kembali
                                            </button>
                                        </form>
                                    @else
                                        <span style="font-size: 0.775rem; color: var(--dash-text-muted); font-weight: 600;">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="sp-empty-state">
                                        <div class="sp-empty-icon"><i class="fa-regular fa-folder-open"></i></div>
                                        <div class="sp-empty-title">Belum Ada Data Izin Siswa</div>
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

    <!-- MODAL: CATAT JAM KELUAR -->
    <div class="sp-modal-overlay" id="catatKeluarModal">
        <div class="sp-modal">
            <div class="sp-modal-header">
                <h3 class="sp-modal-title"><i class="fa-solid fa-right-from-bracket"></i> Catat Jam Keluar</h3>
                <button type="button" class="sp-modal-close" onclick="closeCatatKeluarModal()">&times;</button>
            </div>
            <form id="catatKeluarForm" method="POST">
                @csrf
                <div class="sp-modal-body">
                    <p style="font-size: 0.85rem; color: var(--dash-text-muted); margin-bottom: 16px;">
                        Jam keluar akan dicatat otomatis sesuai waktu sekarang.
                    </p>
                    <div class="sp-form-group" style="margin-bottom: 0;">
                        <label class="sp-form-label">Perkiraan Jam Kembali (opsional)</label>
                        <input type="time" name="perkiraan_kembali" class="sp-input" style="width: 100%;">
                        <p style="font-size: 0.75rem; color: var(--dash-text-muted); margin-top: 6px;">Kosongkan kalau siswa tidak kembali ke sekolah hari ini (misal dijemput karena sakit).</p>
                    </div>
                </div>
                <div class="sp-modal-footer">
                    <button type="button" class="sp-btn sp-btn-outline" onclick="closeCatatKeluarModal()">Batal</button>
                    <button type="submit" class="sp-btn sp-btn-navy"><i class="fa-solid fa-check"></i> Catat Keluar</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openCatatKeluarModal(idIzin) {
        document.getElementById('catatKeluarForm').action = `/satpam/cek-izin/${idIzin}/catat-keluar`;
        document.getElementById('catatKeluarModal').classList.add('active');
    }
    function closeCatatKeluarModal() {
        document.getElementById('catatKeluarModal').classList.remove('active');
    }
</script>
@endpush