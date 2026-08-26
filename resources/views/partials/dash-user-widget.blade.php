@php
    $profileUser = Auth::user();
@endphp

<div class="dash-user-widget-wrap" id="dashUserWidgetWrap">
    <button type="button" class="dash-user-widget" id="dashUserWidgetBtn" aria-expanded="false" aria-haspopup="true" title="Pengaturan profil">
        <div class="dash-user-meta">
            <span class="dash-user-name">{{ $profileUser->name ?? 'Administrator' }}</span>
            <span class="dash-user-role">{{ $profileUser->role_label ?? 'Admin' }}</span>
        </div>
        @if($profileUser->avatar_url)
            <img src="{{ $profileUser->avatar_url }}" alt="Foto profil" class="dash-user-avatar">
        @else
            <span class="dash-user-avatar dash-user-avatar-initial">{{ $profileUser->avatar_initial }}</span>
        @endif
        <svg class="dash-user-caret" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </button>

    <div class="dash-profile-dropdown" id="dashProfileDropdown" hidden>
        <div class="dash-profile-dropdown-head">
            <span>Pengaturan Profil</span>
        </div>

        @if(session('profile_success'))
            <div class="dash-profile-alert success">{{ session('profile_success') }}</div>
        @endif

        @if($errors->has('name') || $errors->has('avatar'))
            <div class="dash-profile-alert error">
                {{ $errors->first('name') ?: $errors->first('avatar') }}
            </div>
        @endif

        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="dash-profile-form">
            @csrf
            @method('PUT')

            <div class="dash-profile-photo-container">
                {{-- Clickable Avatar for Full View Modal --}}
                <div class="dash-profile-photo-view-wrap" id="btnOpenFullAvatar" title="Klik untuk lihat foto profil full view">
                    @if($profileUser->avatar_url)
                        <img src="{{ $profileUser->avatar_url }}" alt="Pratinjau foto" id="profileAvatarPreview" class="dash-profile-photo">
                    @else
                        <span id="profileAvatarPreview" class="dash-profile-photo dash-user-avatar-initial">{{ $profileUser->avatar_initial }}</span>
                    @endif
                    <span class="dash-profile-photo-overlay">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <span>Lihat Foto</span>
                    </span>
                </div>

                {{-- Dedicated Edit/Upload Photo Button --}}
                <div class="dash-profile-photo-actions">
                    <label class="dash-profile-edit-badge" for="profileAvatarInput" title="Ubah atau unggah foto profil baru">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Ubah Foto</span>
                    </label>
                    <input type="file" name="avatar" id="profileAvatarInput" accept="image/jpeg,image/png,image/webp" hidden>
                </div>
            </div>

            <p class="dash-profile-hint">Klik foto untuk perbesar full view, atau tombol <strong>Ubah Foto</strong> di samping untuk memilih foto baru.</p>

            @if($profileUser->avatar)
                <label class="dash-profile-remove">
                    <input type="checkbox" name="remove_avatar" value="1">
                    Hapus foto profil
                </label>
            @endif

            <div class="form-field-group" style="margin-top: 4px;">
                <label for="profile_name">Nama tampilan</label>
                <input type="text" name="name" id="profile_name" class="form-field-input" value="{{ old('name', $profileUser->name) }}" required maxlength="255" autocomplete="name">
            </div>

            <div class="dash-profile-readonly">
                <span>{{ $profileUser->email }}</span>
                <small>{{ $profileUser->role_label }}</small>
            </div>

            <button type="submit" class="btn-modal-submit dash-profile-save">Simpan Profil</button>
        </form>

        <form action="{{ route('logout') }}" method="POST" class="dash-profile-logout-form">
            @csrf
            <button type="submit" class="dash-profile-logout-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Keluar dari akun
            </button>
        </form>
    </div>
</div>

<!-- Pure Image Lightbox / Full View Avatar Modal Popup -->
<div class="modal-overlay" id="profileAvatarModal" style="display: none; align-items: center !important; justify-content: center !important; z-index: 99999; background-color: rgba(1, 1, 1, 0.41); backdrop-filter: blur(8px);">
    <div style="position: relative; max-width: 90vw; max-height: 90vh; display: flex; flex-direction: column; align-items: center; justify-content: center; animation: zoomIn 0.2s cubic-bezier(0.16, 1, 0.3, 1); margin: auto;">
        <button type="button" id="closeProfileAvatarModal" style="position: absolute; top: -46px; right: 0; background: rgba(255, 255, 255, 0.2); color: #ffffff; border: none; font-size: 1.4rem; cursor: pointer; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: background 0.2s ease;" title="Tutup (Esc)">&times;</button>
        
        @if($profileUser->avatar_url)
            <img src="{{ $profileUser->avatar_url }}" alt="{{ $profileUser->name }}" id="fullViewAvatarImg" style="max-width: 85vw; max-height: 75vh; width: 400px; height: 400px; border-radius: 24px; object-fit: cover; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); border: 2px solid rgba(255, 255, 255, 0.2);">
        @else
            <div id="fullViewAvatarImg" class="dash-user-avatar-initial" style="width: 280px; height: 280px; max-width: 85vw; max-height: 75vh; border-radius: 24px; font-size: 6rem; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1e2538, #3b82f6); color: #ffffff; border: 2px solid rgba(255, 255, 255, 0.2); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); font-weight: 800;">
                {{ $profileUser->avatar_initial }}
            </div>
        @endif
    </div>
</div>

@once
    <script src="{{ asset('js/profile-dropdown.js') }}"></script>
@endonce
