@extends('layouts.approver')

@section('title', 'Detail Izin Siswa')
@section('page-title', 'Detail Izin Siswa')
@section('page-subtitle', 'Tinjau surat dispensasi dan ambil keputusan')

@php
    $sayaSetuju = $dispen->{$t['status']} === 'disetujui';
    $sayaTolak = $dispen->{$t['status']} === 'ditolak';
    $fileUrl = $dispen->file_surat_url;
    $filePdf = $fileUrl && \Illuminate\Support\Str::endsWith(strtolower($dispen->file_surat), '.pdf');
    $aktif = null;
    if ($dispen->status_approval !== 'ditolak') {
        if ($dispen->status_waka !== 'disetujui') { $aktif = 'waka'; }
        elseif ($dispen->status_waka_kurikulum !== 'disetujui') { $aktif = 'kur'; }
    }
    $tahapList = [
        ['key' => 'waka', 'nama' => 'Waka', 'status' => $dispen->status_waka, 'oleh' => $dispen->approverWaka, 'tgl' => $dispen->tgl_disetujui_waka],
        ['key' => 'kur', 'nama' => 'Waka Kurikulum', 'status' => $dispen->status_waka_kurikulum, 'oleh' => $dispen->approverWakaKurikulum, 'tgl' => $dispen->tgl_disetujui_waka_kurikulum],
    ];
@endphp

