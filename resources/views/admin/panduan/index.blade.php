<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Admin | Jurnal & Absensi Guru</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
</head>
<body class="dashboard-body">

    <div class="dash-layout">

        <aside class="dash-sidebar">
            @include('partials.dash-brand')

            <ul class="dash-menu">
                <li class="dash-menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="dash-menu-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="dash-menu-category">
                    <button type="button" class="dash-category-btn" onclick="toggleSubmenu('masterDataSub')">
                        <div class="dash-category-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <span>Master Data</span>
                        </div>
                        <svg class="dash-category-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <ul class="dash-sub-menu" id="masterDataSub">
                        <li>
                            <a href="{{ route('users.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Pengguna</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('siswa.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                </svg>
                                <span>Siswa</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('guru.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span>Guru</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kelas.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    <path d="M3 9h18M9 21V9"></path>
                                </svg>
                                <span>Kelas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('mapel.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                <span>Mapel</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="dash-menu-category">
                    <button type="button" class="dash-category-btn" onclick="toggleSubmenu('akademikSub')">
                        <div class="dash-category-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            <span>Akademik</span>
                        </div>
                        <svg class="dash-category-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <ul class="dash-sub-menu" id="akademikSub">
                        <li>
                            <a href="{{ route('jadwal.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span>Jadwal Pelajaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jurnal.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <rect x="8" y="2" width="8" height="4" rx="1"></rect>
                                </svg>
                                <span>Jurnal Mengajar</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            @include('partials.dash-sidebar-footer')
        </aside>

        <main class="dash-main">
            <header class="dash-top-bar">
                <div>
                    <h1 class="dash-header-title">Panduan Admin</h1>
                    <p class="dash-header-subtitle">Petunjuk penggunaan panel administrasi</p>
                </div>

                <div class="dash-top-right">
                    <div class="dash-date-widget">
                        <svg class="dash-date-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <rect x="7" y="14" width="3" height="3" fill="currentColor"></rect>
                        </svg>
                        <div class="dash-date-info">
                            <span class="date-str" id="live_date_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
                            <span class="time-str" id="live_time_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }} WIB</span>
                        </div>
                    </div>

                    @include('partials.dash-user-widget')
                </div>
            </header>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="dash-panel-card">
                    <h2 class="dash-panel-title" style="margin-bottom: 12px;">Selamat Datang, Admin</h2>
                    <p style="color: #64748b; font-size: 0.925rem; line-height: 1.7;">
                        Halaman ini berisi ringkasan cara menggunakan sistem Jurnal & Absensi Guru.
                        Gunakan menu sidebar untuk berpindah antar modul, dan tombol <strong>Export</strong> / <strong>Unduh CSV</strong> untuk mengunduh data.
                    </p>
                </div>

                <div class="dash-panel-card">
                    <h2 class="dash-panel-title" style="margin-bottom: 16px;">1. Dashboard</h2>
                    <ul style="color: #475569; font-size: 0.925rem; line-height: 1.8; padding-left: 20px;">
                        <li>Melihat ringkasan statistik pengguna, siswa, guru, kelas, dan jadwal.</li>
                        <li>Memantau rekap jurnal mengajar hari ini dan guru yang belum mengisi.</li>
                        <li>Mengunduh laporan ringkas melalui tombol <strong>Unduh CSV</strong>.</li>
                    </ul>
                </div>

                <div class="dash-panel-card">
                    <h2 class="dash-panel-title" style="margin-bottom: 16px;">2. Master Data</h2>
                    <ul style="color: #475569; font-size: 0.925rem; line-height: 1.8; padding-left: 20px;">
                        <li><strong>Pengguna</strong> — kelola akun login (admin, guru, kepala sekolah, dll.).</li>
                        <li><strong>Siswa</strong> — tambah, edit, hapus data siswa dan kelasnya. Export CSV tersedia.</li>
                        <li><strong>Guru</strong> — kelola data guru, NUPTK, mapel diampu, dan nomor HP. Export CSV tersedia.</li>
                        <li><strong>Kelas</strong> — atur tingkat, jurusan, rombel, dan wali kelas.</li>
                        <li><strong>Mapel</strong> — kelola daftar mata pelajaran.</li>
                    </ul>
                </div>

                <div class="dash-panel-card">
                    <h2 class="dash-panel-title" style="margin-bottom: 16px;">3. Akademik</h2>
                    <ul style="color: #475569; font-size: 0.925rem; line-height: 1.8; padding-left: 20px;">
                        <li><strong>Jadwal Pelajaran</strong> — buat dan atur jadwal per hari, kelas, guru, dan ruangan. Bisa export CSV.</li>
                        <li><strong>Jurnal Mengajar</strong> — lihat catatan mengajar guru, filter berdasarkan tanggal/guru/kelas/mapel, dan export CSV.</li>
                        <li><strong>Kelola Jam & Waktu Pulang</strong> — atur slot jam pelajaran dari halaman Jadwal.</li>
                    </ul>
                </div>

                <div class="dash-panel-card">
                    <h2 class="dash-panel-title" style="margin-bottom: 16px;">4. Tips Penggunaan</h2>
                    <ul style="color: #475569; font-size: 0.925rem; line-height: 1.8; padding-left: 20px;">
                        <li>Isi master data (kelas, mapel, guru, siswa) terlebih dahulu sebelum membuat jadwal.</li>
                        <li>Gunakan filter di setiap halaman sebelum export agar data CSV sesuai kebutuhan.</li>
                        <li>Pastikan guru sudah terhubung ke akun pengguna agar bisa mengisi jurnal.</li>
                        <li>Hubungi tim pengembang jika menemukan kendala teknis pada sistem.</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleSubmenu(id) {
            const el = document.getElementById(id);
            if (el.style.display === 'none' || el.style.display === '') {
                el.style.display = 'flex';
            } else {
                el.style.display = 'none';
            }
        }
    </script>
    <script src="/js/live-clock.js"></script>
</body>
</html>
