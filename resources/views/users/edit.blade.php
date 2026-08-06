@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2>✏️ Edit Data Pengguna</h2>
            <p>Perbarui informasi akun atau ubah role hak akses pengguna.</p>
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

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group col-6">
                    <label for="name">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group col-6">
                    <label for="email">Alamat Email <span class="required">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group col-6">
                    <label for="password">Kata Sandi (Opsional)</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ganti password">
                </div>

                <div class="form-group col-6">
                    <label for="role">Role / Hak Akses <span class="required">*</span></label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="guru_mengajar" {{ old('role', $user->role) == 'guru_mengajar' ? 'selected' : '' }}>👨‍🏫 Guru Mengajar</option>
                        <option value="wali_kelas" {{ old('role', $user->role) == 'wali_kelas' ? 'selected' : '' }}>🏫 Wali Kelas</option>
                        <option value="guru_piket" {{ old('role', $user->role) == 'guru_piket' ? 'selected' : '' }}>📋 Guru Piket</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>⚡ Admin</option>
                    </select>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">🔄 Perbarui Data</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
