@extends('layouts.app')

@section('title', 'Edit Jurusan')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Edit Data Jurusan</h2>
            <p>Ubah kode atau nama jurusan terpilih.</p>
        </div>
    </div>

    <div class="form-card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <span>⚠️</span>
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

        <form action="{{ route('jurusan.update', $jurusan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group col-6">
                    <label for="kode_jurusan">Kode Jurusan <span class="required">*</span></label>
                    <input type="text" name="kode_jurusan" id="kode_jurusan" class="form-control" value="{{ old('kode_jurusan', $jurusan->kode_jurusan) }}" required>
                </div>

                <div class="form-group col-6">
                    <label for="nama_jurusan">Nama Jurusan <span class="required">*</span></label>
                    <input type="text" name="nama_jurusan" id="nama_jurusan" class="form-control" value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}" required>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">🔄 Perbarui Data</button>
                <a href="{{ route('jurusan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection