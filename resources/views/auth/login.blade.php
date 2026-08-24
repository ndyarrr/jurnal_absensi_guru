<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Jurnal & Absensi Guru</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Login Module CSS -->
    <link rel="stylesheet" href="{{ asset('css/modules/login.css') }}">
</head>
<body class="login-page-body">

    <!-- Split Screen Container -->
    <div class="login-split-container">

        <!-- Left Hero Area (Tagline & Feature Cards) -->
        <div class="login-left-hero">
            <div class="login-brand-header">
                <img src="{{ asset('assets/image/logo/logo-brand.svg') }}" alt="Jurnal & Absensi Guru Logo" class="login-brand-logo-img">
            </div>

            <div class="login-tagline-box">
                <h1 class="login-tagline-title">
                    Digitalisasi Jurnal, Optimalisasi Pembelajaran.
                </h1>
                <p class="login-tagline-subtitle">
                    Sistem Pengelolaan Jurnal Mengajar dan Absensi Guru Terintegrasi untuk Proses Pembelajaran Efisien, Terdokumentasi, dan Berkualitas.
                </p>

                <!-- Feature Grid -->
                <div class="hero-feature-grid">
                    <div class="hero-feature-item">
                        <div class="hero-feature-icon">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div class="hero-feature-text">Realtime Polling</div>
                    </div>
                    <div class="hero-feature-item">
                        <div class="hero-feature-icon">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        <div class="hero-feature-text">Ekspor Rekap CSV</div>
                    </div>
                    <div class="hero-feature-item">
                        <div class="hero-feature-icon">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                        </div>
                        <div class="hero-feature-text">Responsiwitas Mobile</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section (Login Card) -->
        <div class="login-right-section">
            <div class="login-card-mockup">

                <!-- Header Title -->
                <div class="mockup-header">
                    <h2 class="mockup-title">Selamat Datang</h2>
                    <p class="mockup-subtitle">Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <!-- Flash Alerts -->
                @if(session('success'))
                    <div class="mockup-alert mockup-alert-success">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mockup-alert mockup-alert-danger">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="mockup-form">
                    @csrf

                    <!-- Username Input -->
                    <div class="form-group-mockup">
                        <label for="username">Username</label>
                        <div class="input-wrapper-relative">
                            <span class="input-icon-left">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            </span>
                            <input type="text" name="username" id="username" class="input-mockup" value="{{ old('username') }}" placeholder="Masukkan nama pengguna" required autofocus>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="form-group-mockup">
                        <label for="password">Password</label>
                        <div class="input-wrapper-relative">
                            <span class="input-icon-left">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            </span>
                            <input type="password" name="password" id="password" class="input-mockup" placeholder="Masukkan kata sandi" required>
                            <button type="button" class="toggle-pwd-icon" id="togglePasswordBtn" title="Intip Kata Sandi">
                                <svg id="eyeIcon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Role Select -->
                    <div class="form-group-mockup">
                        <label for="role">Hak Akses (Role)</label>
                        <div class="input-wrapper-relative">
                            <span class="input-icon-left">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            </span>
                            <select name="role" id="role" class="input-mockup select-mockup">
                                <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Admin Super</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin Biasa</option>
                                <option value="guru_mengajar" {{ old('role') == 'guru_mengajar' ? 'selected' : '' }}>Guru Mengajar</option>
                                <option value="wali_kelas" {{ old('role') == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                                <option value="guru_piket" {{ old('role') == 'guru_piket' ? 'selected' : '' }}>Guru Piket</option>
                                <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="waka" {{ old('role') == 'waka' ? 'selected' : '' }}>Waka</option>
                                <option value="waka_sdm" {{ old('role') == 'waka_sdm' ? 'selected' : '' }}>Waka SDM</option>
                                <option value="satpam" {{ old('role') == 'satpam' ? 'selected' : '' }}>Satpam</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sign In Button -->
                    <button type="submit" class="btn-mockup-signin">
                        <span>Masuk ke Sistem</span>
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </form>

                <!-- Quick Demo Picker -->
                <div class="mockup-demo-bar">
                    <div class="mockup-demo-title">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18h6M10 22h4M15.09 14A6 6 0 0 0 18 9 6 6 0 0 0 6 9a6 6 0 0 0 2.91 5z"></path></svg>
                        <span>Pilih Akun Demo Cepat</span>
                    </div>
                    <div class="mockup-demo-tags">
                        <button type="button" class="mockup-demo-tag" onclick="fillQuickAccount('Administrator', 'password', 'super_admin')">Admin Super</button>
                        <button type="button" class="mockup-demo-tag" onclick="fillQuickAccount('Trisno Wibowo (Guru)', 'password', 'guru_mengajar')">Guru Mengajar</button>
                        <button type="button" class="mockup-demo-tag" onclick="fillQuickAccount('Kurnila (Wali Kelas)', 'password', 'wali_kelas')">Wali Kelas</button>
                        <button type="button" class="mockup-demo-tag" onclick="fillQuickAccount('Budi Santoso (Guru Piket)', 'password', 'guru_piket')">Guru Piket</button>
                        <button type="button" class="mockup-demo-tag" onclick="fillQuickAccount('Kepala Sekolah', 'password', 'kepala_sekolah')">Kepsek</button>
                        <button type="button" class="mockup-demo-tag" onclick="fillQuickAccount('Waka SDM', 'password', 'waka_sdm')">Waka SDM</button>
                        <button type="button" class="mockup-demo-tag" onclick="fillQuickAccount('Satpam Gerbang', 'password', 'satpam')">Satpam</button>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Toggle Password Visibility & Auto-fill Script -->
    <script>
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            if (isPassword) {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.007 10.007 0 013.98.937c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"></path>
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        });

        function fillQuickAccount(username, pass, role) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = pass;
            if (role) {
                document.getElementById('role').value = role;
            }
        }
    </script>
</body>
</html>
