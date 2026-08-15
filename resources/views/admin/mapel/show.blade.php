@extends('layouts.app')

@section('title', 'Detail Mata Pelajaran')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2> Detail Mata Pelajaran</h2>
            <p>Rincian informasi mata pelajaran.</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('mapel.edit', $mapel) }}" class="btn btn-action-edit"> Edit Data</a>
            <a href="{{ route('mapel.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <tbody>
                    <tr>
                        <th style="width: 30%;">ID Mapel</th>
                        <td><span class="badge-chip badge-neutral">#{{ $mapel->id_mapel }}</span></td>
                    </tr>
                    <tr>
                        <th>Nama Mata Pelajaran</th>
                        <td><strong style="font-size: 1.05rem;">{{ $mapel->nama_mapel }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection