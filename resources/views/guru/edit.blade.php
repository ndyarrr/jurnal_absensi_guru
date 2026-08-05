@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Edit Data Guru</h2>
            <p>Perbarui informasi data guru pengajar.</p>
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

        <form action="{{ route('guru.update', $guru) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group col-6">
                    <label for="nuptk">NUPTK / NIP <span class="required">*</span></label>
                    <input type="text" name="nuptk" id="nuptk" class="form-control" value="{{ old('nuptk', $guru->nuptk) }}" required>
                </div>

                <div class="form-group col-6">
                    <label for="nama_guru">Nama Lengkap Guru <span class="required">*</span></label>
                    <input type="text" name="nama_guru" id="nama_guru" class="form-control" value="{{ old('nama_guru', $guru->nama_guru) }}" required>
                </div>

                <div class="form-group col-12">
                    <label for="no_hp">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp', $guru->no_hp) }}">
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">🔄 Perbarui Data</button>
                <a href="{{ route('guru.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
