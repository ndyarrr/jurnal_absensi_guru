@extends('layouts.guru_mengajar')

@section('title', 'Isi Jurnal Mengajar & Presensi')
@section('page-title', 'Isi Jurnal Mengajar & Presensi Siswa')
@section('page-subtitle', 'Catat topik pembelajaran KBM serta presensi siswa kelas')

@section('content')

    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('guru-mengajar.dashboard') }}" class="gm-btn gm-btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
        </a>
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--dash-text-muted);">
            <i class="fa-regular fa-calendar" style="margin-right: 4px;"></i> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
        </span>
    </div>

    <form action="{{ route('guru-mengajar.jurnal.store') }}" method="POST" id="inputJurnalForm">
        @csrf
        <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <!-- CARD 1: INFORMASI JADWAL KBM -->
        <div class="gm-card" style="margin-bottom: 24px;">
            <div class="gm-card-header" style="background: var(--dash-navy); color: #ffffff; border-radius: 16px 16px 0 0; padding: 16px 24px;">
                <h3 class="gm-card-title" style="color: #ffffff; margin: 0; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Informasi Jadwal Pelajaran</span>
                </h3>
            </div>
            <div class="gm-card-body" style="padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <span style="font-size: 0.775rem; color: var(--dash-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Kelas</span>
                        <div style="font-size: 1.15rem; font-weight: 800; color: var(--dash-navy); margin-top: 2px;">{{ $kelasName }}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.775rem; color: var(--dash-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Mata Pelajaran</span>
                        <div style="font-size: 1.15rem; font-weight: 800; color: var(--dash-navy); margin-top: 2px;">{{ $mapelName }}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.775rem; color: var(--dash-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Waktu & Jam</span>
                        <div style="font-size: 1.15rem; font-weight: 800; color: var(--dash-navy); margin-top: 2px;">{{ $waktuStr }} <span style="font-size: 0.85rem; font-weight: 600; color: var(--dash-text-muted);">({{ $jamStr }})</span></div>
                    </div>
                    <div>
                        <span style="font-size: 0.775rem; color: var(--dash-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal KBM</span>
                        <div style="font-size: 1.05rem; font-weight: 800; color: var(--dash-navy); margin-top: 2px; display: flex; align-items: center; gap: 8px;">
                            <span>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 2: INPUT MATERI & CATATAN KBM -->
        <div class="gm-card" style="margin-bottom: 24px;">
            <div class="gm-card-header">
                <h3 class="gm-card-title"><i class="fa-solid fa-book-bookmark" style="color: var(--dash-navy);"></i> Kegiatan Pembelajaran Hari Ini</h3>
            </div>
            <div class="gm-card-body" style="padding: 24px;">
                <div class="gm-form-group" style="margin-bottom: 20px;">
                    <label class="gm-form-label" style="font-size: 0.95rem;">Materi / Pokok Bahasan Pembelajaran <span style="color: #dc2626;">*</span></label>
                    <textarea name="materi" class="gm-textarea" style="min-height: 90px; font-size: 0.95rem; line-height: 1.5;" placeholder="Tuliskan bahasan materi, bab, atau indikator pembelajaran yang diajarkan..." required>{{ old('materi', $jurnal->materi ?? '') }}</textarea>
                </div>

                <div class="gm-form-group" style="margin-bottom: 0;">
                    <label class="gm-form-label" style="font-size: 0.95rem;">Catatan KBM / Evaluasi Singkat Kelas (Opsional)</label>
                    <textarea name="catatan" class="gm-textarea" style="min-height: 70px; font-size: 0.9rem;" placeholder="Catatan aktivitas kelas, kendala siswa, atau penugasan (opsional)...">{{ old('catatan', $jurnal->catatan ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- CARD 3: PRESENSI KEHADIRAN SISWA -->
        <div class="gm-card" style="margin-bottom: 28px;">
            <div class="gm-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <h3 class="gm-card-title" style="margin: 0;">
                    <i class="fa-solid fa-clipboard-user" style="color: var(--dash-navy);"></i> Presensi Kehadiran Siswa
                </h3>
                <button type="button" class="gm-btn gm-btn-tan" style="padding: 6px 16px; font-size: 0.8rem;" onclick="markAllHadir()">
                    <i class="fa-solid fa-check-double"></i> Tandai Semua Hadir
                </button>
            </div>

            <div class="gm-card-body" style="padding: 0;">
                <div class="gm-table-responsive">
                    <table class="gm-attendance-table" style="width: 100%;">
                        <thead>
                            <tr style="background: var(--dash-navy); color: #ffffff;">
                                <th style="width: 50px; text-align: center; padding: 14px;">#</th>
                                <th style="padding: 14px;">Nama Siswa</th>
                                <th style="width: 240px; text-align: center; padding: 14px;">Status Kehadiran</th>
                                <th style="padding: 14px;">Keterangan</th>
                                <th style="width: 150px; text-align: center; padding: 14px;">Surat / Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaList as $idx => $s)
                                @php
                                    $existing = $existingDetails[$s->id_siswa] ?? null;
                                    $izin = $izinList->get($s->id_siswa);
                                    $dispen = $dispenList->get($s->id_siswa);

                                    $statusVal = 'Hadir';
                                    $ketVal = '';
                                    $hasSurat = false;
                                    $suratData = null;

                                    if ($existing) {
                                        $statusVal = $existing['status'];
                                        $ketVal = $existing['keterangan'];
                                    } elseif ($izin) {
                                        $statusVal = strtolower($izin->jenis_izin) === 'sakit' ? 'Sakit' : 'Izin';
                                        $ketVal = '[Surat Piket: ' . $izin->jenis_izin . ']';
                                    } elseif ($dispen) {
                                        $statusVal = 'Izin';
                                        $ketVal = '[Dispensasi] ' . ($dispen->nama_kegiatan ?? 'Dispensasi');
                                    }

                                    if ($izin) {
                                        $hasSurat = true;
                                        $buktiUrl = $izin->bukti_surat ? asset('storage/' . $izin->bukti_surat) : null;
                                        $suratData = [
                                            'type' => 'izin',
                                            'jenis' => $izin->jenis_izin,
                                            'tanggal' => \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d M Y') . ($izin->tanggal_mulai != $izin->tanggal_selesai ? ' - ' . \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d M Y') : ''),
                                            'alasan' => $izin->alasan ?? 'Tidak ada alasan khusus',
                                            'bukti' => $buktiUrl,
                                            'status_label' => 'Surat ' . $izin->jenis_izin . ' (Guru Piket)',
                                        ];
                                    } elseif ($dispen) {
                                        $hasSurat = true;
                                        $fileUrl = $dispen->file_surat ? asset('storage/' . $dispen->file_surat) : null;
                                        $ttdSiswaUrl = $dispen->ttd_siswa_path ? asset('storage/' . $dispen->ttd_siswa_path) : null;
                                        $ttdGuruUrl = $dispen->ttd_guru_path ? asset('storage/' . $dispen->ttd_guru_path) : null;

                                        $suratData = [
                                            'type' => 'dispensasi',
                                            'nomor' => $dispen->nomor_surat ?? '-',
                                            'kegiatan' => $dispen->nama_kegiatan ?? 'Kegiatan Sekolah',
                                            'lokasi' => $dispen->lokasi_kegiatan ?? '-',
                                            'tanggal' => \Carbon\Carbon::parse($dispen->tanggal_mulai)->translatedFormat('d M Y') . ' s/d ' . \Carbon\Carbon::parse($dispen->tanggal_selesai)->translatedFormat('d M Y'),
                                            'jam' => ($dispen->jam_mulai ?? '-') . ' - ' . ($dispen->jam_selesai ?? '-') . ' WIB',
                                            'alasan' => $dispen->alasan_dispensasi ?? '-',
                                            'file_surat' => $fileUrl,
                                            'ttd_siswa' => $ttdSiswaUrl,
                                            'ttd_siswa_nama' => $dispen->ttd_siswa_signed_name ?? $s->nama_siswa,
                                            'ttd_guru' => $ttdGuruUrl,
                                            'ttd_guru_nama' => $dispen->ttd_guru_signed_name ?? 'Guru Piket / Pengesah',
                                            'status_label' => 'Surat Dispensasi Resmi',
                                        ];
                                    }
                                @endphp
                                <tr style="border-bottom: 1px solid var(--dash-cream-border);">
                                    <td style="text-align: center; font-weight: 700; padding: 14px;">{{ $idx + 1 }}</td>
                                    <td style="padding: 14px;">
                                        <div style="font-weight: 800; color: var(--dash-navy); font-size: 0.95rem;">{{ $s->nama_siswa }}</div>
                                        <div style="font-size: 0.775rem; color: var(--dash-text-muted);">NISN: {{ $s->nisn ?? '-' }}</div>
                                        <input type="hidden" name="presensi[{{ $idx }}][id_siswa]" value="{{ $s->id_siswa }}">
                                    </td>
                                    <td style="padding: 14px;">
                                        <div class="gm-radio-group" style="justify-content: center;">
                                            <label class="gm-radio-label hadir"><input type="radio" name="presensi[{{ $idx }}][status]" value="Hadir" {{ $statusVal === 'Hadir' ? 'checked' : '' }}> H</label>
                                            <label class="gm-radio-label sakit"><input type="radio" name="presensi[{{ $idx }}][status]" value="Sakit" {{ $statusVal === 'Sakit' ? 'checked' : '' }}> S</label>
                                            <label class="gm-radio-label izin"><input type="radio" name="presensi[{{ $idx }}][status]" value="Izin" {{ $statusVal === 'Izin' ? 'checked' : '' }}> I</label>
                                            <label class="gm-radio-label alpa"><input type="radio" name="presensi[{{ $idx }}][status]" value="Alpa" {{ $statusVal === 'Alpa' ? 'checked' : '' }}> A</label>
                                        </div>
                                    </td>
                                    <td style="padding: 14px;">
                                        <input type="text" name="presensi[{{ $idx }}][keterangan]" class="gm-input" style="padding: 8px 12px; font-size: 0.85rem; width: 100%;" placeholder="Keterangan..." value="{{ $ketVal }}">
                                    </td>
                                    <td style="text-align: center; padding: 14px;">
                                        @if($hasSurat && $suratData)
                                            <button type="button" class="gm-btn" style="background: #fef3c7; color: #b45309; border: 1px solid #fde047; padding: 6px 12px; font-size: 0.775rem; font-weight: 700; border-radius: 8px;"
                                                onclick="openDetailSuratModal('{{ $s->nama_siswa }}', {{ json_encode($suratData) }})">
                                                <i class="fa-solid fa-envelope-open-text"></i> Lihat Surat
                                            </button>
                                        @else
                                            <span style="font-size: 0.775rem; color: #94a3b8;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 30px; color: #94a3b8;">
                                        Belum ada data siswa terdaftar di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- FORM ACTION BUTTONS -->
        <div style="display: flex; gap: 14px; justify-content: flex-end; align-items: center; margin-bottom: 40px;">
            <a href="{{ route('guru-mengajar.dashboard') }}" class="gm-btn gm-btn-outline" style="padding: 12px 24px; font-size: 0.95rem;">
                Batal
            </a>
            <button type="submit" class="gm-btn gm-btn-navy" style="padding: 12px 32px; font-size: 0.95rem; box-shadow: 0 4px 12px rgba(35, 41, 59, 0.2);">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Jurnal & Presensi
            </button>
        </div>
    </form>


    <!-- MODAL POPUP: DETAIL & FOTO SURAT -->
    <div class="gm-modal-overlay" id="detailSuratModal" style="display: none; align-items: center; justify-content: center; z-index: 9999;">
        <div class="gm-modal" style="max-width: 650px; width: 90%; max-height: 90vh; overflow-y: auto; border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.25);">
            <div class="gm-modal-header" style="background: var(--dash-navy); color: #ffffff; padding: 18px 24px; border-radius: 20px 20px 0 0;">
                <h3 class="gm-modal-title" style="color: #ffffff; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;" id="suratModalTitle">
                    <i class="fa-solid fa-file-invoice"></i> Detail Surat Siswa
                </h3>
                <button type="button" class="gm-modal-close" style="color: #ffffff;" onclick="closeDetailSuratModal()">&times;</button>
            </div>

            <div class="gm-modal-body" style="padding: 24px;">
                <div id="suratModalContent">
                    <!-- Dynamic Surat Content Inserted by JS -->
                </div>
            </div>

            <div class="gm-modal-footer" style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
                <button type="button" class="gm-btn gm-btn-navy" onclick="closeDetailSuratModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL ZOOM GAMBAR FOTO SURAT FULL SCREEN -->
    <div class="gm-modal-overlay" id="zoomImageModal" style="display: none; align-items: center; justify-content: center; z-index: 10000; background: rgba(0,0,0,0.85);" onclick="closeZoomImageModal()">
        <div style="position: relative; max-width: 90%; max-height: 90vh;">
            <img id="zoomImageTarget" src="" alt="Foto Surat Full" style="max-width: 100%; max-height: 85vh; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); object-fit: contain;">
            <div style="color: #fff; text-align: center; margin-top: 10px; font-weight: 700; font-size: 0.85rem;">Klik di mana saja untuk menutup gambar</div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function markAllHadir() {
        document.querySelectorAll('input[type="radio"][value="Hadir"]').forEach(r => r.checked = true);
    }

    function openDetailSuratModal(namaSiswa, data) {
        const modal = document.getElementById('detailSuratModal');
        const titleEl = document.getElementById('suratModalTitle');
        const contentEl = document.getElementById('suratModalContent');

        titleEl.innerHTML = `<i class="fa-solid fa-file-invoice"></i> Detail Surat - ${namaSiswa}`;

        let html = '';

        if (data.type === 'izin') {
            html = `
                <div style="background: #fef3c7; border: 1px solid #fde047; border-radius: 12px; padding: 12px 16px; margin-bottom: 16px; color: #b45309; font-weight: 700; font-size: 0.9rem;">
                    <i class="fa-solid fa-envelope-open-text" style="margin-right: 6px;"></i> ${data.status_label}
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 18px;">
                    <div>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Jenis Izin</span>
                        <div style="font-size: 1rem; font-weight: 800; color: #0f172a;">${data.jenis}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Tanggal Berlaku</span>
                        <div style="font-size: 0.95rem; font-weight: 800; color: #0f172a;">${data.tanggal}</div>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Alasan Keterangan</span>
                    <div style="font-size: 0.9rem; color: #334155; margin-top: 4px; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">${data.alasan}</div>
                </div>
            `;

            if (data.bukti) {
                const isPdf = data.bukti.toLowerCase().endsWith('.pdf');
                if (isPdf) {
                    html += `
                        <div style="margin-top: 16px;">
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 8px;">File Bukti Surat (PDF)</span>
                            <a href="${data.bukti}" target="_blank" class="gm-btn gm-btn-navy" style="display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-file-pdf"></i> Buka File Surat PDF
                            </a>
                        </div>
                    `;
                } else {
                    html += `
                        <div style="margin-top: 16px;">
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 8px;">Foto / Lampiran Surat (Klik untuk memperbesar)</span>
                            <div style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 8px; text-align: center; background: #f8fafc; cursor: pointer;" onclick="zoomImage('${data.bukti}')">
                                <img src="${data.bukti}" alt="Foto Surat Izin" style="max-height: 250px; max-width: 100%; border-radius: 8px; object-fit: contain;">
                            </div>
                        </div>
                    `;
                }
            } else {
                html += `<div style="font-size: 0.85rem; color: #94a3b8; font-style: italic;">Tidak ada foto/lampiran surat fisik yang diunggah.</div>`;
            }
        } else if (data.type === 'dispensasi') {
            html = `
                <div style="background: #e0f2fe; border: 1px solid #7dd3fc; border-radius: 12px; padding: 12px 16px; margin-bottom: 16px; color: #0369a1; font-weight: 700; font-size: 0.9rem;">
                    <i class="fa-solid fa-award" style="margin-right: 6px;"></i> ${data.status_label}
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 16px;">
                    <div>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Nomor Surat</span>
                        <div style="font-size: 0.95rem; font-weight: 800; color: #0f172a;">${data.nomor}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Nama Kegiatan</span>
                        <div style="font-size: 0.95rem; font-weight: 800; color: #0f172a;">${data.kegiatan}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Lokasi Kegiatan</span>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #334155;">${data.lokasi}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Tanggal & Waktu</span>
                        <div style="font-size: 0.85rem; font-weight: 700; color: #334155;">${data.tanggal} (${data.jam})</div>
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Alasan / Keperluan Dispensasi</span>
                    <div style="font-size: 0.9rem; color: #334155; margin-top: 4px; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">${data.alasan}</div>
                </div>

                <!-- TTD DIGITAL -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 20px; background: #fafafa; padding: 14px; border-radius: 12px; border: 1px solid #f1f5f9;">
                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">TTD Siswa Pemohon</span>
                        ${data.ttd_siswa ? `<img src="${data.ttd_siswa}" alt="TTD Siswa" style="max-height: 60px; object-fit: contain; border-bottom: 1px solid #cbd5e1;">` : '<span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Belum TTD</span>'}
                        <div style="font-size: 0.8rem; font-weight: 700; color: #0f172a; margin-top: 4px;">${data.ttd_siswa_nama}</div>
                    </div>
                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">TTD Guru Piket / Pengesah</span>
                        ${data.ttd_guru ? `<img src="${data.ttd_guru}" alt="TTD Guru" style="max-height: 60px; object-fit: contain; border-bottom: 1px solid #cbd5e1;">` : '<span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Belum TTD</span>'}
                        <div style="font-size: 0.8rem; font-weight: 700; color: #0f172a; margin-top: 4px;">${data.ttd_guru_nama}</div>
                    </div>
                </div>
            `;

            if (data.file_surat) {
                const isPdf = data.file_surat.toLowerCase().endsWith('.pdf');
                if (isPdf) {
                    html += `
                        <div>
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 8px;">File Surat Undangan Dispensasi (PDF)</span>
                            <a href="${data.file_surat}" target="_blank" class="gm-btn gm-btn-navy" style="display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-file-pdf"></i> Buka Surat Undangan PDF
                            </a>
                        </div>
                    `;
                } else {
                    html += `
                        <div>
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 8px;">Foto Surat Undangan / Lampiran (Klik untuk memperbesar)</span>
                            <div style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 8px; text-align: center; background: #f8fafc; cursor: pointer;" onclick="zoomImage('${data.file_surat}')">
                                <img src="${data.file_surat}" alt="Foto Surat Dispensasi" style="max-height: 250px; max-width: 100%; border-radius: 8px; object-fit: contain;">
                            </div>
                        </div>
                    `;
                }
            } else {
                html += `<div style="font-size: 0.85rem; color: #94a3b8; font-style: italic;">Tidak ada foto/lampiran surat undangan yang diunggah.</div>`;
            }
        }

        contentEl.innerHTML = html;
        modal.style.display = 'flex';
    }

    function closeDetailSuratModal() {
        document.getElementById('detailSuratModal').style.display = 'none';
    }

    function zoomImage(url) {
        document.getElementById('zoomImageTarget').src = url;
        document.getElementById('zoomImageModal').style.display = 'flex';
    }

    function closeZoomImageModal() {
        document.getElementById('zoomImageModal').style.display = 'none';
    }
</script>
@endpush
