@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="card-panel">
    <div class="card-header-bar">
        <div class="card-header-title">
            <h2>👤 Manajemen Pengguna</h2>
            <p>Kelola daftar pengguna dan hak akses role sistem.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <span>➕</span> Tambah Pengguna Baru
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Nama Pengguna</th>
                        <th style="width: 30%;">Email</th>
                        <th style="width: 25%;">Role Hak Akses</th>
                        <th style="width: 15%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <strong style="font-size: 0.95rem; color: var(--text-main);">{{ $user->name }}</strong>
                            </td>
                            <td>
                                <span class="user-email-text">✉️ {{ $user->email }}</span>
                            </td>
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
                            <td style="text-align: center;">
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-action-show" title="Detail">👁️ Detail</a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-action-edit" title="Edit">✏️ Edit</a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Yakin hapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-action-delete" title="Hapus">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-state-icon">👤</div>
                                    <h4>Belum Ada Data Pengguna</h4>
                                    <p>Silakan klik tombol <strong>+ Tambah Pengguna Baru</strong> untuk membuat akun.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
