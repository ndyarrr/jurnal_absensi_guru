@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Tambah Guru</h2>
            <p>Masukkan data pengajar baru ke dalam sistem.</p>
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

        <form action="{{ route('guru.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group col-6">
                    <label for="nuptk">NUPTK / NIP <span class="required">*</span></label>
                    <input type="text" name="nuptk" id="nuptk" class="form-control" value="{{ old('nuptk') }}" placeholder="Contoh: 1234567890" required>
                </div>

                <div class="form-group col-6">
                    <label for="nama_guru">Nama Lengkap Guru <span class="required">*</span></label>
                    <input type="text" name="nama_guru" id="nama_guru" class="form-control" value="{{ old('nama_guru') }}" placeholder="Nama beserta gelar" required>
                </div>

                <div class="form-group col-12">
                    <label for="no_hp">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789">
                    <span class="help-text">Nomor kontak aktif yang dapat dihubungi.</span>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Guru</button>
                <a href="{{ route('guru.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
