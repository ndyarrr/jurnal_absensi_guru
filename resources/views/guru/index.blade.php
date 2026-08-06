@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2> Data Guru</h2>
            <p>Daftar seluruh guru pengajar dan tenaga pendidik.</p>
        </div>
        <a href="{{ route('guru.create') }}" class="btn btn-primary">
            <span><i class="fa-solid fa-plus"></i></span> Tambah Guru Baru
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">NUPTK / NIP</th>
                        <th style="width: 25%;">Nama Guru</th>
                        <th style="width: 25%;">Mapel Diampu</th>
                        <th style="width: 18%;">No. Telepon / WA</th>
                        <th style="width: 12%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru as $g)
                        <tr>
                            <td>
                                <span class="guru-nuptk-pill">{{ $g->nuptk ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="guru-name">{{ $g->nama_guru }}</span>
                            </td>
                            <td>
                                @forelse($g->mapel as $mp)
                                    <span style="display: inline-block; background: rgba(79, 70, 229, 0.1); color: #6366f1; padding: 2px 8px; border-radius: 4px; font-size: 0.82rem; font-weight: 500; margin: 2px 2px 2px 0; border: 1px solid rgba(79, 70, 229, 0.2);">
                                        {{ $mp->nama_mapel }}
                                    </span>
                                @empty
                                    <span style="color: var(--text-light); font-size: 0.85rem;">-</span>
                                @endforelse
                            </td>
                            <td>
                                @if($g->no_hp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $g->no_hp) }}" target="_blank" class="guru-hp-link">
                                       {{ $g->no_hp }}
                                    </a>
                                @else
                                    <span style="color: var(--text-light);">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="{{ route('guru.show', $g) }}" class="btn btn-sm btn-action-show" title="Detail"> Detail</a>
                                    <a href="{{ route('guru.edit', $g) }}" class="btn btn-sm btn-action-edit" title="Edit"> Edit</a>
                                    <form action="{{ route('guru.destroy', $g) }}" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Yakin hapus data guru ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-action-delete" title="Hapus">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon"></div>
                                    <h4>Belum Ada Data Guru</h4>
                                    <p>Silakan klik tombol <strong>+ Tambah Guru Baru</strong> untuk memasukkan data guru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
