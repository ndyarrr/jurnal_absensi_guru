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
    <script src="/js/sidebar-toggle.js"></script>
    <style>
        .guide-step-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            transition: all 0.2s ease;
        }
        .guide-step-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 6px 20px rgba(0,0,0,0.04);
        }
        .step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #1d4ed8;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 800;
            margin-right: 10px;
        }
        .step-header {
            display: flex;
            align-items: center;
            font-size: 1.1rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .flow-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin: 16px 0;
        }
        .flow-node {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 14px;
            text-align: center;
            position: relative;
        }
        .flow-node-title {
            font-size: 0.875rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .flow-node-desc {
            font-size: 0.775rem;
            color: #64748b;
        }
        .tip-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 14px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .warning-box {
            background: #fffbebfb;
            border: 1px solid #fde68a;
            color: #92400e;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 14px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .search-guide-input {
            width: 100%;
            padding: 12px 18px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            font-size: 0.9rem;
            font-family: inherit;
            outline: none;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }
        .search-guide-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }
        .guide-step-list {
            color: #334155;
            font-size: 0.875rem;
            line-height: 1.8;
            padding-left: 20px;
            margin: 0 0 14px 0;
        }
        .guide-step-list li {
            margin-bottom: 8px;
        }
        .guide-step-list strong {
            color: #1e293b;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin: 14px 0;
        }
        .feature-tile {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
            transition: all 0.2s ease;
        }
        .feature-tile:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
        }
        .feature-tile-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .feature-tile-desc {
            font-size: 0.775rem;
            color: #64748b;
            line-height: 1.5;
        }
        .faq-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
        }
        .faq-q {
            font-weight: 800;
            color: #1e293b;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }
        .faq-a {
            font-size: 0.85rem;
            color: #475569;
            line-height: 1.7;
        }
        .step-list-num {
            list-style: none;
            counter-reset: guide;
            padding-left: 0;
        }
        .step-list-num li {
            counter-increment: guide;
            position: relative;
            padding-left: 38px;
            margin-bottom: 12px;
        }
        .step-list-num li::before {
            content: counter(guide);
            position: absolute;
            left: 0;
            top: 0;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #2563eb;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mini-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 12px 0;
            font-size: 0.82rem;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .mini-table th {
            background: #eff6ff;
            color: #1e40af;
            font-weight: 800;
            text-align: left;
            padding: 10px 12px;
        }
        .mini-table td {
            padding: 10px 12px;
            border-top: 1px solid #e2e8f0;
            color: #334155;
        }
        .mini-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .panduan-icon-circle {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .guide-menu-note {
            display: inline-block;
            background: #eef2ff;
            color: #4338ca;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 6px;
        }
    </style>
</head>
<body class="dashboard-body">

    <div class="dash-layout">

        @include('partials.dash-sidebar')

        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <main class="dash-main">
            <header class="dash-top-bar">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button type="button" class="dash-hamburger-btn" onclick="toggleSidebar()" title="Menu">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <div>
                        <h1 class="dash-header-title">Panduan Lengkap Admin</h1>
                        <p class="dash-header-subtitle">Petunjuk lengkap penggunaan sistem Jurnal & Absensi Guru, ditulis dengan bahasa sederhana</p>
                    </div>
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

            <!-- Search Filter Bar -->
            <div style="margin-bottom: 8px;">
                <input type="text" id="guideSearchInput" onkeyup="filterGuides()" class="search-guide-input" placeholder="Cari bantuan (contoh: jadwal, guru, siswa, piket, export, istirahat, whatsapp, bot)...">
            </div>

            <!-- Visual Workflow Overview -->
            <div class="dash-panel-card guide-topic-card">
                <h2 class="dash-panel-title" style="margin-bottom: 8px;">Alur Kerja Setup Sistem (Urutan Wajib)</h2>
                <p style="color: #64748b; font-size: 0.875rem;">Ikuti 5 langkah berurutan di bawah ini saat pertama kali mengonfigurasi aplikasi sekolah:</p>

                <div class="flow-container">
                    <div class="flow-node">
                        <div class="flow-node-title">1. Master Data</div>
                        <div class="flow-node-desc">Isi Pengguna, Siswa, Guru, Kelas, Mapel, dan Ruangan.</div>
                    </div>
                    <div class="flow-node">
                        <div class="flow-node-title">2. Pengaturan Jam</div>
                        <div class="flow-node-desc">Atur Jam Masuk, Pulang, KBM, dan Istirahat.</div>
                    </div>
                    <div class="flow-node">
                        <div class="flow-node-title">3. Plotting Jadwal</div>
                        <div class="flow-node-desc">Hubungkan Guru, Mapel, Kelas ke sesi jam pelajaran.</div>
                    </div>
                    <div class="flow-node">
                        <div class="flow-node-title">4. Jadwal Piket</div>
                        <div class="flow-node-desc">Tentukan guru yang bertugas piket tiap hari.</div>
                    </div>
                    <div class="flow-node">
                        <div class="flow-node-title">5. Monitoring Jurnal</div>
                        <div class="flow-node-desc">Pantau jurnal guru & ekspor rekap laporan.</div>
                    </div>
                    <div class="flow-node">
                        <div class="flow-node-title">6. Notifikasi WhatsApp</div>
                        <div class="flow-node-desc">Hubungkan Bot WA, atur template pesan & penerima otomatis.</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 18px;" id="guidesContainer">

                <!-- TOPIC 1: Dashboard -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">1</span>
                        <span>Dashboard - Ringkasan Aktivitas</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 14px;">
                        Dashboard adalah "halaman beranda" admin. Semua angka penting sekolah ditampilkan di sini supaya Anda bisa memantau kondisi tanpa harus membuka banyak halaman.
                    </p>
                    <ul class="guide-step-list">
                        <li><strong>Angka Ringkasan:</strong> Di bagian atas terdapat total Pengguna, Siswa, Guru, dan Kelas yang terdaftar di sistem.</li>
                        <li><strong>Grafik Aktivitas Jurnal:</strong> Menampilkan jumlah jurnal yang diisi guru dalam 7, 14, atau 30 hari terakhir (tinggal klik pilihan rentang waktu).</li>
                        <li><strong>Progres Pengisian Hari Ini:</strong> Lingkaran progres menunjukkan berapa persen guru yang sudah mengisi jurnal hari ini.</li>
                        <li><strong>Daftar Guru Belum Mengisi:</strong> Menampilkan guru yang belum mengisi jurnal hari ini agar bisa segera diingatkan.</li>
                        <li><strong>Menyegarkan Otomatis:</strong> Data dashboard diperbarui otomatis setiap 15 detik, jadi Anda tidak perlu me-refresh halaman.</li>
                        <li><strong>Unduh CSV:</strong> Tombol <em>Unduh CSV</em> untuk menyimpan ringkasan dashboard dalam bentuk file.</li>
                    </ul>
                    <div class="tip-box">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <span><strong>Tips Admin:</strong> Gunakan Dashboard setiap pagi untuk melihat siapa saja guru yang belum mengisi jurnal, sehingga bisa diingatkan lebih awal.</span>
                    </div>
                </div>

                <!-- TOPIC 2: Master Data -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">2</span>
                        <span>Master Data - Data Dasar Sekolah</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 8px;">
                        Master Data adalah "gudang data" sekolah. Seluruh data guru, siswa, kelas, dan lainnya disimpan di sini sebelum dipakai untuk jadwal pelajaran. Menu ini ada di kategori <span class="guide-menu-note">Master Data</span> pada sidebar.
                    </p>

                    <div class="feature-grid">
                        <div class="feature-tile">
                            <div class="feature-tile-title">
                                <span class="panduan-icon-circle" style="background:#eff6ff;color:#2563eb;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span>
                                Pengguna
                            </div>
                            <div class="feature-tile-desc">Akun untuk login (Admin, Guru, Wali Kelas, Guru Piket). Setiap pengguna wajib punya akun agar bisa masuk sistem.</div>
                        </div>
                        <div class="feature-tile">
                            <div class="feature-tile-title">
                                <span class="panduan-icon-circle" style="background:#fef3c7;color:#b45309;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg></span>
                                Siswa
                            </div>
                            <div class="feature-tile-desc">Data siswa per kelas. Bisa tambah, ubah, hapus, dan unduh CSV.</div>
                        </div>
                        <div class="feature-tile">
                            <div class="feature-tile-title">
                                <span class="panduan-icon-circle" style="background:#dcfce7;color:#15803d;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg></span>
                                Guru
                            </div>
                            <div class="feature-tile-desc">Profil guru (nama, NUPTK, mapel diampu, no. WhatsApp). Bisa unduh CSV.</div>
                        </div>
                        <div class="feature-tile">
                            <div class="feature-tile-title">
                                <span class="panduan-icon-circle" style="background:#fee2e2;color:#b91c1c;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"></rect><path d="M3 9h18M9 21V9"></path></svg></span>
                                Kelas
                            </div>
                            <div class="feature-tile-desc">Kelas (X/XI/XII, rombel, jurusan) serta penunjukan wali kelas.</div>
                        </div>
                        <div class="feature-tile">
                            <div class="feature-tile-title">
                                <span class="panduan-icon-circle" style="background:#e0f2fe;color:#0369a1;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg></span>
                                Mapel
                            </div>
                            <div class="feature-tile-desc">Daftar mata pelajaran yang diajarkan di sekolah.</div>
                        </div>
                        <div class="feature-tile">
                            <div class="feature-tile-title">
                                <span class="panduan-icon-circle" style="background:#f3e8ff;color:#7e22ce;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></span>
                                Ruangan
                            </div>
                            <div class="feature-tile-desc">Data ruangan/lab/kelas fisik yang dipakai untuk jadwal mengajar.</div>
                        </div>
                    </div>

                    <div class="tip-box">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <span><strong>Tips Admin:</strong> Hubungkan akun <em>Pengguna</em> dengan data <em>Guru</em> yang bersangkutan. Ini penting agar guru bisa login dan mengisi jurnal mengajarnya sendiri.</span>
                    </div>
                </div>

                <!-- TOPIC 3: Pengaturan Jam -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">3</span>
                        <span>Pengaturan Jam Pelajaran & Waktu Istirahat</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 14px;">
                        Halaman ini berfungsi untuk mengatur kapan sekolah masuk, kapan pulang, durasi tiap jam pelajaran, dan kapan istirahat. Sistem akan otomatis membuat daftar slot jam pelajaran berdasarkan pengaturan tersebut.
                    </p>
                    <ol class="guide-step-list step-list-num">
                        <li><strong>Buka halamannya:</strong> Klik menu <em>Akademik ➔ Jam Pelajaran</em> di sidebar.</li>
                        <li><strong>Atur jam masuk & pulang:</strong> Isi jam mulai dan jam selesai sekolah.</li>
                        <li><strong>Atur durasi jam:</strong> Tentukan berapa menit tiap jam pelajaran (mis. 40 menit atau 35 menit). Anda juga bisa mengatur jam pelajaran yang durasinya berbeda-beda.</li>
                        <li><strong>Atur istirahat:</strong>
                            <ul style="margin-top: 4px; padding-left: 18px;">
                                <li><strong>Mode Pukul:</strong> Tentukan waktu mulai dan selesai istirahat secara pasti (contoh: 09:40 - 10:00).</li>
                                <li><strong>Mode Durasi:</strong> Masukkan jumlah menit istirahat dan posisinya setelah jam ke berapa.</li>
                            </ul>
                        </li>
                        <li><strong>Simpan:</strong> Sistem otomatis menyusun ulang slot jam berdasarkan pengaturan tersebut.</li>
                    </ol>
                    <div class="warning-box">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <span><strong>Catatan Penting:</strong> Gunakan format jam 24 jam (contoh: 11:45, 13:15, 15:00). Sistem sudah dilengkapi koreksi otomatis, jadi aman walau browser Anda memakai format AM/PM.</span>
                    </div>
                </div>

                <!-- TOPIC 4: Jadwal Pelajaran -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">4</span>
                        <span>Pembuatan & Plotting Jadwal Pelajaran</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 14px;">
                        Jadwal Pelajaran mengatur "siapa mengajar apa, di kelas mana, jam berapa". Di menu ini Anda menghubungkan jam pelajaran dengan Guru, Mapel, Kelas, dan Ruangan.
                    </p>
                    <ul class="guide-step-list">
                        <li><strong>Tambah Jadwal:</strong> Klik tombol <em>Tambah Jadwal</em>, lalu pilih Hari, Jam Pelajaran, Kelas, Mapel, Guru Pengampu, dan Ruangan.</li>
                        <li><strong>Filter / Penyaringan:</strong> Saring daftar jadwal berdasarkan Hari, Kelas, atau Guru untuk memudahkan pengecekan bentrok.</li>
                        <li><strong>Tampilan Matriks:</strong> Melihat jadwal dalam bentuk tabel (Hari &times; Jam) per kelas agar mudah dicek keseluruhan.</li>
                        <li><strong>Pindahin Jadwal:</strong> Geser (drag & drop) jadwal yang sudah ada untuk memindahkannya ke jam/hari lain tanpa membuat ulang.</li>
                        <li><strong>Unduh CSV:</strong> Simpan rekap jadwal pelajaran dalam format CSV.</li>
                        <li><strong>Unduh PDF:</strong> Simpan jadwal pelajaran dalam bentuk PDF untuk dicetak/dibagikan.</li>
                    </ul>
                    <div class="tip-box">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <span><strong>Tips Admin:</strong> Pastikan Guru yang bersangkutan sudah dibuatkan jadwal di hari itu, karena jadwal inilah yang menjadi dasar guru mengisi jurnal mengajar.</span>
                    </div>
                </div>

                <!-- TOPIC 5: Jadwal Guru Piket -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">5</span>
                        <span>Jadwal Guru Piket</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 14px;">
                        Menu ini digunakan untuk menunjuk guru yang bertugas piket pada tiap hari (Senin sampai Jumat). Berguna untuk mengatur jadwal piket dengan jelas dan terpusat.
                    </p>
                    <ul class="guide-step-list">
                        <li><strong>Lihat Kondisi:</strong> Buka menu <em>Akademik ➔ Jadwal Guru Piket</em>. Jadwal ditampilkan per hari.</li>
                        <li><strong>Tambah Petugas:</strong> Klik tombol <em>Tambah</em> pada hari yang diinginkan, lalu pilih nama guru piket. Anda bisa menambahkan keterangan seperti "Petugas Piket Utama".</li>
                        <li><strong>Pilih Guru dengan Cepat:</strong> Kolom pilihan guru bisa diketik untuk dicari langsung, sehingga lebih cepat dan tidak salah pilih.</li>
                        <li><strong>Ubah / Hapus:</strong> Anda bisa menghapus atau mengganti guru piket suatu hari kapan saja.</li>
                    </ul>
                </div>

                <!-- TOPIC 6 (BARU): Pengaturan WhatsApp / Bot Konfigurasi -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">6</span>
                        <span>Pengaturan WhatsApp &amp; Notifikasi Otomatis (Bot Konfigurasi)</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 14px;">
                        Fitur <strong>Bot Konfigurasi</strong> (menu di sidebar) adalah pusat kendali notifikasi WhatsApp. Dari sini Anda bisa
                        menyambungkan bot ke nomor WhatsApp sekolah, menghidupkan/mematikan proses bot, mengatur pengingat jurnal mengajar,
                        mengubah isi pesan otomatis, serta menentukan siapa saja yang menerima notifikasi. Seluruh halaman dikemas dalam 5 tab:
                    </p>

                    <table class="mini-table">
                        <thead>
                            <tr>
                                <th>Tab</th>
                                <th>Fungsi Singkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Status Bot</strong></td>
                                <td>Melihat status koneksi WA, kontrol proses bot (PM2), scan QR / kode pairing, muat ulang sesi, dan logout.</td>
                            </tr>
                            <tr>
                                <td><strong>Pengaturan</strong></td>
                                <td>Saklar utama notifikasi WA, pengingat jurnal (menit sebelum jam selesai), dan target penerima laporan.</td>
                            </tr>
                            <tr>
                                <td><strong>Template Pesan</strong></td>
                                <td>Edit isi pesan otomatis per kategori (Reminder, Izin, Dispensasi, Presensi) dalam tampilan gelembung chat.</td>
                            </tr>
                            <tr>
                                <td><strong>Penerima Khusus</strong></td>
                                <td>Daftar kontak (kepala sekolah, pengawas, admin) yang selalu menerima tembusan notifikasi sistem.</td>
                            </tr>
                            <tr>
                                <td><strong>Uji Coba Kirim</strong></td>
                                <td>Mengirim pesan tes untuk memastikan bot benar-benar aktif sebelum dipakai normal.</td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="margin-top: 16px;">
                        <strong style="color: #0f172a; font-size: 0.9rem;">Cara Menyambungkan Bot WhatsApp (Pertama Kali):</strong>
                    </div>
                    <ol class="guide-step-list step-list-num">
                        <li><strong>Buka halamannya:</strong> Klik menu <em>Bot Konfigurasi</em> di sidebar (kategori paling bawah).</li>
                        <li><strong>Tab Status Bot:</strong> Lihat bagian "Kontrol Proses Bot (PM2)". Jika tertulis <em>PM2 Tidak Terdeteksi</em>, selesaikan dulu setup PM2 di server (lihat README).</li>
                        <li><strong>Hidupkan Bot:</strong> Klik tombol hijau <em>Hidupkan Bot</em> agar proses Node.js berjalan.</li>
                        <li><strong>Sambungkan WhatsApp:</strong> Pilih salah satu cara &mdash; <em>scan QR Code</em> dari WhatsApp ponsel (menu <em>Settings ➔ Perangkat Tertaut ➔ Tautkan Perangkat</em>), atau gunakan <em>kode pairing</em> 8 digit dengan memasukkan nomor HP bot terlebih dahulu.</li>
                        <li><strong>Tunggu status "Bot Terhubung":</strong> Jika badge hijau <em>Bot Terhubung Sempurna</em> muncul, sistem siap mengirim notifikasi otomatis.</li>
                        <li><strong>Tab Pengaturan:</strong> Pastikan saklar <em>Aktifkan Layanan WhatsApp Notifikasi</em> dan <em>Pengingat Jurnal Mengajar</em> dalam keadaan aktif.</li>
                        <li><strong>Tab Template Pesan:</strong> Sesuaikan isi pesan otomatis bila perlu (klik <em>Edit</em> pada gelembung chat).</li>
                        <li><strong>Tab Uji Coba Kirim:</strong> Kirim pesan tes ke nomor Anda sendiri untuk memastikan pengiriman berjalan.</li>
                    </ol>

                    <div class="feature-grid">
                        <div class="feature-tile">
                            <div class="feature-tile-title">
                                <span class="panduan-icon-circle" style="background:#dcfce7;color:#15803d;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg></span>
                                Kontrol PM2
                            </div>
                            <div class="feature-tile-desc">Tombol <em>Hidupkan</em> / <em>Matikan Bot</em> setara dengan menjalankan atau menghentikan proses bot di terminal.</div>
                        </div>
                        <div class="feature-tile">
                            <div class="feature-tile-title">
                                <span class="panduan-icon-circle" style="background:#eff6ff;color:#2563eb;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="2" width="18" height="20" rx="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg></span>
                                Reminder Jurnal
                            </div>
                            <div class="feature-tile-desc">Pengingat otomatis dikirim ke WhatsApp guru beberapa menit sebelum jam pelajaran berakhir (default 15 menit, bisa 1-120 menit).</div>
                        </div>
                        <div class="feature-tile">
                            <div class="feature-tile-title">
                                <span class="panduan-icon-circle" style="background:#fef3c7;color:#b45309;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></span>
                                Template Pesan
                            </div>
                            <div class="feature-tile-desc">Gunakan tombol <em>Panduan Variabel</em> untuk melihat variabel otomatis seperti {nama_guru}, {mapel}, {nama_kelas}, dan lainnya.</div>
                        </div>
                        <div class="feature-tile">
                            <div class="feature-tile-title">
                                <span class="panduan-icon-circle" style="background:#f3e8ff;color:#7e22ce;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg></span>
                                Penerima Khusus
                            </div>
                            <div class="feature-tile-desc">Nomor WhatsApp memakai format 62 di awal (contoh: 628123456789), bukan 08.</div>
                        </div>
                    </div>

                    <div class="tip-box">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <span><strong>Tips Admin:</strong> Selalu uji lewat tab <em>Uji Coba Kirim</em> setelah menyambungkan bot, sebelum sistem berjalan normal. Pastikan nomor WhatsApp guru sudah terisi benar di <em>Master Data ➔ Guru</em>.</span>
                    </div>

                    <div class="warning-box">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <span><strong>Catatan Penting:</strong> Tombol <em>Matikan Bot</em> benar-benar menghentikan proses (tidak auto-reconnect). Seluruh notifikasi WA berhenti sampai bot dihidupkan kembali. Gunakan tombol <em>Logout &amp; Hapus Sesi</em> hanya jika ingin menyambungkan nomor WhatsApp yang berbeda.</span>
                    </div>
                </div>

                <!-- TOPIC 7: Jurnal Mengajar & Laporan -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">7</span>
                        <span>Monitoring Jurnal Mengajar & Ekspor Laporan</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 14px;">
                        Menu Jurnal Mengajar merekam aktivitas mengajar guru tiap hari: materi yang diajarkan, kehadiran siswa, dan catatan pembelajaran. Admin bisa memantau dan mengunduh datanya.
                    </p>
                    <ul class="guide-step-list">
                        <li><strong>Pantau Pengisian:</strong> Lihat di Dashboard berapa guru yang sudah/belum mengisi jurnal hari ini (auto-refresh 15 detik).</li>
                        <li><strong>Cari & Filter Jurnal:</strong> Saring data berdasarkan rentang tanggal, nama guru, kelas, atau mata pelajaran.</li>
                        <li><strong>Unduh Rekap:</strong> Klik <em>Unduh CSV</em> untuk menyimpan seluruh data jurnal, misalnya untuk laporan bulanan atau supervisi kepala sekolah.</li>
                    </ul>
                </div>

                <!-- TOPIC 8: Profil Admin -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">8</span>
                        <span>Mengubah Profil Pribadi</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 14px;">
                        Anda bisa memperbarui data diri dan kata sandi sendiri. Menu ini tersedia untuk semua pengguna yang sudah login.
                    </p>
                    <ul class="guide-step-list">
                        <li><strong>Buka Profil:</strong> Klik menu profil (nama pengguna) di pojok kanan atas.</li>
                        <li><strong>Ubah data:</strong> Perbarui nama, informasi kontak, atau kata sandi sesuai kebutuhan.</li>
                        <li><strong>Simpan:</strong> Klik Simpan untuk menyimpan perubahan.</li>
                    </ul>
                </div>

                <!-- TOPIC 9: FAQ -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">9</span>
                        <span>Pertanyaan Umum (FAQ) & Solusi Kendala</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 14px; margin-top: 10px;">
                        <div class="faq-item">
                            <div class="faq-q">Q: Mengapa Guru tidak bisa mengisi Jurnal Mengajar?</div>
                            <div class="faq-a">A: Pastikan akun login guru sudah dihubungkan dengan data Guru di menu <em>Master Data ➔ Guru</em>. Selain itu, pastikan jadwal pelajaran guru untuk hari tersebut sudah dibuat oleh Admin.</div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-q">Q: Bagaimana cara membuat akun untuk guru baru?</div>
                            <div class="faq-a">A: Buka <em>Master Data ➔ Pengguna</em>, klik <em>Tambah</em>, isi nama/username/password, lalu pilih role yang sesuai. Jangan lupa hubungkan akun tersebut dengan data guru agar guru bisa login dan mengisi jurnal.</div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-q">Q: Bagaimana jika waktu pulang sekolah diperpanjang atau diubah?</div>
                            <div class="faq-a">A: Buka <em>Akademik ➔ Jam Pelajaran</em>, ubah jam pulang (misal dari 14:30 menjadi 15:00), lalu simpan. Sistem otomatis menyesuaikan slot jam tanpa menghapus jadwal yang sudah ada.</div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-q">Q: Bagaimana cara mengunduh laporan bulanan?</div>
                            <div class="faq-a">A: Gunakan menu <em>Akademik ➔ Jurnal Mengajar</em>, atur filter tanggal dari awal sampai akhir bulan, lalu klik <strong>Unduh CSV</strong> di pojok kanan atas.</div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-q">Q: Bagaimana jika saya lupa kata sandi?</div>
                            <div class="faq-a">A: Mintalah admin lain untuk mengubah kata sandi Anda melalui <em>Master Data ➔ Pengguna</em>, atau ubah sendiri lewat menu Profil Anda.</div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-q">Q: Guru tidak menerima notifikasi WhatsApp, kenapa?</div>
                            <div class="faq-a">A: Periksa di <em>Bot Konfigurasi ➔ Status Bot</em> apakah status sudah <em>Bot Terhubung</em>. Pastikan saklar notifikasi dan pengingat aktif di tab <em>Pengaturan</em>. Terakhir, pastikan nomor WhatsApp guru (format 62....) sudah terisi dengan benar di <em>Master Data ➔ Guru</em>.</div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-q">Q: Bagaimana cara mengganti nomor WhatsApp bot?</div>
                            <div class="faq-a">A: Buka <em>Bot Konfigurasi ➔ Status Bot</em>, klik tombol <em>Logout &amp; Hapus Sesi</em>, lalu sambungkan kembali dengan nomor baru melalui scan QR atau kode pairing.</div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Toggle Submenu & Live Search Script -->
    <script>
        function toggleSubmenu(id) {
            const el = document.getElementById(id);
            if (el.style.display === 'none' || el.style.display === '') {
                el.style.display = 'flex';
            } else {
                el.style.display = 'none';
            }
        }

        // Live Filter Guide Cards
        function filterGuides() {
            const query = document.getElementById('guideSearchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.guide-topic-card');

            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                if (text.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
    <script src="/js/sidebar-toggle.js"></script>
    <script src="/js/live-clock.js"></script>
</body>
</html>
