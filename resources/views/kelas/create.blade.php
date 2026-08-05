@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2>Tambah Kelas</h2>
            <p>Daftarkan rombongan belajar / kelas baru.</p>
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

        <form action="{{ route('kelas.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group col-4">
                    <label for="tingkat">Tingkat Kelas <span class="required">*</span></label>
                    <select name="tingkat" id="tingkat" class="form-control" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="X" {{ old('tingkat') == 'X' ? 'selected' : '' }}>X (Sepuluh)</option>
                        <option value="XI" {{ old('tingkat') == 'XI' ? 'selected' : '' }}>XI (Sebelas)</option>
                        <option value="XII" {{ old('tingkat') == 'XII' ? 'selected' : '' }}>XII (Dua Belas)</option>
                    </select>
                </div>

                <div class="form-group col-4">
                    <label for="id_jurusan">Jurusan <span class="required">*</span></label>
                    <select name="id_jurusan" id="id_jurusan" class="form-control" required>
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($jurusan as $j)
                            <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-4">
                    <label for="rombel">Rombel (Urutan) <span class="required">*</span></label>
                    <input type="number" name="rombel" id="rombel" class="form-control" value="{{ old('rombel', 1) }}" min="1" required>
                    <span class="help-text">Nomor rombel (misal: 1 untuk X RPL 1).</span>
                </div>

                <div class="form-group col-8">
                    <label for="wali_kelas">Wali Kelas</label>
                    <input type="text" name="wali_kelas" id="wali_kelas" class="form-control" value="{{ old('wali_kelas') }}" placeholder="Nama Wali Kelas">
                </div>

                <div class="form-group col-4">
                    <label for="jumlah_siswa">Jumlah Siswa</label>
                    <input type="number" name="jumlah_siswa" id="jumlah_siswa" class="form-control" value="{{ old('jumlah_siswa', 0) }}" min="0">
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Kelas</button>
                <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection