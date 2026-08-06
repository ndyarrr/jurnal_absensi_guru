@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2>👤 Detail Pengguna</h2>
            <p>Informasi profil akun pengguna.</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-action-edit">✏️ Edit Data</a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <tbody>
                    <tr>
                        <th style="width: 30%;">ID Pengguna</th>
                        <td><span class="badge-chip badge-neutral">#{{ $user->id }}</span></td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td><strong style="font-size: 1.05rem;">{{ $user->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Alamat Email</th>
                        <td><span class="user-email-text">✉️ {{ $user->email }}</span></td>
                    </tr>
                    <tr>
                        <th>Role / Hak Akses</th>
                        <td>
                            @if($user->role === 'admin')
                                <span class="role-badge role-admin">⚡ Admin</span>
                            @elseif($user->role === 'guru_mengajar')
                                <span class="role-badge role-guru_mengajar">👨‍🏫 Guru Mengajar</span>
                            @elseif($user->role === 'wali_kelas')
                                <span class="role-badge role-wali_kelas">🏫 Wali Kelas</span>
                            @elseif($user->role === 'guru_piket')
                                <span class="role-badge role-guru_piket">📋 Guru Piket</span>
                            @else
                                <span class="role-badge role-guru_mengajar">{{ $user->role_label }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Terdaftar</th>
                        <td>{{ $user->created_at ? $user->created_at->translatedFormat('d F Y, H:i') : '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
