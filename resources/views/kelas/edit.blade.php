@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Edit Data Kelas</h2>
            <p>Perbarui informasi detail kelas sekolah.</p>
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

        <form action="{{ route('kelas.update', $kelas) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group col-4">
                    <label for="tingkat">Tingkat Kelas <span class="required">*</span></label>
                    <select name="tingkat" id="tingkat" class="form-control" required>
                        <option value="X" {{ old('tingkat', $kelas->tingkat) == 'X' ? 'selected' : '' }}>X (Sepuluh)</option>
                        <option value="XI" {{ old('tingkat', $kelas->tingkat) == 'XI' ? 'selected' : '' }}>XI (Sebelas)</option>
                        <option value="XII" {{ old('tingkat', $kelas->tingkat) == 'XII' ? 'selected' : '' }}>XII (Dua Belas)</option>
                    </select>
                </div>

                <div class="form-group col-4">
                    <label for="id_jurusan">Jurusan <span class="required">*</span></label>
                    <select name="id_jurusan" id="id_jurusan" class="form-control" required>
                        @foreach($jurusan as $j)
                            <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan', $kelas->id_jurusan) == $j->id_jurusan ? 'selected' : '' }}>
                                {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-4">
                    <label for="rombel">Rombel (Urutan) <span class="required">*</span></label>
                    <input type="number" name="rombel" id="rombel" class="form-control" value="{{ old('rombel', $kelas->rombel) }}" min="1" required>
                </div>

                <div class="form-group col-8">
                    <label for="wali_kelas">Wali Kelas</label>
                    <input type="text" name="wali_kelas" id="wali_kelas" class="form-control" value="{{ old('wali_kelas', $kelas->wali_kelas) }}">
                </div>

                <div class="form-group col-4">
                    <label for="jumlah_siswa">Jumlah Siswa</label>
                    <input type="number" name="jumlah_siswa" id="jumlah_siswa" class="form-control" value="{{ old('jumlah_siswa', $kelas->jumlah_siswa) }}" min="0">
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">🔄 Perbarui Data</button>
                <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection