@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2>👤 Tambah Pengguna Baru</h2>
            <p>Buat akun pengguna baru dan tentukan role hak aksesnya.</p>
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

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group col-6">
                    <label for="name">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Nama Pengguna" required>
                </div>

                <div class="form-group col-6">
                    <label for="email">Alamat Email <span class="required">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="contoh@gmail.com" required>
                </div>

                <div class="form-group col-6">
                    <label for="password">Kata Sandi (Password) <span class="required">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="form-group col-6">
                    <label for="role">Role / Hak Akses <span class="required">*</span></label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="">-- Pilih Role Pengguna --</option>
                        <option value="guru_mengajar" {{ old('role') == 'guru_mengajar' ? 'selected' : '' }}>👨‍🏫 Guru Mengajar</option>
                        <option value="wali_kelas" {{ old('role') == 'wali_kelas' ? 'selected' : '' }}>🏫 Wali Kelas</option>
                        <option value="guru_piket" {{ old('role') == 'guru_piket' ? 'selected' : '' }}>📋 Guru Piket</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>⚡ Admin</option>
                    </select>
                    <span class="help-text">Tentukan peran akses pengguna di dalam aplikasi.</span>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Pengguna</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
