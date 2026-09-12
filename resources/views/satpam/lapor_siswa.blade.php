@extends('layouts.satpam')

@section('title', 'Lapor Siswa')
@section('page-title', 'Lapor Siswa')
@section('page-subtitle', 'Laporkan kejadian & kirim laporan ke pihak terkait')

@section('content')

<form action="{{ route('satpam.lapor-siswa.store') }}" method="POST">
    @csrf

    <div class="sp-content-grid">

        <div class="sp-card">
            <div class="sp-card-header">
                <h3 class="sp-card-title"><i class="fa-solid fa-file-lines" style="color: var(--dash-navy);"></i> Detail Laporan</h3>
            </div>
            <div class="sp-card-body">

                <div class="sp-form-grid">
                    <div class="sp-form-group">
                        <label class="sp-form-label">Nama Siswa <span style="color: #dc2626;">*</span></label>
                        <select name="id_siswa" id="pilihSiswa" class="sp-select" style="width: 100%;" required onchange="isiKelasOtomatis()">
                           
                        <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaList as $s)
                                @php
                                    $kelasLabel = trim((optional($s->kelas)->tingkat ?? '') . ' ' . (optional(optional($s->kelas)->jurusan)->kode_jurusan ?? '') . ' ' . (optional($s->kelas)->rombel ?? '')) ?: '-';
                                @endphp
                                <option value="{{ $s->id_siswa }}" data-kelas="{{ $kelasLabel }}">{{ $s->nama_siswa }} &middot; {{ $kelasLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sp-form-group">
                        <label class="sp-form-label">Kelas</label>
                        <input type="text" id="kelasOtomatis" class="sp-input" style="width: 100%; background: #f1f5f9;" value="Pilih siswa dulu" disabled>
                    </div>
                </div>
                
                <div class="sp-form-group">
                    <label class="sp-form-label">Jenis Kejadian <span style="color: #dc2626;">*</span></label>
                    <div class="sp-chip-group">
                        <label class="sp-chip-radio">
                            <input type="radio" name="jenis_kejadian" value="terlambat_kembali" checked>
                            Terlambat Kembali dari Izin
                        </label>
                        <label class="sp-chip-radio">
                            <input type="radio" name="jenis_kejadian" value="keluar_tanpa_izin">
                            Keluar Tanpa Izin
                        </label>
                        <label class="sp-chip-radio">
                            <input type="radio" name="jenis_kejadian" value="pelanggaran_tata_tertib">
                            Pelanggaran Tata Tertib
                        </label>
                        <label class="sp-chip-radio">
                            <input type="radio" name="jenis_kejadian" value="lainnya">
                            Lainnya
                        </label>
                    </div>
                </div>

                <div class="sp-form-group" style="margin-bottom: 0;">
                    <label class="sp-form-label">Catatan Kejadian <span style="color: #dc2626;">*</span></label>
                    <textarea name="catatan_kejadian" class="sp-textarea" placeholder="Jelaskan kejadian secara singkat dan jelas..." required></textarea>
                </div>

            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 20px;">

            <div class="sp-card">
                <div class="sp-card-header">
                    <h3 class="sp-card-title"><i class="fa-solid fa-paper-plane" style="color: var(--dash-navy);"></i> Kirim Laporan Ke</h3>
                </div>
                <div class="sp-card-body">

                    <div class="sp-recipient-card">
                        <div>
                            <div class="sp-recipient-name">Wali Kelas</div>
                            <div class="sp-recipient-role">Diambil otomatis sesuai kelas siswa</div>
                        </div>
                        <label class="sp-toggle">
                            <input type="checkbox" name="kirim_ke_wali_kelas" value="1" checked>
                            <span class="sp-toggle-slider"></span>
                        </label>
                    </div>

                    <div class="sp-recipient-card" style="margin-bottom: 0;">
                        <div>
                            <div class="sp-recipient-name">Guru Piket Hari Ini</div>
                            <div class="sp-recipient-role">Sesuai jadwal piket hari ini</div>
                        </div>
                        <label class="sp-toggle">
                            <input type="checkbox" name="kirim_ke_guru_piket" value="1" checked>
                            <span class="sp-toggle-slider"></span>
                        </label>
                    </div>

                </div>
            </div>

            <div class="sp-card">
                <div class="sp-card-body" style="display: flex; flex-direction: column; gap: 10px;">
                    <button type="submit" name="aksi" value="kirim" class="sp-btn sp-btn-danger" style="width: 100%; padding: 13px;">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Laporan
                    </button>
                    <button type="submit" name="aksi" value="draft" class="sp-btn sp-btn-outline" style="width: 100%; padding: 13px;">
                        Simpan sebagai Draft
                    </button>
                </div>
            </div>

        </div>
    </div>

</form>

@push('scripts')
<script>
    function isiKelasOtomatis() {
        const select = document.getElementById('pilihSiswa');
        const kelasInput = document.getElementById('kelasOtomatis');
        const selectedOption = select.options[select.selectedIndex];
        const kelas = selectedOption ? selectedOption.getAttribute('data-kelas') : null;
        kelasInput.value = kelas || 'Pilih siswa dulu';
    }
</script>
@endpush

@endsection