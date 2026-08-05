@extends('layouts.app')

@section('title', 'Detail Jurusan')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2>🎓 Detail Jurusan</h2>
            <p>Informasi rincian program keahlian.</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('jurusan.edit', $jurusan) }}" class="btn btn-action-edit"> Edit Data</a>
            <a href="{{ route('jurusan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <tbody>
                    <tr>
                        <th style="width: 30%;">Kode Jurusan</th>
                        <td><span class="jurusan-code-badge">{{ $jurusan->kode_jurusan }}</span></td>
                    </tr>
                    <tr>
                        <th>Nama Jurusan</th>
                        <td><strong style="font-size: 1.05rem;">{{ $jurusan->nama_jurusan }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection