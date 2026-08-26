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
                        <p class="dash-header-subtitle">Petunjuk pengoperasian sistem Jurnal & Absensi Guru</p>
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
                <input type="text" id="guideSearchInput" onkeyup="filterGuides()" class="search-guide-input" placeholder="Cari bantuan (contoh: jadwal, istirahat, export, guru, siswa)...">
            </div>

            <!-- Visual Workflow Overview -->
            <div class="dash-panel-card guide-topic-card">
                <h2 class="dash-panel-title" style="margin-bottom: 8px;">Alur Kerja Setup Sistem (Urutan Wajib)</h2>
                <p style="color: #64748b; font-size: 0.875rem;">Ikuti 4 langkah berurutan di bawah ini saat pertama kali mengonfigurasi aplikasi sekolah:</p>
                
                <div class="flow-container">
                    <div class="flow-node">
                        <div class="flow-node-title">1. Master Data</div>
                        <div class="flow-node-desc">Isi Kelas, Jurusan, Mapel, Guru, Siswa, dan Pengguna.</div>
                    </div>
                    <div class="flow-node">
                        <div class="flow-node-title">2. Pengaturan Jam</div>
                        <div class="flow-node-desc">Atur Jam Masuk, Pulang, KBM, dan Istirahat 1 & 2.</div>
                    </div>
                    <div class="flow-node">
                        <div class="flow-node-title">3. Plotting Jadwal</div>
                        <div class="flow-node-desc">Hubungkan Guru, Mapel, Kelas ke Sesi Jam Pelajaran.</div>
                    </div>
                    <div class="flow-node">
                        <div class="flow-node-title">4. Monitoring Jurnal</div>
                        <div class="flow-node-desc">Pantau pengisian jurnal guru & ekspor rekap CSV.</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 18px;" id="guidesContainer">
                
                <!-- TOPIC 1: Pengelolaan Master Data -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">1</span>
                        <span>Pengelolaan Master Data Sekolah</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 14px;">
                        Master Data merupakan fondasi utama sistem. Semua entitas (Guru, Siswa, Kelas, Mapel) disimpan di sini sebelum dapat dipakai di jadwal pelajaran.
                    </p>
                    <ul style="color: #334155; font-size: 0.875rem; line-height: 1.8; padding-left: 20px;">
                        <li><strong>Pengguna:</strong> Tempat mengelola akun login. Setiap Guru/Admin wajib memiliki akun pengguna aktif dengan role yang sesuai.</li>
                        <li><strong>Siswa:</strong> Berisi data siswa per kelas. Menyediakan tombol <em>Tambah Siswa</em>, Edit, Hapus, dan <em>Unduh CSV</em>.</li>
                        <li><strong>Guru:</strong> Berisi data profil guru, NUPTK, mapel diampu, dan nomor WhatsApp/HP.</li>
                        <li><strong>Kelas & Jurusan:</strong> Mengatur tingkat kelas (X, XI, XII), kode jurusan (RPL, TKJ, DKV, dll), serta wali kelas.</li>
                        <li><strong>Mata Pelajaran (Mapel):</strong> Berisi daftar mata pelajaran yang diajarkan di sekolah.</li>
                    </ul>

                    <div class="tip-box">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <span><strong>Tips Admin:</strong> Hubungkan akun User dengan data Guru agar guru dapat login ke dashboard dan mengisi jurnal mengajar mereka sendiri.</span>
                    </div>
                </div>

                <!-- TOPIC 2: Pengaturan Jam Pelajaran & Istirahat -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">2</span>
                        <span>Pengaturan Jam Pelajaran & Waktu Istirahat</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 14px;">
                        Modul Pengaturan Jam Pelajaran digunakan untuk men-generate slot waktu jam ke-1, ke-2, ke-3, dst secara otomatis berdasarkan jam masuk dan jam pulang sekolah.
                    </p>
                    <ul style="color: #334155; font-size: 0.875rem; line-height: 1.8; padding-left: 20px;">
                        <li><strong>Akses Halaman:</strong> Buka menu <em>Akademik ➔ Jadwal Pelajaran</em>, lalu klik tombol biru <strong>"Kelola Jam & Waktu Pulang"</strong> di pojok kanan atas.</li>
                        <li><strong>Tab Kategori Hari:</strong> Atur jam secara terpisah untuk <em>Senin - Kamis</em> dan <em>Jumat</em>.</li>
                        <li><strong>Mode KBM Variatif:</strong> Mengatur jam 1–4 durasi 40 menit, dan jam 5 ke atas durasi 35 menit secara otomatis.</li>
                        <li><strong>Mode Istirahat (Pukul vs Durasi):</strong>
                            <ul style="margin-top: 4px; padding-left: 18px;">
                                <li><strong>Mode Pukul:</strong> Tentukan jam mulai dan jam selesai istirahat secara pasti (contoh: `09:40 - 10:00` atau `11:45 - 13:15`). Sistem akan otomatis memotong slot KBM jika mendekati jam istirahat!</li>
                                <li><strong>Mode Durasi:</strong> Masukkan jumlah menit istirahat dan posisinya setelah jam ke berapa.</li>
                            </ul>
                        </li>
                    </ul>

                    <div class="warning-box">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <span><strong>Catatan Penting:</strong> Fitur server kami telah dilengkapi <em>Smart Auto-Correction</em>. Pengisian jam dalam format 24 jam (`11:45`, `13:15`, `15:00`) akan diproses tanpa error walau browser menggunakan format AM/PM!</span>
                    </div>
                </div>

                <!-- TOPIC 3: Kelola Jadwal Pelajaran -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">3</span>
                        <span>Pembuatan & Plotting Jadwal Pelajaran</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 14px;">
                        Jadwal Pelajaran menghubungkan Slot Jam Pelajaran dengan Guru, Mata Pelajaran, Kelas, dan Ruangan.
                    </p>
                    <ul style="color: #334155; font-size: 0.875rem; line-height: 1.8; padding-left: 20px;">
                        <li><strong>Tambah Jadwal:</strong> Klik tombol <em>Tambah Jadwal</em>, pilih Hari, Jam Pelajaran, Kelas, Mapel, Guru Pengampu, dan Ruangan.</li>
                        <li><strong>Penyaringan Data (Filter):</strong> Filter daftar jadwal berdasarkan Hari, Kelas, atau Guru Pengampu untuk mempermudah pengecekan bentrok.</li>
                        <li><strong>Ekspor CSV:</strong> Klik tombol <em>Unduh CSV</em> untuk mengunduh rekap jadwal pelajaran per kelas/guru.</li>
                    </ul>
                </div>

                <!-- TOPIC 4: Monitoring Jurnal Mengajar & Laporan -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">4</span>
                        <span>Monitoring Jurnal Mengajar & Ekspor Laporan</span>
                    </div>
                    <p style="color: #475569; font-size: 0.9rem; line-height: 1.6; margin-bottom: 14px;">
                        Menu Jurnal Mengajar merekam aktivitas mengajar guru setiap harinya, termasuk materi yang disampaikan, absensi kehadiran siswa, dan catatan KBM.
                    </p>
                    <ul style="color: #334155; font-size: 0.875rem; line-height: 1.8; padding-left: 20px;">
                        <li><strong>Pantau Realtime di Dashboard:</strong> Di halaman utama Dashboard, Admin dapat melihat grafik kehadiran harian, jumlah guru yang sudah/belum mengisi jurnal hari ini secara otomatis (auto-refresh per 15 detik).</li>
                        <li><strong>Pencarian & Filter Jurnal:</strong> Saring data jurnal berdasarkan rentang tanggal, nama guru, kelas, atau mata pelajaran.</li>
                        <li><strong>Ekspor Rekap Laporan:</strong> Admin dapat mengunduh seluruh data rekapitulasi jurnal dalam format CSV untuk kebutuhan pelaporan bulanan / supervisi kepala sekolah.</li>
                    </ul>
                </div>

                <!-- TOPIC 5: Pertanyaan Umum (FAQ) & Solusi Kendala -->
                <div class="guide-step-card guide-topic-card">
                    <div class="step-header">
                        <span class="step-badge">5</span>
                        <span>Pertanyaan Umum (FAQ) & Solusi Kendala</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 14px; margin-top: 10px;">
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">
                            <div style="font-weight: 800; color: #1e293b; font-size: 0.9rem; margin-bottom: 4px;">Q: Mengapa Guru tidak bisa mengisi Jurnal Mengajar?</div>
                            <div style="font-size: 0.85rem; color: #475569; line-height: 1.6;">A: Pastikan akun login Guru tersebut sudah dihubungkan dengan data Guru di menu <em>Master Data ➔ Guru</em>. Selain itu, pastikan jadwal pelajaran guru untuk hari tersebut sudah dibuat oleh Admin.</div>
                        </div>

                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">
                            <div style="font-weight: 800; color: #1e293b; font-size: 0.9rem; margin-bottom: 4px;">Q: Bagaimana jika waktu pulang sekolah diperpanjang atau diubah?</div>
                            <div style="font-size: 0.85rem; color: #475569; line-height: 1.6;">A: Buka <em>Akademik ➔ Jadwal Pelajaran ➔ Kelola Jam & Waktu Pulang</em>. Ubah Jam Pulang (contoh dari `14:30` menjadi `15:00`), lalu klik Simpan. Sistem generator kami akan otomatis menyesuaikan slot sisa waktu tanpa menghapus slot jam pelajaran yang sudah ada.</div>
                        </div>

                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">
                            <div style="font-weight: 800; color: #1e293b; font-size: 0.9rem; margin-bottom: 4px;">Q: Bagaimana cara mengunduh laporan bulanan?</div>
                            <div style="font-size: 0.85rem; color: #475569; line-height: 1.6;">A: Gunakan menu <em>Akademik ➔ Jurnal Mengajar</em>, atur filter tanggal dari awal bulan hingga akhir bulan, kemudian klik tombol <strong>Unduh CSV</strong> di pojok kanan atas.</div>
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
