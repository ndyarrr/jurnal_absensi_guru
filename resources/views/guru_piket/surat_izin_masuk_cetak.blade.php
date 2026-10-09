<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak {{ $surat->nomor_surat }}</title>
    <style>
        body { margin: 0; padding: 24px; background: #f1f5f9; font-family: Arial, sans-serif; color: #000; }
        .toolbar { max-width: 780px; margin: 0 auto 16px; display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; }
        .toolbar button { background: #1e2538; color: #fff; border: 0; padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .slip { max-width: 780px; margin: 0 auto; background: #fcd5ce; border: 2px solid #000; padding: 24px 30px; box-sizing: border-box; }
        .judul { text-align: center; font-weight: 900; text-transform: uppercase; line-height: 1.3; }
        .row { display: flex; align-items: flex-end; margin-bottom: 14px; font-weight: 900; }
        .row .label { width: 310px; flex-shrink: 0; text-transform: uppercase; }
        .row .sep { width: 20px; text-align: center; }
        .row .isi { flex: 1; border-bottom: 1.5px solid #000; padding-bottom: 2px; }
        .ttd-grid { display: flex; justify-content: space-between; align-items: flex-start; text-align: center; font-size: 0.875rem; font-weight: 900; margin-top: 10px; }
        .ttd-box { height: 60px; display: flex; align-items: center; justify-content: center; }
        .ttd-box img { max-height: 55px; max-width: 130px; object-fit: contain; }
        .ttd-nama { border-top: 1.5px solid #000; padding-top: 4px; min-width: 140px; margin: 0 auto; }
        .meta { margin-top: 18px; font-size: 0.7rem; font-weight: 600; color: #333; }
        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none; }
            .slip { background: #fff !important; max-width: none; }
            @page { margin: 12mm; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <span>{{ $surat->nomor_surat }} &mdash; tanda tangan digital lengkap</span>
        <button type="button" onclick="window.print()">Cetak</button>
    </div>

    <div class="slip">
        <div class="judul" style="font-size: 1.15rem;">SURAT IJIN MASUK KELAS / MENINGGALKAN KELAS</div>
        <div class="judul" style="font-size: 1.1rem; margin: 4px 0 24px;">SMK NEGERI 1 BOYOLANGU</div>

        <div class="row"><div class="label">NAMA</div><div class="sep">:</div><div class="isi">{{ $surat->nama_siswa }}</div></div>
        <div class="row"><div class="label">KELAS / KONSENTRASI KEAHLIAN</div><div class="sep">:</div><div class="isi">{{ $surat->kelas_str }}</div></div>
        <div class="row"><div class="label">JAM PELAJARAN KE</div><div class="sep">:</div><div class="isi">{{ $surat->jam_pelajaran_ke }}</div></div>
        <div class="row"><div class="label">ALASAN</div><div class="sep">:</div><div class="isi">{{ $surat->alasan }}</div></div>

        <div style="text-align: center; font-weight: 900; font-size: 0.95rem; margin: 28px 0 12px; letter-spacing: 0.05em;">MENGETAHUI / MENYETUJUI</div>

        <div class="ttd-grid">
            <div style="width: 30%;">
                <div>PIKET WAKASEK</div>
                <div class="ttd-box"><img src="{{ $surat->ttdUrl('wakasek') }}" alt="TTD Piket Wakasek"></div>
                <div class="ttd-nama">{{ $surat->ttd_wakasek_signed_name ?: $surat->nama_piket_wakasek }}</div>
            </div>
            <div style="width: 30%;">
                <div>GURU PIKET</div>
                <div class="ttd-box"><img src="{{ $surat->ttdUrl('guru-piket') }}" alt="TTD Guru Piket"></div>
                <div class="ttd-nama">{{ $surat->ttd_guru_piket_signed_name ?: $surat->nama_guru_piket }}</div>
            </div>
            <div style="width: 38%;">
                <div>TULUNGAGUNG, {{ $surat->tanggal ? $surat->tanggal->format('d-m-Y') : '-' }}</div>
                <div style="margin-top: 2px;">TANDA TANGAN SISWA</div>
                <div class="ttd-box" style="height: 50px;"><img src="{{ $surat->ttdUrl('siswa') }}" alt="TTD Siswa" style="max-height: 48px;"></div>
                <div class="ttd-nama">{{ $surat->ttd_siswa_signed_name ?: $surat->nama_siswa }}</div>
            </div>
        </div>

        <div class="meta">
            No. {{ $surat->nomor_surat }} &bull; Ditandatangani digital:
            Siswa {{ optional($surat->ttd_siswa_signed_at)->format('d/m/Y H:i') }},
            Guru Piket {{ optional($surat->ttd_guru_piket_signed_at)->format('d/m/Y H:i') }},
            Piket Wakasek {{ optional($surat->ttd_wakasek_signed_at)->format('d/m/Y H:i') }} WIB
        </div>
    </div>
</body>
</html>