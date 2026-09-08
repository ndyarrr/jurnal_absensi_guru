<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Surat Dispensasi Siswa - {{ optional($dispen->siswa)->nama_siswa ?? 'Siswa' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --success: #16a34a;
            --danger: #dc2626;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-sub: #64748b;
            --border: #e2e8f0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body { background: var(--bg); color: var(--text-main); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .approval-card { background: var(--card-bg); width: 100%; max-width: 540px; border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); overflow: hidden; }
        .card-header { background: #0f172a; color: #ffffff; padding: 24px; text-align: center; }
        .card-header h2 { font-size: 1.25rem; font-weight: 800; margin-bottom: 6px; }
        .card-header p { font-size: 0.85rem; color: #94a3b8; }
        .card-body { padding: 24px; }
        .alert-box { padding: 14px; border-radius: 10px; font-size: 0.9rem; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
        .info-grid { display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px; }
        .info-item { display: flex; flex-direction: column; gap: 4px; border-bottom: 1px dashed var(--border); padding-bottom: 12px; }
        .info-item:last-child { border-bottom: none; }
        .info-label { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-sub); }
        .info-value { font-size: 0.95rem; font-weight: 700; color: var(--text-main); }
        
        .track-box { background: #f8fafc; border: 1px solid var(--border); border-radius: 12px; padding: 14px; margin-bottom: 20px; }
        .track-title { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-sub); margin-bottom: 10px; }
        .track-step-item { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e2e8f0; font-size: 0.85rem; }
        .track-step-item:last-child { border-bottom: none; }
        .track-status-ok { color: #16a34a; font-weight: 700; display: flex; align-items: center; gap: 5px; }
        .track-status-wait { color: #ca8a04; font-weight: 600; display: flex; align-items: center; gap: 5px; }

        .btn-action-group { display: flex; flex-direction: column; gap: 10px; }
        .btn-action { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px; border-radius: 12px; font-weight: 800; font-size: 0.95rem; border: none; cursor: pointer; transition: all 0.2s ease; text-decoration: none; }
        .btn-approve-waka { background: #2563eb; color: #ffffff; }
        .btn-approve-waka:hover { background: #1d4ed8; }
        .btn-approve-kepsek { background: #16a34a; color: #ffffff; }
        .btn-approve-kepsek:hover { background: #15803d; }
        .btn-reject { background: #fef2f2; color: var(--danger); border: 1px solid #fecaca; }
        .btn-reject:hover { background: #fee2e2; }
    </style>
</head>
<body>

<div class="approval-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-id-card"></i> Persetujuan Surat Dispensasi Siswa</h2>
        <p>Proses Persetujuan 3 Tingkat (Guru Piket → Waka → Kepala Sekolah)</p>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert-box alert-success">
                <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="alert-box alert-info">
                <i class="fa-solid fa-circle-info" style="font-size: 1.2rem;"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <!-- Progress 3-Tingkat Track -->
        <div class="track-box">
            <div class="track-title">Progress Persetujuan 3 Pihak:</div>
            
            <div class="track-step-item">
                <span>1. Verification Guru Piket</span>
                <span class="track-status-ok"><i class="fa-solid fa-circle-check"></i> Disetujui ({{ optional($dispen->approver)->name ?? 'Guru Piket' }})</span>
            </div>

            <div class="track-step-item">
                <span>2. Konfirmasi Waka / Waka SDM</span>
                @if($dispen->status_waka === 'disetujui')
                    <span class="track-status-ok"><i class="fa-solid fa-circle-check"></i> Disetujui ({{ optional($dispen->approverWaka)->name ?? 'Waka' }})</span>
                @else
                    <span class="track-status-wait"><i class="fa-solid fa-clock"></i> Menunggu Persetujuan</span>
                @endif
            </div>

            <div class="track-step-item">
                <span>3. Konfirmasi Kepala Sekolah</span>
                @if($dispen->status_kepsek === 'disetujui')
                    <span class="track-status-ok"><i class="fa-solid fa-circle-check"></i> Disetujui ({{ optional($dispen->approverKepsek)->name ?? 'Kepsek' }})</span>
                @else
                    <span class="track-status-wait"><i class="fa-solid fa-clock"></i> Menunggu Persetujuan</span>
                @endif
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nomor Surat</span>
                <span class="info-value" style="color: var(--primary);">{{ $dispen->nomor_surat }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Nama Siswa Pemohon</span>
                <span class="info-value">{{ optional($dispen->siswa)->nama_siswa ?? '-' }}</span>
                <small style="color: #64748b;">
                    NISN: {{ optional($dispen->siswa)->nisn ?? '-' }} | Kelas: 
                    {{ optional(optional($dispen->siswa)->kelas)->tingkat }} 
                    {{ optional(optional(optional($dispen->siswa)->kelas)->jurusan)->kode_jurusan }} 
                    {{ optional(optional($dispen->siswa)->kelas)->rombel }}
                </small>
            </div>

            <div class="info-item">
                <span class="info-label">Nama Kegiatan & Lokasi</span>
                <span class="info-value">{{ $dispen->nama_kegiatan }}</span>
                <small style="color: #64748b;">Lokasi: {{ $dispen->lokasi_kegiatan ?? 'Sekolah/Luar' }}</small>
            </div>

            <div class="info-item">
                <span class="info-label">Waktu Dispensasi</span>
                <span class="info-value">
                    {{ \Carbon\Carbon::parse($dispen->tanggal_mulai)->translatedFormat('d F Y') }}
                    @if($dispen->tanggal_mulai != $dispen->tanggal_selesai)
                        s/d {{ \Carbon\Carbon::parse($dispen->tanggal_selesai)->translatedFormat('d F Y') }}
                    @endif
                </span>
                @if($dispen->jam_mulai && $dispen->jam_selesai)
                    <small style="color: #64748b;">Jam: {{ \Carbon\Carbon::parse($dispen->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($dispen->jam_selesai)->format('H:i') }} WIB</small>
                @endif
            </div>

            <div class="info-item">
                <span class="info-label">Alasan / Keterangan</span>
                <p style="font-size: 0.9rem; color: #334155; line-height: 1.5; margin-top: 4px;">{{ $dispen->alasan_dispensasi }}</p>
            </div>

            @if($dispen->file_surat_url)
                <div class="info-item">
                    <span class="info-label">File Lampiran</span>
                    <a href="{{ $dispen->file_surat_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; margin-top: 6px; color: var(--primary); font-weight: 700; font-size: 0.875rem;">
                        <i class="fa-solid fa-paperclip"></i> Lihat / Download Dokumen
                    </a>
                </div>
            @endif
        </div>

        @if($dispen->status_approval !== 'ditolak' && ($dispen->status_waka !== 'disetujui' || $dispen->status_kepsek !== 'disetujui'))
            <div class="btn-action-group">
                @php
                    $user = auth()->user();
                    $isWaka = $user && in_array($user->role, ['waka', 'waka_sdm'], true);
                    $isKepsek = $user && $user->role === 'kepala_sekolah';
                @endphp

                @if(!$isKepsek && $dispen->status_waka !== 'disetujui')
                    <form action="{{ route('dispensasi.approval.approve', ['id' => $dispen->id_dispen, 'token' => $dispen->barcode_token]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="as_role" value="waka">
                        <button type="submit" class="btn-action btn-approve-waka" onclick="return confirm('Setujui surat dispensasi ini?')">
                            <i class="fa-solid fa-check-circle"></i> Setujui
                        </button>
                    </form>
                @endif

                @if(!$isWaka && $dispen->status_kepsek !== 'disetujui')
                    <form action="{{ route('dispensasi.approval.approve', ['id' => $dispen->id_dispen, 'token' => $dispen->barcode_token]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="as_role" value="kepala_sekolah">
                        <button type="submit" class="btn-action btn-approve-kepsek" onclick="return confirm('Setujui surat dispensasi ini?')">
                            <i class="fa-solid fa-check-circle"></i> Setujui
                        </button>
                    </form>
                @endif

                <form action="{{ route('dispensasi.approval.reject', ['id' => $dispen->id_dispen, 'token' => $dispen->barcode_token]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-action btn-reject" onclick="return confirm('Apakah Anda yakin ingin MENOLAK surat dispensasi siswa ini?')">
                        <i class="fa-solid fa-xmark-circle"></i> Tolak Permohonan
                    </button>
                </form>
            </div>
        @else
            <div style="text-align: center; padding: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; color: #166534; font-weight: 800;">
                <i class="fa-solid fa-check-double"></i> Poin Persetujuan Telah Lengkap & Final.
            </div>
        @endif
    </div>
</div>

</body>
</html>
