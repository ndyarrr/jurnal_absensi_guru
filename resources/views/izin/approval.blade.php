<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Izin Guru - {{ $izin->guru->nama_guru ?? 'Guru' }}</title>
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
        .approval-card { background: var(--card-bg); width: 100%; max-width: 520px; border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); overflow: hidden; }
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
        .badge-status { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px; font-weight: 800; font-size: 0.825rem; width: fit-content; }
        .badge-pending { background: #fefce8; color: #ca8a04; border: 1px solid #fef08a; }
        .badge-approved { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .badge-rejected { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .btn-action-group { display: flex; flex-direction: column; gap: 12px; }
        .btn-action { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px; border-radius: 12px; font-weight: 800; font-size: 0.95rem; border: none; cursor: pointer; transition: all 0.2s ease; text-decoration: none; }
        .btn-approve { background: var(--success); color: #ffffff; }
        .btn-approve:hover { background: #15803d; }
        .btn-reject { background: #fef2f2; color: var(--danger); border: 1px solid #fecaca; }
        .btn-reject:hover { background: #fee2e2; }
        .reject-reason-box { display: none; margin-top: 12px; background: #f8fafc; border: 1px solid var(--border); border-radius: 12px; padding: 16px; }
        .reject-reason-box textarea { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.9rem; margin-bottom: 12px; font-family: inherit; }
    </style>
</head>
<body>

<div class="approval-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-file-signature"></i> Persetujuan Izin Guru</h2>
        <p>Jurnal Absensi & Perizinan Guru Sekolah</p>
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

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nama Guru Pemohon</span>
                <span class="info-value">{{ $izin->guru->nama_guru ?? 'Guru' }}</span>
                <small style="color: #64748b;">NUPTK: {{ $izin->guru->nuptk ?? '-' }}</small>
            </div>

            <div class="info-item">
                <span class="info-label">Jenis / Kategori Izin</span>
                <span class="info-value" style="color: var(--primary);">{{ $izin->kategori_label }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Tanggal Tanggal Izin</span>
                <span class="info-value">
                    {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d F Y') }}
                    @if($izin->tanggal_mulai != $izin->tanggal_selesai)
                        s/d {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d F Y') }}
                    @endif
                </span>
                <small style="color: #64748b;">
                    (Durasi {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($izin->tanggal_selesai)) + 1 }} hari)
                </small>
            </div>

            <div class="info-item">
                <span class="info-label">Keterangan / Alasan</span>
                <p style="font-size: 0.9rem; color: #334155; line-height: 1.5; margin-top: 4px;">{{ $izin->alasan_izin }}</p>
            </div>

            <div class="info-item">
                <span class="info-label">Lampiran Bukti Dokumen</span>
                @if($izin->bukti_surat_url)
                    <a href="{{ $izin->bukti_surat_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; margin-top: 6px; color: var(--primary); font-weight: 700; font-size: 0.875rem;">
                        <i class="fa-solid fa-paperclip"></i> Lihat / Download Lampiran Dokumen
                    </a>
                @else
                    <span style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">Tidak ada dokumen dilampirkan</span>
                @endif
            </div>

            <div class="info-item">
                <span class="info-label">Status Persetujuan</span>
                @if($izin->status_approval === 'pending')
                    <span class="badge-status badge-pending">
                        <i class="fa-solid fa-clock"></i> Menunggu Persetujuan
                    </span>
                @elseif(in_array($izin->status_approval, ['approved', 'disetujui', 'disetujui_piket', 'disetujui_waka', 'disetujui_kepsek'], true))
                    <span class="badge-status badge-approved">
                        <i class="fa-solid fa-circle-check"></i> Telah Disetujui
                    </span>
                @else
                    <span class="badge-status badge-rejected">
                        <i class="fa-solid fa-circle-xmark"></i> Ditolak
                    </span>
                    @if($izin->catatan_approver)
                        <small style="color: #dc2626; margin-top: 4px;">Catatan: {{ $izin->catatan_approver }}</small>
                    @endif
                @endif
            </div>
        </div>

        @if($izin->status_approval === 'pending')
            <div class="btn-action-group">
                <form action="{{ route('izin.approval.approve', ['id' => $izin->id_izin_guru, 'token' => $izin->approval_token]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-action btn-approve" onclick="return confirm('Apakah Anda yakin ingin MENSETUJUI permohonan izin ini?')">
                        <i class="fa-solid fa-check-circle"></i> Setujui Permohonan Izin
                    </button>
                </form>

                <button type="button" class="btn-action btn-reject" onclick="toggleRejectBox()">
                    <i class="fa-solid fa-xmark-circle"></i> Tolak Permohonan Izin
                </button>

                <div class="reject-reason-box" id="rejectBox">
                    <form action="{{ route('izin.approval.reject', ['id' => $izin->id_izin_guru, 'token' => $izin->approval_token]) }}" method="POST">
                        @csrf
                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Catatan Alasan Penolakan (Opsional)</label>
                        <textarea name="catatan_approver" rows="3" placeholder="Contoh: Tugas mengajar jam ke-3 tidak ada pengganti..."></textarea>
                        <button type="submit" class="btn-action" style="background: var(--danger); color: white;" onclick="return confirm('Apakah Anda yakin ingin MENOLAK permohonan izin ini?')">
                            Kirim Penolakan
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleRejectBox() {
        const box = document.getElementById('rejectBox');
        if (box) {
            box.style.display = (box.style.display === 'block') ? 'none' : 'block';
        }
    }
</script>

</body>
</html>
