@extends('layouts.kepsek')

@section('title', 'Detail Izin')
@section('page-title', 'Detail Izin Guru')
@section('page-subtitle', 'Tinjau permohonan dan ambil keputusan')

@php
    $periode = $izin->tanggal_mulai->format('d/m/Y') . ($izin->tanggal_mulai->isSameDay($izin->tanggal_selesai) ? '' : ' - ' . $izin->tanggal_selesai->format('d/m/Y'));
    $namaGuru = $izin->guru->nama_guru ?? 'Guru';
    $sayaSetuju = $izin->status_kepsek === 'disetujui';
    $sayaTolak = $izin->status_kepsek === 'ditolak';
    $buktiUrl = $izin->bukti_surat_url;
    $buktiPdf = $buktiUrl && \Illuminate\Support\Str::endsWith(strtolower($izin->bukti_surat), '.pdf');
@endphp

@section('content')
    <div class="page-head">
        <div>
            <a href="{{ route('kepsek.izin.index') }}" class="card-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke daftar izin</a>
            <h1 class="page-title" style="margin-top:8px;">Izin {{ $izin->kategori_label }} &ndash; {{ $namaGuru }}</h1>
            <p class="page-sub">Diajukan {{ $izin->created_at ? $izin->created_at->translatedFormat('d F Y, H:i') : '-' }}</p>
        </div>
        <div>
            @if($izin->status_approval === 'disetujui')
                <span class="badge badge-green" style="font-size:.85rem; padding:6px 14px;">Disetujui lengkap</span>
            @elseif($izin->status_approval === 'ditolak')
                <span class="badge badge-red" style="font-size:.85rem; padding:6px 14px;">Ditolak</span>
            @else
                <span class="badge badge-amber" style="font-size:.85rem; padding:6px 14px;">Dalam proses persetujuan</span>
            @endif
        </div>
    </div>

    <div class="grid-2">
        <div style="display:flex; flex-direction:column; gap:16px;">
            {{-- ===== Data izin ===== --}}
            <div class="content-card">
                <div class="card-head"><div class="card-title"><i class="fa-solid fa-file-lines" style="color:#2563eb;"></i> Data Permohonan</div></div>
                <div class="card-body">
                    <div class="detail-grid">
                        <div>
                            <div class="detail-label">Guru</div>
                            <div class="detail-value">{{ $namaGuru }}</div>
                            <div class="list-sub">NIP: {{ $izin->guru->nip ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Jenis izin</div>
                            <div class="detail-value">{{ $izin->kategori_label }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Periode</div>
                            <div class="detail-value">{{ $periode }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Lama izin</div>
                            <div class="detail-value">{{ $izin->durasi_hari }} hari</div>
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <div class="detail-label">Alasan / keterangan</div>
                        <div class="note-box">{!! nl2br(e($izin->alasan_izin)) !!}</div>
                    </div>
                    <div style="margin-top:16px;">
                        <div class="detail-label">Bukti surat</div>
                        @if($buktiUrl)
                            @if($buktiPdf)
                                <a href="{{ $buktiUrl }}" target="_blank" rel="noopener" class="btn btn-light btn-sm"><i class="fa-solid fa-file-pdf"></i> Buka bukti (PDF)</a>
                            @else
                                <a href="{{ $buktiUrl }}" target="_blank" rel="noopener"><img src="{{ $buktiUrl }}" alt="Bukti izin" class="bukti-img"></a>
                            @endif
                        @else
                            <div class="list-sub">Tidak ada bukti yang dilampirkan.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ===== Jadwal terdampak ===== --}}
            <div class="content-card">
                <div class="card-head">
                    <div class="card-title"><i class="fa-solid fa-calendar-days" style="color:#7c3aed;"></i> Jadwal Mengajar yang Terdampak</div>
                    <span class="badge badge-blue">&plusmn; {{ $terdampak['total_pertemuan'] }} pertemuan</span>
                </div>
                @forelse($terdampak['sesi_per_hari'] as $hari => $sesi)
                    <div class="list-row" style="align-items:flex-start;">
                        <div class="list-main" style="min-width:70px;">{{ $hari }}</div>
                        <div style="flex:1; display:flex; flex-wrap:wrap; gap:6px;">
                            @foreach($sesi->sortBy('jam_ke') as $j)
                                <span class="badge badge-gray">
                                    Jam {{ $j->jam_ke }} &middot; {{ $j->mapel->nama_mapel ?? '-' }} &middot; {{ optional($j->kelas)->nama_lengkap ?: '-' }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="empty-note"><i class="fa-solid fa-calendar-check"></i>Tidak ada jadwal mengajar pada periode izin ini.</div>
                @endforelse
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:16px;">
            {{-- ===== Alur persetujuan ===== --}}
            <div class="content-card">
                <div class="card-head"><div class="card-title"><i class="fa-solid fa-route" style="color:#059669;"></i> Alur Persetujuan</div></div>
                <div class="card-body">
                    @php
                        $aktif = null;
                        if ($izin->status_approval !== 'ditolak') {
                            if ($izin->status_waka !== 'disetujui') { $aktif = 'waka'; }
                            elseif ($izin->status_waka_kurikulum !== 'disetujui') { $aktif = 'kur'; }
                            elseif ($izin->status_kepsek !== 'disetujui') { $aktif = 'kep'; }
                        }
                        $tahap = [
                            ['key' => 'waka', 'nama' => 'Waka', 'status' => $izin->status_waka, 'oleh' => $izin->approverWaka, 'tgl' => $izin->tgl_disetujui_waka],
                            ['key' => 'kur',  'nama' => 'Waka Kurikulum', 'status' => $izin->status_waka_kurikulum, 'oleh' => $izin->approverWakaKurikulum, 'tgl' => $izin->tgl_disetujui_waka_kurikulum],
                            ['key' => 'kep',  'nama' => 'Kepala Sekolah', 'status' => $izin->status_kepsek, 'oleh' => $izin->approverKepsek, 'tgl' => $izin->tgl_disetujui_kepsek],
                        ];
                    @endphp
                    <div class="stepper">
                        <div class="step">
                            <div class="step-dot ok"><i class="fa-solid fa-check"></i></div>
                            <div>
                                <div class="step-title">Guru Piket</div>
                                <div class="step-meta">Permohonan diterima &amp; diteruskan</div>
                            </div>
                        </div>
                        @foreach($tahap as $t)
                            @php
                                $cls = $t['status'] === 'disetujui' ? 'ok' : ($t['status'] === 'ditolak' ? 'no' : ($aktif === $t['key'] ? 'now' : ''));
                                $ico = $cls === 'ok' ? 'fa-check' : ($cls === 'no' ? 'fa-xmark' : ($cls === 'now' ? 'fa-hourglass-half' : 'fa-ellipsis'));
                            @endphp
                            <div class="step">
                                <div class="step-dot {{ $cls }}"><i class="fa-solid {{ $ico }}"></i></div>
                                <div>
                                    <div class="step-title">{{ $t['nama'] }}</div>
                                    <div class="step-meta">
                                        @if($t['status'] === 'disetujui')
                                            Disetujui{{ $t['oleh'] ? ' oleh ' . $t['oleh']->name : '' }}{{ $t['tgl'] ? ' · ' . $t['tgl']->translatedFormat('d M Y, H:i') : '' }}
                                        @elseif($t['status'] === 'ditolak')
                                            Ditolak{{ $t['oleh'] ? ' oleh ' . $t['oleh']->name : '' }}{{ $t['tgl'] ? ' · ' . $t['tgl']->translatedFormat('d M Y, H:i') : '' }}
                                        @elseif($aktif === $t['key'])
                                            Sedang menunggu keputusan
                                        @else
                                            Belum sampai ke tahap ini
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($izin->status_approval === 'ditolak' && $izin->catatan_approver)
                        <div class="note-box red" style="margin-top:16px;">
                            <strong>Alasan penolakan:</strong><br>{!! nl2br(e($izin->catatan_approver)) !!}
                        </div>
                    @endif
                </div>
            </div>

            {{-- ===== Keputusan ===== --}}
            <div class="content-card">
                <div class="card-head"><div class="card-title"><i class="fa-solid fa-gavel" style="color:#ea580c;"></i> Keputusan Anda</div></div>
                <div class="card-body">
                    @if($sayaSetuju || $sayaTolak)
                        <div class="note-box {{ $sayaSetuju ? 'green' : 'red' }}" style="margin-bottom:12px;">
                            Anda sudah <strong>{{ $sayaSetuju ? 'menyetujui' : 'menolak' }}</strong> permohonan ini.
                        </div>
                        <form action="{{ route('approver.izin.reset', $izin->id_izin_guru) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-light" onclick="return confirm('Batalkan keputusan Anda dan kembalikan ke status menunggu?')">
                                <i class="fa-solid fa-rotate-left"></i> Batalkan Keputusan
                            </button>
                        </form>
                    @else
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <button type="button" class="btn btn-success" data-open-setujui
                                    data-action="{{ route('approver.izin.approve', $izin->id_izin_guru) }}"
                                    data-guru="{{ $namaGuru }}" data-periode="{{ $periode }}">
                                <i class="fa-solid fa-check"></i> Setujui
                            </button>
                            <button type="button" class="btn btn-danger" data-open-tolak
                                    data-action="{{ route('approver.izin.reject', $izin->id_izin_guru) }}"
                                    data-guru="{{ $namaGuru }}" data-periode="{{ $periode }}">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ===== Riwayat guru ===== --}}
            <div class="content-card">
                <div class="card-head">
                    <div class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color:#64748b;"></i> Riwayat Izin Guru Ini</div>
                    <span class="badge badge-gray">{{ $hariIzinTahunIni }} hari disetujui di {{ $izin->tanggal_mulai->year }}</span>
                </div>
                @forelse($riwayat as $r)
                    <div class="list-row">
                        <div>
                            <div class="list-main">{{ $r->kategori_label }}</div>
                            <div class="list-sub">{{ $r->tanggal_mulai->format('d/m/Y') }}@if(! $r->tanggal_mulai->isSameDay($r->tanggal_selesai)) - {{ $r->tanggal_selesai->format('d/m/Y') }}@endif</div>
                        </div>
                        <span class="badge {{ $r->status_approval === 'disetujui' ? 'badge-green' : ($r->status_approval === 'ditolak' ? 'badge-red' : 'badge-amber') }}">
                            {{ $r->status_approval === 'disetujui' ? 'Disetujui' : ($r->status_approval === 'ditolak' ? 'Ditolak' : 'Proses') }}
                        </span>
                    </div>
                @empty
                    <div class="empty-note">Belum ada izin sebelumnya.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection