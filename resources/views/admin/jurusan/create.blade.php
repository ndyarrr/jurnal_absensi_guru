@extends('layouts.app')

@section('title', 'Tambah Jurusan')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2>🎓 Tambah Jurusan</h2>
            <p>Tambah program keahlian / jurusan sekolah baru.</p>
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

        <form action="{{ route('jurusan.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group col-6">
                    <label for="kode_jurusan">Kode Jurusan <span class="required">*</span></label>
                    <input type="text" name="kode_jurusan" id="kode_jurusan" class="form-control" value="{{ old('kode_jurusan') }}" placeholder="Contoh: RPL, TKJ, AKL" required>
                    <span class="help-text">Singkatan atau kode unik jurusan.</span>
                </div>

                <div class="form-group col-6">
                    <label for="nama_jurusan">Nama Jurusan <span class="required">*</span></label>
                    <input type="text" name="nama_jurusan" id="nama_jurusan" class="form-control" value="{{ old('nama_jurusan') }}" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Jurusan</button>
                <a href="{{ route('jurusan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection