<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judulShift }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }
        h2, p {
            text-align: center;
            margin: 4px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }
        th, td {
            border: 1px solid #555;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #e8edf3;
        }
        .kosong {
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>
    <h2>JADWAL {{ strtoupper($judulShift) }}</h2>
    <p>
        {{ $senin->translatedFormat('d F Y') }}
        – {{ $jumat->translatedFormat('d F Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>Peran</th>
                <th>Nama Guru</th>
                <th>NIP</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $r->tanggal_tampil->translatedFormat('d F Y') }}</td>
                    <td>{{ $r->tanggal_tampil->translatedFormat('l') }}</td>
                    <td>{{ $r->peran ?? '-' }}</td>
                    <td>{{ optional($r->guru)->nama_guru ?? '-' }}</td>
                    <td>{{ optional($r->guru)->nip ?? '-' }}</td>
                    <td>{{ $r->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="kosong">
                        Belum ada jadwal untuk shift ini pada minggu tersebut.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>