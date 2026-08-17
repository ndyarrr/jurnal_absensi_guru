@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Tambah Mata Pelajaran</h2>
            <p>Daftarkan mata pelajaran baru ke dalam kurikulum.</p>
        </div>
    </div>

    <div class="form-card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <span><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg></span>
                <div>
                    <strong>Terjadi kesalahan input:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('mapel.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group col-12">
                    <label for="nama_mapel">Nama Mata Pelajaran <span class="required">*</span></label>
                    <input type="text" name="nama_mapel" id="nama_mapel" class="form-control" value="{{ old('nama_mapel') }}" placeholder="Contoh: Pemrograman Web dan Perangkat Bergerak" required>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Mapel</button>
                <a href="{{ route('mapel.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