@section('content')
    <div class="page-head">
        <div>
            <a href="{{ route('approver.izin-siswa.index') }}" class="card-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke daftar izin siswa</a>
            <h1 class="page-title" style="margin-top:8px;">{{ $dispen->nama_kegiatan ?? 'Dispensasi Siswa' }}</h1>
            <p class="page-sub">No. surat {{ $dispen->nomor_surat ?? '-' }} &middot; diajukan {{ $dispen->created_at ? $dispen->created_at->translatedFormat('d F Y, H:i') : '-' }}</p>
        </div>
        <div>
            @if($dispen->status_approval === 'disetujui')
                <span class="badge badge-green" style="font-size:.85rem; padding:6px 14px;">Disetujui lengkap</span>
            @elseif($dispen->status_approval === 'ditolak')
                <span class="badge badge-red" style="font-size:.85rem; padding:6px 14px;">Ditolak</span>
            @else
                <span class="badge badge-amber" style="font-size:.85rem; padding:6px 14px;">Dalam proses persetujuan</span>
            @endif
        </div>
    </div>

    <div class="grid-2">
        <div style="display:flex; flex-direction:column; gap:16px;">
            <div class="content-card">
                <div class="card-head"><div class="card-title"><i class="fa-solid fa-file-lines" style="color:#2563eb;"></i> Data Permohonan</div></div>
                <div class="card-body">
                    <div class="detail-grid">
                        <div>
                            <div class="detail-label">Kegiatan</div>
                            <div class="detail-value">{{ $dispen->nama_kegiatan ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Lokasi</div>
                            <div class="detail-value">{{ $dispen->lokasi_kegiatan ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Tanggal</div>
                            <div class="detail-value">{{ $dispen->periode_label }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Jam</div>
                            <div class="detail-value">{{ $dispen->jam_label }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Kelas</div>
                            <div class="detail-value">{{ $dispen->kelas_label }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Guru pengaju</div>
                            <div class="detail-value">{{ optional($dispen->guru)->nama_guru ?: '-' }}</div>
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <div class="detail-label">Alasan / keterangan</div>
                        <div class="note-box">{!! nl2br(e($dispen->alasan_dispensasi ?: '-')) !!}</div>
                    </div>
                    <div style="margin-top:16px;">
                        <div class="detail-label">Lampiran surat</div>
                        @if($fileUrl)
                            @if($filePdf)
                                <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="btn btn-light btn-sm"><i class="fa-solid fa-file-pdf"></i> Buka lampiran (PDF)</a>
                            @else
                                <a href="{{ $fileUrl }}" target="_blank" rel="noopener"><img src="{{ $fileUrl }}" alt="Lampiran surat" class="bukti-img"></a>
                            @endif
                        @else
                            <div class="list-sub">Tidak ada lampiran.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-head">
                    <div class="card-title"><i class="fa-solid fa-users" style="color:#7c3aed;"></i> Daftar Siswa</div>
                    <span class="badge badge-blue">{{ $dispen->nama_siswa_list->count() }} siswa</span>
                </div>
                @forelse($dispen->nama_siswa_list as $i => $nama)
                    <div class="list-row">
                        <div class="list-main">{{ $i + 1 }}. {{ $nama }}</div>
                    </div>
                @empty
                    <div class="empty-note">Belum ada siswa pada surat ini.</div>
                @endforelse
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:16px;">
            <div class="content-card">
                <div class="card-head"><div class="card-title"><i class="fa-solid fa-route" style="color:#059669;"></i> Alur Persetujuan</div></div>
                <div class="card-body">
                    <div class="stepper">
                        <div class="step">
                            <div class="step-dot ok"><i class="fa-solid fa-check"></i></div>
                            <div>
                                <div class="step-title">Guru Piket</div>
                                <div class="step-meta">Surat dibuat &amp; diteruskan</div>
                            </div>
                        </div>
                        @foreach($tahapList as $s)
                            @php
                                $cls = $s['status'] === 'disetujui' ? 'ok' : ($s['status'] === 'ditolak' ? 'no' : ($aktif === $s['key'] ? 'now' : ''));
                                $ico = $cls === 'ok' ? 'fa-check' : ($cls === 'no' ? 'fa-xmark' : ($cls === 'now' ? 'fa-hourglass-half' : 'fa-ellipsis'));
                            @endphp
                            <div class="step">
                                <div class="step-dot {{ $cls }}"><i class="fa-solid {{ $ico }}"></i></div>
                                <div>
                                    <div class="step-title">{{ $s['nama'] }}</div>
                                    <div class="step-meta">
                                        @if($s['status'] === 'disetujui')
                                            Disetujui{{ $s['oleh'] ? ' oleh ' . $s['oleh']->name : '' }}{{ $s['tgl'] ? ' · ' . $s['tgl']->translatedFormat('d M Y, H:i') : '' }}
                                        @elseif($s['status'] === 'ditolak')
                                            Ditolak{{ $s['oleh'] ? ' oleh ' . $s['oleh']->name : '' }}{{ $s['tgl'] ? ' · ' . $s['tgl']->translatedFormat('d M Y, H:i') : '' }}
                                        @elseif($aktif === $s['key'])
                                            Sedang menunggu keputusan
                                        @else
                                            Belum diputuskan
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($dispen->status_approval === 'ditolak' && $dispen->catatan_approver)
                        <div class="note-box red" style="margin-top:16px;">
                            <strong>Alasan penolakan:</strong><br>{!! nl2br(e($dispen->catatan_approver)) !!}
                        </div>
                    @endif
                </div>
            </div>

            <div class="content-card">
                <div class="card-head"><div class="card-title"><i class="fa-solid fa-gavel" style="color:#ea580c;"></i> Keputusan Anda ({{ $t['label'] }})</div></div>
                <div class="card-body">
                    @if($sayaSetuju || $sayaTolak)
                        <div class="note-box {{ $sayaSetuju ? 'green' : 'red' }}" style="margin-bottom:12px;">
                            Anda sudah <strong>{{ $sayaSetuju ? 'menyetujui' : 'menolak' }}</strong> permohonan ini.
                        </div>
                        <form action="{{ route('approver.dispensasi.reset', $dispen->id_dispen) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-light" onclick="return confirm('Batalkan keputusan Anda dan kembalikan ke status menunggu?')">
                                <i class="fa-solid fa-rotate-left"></i> Batalkan Keputusan
                            </button>
                        </form>
                    @else
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <button type="button" class="btn btn-success" data-open-setujui
                                    data-jenis="dispensasi siswa" data-jenis-title="Izin Siswa"
                                    data-action="{{ route('approver.dispensasi.approve', $dispen->id_dispen) }}"
                                    data-guru="{{ $dispen->ringkas_siswa }}" data-periode="{{ $dispen->periode_label }}">
                                <i class="fa-solid fa-check"></i> Setujui
                            </button>
                            <button type="button" class="btn btn-danger" data-open-tolak
                                    data-jenis="dispensasi siswa" data-jenis-title="Izin Siswa"
                                    data-action="{{ route('approver.dispensasi.reject', $dispen->id_dispen) }}"
                                    data-guru="{{ $dispen->ringkas_siswa }}" data-periode="{{ $dispen->periode_label }}">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection