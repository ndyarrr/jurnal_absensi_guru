@extends('layouts.approver')

@section('title', 'Persetujuan Izin')
@section('page-title', 'Persetujuan Izin Guru')
@section('page-subtitle', 'Keputusan Anda sebagai ' . $t['label'])

@section('content')
    <div class="content-card">
        <div class="filter-bar">
            <div class="filter-status-group" style="flex-wrap: wrap;">
                @php
                    $chips = [
                        'perlu'     => 'Perlu Keputusan Saya',
                        'disetujui' => 'Disetujui',
                        'ditolak'   => 'Ditolak',
                        'semua'     => 'Semua',
                    ];
                @endphp
                @foreach($chips as $key => $label)
                    <a href="{{ route('approver.izin-guru.index', ['status' => $key, 'search' => $search]) }}"
                       class="filter-chip {{ $filter === $key ? 'active' : '' }}">
                        {{ $label }} <span style="opacity:.7;">({{ $counts[$key] }})</span>
                    </a>
                @endforeach
            </div>

            <form action="{{ route('approver.izin-guru.index') }}" method="GET">
                <input type="hidden" name="status" value="{{ $filter }}">
                <div class="search-container">
                    <input type="text" name="search" class="search-input" placeholder="Cari guru / alasan..." value="{{ $search }}">
                    <i class="fa-solid fa-magnifying-glass search-icon-inside"></i>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Guru Pemohon</th>
                        <th>Jenis &amp; Waktu</th>
                        <th>Alasan</th>
                        <th>Progres</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($izinList as $idx => $izin)
                        @php
                            $periode = $izin->tanggal_mulai->format('d/m/Y') . ($izin->tanggal_mulai->isSameDay($izin->tanggal_selesai) ? '' : ' - ' . $izin->tanggal_selesai->format('d/m/Y'));
                            $sayaSetuju = $izin->{$t['status']} === 'disetujui';
                            $sayaTolak = $izin->{$t['status']} === 'ditolak';
                        @endphp
                        <tr>
                            <td>{{ $izinList->firstItem() + $idx }}</td>
                            <td>
                                <div style="font-weight:700;">{{ $izin->guru->nama_guru ?? 'Guru' }}</div>
                                <small style="color: var(--app-subtext); font-size:.75rem;">NIP: {{ $izin->guru->nip ?? '-' }}</small>
                            </td>
                            <td>
                                <div style="font-weight:700;">{{ $izin->kategori_label }}</div>
                                <small style="color:#64748b; font-size:.75rem;">{{ $periode }} &middot; {{ $izin->durasi_hari }} hari</small>
                            </td>
                            <td style="max-width: 240px;">
                                <div style="font-size:.85rem; color:#374151;">{{ \Illuminate\Support\Str::limit($izin->alasan_izin, 90) }}</div>
                            </td>
                            <td>
                                @include('partials.mini-steps', ['izin' => $izin])
                                @if($izin->status_approval === 'disetujui')
                                    <span class="badge badge-green" style="margin-top:6px;">Disetujui lengkap</span>
                                @elseif($izin->status_approval === 'ditolak')
                                    <span class="badge badge-red" style="margin-top:6px;">Ditolak</span>
                                @elseif($izin->{$t['status']} === 'pending')
                                    <span class="badge badge-amber" style="margin-top:6px;">Menunggu Anda</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex; gap:6px; flex-wrap:wrap; justify-content:flex-end;">
                                    <a href="{{ route('approver.izin-guru.show', $izin->id_izin_guru) }}" class="btn btn-light btn-sm">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                    @if(! $sayaSetuju && ! $sayaTolak)
                                        <button type="button" class="btn btn-success btn-sm" data-open-setujui
                                                data-action="{{ route('approver.izin.approve', $izin->id_izin_guru) }}"
                                                data-guru="{{ $izin->guru->nama_guru ?? 'Guru' }}"
                                                data-periode="{{ $periode }}">Setujui</button>
                                        <button type="button" class="btn btn-danger btn-sm" data-open-tolak
                                                data-action="{{ route('approver.izin.reject', $izin->id_izin_guru) }}"
                                                data-guru="{{ $izin->guru->nama_guru ?? 'Guru' }}"
                                                data-periode="{{ $periode }}">Tolak</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:40px; color: var(--app-subtext);">
                                <i class="fa-solid fa-folder-open" style="font-size:2rem; margin-bottom:8px; color:#cbd5e1; display:block;"></i>
                                Tidak ada permohonan izin pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($izinList->hasPages())
            <div class="pagination-container">{{ $izinList->links() }}</div>
        @endif
    </div>
@endsection