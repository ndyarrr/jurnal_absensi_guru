@extends('layouts.app')

@section('title', 'Detail Guru')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2> Detail Guru</h2>
            <p>Informasi profil pengajar.</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('guru.edit', $guru) }}" class="btn btn-action-edit"> Edit Data</a>
            <a href="{{ route('guru.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <tbody>
                    <tr>
                        <th style="width: 30%;">NUPTK / NIP</th>
                        <td><span class="guru-nuptk-pill">{{ $guru->nuptk ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td><strong style="font-size: 1.05rem;">{{ $guru->nama_guru }}</strong></td>
                    </tr>
                    <tr>
                        <th>No. Telepon / WA</th>
                        <td>
                            @if($guru->no_hp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guru->no_hp) }}" target="_blank" class="guru-hp-link">
                                   {{ $guru->no_hp }}
                                </a>
                            @else
                                <span style="color: var(--text-light);">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Mata Pelajaran Diampu</th>
                        <td>
                            @forelse($guru->mapel as $mp)
                                <span style="display: inline-block; background: rgba(79, 70, 229, 0.1); color: #6366f1; padding: 4px 10px; border-radius: 6px; font-size: 0.88rem; font-weight: 500; margin: 2px 4px 2px 0; border: 1px solid rgba(79, 70, 229, 0.2);">
                                    {{ $mp->nama_mapel }}
                                </span>
                            @empty
                                <span style="color: var(--text-light);">-</span>
                            @endforelse
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
