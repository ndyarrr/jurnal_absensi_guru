<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Jurnal Mengajar</title>

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

        <!-- Left Hero Area (Tagline & Illustration) -->
        <div class="login-left-hero">
            <div class="login-tagline-box">
                <h1 class="login-tagline-title">
                    Digitalisasi Jurnal, Optimalisasi Pembelajaran.
                </h1>
                <p class="login-tagline-subtitle">
                    Digitalisasi Jurnal Mengajar untuk Menciptakan Proses Pembelajaran yang Lebih Efisien, Terdokumentasi, dan Berkualitas.
                </p>
            </div>
        </div>

        <!-- Right Section (Login Card) -->
        <div class="login-right-section">
            <div class="login-card-mockup">

                <!-- Admin Quick Badge -->
                <button type="button" class="admin-badge-btn" onclick="fillQuickAccount('Administrator', 'password', 'admin')">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <span>Admin</span>
                </button>

                <!-- Header Title -->
                <div class="mockup-header">
                    <h2 class="mockup-title">Selamat Datang</h2>
                    <p class="mockup-subtitle">Masuk untuk memulai</p>
                </div>

                <!-- Flash Alerts -->
                @if(session('success'))
                    <div class="mockup-alert mockup-alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mockup-alert mockup-alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="mockup-form">
                    @csrf

                    <!-- Username Input -->
                    <div class="form-group-mockup">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="input-mockup" value="{{ old('username') }}" placeholder="Masukkan nama pengguna" required autofocus>
                    </div>

                    <!-- Password Input -->
                    <div class="form-group-mockup">
                        <label for="password">Password</label>
                        <div class="input-wrapper-relative">
                            <input type="password" name="password" id="password" class="input-mockup" required>
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
                        <label for="role">Role</label>
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

                    <!-- Sign In Button -->
                    <button type="submit" class="btn-mockup-signin">
                        Sign in
                    </button>
                </form>

                <!-- Quick Demo Picker -->
                <div class="mockup-demo-bar">
                    <div class="mockup-demo-title">💡 Akun Demo Cepat (Database Real)</div>
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
