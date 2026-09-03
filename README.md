<div align="center">

<a href="https://github.com/ndyarrr/jurnal_absensi_guru">
  <img src="https://img.shields.io/badge/GITHUB-e--jurnal-6f42c1?style=for-the-badge&logo=github&logoColor=white&labelColor=181717" />
</a>
<a href="mailto:youremail@example.com">
  <img src="https://img.shields.io/badge/CONTACT-Email-e91e63?style=for-the-badge&logo=gmail&logoColor=white&labelColor=181717" />
</a>
<img src="https://img.shields.io/badge/STATUS-Tugas%20Akhir-00bcd4?style=for-the-badge&logo=googleclassroom&logoColor=white&labelColor=181717" />

<br/><br/>
</div>

## Overview

```bash
$ cat about.txt
> E-Jurnal — sistem jurnal mengajar & absensi guru berbasis web
> Mengelola: jurnal mengajar, absensi siswa, perizinan guru & siswa
> Notifikasi otomatis via WhatsApp
> Multi-role: Admin/TU, Guru Mapel, Guru Piket, Wali Kelas, Waka, Waka SDM, Kepsek, Satpam
> Dibuat untuk Tugas Akhir
```



## ✨ Fitur

<div align="center">

| Fitur | Deskripsi |
|-------|-----------|
| 🔐 **Login Multi-Role** | Admin/TU, Guru Mapel, Guru Piket, Wali Kelas, Waka, Waka SDM, Kepala Sekolah, Satpam |
| 📝 **Jurnal Mengajar** | Tombol pengisian aktif otomatis hanya saat jam mengajar berlangsung |
| ⏰ **Reminder Otomatis WA** | Notifikasi WhatsApp jika jurnal belum diisi 15 menit sebelum jam berakhir |
| 🙋 **Perizinan Guru (Hybrid)** | Guru Piket kirim izin guru paralel ke Waka, Waka SDM, dan Kepala Sekolah |
| 🎓 **Perizinan & Absensi Siswa** | Guru Piket input surat izin siswa, otomatis update rekap absensi kelas |
| 👀 **Pantauan Wali Kelas** | Rekap kehadiran & izin siswa per kelas |
| 🚧 **Pos Satpam** | Cek status izin siswa di gerbang, lapor ke Wali Kelas & Guru Piket |
| 📊 **Dashboard Admin/TU** | Kelola data guru, kelas, jurusan, mapel, dan jadwal pelajaran |

</div>


## 🛠️ Tech Stack

<div align="center">

**Backend**
<br/>
<img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" />
<img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
<img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
<br/><br/>

**Frontend**
<br/>
<img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" />
<img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" />
<img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" />
<br/><br/>

**Notifikasi & Tools**
<br/>
<img src="https://img.shields.io/badge/WhatsApp%20Gateway-25D366?style=for-the-badge&logo=whatsapp&logoColor=white" />
<img src="https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white" />
<img src="https://img.shields.io/badge/VS_Code-007ACC?style=for-the-badge&logo=visualstudiocode&logoColor=white" />

</div>


## 🚀 Cara Install (Pertama Kali)

### Prasyarat
- PHP >= 8.1 · Composer · MySQL / XAMPP / Laragon · Node.js & NPM · Git

### Langkah-langkah

```bash
# Verifikasi contributor
git config --global user.name "nama lu"
git config --global user.email "email_lu_@example.com"

# 1. Clone repo
git clone https://github.com/[username]/e-jurnal.git

# 2. Masuk ke folder project
cd e-jurnal

# 3. Install dependency PHP
composer install

# 4. Install dependency frontend
npm install
```

5. **Konfigurasi environment** → copy `.env.example` menjadi `.env`, sesuaikan koneksi database & token WhatsApp gateway
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
6. **Import database** → phpMyAdmin → import `database/jurnal_absensi_guru.sql`, **atau** jalankan migrasi:
   ```bash
   php artisan migrate
   ```
7. Build asset frontend & jalankan server:
   ```bash
   npm run dev
   php artisan serve
   ```
8. Buka browser → `http://localhost:8000`

> ⚠️ Untuk reminder WhatsApp otomatis, pastikan queue worker jalan:
> ```bash
> php artisan queue:work
> ```

## 🤖 Mengelola Proses Bot WhatsApp via Web (PM2)

Bot WhatsApp (`bot/index.js`) dapat **dihidupkan & dimatikan langsung dari web** di halaman
**Pengaturan WhatsApp → tab Bot Status** (kartu "Kontrol Proses Bot (PM2)").

Tombol **Hidupkan Bot** setara menjalankan `npm start`, dan **Matikan Bot** menghentikan total
proses (tidak auto-reconnect). Fitur ini bekerja lewat PM2.

### Setup di server (sekali saja)

1. Pasang PM2 (global):
   ```bash
   npm install -g pm2
   ```
