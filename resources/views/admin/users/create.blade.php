@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2>Tambah Pengguna Baru</h2>
            <p>Buat akun pengguna baru dan tentukan role hak aksesnya.</p>
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
                    <select name="role" id="role" class="form-control" required onchange="toggleGuruField(this.value)">
                        <option value="">-- Pilih Role Pengguna --</option>
                        <option value="guru_mengajar" {{ old('role') == 'guru_mengajar' ? 'selected' : '' }}>👨‍🏫 Guru Mengajar</option>
                        <option value="wali_kelas" {{ old('role') == 'wali_kelas' ? 'selected' : '' }}>🏫 Wali Kelas</option>
                        <option value="guru_piket" {{ old('role') == 'guru_piket' ? 'selected' : '' }}>📋 Guru Piket</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <span class="help-text">Tentukan peran akses pengguna di dalam aplikasi.</span>
                </div>

                <div class="form-group col-12" id="guruFieldWrapper" style="display: none;">
                    <label for="id_guru">Tautkan ke Profil Guru</label>
                    <select name="id_guru" id="id_guru" class="form-control">
                        <option value="">-- Pilih Guru (Opsional) --</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }} ({{ $g->nuptk }})
                            </option>
                        @endforeach
                    </select>
                    <span class="help-text">Hubungkan akun ini dengan data profil guru yang sudah ada di sistem.</span>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Pengguna</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleGuruField(role) {
        const wrapper = document.getElementById('guruFieldWrapper');
        const select = document.getElementById('id_guru');
        if (role && role !== 'admin') {
            wrapper.style.display = 'block';
        } else {
            wrapper.style.display = 'none';
            select.value = '';
        }
    }
    // Jalankan saat halaman load (untuk validasi error old value)
    toggleGuruField(document.getElementById('role').value);
</script>
@endsection
