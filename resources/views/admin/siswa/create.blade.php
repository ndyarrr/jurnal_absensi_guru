@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2>Tambah Siswa</h2>
            <p>Masukkan data siswa baru ke dalam sistem absensi.</p>
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

        <form action="{{ route('siswa.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group col-6">
                    <label for="nisn">NISN / NIS <span class="required">*</span></label>
                    <input type="text" name="nisn" id="nisn" class="form-control" value="{{ old('nisn') }}" placeholder="Nomor Induk Siswa" required>
                </div>

                <div class="form-group col-6">
                    <label for="nama_siswa">Nama Lengkap Siswa <span class="required">*</span></label>
                    <input type="text" name="nama_siswa" id="nama_siswa" class="form-control" value="{{ old('nama_siswa') }}" placeholder="Nama siswa" required>
                </div>

                <div class="form-group col-12">
                    <label for="id_kelas">Kelas <span class="required">*</span></label>
                    <select name="id_kelas" id="id_kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                Kelas {{ $k->tingkat }} {{ $k->jurusan->kode_jurusan ?? '' }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Siswa</button>
                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection