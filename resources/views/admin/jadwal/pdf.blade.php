<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Pelajaran Matriks</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9px;
            color: #0f172a;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e2538;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 16px;
            font-weight: 800;
            color: #1e2538;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            font-size: 9.5px;
            color: #64748b;
            margin: 0;
        }
        .meta-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 10px;
            margin-bottom: 14px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 2px 4px;
            font-size: 9px;
        }

        .class-block {
            margin-bottom: 22px;
            page-break-inside: avoid;
        }
        .class-header-bar {
            background: #1e2538;
            color: #ffffff;
            padding: 6px 10px;
            border-radius: 4px 4px 0 0;
            font-size: 11px;
            font-weight: 800;
        }
        .matrix-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .matrix-table th {
            background-color: #334155;
            color: #ffffff;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #334155;
        }
        .matrix-table td {
            padding: 5px 4px;
            border: 1px solid #cbd5e1;
            font-size: 8.5px;
            vertical-align: middle;
            height: 36px;
        }
        .jam-col {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            color: #334155;
            width: 10%;
        }
        .cell-content {
            text-align: center;
        }
        .cell-mapel {
            font-weight: 800;
            color: #1e2538;
            font-size: 10px;
            display: block;
            margin-bottom: 2px;
            line-height: 1.2;
        }
        .cell-guru {
            color: #475569;
            font-size: 7.5px;
            font-weight: 600;
            display: block;
            margin-bottom: 1px;
        }
        .cell-room {
            color: #64748b;
            font-size: 7px;
            font-weight: 500;
            display: block;
        }
        .cell-ket {
            color: #64748b;
            font-size: 6.5px;
            font-weight: 500;
            display: block;
            margin-top: 1.5px;
        }
        .cell-nonkbm {
            color: #b91c1c;
            font-size: 7.5px;
            font-weight: bold;
            text-align: center;
            background: #fef2f2;
            padding: 3px 2px;
            border-radius: 3px;
        }
        .cell-empty {
            color: #cbd5e1;
            text-align: center;
            font-size: 10px;
        }
        .footer {
            margin-top: 14px;
            text-align: right;
            font-size: 8px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Jurnal & Absensi Guru</h1>
        <p>Matriks Jadwal Pelajaran Sekolah</p>
    </div>

    <div class="meta-card">
        <table class="meta-table">
            <tr>
                <td style="width: 15%; font-weight: bold;">Tanggal Cetak</td>
                <td style="width: 35%;">: {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</td>
                <td style="width: 15%; font-weight: bold;">Filter Hari</td>
                <td style="width: 35%;">: {{ $filterHari }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Filter Kelas</td>
                <td>: {{ $filterKelas }}</td>
                <td style="font-weight: bold;">Filter Mapel</td>
                <td>: {{ $filterMapel }}</td>
            </tr>
        </table>
    </div>

    @php
        $activeDays = ($request->filled('hari') && in_array($request->input('hari'), $hariList))
            ? [$request->input('hari')]
            : $hariList;
        $dayColWidth = floor(90 / count($activeDays));
    @endphp

    @forelse($kelases as $k)
        <div class="class-block">
            <div class="class-header-bar">
                KELAS: {{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}
                @if(optional($k->waliKelas)->nama_guru)
                    <span style="font-size: 9px; font-weight: 400; opacity: 0.9; float: right;">Wali Kelas: {{ $k->waliKelas->nama_guru }}</span>
                @endif
            </div>

            <table class="matrix-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">Jam Ke</th>
                        @foreach($activeDays as $day)
                            <th style="width: {{ $dayColWidth }}%;">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @for($jam = 1; $jam <= $maxJamOverall; $jam++)
                        <tr>
                            <td class="jam-col">
                                <div>Jam {{ $jam }}</div>
                            </td>

                            @foreach($activeDays as $day)
                                @php
                                    $item = $matrix[$k->id_kelas][$day][$jam] ?? null;
                                    $kat = ($day === 'Jumat') ? 'Jumat' : 'Senin-Kamis';
                                    $jamSlot = $jamMap[$kat][$jam] ?? null;

                                    $isNonKbm = false;
                                    $nonKbmKet = '';
                                    if ($jamSlot) {
                                        $appliesToDay = !$jamSlot->berlaku_hari || $jamSlot->berlaku_hari === 'Semua Hari' || $jamSlot->berlaku_hari === $day;
                                        if ($jamSlot->bisa_diisi_mapel == 0 && $appliesToDay) {
                                            $isNonKbm = true;
                                            $nonKbmKet = ($day === 'Senin' && $jam == 1) ? 'UPACARA / APEL' : ($jamSlot->keterangan ?: 'NON-KBM');
                                        }
                                    }
                                @endphp
                                <td>
                                    @if($item)
                                        <div class="cell-content">
                                            <span class="cell-mapel">{{ optional($item->mapel)->nama_mapel ?? '-' }}</span>
                                            <span class="cell-guru">{{ optional($item->guru)->nama_guru ?? '-' }}</span>
                                            <span class="cell-room">Ruangan: {{ $item->ruangan ?: '-' }}</span>
                                        </div>
                                    @elseif($isNonKbm)
                                        <div class="cell-nonkbm">{{ strtoupper($nonKbmKet) }}</div>
                                    @else
                                        <div class="cell-empty">-</div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    @empty
        <div style="text-align: center; padding: 30px; color: #94a3b8; font-size: 11px;">
            Tidak ada data jadwal pelajaran yang sesuai dengan filter.
        </div>
    @endforelse

    <div class="footer">
        Dicetak otomatis oleh Sistem Jurnal & Absensi Guru pada {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d-m-Y H:i:s') }} WIB
    </div>
</body>
</html>