2. Daftarkan bot sebagai aplikasi PM2:
   ```bash
   cd bot
   pm2 start index.js --name wa-bot
   pm2 save
   ```
   (opsional, agar nyala otomatis saat server reboot: `pm2 startup` lalu ikuti perintah yang muncul)
3. Atur di `.env`:
   ```
   WA_BOT_URL=http://127.0.0.1:3000
   WA_BOT_DIR=/path/ke/project/bot
   WA_BOT_PM2_APP_NAME=wa-bot
   WA_BOT_PM2_BIN=pm2
   ```
   - `WA_BOT_PM2_BIN` diisi jika binary pm2 tidak ada di PATH user web (mis. `www-data`).
   - `WA_BOT_PM2_HOME` diisi jika daemon PM2 memakai home khusus (mis. `/home/www-data/.pm2`).
   - Pastikan user web (www-data) dapat mengeksekusi binary `pm2` tersebut.

### 🪟 Mengembangkan di Windows

Fitur ini **cross-platform**. Untuk `npm install pm2` di folder `bot/`, lalu:

1. Jalankan dari PowerShell/CMD (di folder `bot`):
   ```powershell
   .\node_modules\.bin\pm2 start index.js --name wa-bot
   .\node_modules\.bin\pm2 save
   ```
2. Atur `.env` ke path Windows:
   ```
   WA_BOT_DIR=C:\project\jurnal_absensi_guru\bot
   WA_BOT_PM2_BIN=C:\project\jurnal_absensi_guru\bot\node_modules\.bin\pm2.cmd
   ```
   > Helper sudah lintas platform: jika `WA_BOT_PM2_BIN` berisi path tanpa ekstensi,
   > otomatis dicoba `pm2.cmd`. Jika dibiarkan `pm2`, akan dicari lewat PATH (`where pm2`).
3. Jebakan Windows:
   - Web jalan sebagai service (XAMPP/Apache, IIS) → user service berbeda dari user yang
     menjalankan `pm2 start`. Set **`WA_BOT_PM2_HOME`** ke folder `.pm2` milik user service
     (mis. `C:\Windows\System32\config\systemprofile\.pm2`) agar daemon PM2 yang sama yang dipakai.
   - `pm2 startup` (auto-nyala saat reboot) **tidak otomatis** di Windows; gunakan Task Scheduler
     atau paket `pm2-installer` jika diinginkan. Tidak wajib untuk tombol hidup/mati via web.
   - Pastikan user service punya izin eksekusi ke folder `bot` dan `pm2.cmd`.

### Penting
- Web (Laravel) dan bot Node.js harus berada di **server/mesin yang sama**.
- `pm2 start/stop` memerlukan izin eksekusi untuk user yang menjalankan web (PHP).
- Catatan: jika `pm2 stop wa-bot`, seluruh fitur notifikasi/reminder WA berhenti sampai bot
  dinyalakan kembali (manual atau via tombol **Hidupkan Bot**).


## 🔄 Workflow Sebelum Ngoding

> Wajib dilakukan setiap kali mau mulai kerja!

```bash
git status
git pull origin main
```

## 📤 Upload Perubahan

```bash
git add .
git commit -m "tambah fitur"
git push -u origin main
```


## Role Pengguna

| Role | Tugas Utama |
|------|--------------|
| **Admin/TU** | Kelola data mastyay -S opencode
er (guru, kelas, jurusan, mapel, jadwal) |
| **Guru Mapel** | Isi jurnal mengajar, ajukan izin/sakit |
| **Guru Piket** | Input izin siswa & guru, cari guru pengganti, teruskan izin guru ke Waka/Waka SDM/Kepsek |
| **Wali Kelas** | Pantau rekap absensi & izin siswa di kelasnya |
| **Waka** | Approval izin guru mapel |
| **Waka SDM** | Approval izin guru mapel & rekap kehadiran guru |
| **Kepala Sekolah** | Approval izin guru mapel & lihat laporan keseluruhan |
| **Satpam** | Cek status izin siswa di gerbang, lapor ke Wali Kelas & Guru Piket |



## 📁 Struktur Folder

```
$ tree
.
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Jobs/              # Job reminder WA
├── database/
│   ├── migrations/
│   └── jurnal_absensi_guru.sql
├── resources/
│   └── views/
│       ├── admin/
│       ├── guru-mapel/
│       ├── guru-piket/
│       ├── wali-kelas/
│       ├── waka/
│       ├── waka-sdm/
│       ├── kepala-sekolah/
│       └── satpam/
├── routes/
│   └── web.php
├── public/
├── .env.example
└── README.md
```

> 📌 *Struktur folder di atas mengikuti konvensi standar Laravel — sesuaikan dengan struktur folder proyek kalian yang sebenarnya.*


<div align="center">

## Tim

**[Nama Tim Kalian]** — *Tugas Akhir.*


</div>