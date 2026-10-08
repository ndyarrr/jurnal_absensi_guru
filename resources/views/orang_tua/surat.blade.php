@extends('layouts.orang_tua')

@section('title', 'Izin & Surat')
@section('page-title', 'Izin & Surat')
@section('page-subtitle', 'Pengajuan izin, dispensasi, dan surat izin masuk kelas')

@section('content')
<div class="ot-page">
@if(! $anak)
    @include('orang_tua._kosong')
@else
    @include('orang_tua._anak', ['routeName' => 'orang-tua.surat'])

    <div class="ot-card">
        <div class="ot-card-header">
            <h3 class="ot-card-title"><i class="fa-solid fa-file-circle-check" style="color: var(--dash-navy);"></i> Pengajuan Izin / Sakit</h3>
        </div>
        <div class="ot-table-wrap">
            <table class="ot-table">
                <thead><tr><th>Jenis</th><th>Tanggal</th><th>Alasan</th><th>Diajukan</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($permohonan as $p)
                    <tr>
                        <td>{{ $p['jenis'] }}</td>
                        <td>{{ $p['tanggal'] }}</td>
                        <td>{{ $p['alasan'] }}</td>
                        <td>{{ $p['diajukan'] }}</td>
                        <td><span class="ot-badge {{ $p['status']['key'] }}">{{ $p['status']['label'] }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="ot-empty" style="padding:20px;">Belum ada pengajuan izin.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="ot-card">
        <div class="ot-card-header">
            <h3 class="ot-card-title"><i class="fa-solid fa-award" style="color: var(--dash-navy);"></i> Dispensasi Kegiatan</h3>
        </div>
        <div class="ot-table-wrap">
            <table class="ot-table">
                <thead><tr><th>Kegiatan</th><th>Tanggal</th><th>Jam</th><th>Alasan</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($dispensasi as $d)
                    <tr>
                        <td>{{ $d['kegiatan'] }}@if($d['lokasi'])<div class="ot-row-meta">{{ $d['lokasi'] }}</div>@endif</td>
                        <td>{{ $d['tanggal'] }}</td>
                        <td>{{ $d['jam'] ?: '-' }}</td>
                        <td>{{ $d['alasan'] ?: '-' }}</td>
                        <td><span class="ot-badge {{ $d['status']['key'] }}">{{ $d['status']['label'] }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="ot-empty" style="padding:20px;">Belum ada dispensasi.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="ot-card">
        <div class="ot-card-header">
            <div>
                <h3 class="ot-card-title"><i class="fa-solid fa-door-open" style="color: var(--dash-navy);"></i> Surat Izin Masuk Kelas</h3>
                <div class="ot-card-subtitle">Diterbitkan guru piket saat siswa terlambat atau meninggalkan kelas</div>
            </div>
        </div>
        <div class="ot-table-wrap">
            <table class="ot-table">
                <thead><tr><th>Tanggal</th><th>No. Surat</th><th>Jam Pelajaran</th><th>Alasan</th></tr></thead>
                <tbody>
                @forelse($izinMasuk as $s)
                    <tr>
                        <td>{{ $s['tanggal'] }}</td>
                        <td>{{ $s['nomor'] }}</td>
                        <td>{{ $s['jam_ke'] }}</td>
                        <td>{{ $s['alasan'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="ot-empty" style="padding:20px;">Belum ada surat izin masuk kelas.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
</div>
@endsection