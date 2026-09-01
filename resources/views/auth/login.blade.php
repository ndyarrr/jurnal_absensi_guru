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

    <div class="login-split-layout">
        <!-- Left empty column (reserves space for laptop illustration in background) -->
        <div class="login-hero-space"></div>

        <!-- Right column (centers form in cream space) -->
        <div class="login-form-area">
            <div class="login-box">

                <!-- Logo Section -->
                <div class="login-logo-container">
                    <img src="{{ asset('assets/image/logo/logo-only.svg') }}" alt="Logo" class="login-brand-icon">
                </div>

                <!-- Header Title -->
                <div class="login-header">
                    <h1 class="login-title">Selamat Datang</h1>
                    <p class="login-subtitle">Masuk untuk memulai</p>
                </div>

                <!-- Flash Alerts -->
                @if(session('success'))
                    <div class="login-alert login-alert-success" style="transition: opacity 0.5s;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="login-alert login-alert-danger" style="transition: opacity 0.5s;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('login') }}" method="POST" class="login-form">
                    @csrf

                    <!-- Username Input -->
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-input" value="{{ old('username') }}" required autofocus>
                    </div>

                    <!-- Password Input -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-password-wrapper">
                            <input type="password" name="password" id="password" class="form-input" required>
                            <button type="button" class="toggle-pwd-btn" id="togglePasswordBtn" title="Intip Kata Sandi">
                                <svg id="eyeIcon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Role Input / Select -->
                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <div class="select-wrapper">
                            <select name="role" id="role" class="form-select">
                                <option value="" selected disabled>Pilih Role</option>
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

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">
                        Masuk
                    </button>
                </form>

                <!-- Minimal Floating/Compact Demo Accounts Pick -->
                <div class="demo-accounts-compact">
                    <button type="button" class="demo-toggle-btn" id="demoToggleBtn" onclick="toggleDemoMenu()">
                        <span>⚡ Akun Demo Quick Pick</span>
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="demo-tags-dropdown" id="demoDropdown">
                        <button type="button" class="demo-tag" onclick="fillQuickAccount('Administrator', 'password', 'super_admin')">Admin Super</button>
                        <button type="button" class="demo-tag" onclick="fillQuickAccount('Trisno Wibowo (Guru)', 'password', 'guru_mengajar')">Guru Mengajar</button>
                        <button type="button" class="demo-tag" onclick="fillQuickAccount('Kurnila (Wali Kelas)', 'password', 'wali_kelas')">Wali Kelas</button>
                        <button type="button" class="demo-tag" onclick="fillQuickAccount('Budi Santoso (Guru Piket)', 'password', 'guru_piket')">Guru Piket</button>
                        <button type="button" class="demo-tag" onclick="fillQuickAccount('Kepala Sekolah', 'password', 'kepala_sekolah')">Kepsek</button>
                        <button type="button" class="demo-tag" onclick="fillQuickAccount('Waka SDM', 'password', 'waka_sdm')">Waka SDM</button>
                        <button type="button" class="demo-tag" onclick="fillQuickAccount('Satpam Gerbang', 'password', 'satpam')">Satpam</button>
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

        if (toggleBtn && passwordInput && eyeIcon) {
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    `;
                }
            });
        }

        function fillQuickAccount(username, pass, role) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = pass;
            if (role) {
                document.getElementById('role').value = role;
            }
        }

        function toggleDemoMenu() {
            const dropdown = document.getElementById('demoDropdown');
            dropdown.classList.toggle('show');
        }

        function autoDismissAlerts() {
            var alerts = document.querySelectorAll('.login-alert');
            if (alerts.length === 0) return;
            setTimeout(function () {
                alerts.forEach(function (alert) {
                    alert.style.opacity = '0';
                    setTimeout(function () { alert.remove(); }, 500);
                });
            }, 3000);
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', autoDismissAlerts);
    </script>
</body>
</html>
