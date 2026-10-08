@extends('layouts.approver')

@section('title', 'Izin Siswa')
@section('page-title', 'Persetujuan Izin Siswa')
@section('page-subtitle', 'Surat dispensasi siswa - keputusan Anda sebagai ' . $t['label'])

@section('content')
    <div class="content-card">
        <div class="filter-bar">
            <div class="filter-status-group" style="flex-wrap: wrap;">
                @php
                    $chips = ['perlu' => 'Perlu Keputusan Saya', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'semua' => 'Semua'];
                @endphp
                @foreach($chips as $key => $label)
                    <a href="{{ route('approver.izin-siswa.index', ['status' => $key, 'search' => $search]) }}"
                       class="filter-chip {{ $filter === $key ? 'active' : '' }}">
                        {{ $label }} <span style="opacity:.7;">({{ $counts[$key] }})</span>
                    </a>
                @endforeach
            </div>

            <form action="{{ route('approver.izin-siswa.index') }}" method="GET">
                <input type="hidden" name="status" value="{{ $filter }}">
                <div class="search-container">
                    <input type="text" name="search" class="search-input" placeholder="Cari siswa / kegiatan..." value="{{ $search }}">
                    <i class="fa-solid fa-magnifying-glass search-icon-inside"></i>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Kegiatan &amp; Waktu</th>
                        <th>Alasan</th>
                        <th>Progres</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dispenList as $idx => $dispen)
                        @php
                            $sayaSetuju = $dispen->{$t['status']} === 'disetujui';
                            $sayaTolak = $dispen->{$t['status']} === 'ditolak';
                        @endphp
                        <tr>
                            <td>{{ $dispenList->firstItem() + $idx }}</td>
                            <td>
                                <div style="font-weight:700;">{{ $dispen->ringkas_siswa }}</div>
                                <small style="color: var(--app-subtext); font-size:.75rem;">{{ $dispen->kelas_label }}</small>
                            </td>
                            <td>
                                <div style="font-weight:700;">{{ $dispen->nama_kegiatan ?? '-' }}</div>
                                <small style="color:#64748b; font-size:.75rem;">{{ $dispen->periode_label }} &middot; {{ $dispen->jam_label }}</small>
                            </td>
                            <td style="max-width: 240px;">
                                <div style="font-size:.85rem; color:#374151;">{{ \Illuminate\Support\Str::limit($dispen->alasan_dispensasi, 90) }}</div>
                            </td>
                            <td>
                                @include('partials.mini-steps-dispen', ['dispen' => $dispen])
                                @if($dispen->status_approval === 'disetujui')
                                    <span class="badge badge-green" style="margin-top:6px;">Disetujui lengkap</span>
                                @elseif($dispen->status_approval === 'ditolak')
                                    <span class="badge badge-red" style="margin-top:6px;">Ditolak</span>
                                @elseif($dispen->{$t['status']} === 'pending')
                                    <span class="badge badge-amber" style="margin-top:6px;">Menunggu Anda</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex; gap:6px; flex-wrap:wrap; justify-content:flex-end;">
                                    <a href="{{ route('approver.izin-siswa.show', $dispen->id_dispen) }}" class="btn btn-light btn-sm">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                    @if(! $sayaSetuju && ! $sayaTolak)
                                        <button type="button" class="btn btn-success btn-sm" data-open-setujui
                                                data-jenis="dispensasi siswa" data-jenis-title="Izin Siswa"
                                                data-action="{{ route('approver.dispensasi.approve', $dispen->id_dispen) }}"
                                                data-guru="{{ $dispen->ringkas_siswa }}" data-periode="{{ $dispen->periode_label }}">Setujui</button>
                                        <button type="button" class="btn btn-danger btn-sm" data-open-tolak
                                                data-jenis="dispensasi siswa" data-jenis-title="Izin Siswa"
                                                data-action="{{ route('approver.dispensasi.reject', $dispen->id_dispen) }}"
                                                data-guru="{{ $dispen->ringkas_siswa }}" data-periode="{{ $dispen->periode_label }}">Tolak</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:40px; color: var(--app-subtext);">
                                <i class="fa-solid fa-folder-open" style="font-size:2rem; margin-bottom:8px; color:#cbd5e1; display:block;"></i>
                                Tidak ada izin siswa pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dispenList->hasPages())
            <div class="pagination-container">{{ $dispenList->links() }}</div>
        @endif
    </div>
@endsection