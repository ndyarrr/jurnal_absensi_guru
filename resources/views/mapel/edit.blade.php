@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Edit Mata Pelajaran</h2>
            <p>Ubah nama mata pelajaran terpilih.</p>
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

        <form action="{{ route('mapel.update', $mapel) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group col-12">
                    <label for="nama_mapel">Nama Mata Pelajaran <span class="required">*</span></label>
                    <input type="text" name="nama_mapel" id="nama_mapel" class="form-control" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">🔄 Perbarui Data</button>
                <a href="{{ route('mapel.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection