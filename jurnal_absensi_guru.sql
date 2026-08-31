-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 31, 2026 at 09:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jurnal_absensi_guru`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval_logs`
--

CREATE TABLE `approval_logs` (
  `id_approval` int(11) NOT NULL,
  `id_permohonan` int(11) NOT NULL,
  `id_user_approver` bigint(20) UNSIGNED NOT NULL,
  `role_approver` varchar(50) NOT NULL,
  `aksi` enum('approved','rejected') NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_ketidakhadiran`
--

CREATE TABLE `detail_ketidakhadiran` (
  `id_detail` int(11) NOT NULL,
  `id_jurnal` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `status` enum('S','I','A') NOT NULL,
  `kategori` enum('sakit','izin_ortu','dispensasi','alpa') NOT NULL DEFAULT 'sakit',
  `bukti_surat` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `id_guru_piket` int(11) DEFAULT NULL,
  `waktu_input` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `detail_ketidakhadiran`
--

INSERT INTO `detail_ketidakhadiran` (`id_detail`, `id_jurnal`, `id_siswa`, `status`, `kategori`, `bukti_surat`, `catatan`, `id_guru_piket`, `waktu_input`) VALUES
(1, 1, 1, 'A', 'sakit', NULL, NULL, NULL, NULL),
(6, 8, 5, 'S', 'sakit', NULL, NULL, NULL, NULL),
(7, 8, 6, 'I', 'sakit', NULL, NULL, NULL, NULL),
(8, 10, 9, 'A', 'sakit', NULL, NULL, NULL, NULL),
(9, 10, 10, 'S', 'sakit', NULL, NULL, NULL, NULL),
(10, 1, 2, 'I', 'sakit', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nuptk` varchar(20) NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id_guru`, `nuptk`, `nama_guru`, `no_hp`, `deleted_at`) VALUES
(1, '198101152003121003', 'Trisno Wibowo, S.Pd., M.M.', '081234500001', NULL),
(2, '198502102010012004', 'Kurnila Putri Islamawati, S.Pd', '081234500002', NULL),
(3, '198703152011012005', 'Shinta Indyar Smoney Susanto, S.Pd', '081234500003', NULL),
(4, '199001202015012006', 'Anisa Kusumawati, S.Pd', '081234500004', NULL),
(5, '198906302012011007', 'Budi Santoso, S.Kom', '081234500005', NULL),
(6, '199203102016022008', 'Rina Amelia, S.Pd', '081234500006', NULL),
(7, '198512252010011009', 'Agus Prasetyo, S.T', '081234500007', NULL),
(8, '199107182017012010', 'Dewi Lestari, S.Pd', '081234500008', NULL),
(9, '198809052013011011', 'Hendra Wijaya, S.Kom', '081234500009', NULL),
(10, '199305142018022012', 'Siti Nurhaliza, S.Pd', '081234500010', NULL),
(11, '198501152010011001', 'Trisno Wibowo, S.Pd., M.M.', '081234567801', NULL),
(12, '199002202015022002', 'Kurnila Putri Islamawati, S.Pd', '081234567802', NULL),
(13, '198803102012011003', 'Budi Santoso, S.Kom', '081234567803', NULL),
(14, '199204052018022004', 'Rina Amelia, S.Pd', '081234567804', NULL),
(15, '198605122011011005', 'Agus Prasetyo, S.T', '081234567805', NULL),
(16, '199106182017022006', 'Dewi Lestari, S.Pd', '081234567806', NULL),
(17, '198907252014011007', 'Hendra Wijaya, S.Kom', '081234567807', NULL),
(18, '199308302019022008', 'Siti Nurhaliza, S.Pd', '081234567808', NULL),
(19, '199409142020022009', 'Anisa Kusumawati, S.Pd', '081234567809', NULL),
(20, '1111123456789094', 'Pandy', '081234567899', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `guru_mapel`
--

CREATE TABLE `guru_mapel` (
  `id_guru` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guru_mapel`
--

INSERT INTO `guru_mapel` (`id_guru`, `id_mapel`) VALUES
(15, 6),
(15, 7),
(15, 12),
(15, 14),
(15, 18),
(20, 6),
(20, 12);

-- --------------------------------------------------------

--
-- Table structure for table `izin_guru`
--

CREATE TABLE `izin_guru` (
  `id_izin_guru` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `kategori_izin` enum('sakit','dinas_luar','urusan_keluarga','pelatihan','lainnya') NOT NULL DEFAULT 'sakit',
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `alasan_izin` text NOT NULL,
  `bukti_surat` varchar(255) DEFAULT NULL,
  `status_approval` enum('pending','disetujui_piket','disetujui_waka','disetujui_kepsek','ditolak') NOT NULL DEFAULT 'pending',
  `disetujui_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `catatan_approver` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_pelajaran`
--

CREATE TABLE `jadwal_pelajaran` (
  `id_jadwal` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_ke` int(11) NOT NULL,
  `id_jam` int(11) DEFAULT NULL,
  `id_guru` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `ruangan` varchar(50) DEFAULT 'R. 57'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jadwal_pelajaran`
--

INSERT INTO `jadwal_pelajaran` (`id_jadwal`, `id_kelas`, `hari`, `jam_ke`, `id_jam`, `id_guru`, `id_mapel`, `ruangan`) VALUES
(1, 1, 'Selasa', 1, NULL, 2, 1, NULL),
(8, 2, 'Selasa', 1, NULL, 5, 3, NULL),
(9, 3, 'Rabu', 2, NULL, 6, 5, NULL),
(10, 4, 'Kamis', 4, NULL, 7, 4, NULL),
(13, 12, 'Senin', 3, NULL, 13, 7, NULL),
(14, 6, 'Senin', 2, 710, 2, 1, 'R.58'),
(15, 14, 'Selasa', 1, NULL, 15, 13, NULL),
(17, 5, 'Selasa', 7, 716, 18, 4, 'Lab. RPL 1');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_piket`
--

CREATE TABLE `jadwal_piket` (
  `id_piket` bigint(20) UNSIGNED NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat') NOT NULL,
  `id_guru` bigint(20) UNSIGNED NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_piket`
--

INSERT INTO `jadwal_piket` (`id_piket`, `hari`, `id_guru`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'Senin', 1, 'Petugas Piket Utama Senin', '2026-08-31 04:19:07', '2026-08-31 04:19:07'),
(2, 'Selasa', 2, 'Petugas Piket Utama Selasa', '2026-08-31 04:19:07', '2026-08-31 04:19:07'),
(3, 'Rabu', 3, 'Petugas Piket Utama Rabu', '2026-08-31 04:19:07', '2026-08-31 04:19:07'),
(4, 'Kamis', 4, 'Petugas Piket Utama Kamis', '2026-08-31 04:19:07', '2026-08-31 04:19:07'),
(5, 'Jumat', 5, 'Petugas Piket Utama Jumat', '2026-08-31 04:19:07', '2026-08-31 04:19:07');

-- --------------------------------------------------------

--
-- Table structure for table `jam_pelajaran`
--

CREATE TABLE `jam_pelajaran` (
  `id_jam` int(11) NOT NULL,
  `hari_kategori` varchar(255) NOT NULL DEFAULT 'Senin-Kamis',
  `jam_ke` int(11) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `is_istirahat` tinyint(1) NOT NULL DEFAULT 0,
  `bisa_diisi_mapel` tinyint(1) NOT NULL DEFAULT 1,
  `berlaku_hari` varchar(255) DEFAULT NULL COMMENT 'Null/Semua Hari, atau comma-separated misal: Senin,Selasa',
  `durasi_menit` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_pelajaran`
--

INSERT INTO `jam_pelajaran` (`id_jam`, `hari_kategori`, `jam_ke`, `jam_mulai`, `jam_selesai`, `is_istirahat`, `bisa_diisi_mapel`, `berlaku_hari`, `durasi_menit`, `keterangan`, `created_at`, `updated_at`) VALUES
(709, 'Senin-Kamis', 1, '07:00:00', '07:40:00', 0, 0, 'Senin', 40, 'Jam ke-1', '2026-08-24 05:11:24', '2026-08-24 05:16:33'),
(710, 'Senin-Kamis', 2, '07:40:00', '08:20:00', 0, 1, NULL, 40, 'Jam ke-2', '2026-08-24 05:11:24', '2026-08-24 05:11:24'),
(711, 'Senin-Kamis', 3, '08:20:00', '09:00:00', 0, 1, NULL, 40, 'Jam ke-3', '2026-08-24 05:11:24', '2026-08-24 05:11:24'),
(712, 'Senin-Kamis', 4, '09:00:00', '09:40:00', 0, 1, NULL, 40, 'Jam ke-4', '2026-08-24 05:11:24', '2026-08-24 05:11:24'),
(713, 'Senin-Kamis', 0, '09:40:00', '10:00:00', 1, 0, NULL, 20, 'Istirahat 1', '2026-08-24 05:11:24', '2026-08-24 05:11:24'),
(714, 'Senin-Kamis', 5, '10:00:00', '10:35:00', 0, 1, NULL, 35, 'Jam ke-5', '2026-08-24 05:11:24', '2026-08-24 05:11:24'),
(715, 'Senin-Kamis', 6, '10:35:00', '11:10:00', 0, 1, NULL, 35, 'Jam ke-6', '2026-08-24 05:11:24', '2026-08-24 05:11:24'),
(716, 'Senin-Kamis', 7, '11:10:00', '11:45:00', 0, 1, NULL, 35, 'Jam ke-7', '2026-08-24 05:11:24', '2026-08-24 05:11:24'),
(717, 'Senin-Kamis', 0, '11:45:00', '13:15:00', 1, 0, NULL, 90, 'Istirahat 2 (Sholat/Makan)', '2026-08-24 05:11:24', '2026-08-24 05:13:43'),
(718, 'Senin-Kamis', 8, '13:15:00', '13:50:00', 0, 1, NULL, 35, 'Jam ke-8', '2026-08-24 05:11:24', '2026-08-24 05:13:43'),
(719, 'Senin-Kamis', 9, '13:50:00', '14:25:00', 0, 1, NULL, 35, 'Jam ke-9', '2026-08-24 05:11:24', '2026-08-24 06:05:12'),
(720, 'Senin-Kamis', 10, '14:25:00', '15:00:00', 0, 1, NULL, 35, 'Jam ke-10', '2026-08-24 05:11:24', '2026-08-24 06:05:12'),
(722, 'Jumat', 1, '07:00:00', '07:30:00', 0, 0, NULL, 30, 'Jam ke-1', '2026-08-24 05:15:57', '2026-08-24 05:16:15'),
(723, 'Jumat', 2, '07:30:00', '08:00:00', 0, 1, NULL, 30, 'Jam ke-2', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(724, 'Jumat', 3, '08:00:00', '08:30:00', 0, 1, NULL, 30, 'Jam ke-3', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(725, 'Jumat', 4, '08:30:00', '09:00:00', 0, 1, NULL, 30, 'Jam ke-4', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(726, 'Jumat', 5, '09:00:00', '09:30:00', 0, 1, NULL, 30, 'Jam ke-5', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(727, 'Jumat', 0, '09:30:00', '09:50:00', 1, 0, NULL, 20, 'Istirahat 1', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(728, 'Jumat', 6, '09:50:00', '10:20:00', 0, 1, NULL, 30, 'Jam ke-6', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(729, 'Jumat', 7, '10:20:00', '10:50:00', 0, 1, NULL, 30, 'Jam ke-7', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(730, 'Jumat', 8, '10:50:00', '11:20:00', 0, 1, NULL, 30, 'Jam ke-8', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(731, 'Jumat', 0, '11:20:00', '13:00:00', 1, 0, NULL, 100, 'Istirahat 2 (Sholat/Makan)', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(732, 'Jumat', 9, '13:00:00', '13:30:00', 0, 1, NULL, 30, 'Jam ke-9', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(733, 'Jumat', 10, '13:30:00', '14:00:00', 0, 1, NULL, 30, 'Jam ke-10', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(734, 'Jumat', 11, '14:00:00', '14:30:00', 0, 1, NULL, 30, 'Jam ke-11', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(735, 'Jumat', 12, '14:30:00', '15:00:00', 0, 1, NULL, 30, 'Jam ke-12', '2026-08-24 05:15:57', '2026-08-24 05:15:57'),
(736, 'Jumat', 13, '15:00:00', '15:30:00', 0, 1, NULL, 30, 'Jam ke-13', '2026-08-24 05:15:57', '2026-08-24 05:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_mengajar`
--

CREATE TABLE `jurnal_mengajar` (
  `id_jurnal` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status_kehadiran` enum('Hadir','Izin','Sakit','Tanpa Keterangan') NOT NULL,
  `id_guru_pengganti` int(11) DEFAULT NULL,
  `materi` varchar(255) DEFAULT NULL,
  `jumlah_hadir` int(11) DEFAULT 0,
  `jumlah_tidak_hadir` int(11) DEFAULT 0,
  `catatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jurnal_mengajar`
--

INSERT INTO `jurnal_mengajar` (`id_jurnal`, `id_jadwal`, `tanggal`, `status_kehadiran`, `id_guru_pengganti`, `materi`, `jumlah_hadir`, `jumlah_tidak_hadir`, `catatan`) VALUES
(1, 1, '2026-07-28', 'Hadir', NULL, 'Konsentrasi RPL - Pengenalan Basis Data (Revisi: ERD & Normalisasi)', 31, 1, NULL),
(8, 8, '2026-07-27', 'Hadir', NULL, 'Pemrograman Web - HTML Dasar', 29, 1, NULL),
(10, 10, '2026-07-27', 'Hadir', NULL, 'Basis Data - Normalisasi', 28, 1, NULL),
(13, 13, '2026-08-11', 'Hadir', NULL, 'Reading Comprehension & Technical Vocabulary', 33, 1, '1 siswa izin sakit.');

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int(11) NOT NULL,
  `kode_jurusan` varchar(10) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `kode_jurusan`, `nama_jurusan`, `deleted_at`) VALUES
(1, 'RPL', 'Rekayasa Perangkat Lunak', NULL),
(2, 'TKJ', 'Teknik Komputer dan Jaringan', NULL),
(3, 'MM', 'Multimedia', NULL),
(4, 'DKV', 'Desain Komunikasi Visual', NULL),
(5, 'ANIM', 'Animasi', NULL),
(6, 'KLN', 'Kuliner', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `tingkat` enum('X','XI','XII') NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `rombel` int(11) NOT NULL,
  `id_guru_wali` int(11) DEFAULT NULL,
  `wali_kelas` varchar(100) DEFAULT NULL,
  `jumlah_siswa` int(11) DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `tingkat`, `id_jurusan`, `rombel`, `id_guru_wali`, `wali_kelas`, `jumlah_siswa`, `deleted_at`) VALUES
(1, 'XI', 1, 1, NULL, 'Kurnila Putri Islamawati, S.Pd', 32, NULL),
(2, 'XI', 1, 2, NULL, 'Budi Santoso, S.Kom', 30, NULL),
(3, 'XI', 2, 1, NULL, 'Agus Prasetyo, S.T', 31, NULL),
(4, 'XI', 2, 2, NULL, 'Hendra Wijaya, S.Kom', 29, '2026-08-12 09:57:37'),
(5, 'X', 1, 1, 17, 'Hendra Wijaya, S.Kom', 33, NULL),
(6, 'X', 1, 2, NULL, 'Dewi Lestari, S.Pd', 32, NULL),
(7, 'XII', 1, 1, NULL, 'Anisa Kusumawati, S.Pd', 30, NULL),
(8, 'XII', 1, 2, NULL, 'Siti Nurhaliza, S.Pd', 28, NULL),
(9, 'X', 2, 1, NULL, 'Shinta Indyar Smoney Susanto, S.Pd', 31, NULL),
(10, 'XII', 2, 1, NULL, 'Trisno Wibowo, S.Pd., M.M.', 30, NULL),
(11, 'X', 2, 2, NULL, 'PAK TRIS', 30, NULL),
(12, 'XI', 4, 1, 13, 'Budi Santoso, S.Kom', 34, NULL),
(13, 'X', 5, 1, 16, 'Dewi Lestari, S.Pd', 28, NULL),
(14, 'X', 6, 2, 15, 'Agus Prasetyo, S.T', 31, NULL),
(15, 'X', 3, 5, 5, 'Budi Santoso, S.Kom', 36, NULL),
(16, 'X', 4, 4, 4, 'Anisa Kusumawati, S.Pd', 36, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` int(11) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `nama_mapel`, `deleted_at`) VALUES
(1, 'Konsentrasi RPL', NULL),
(2, 'Kreativitas, Inovasi dan Kewirausahaan', NULL),
(3, 'Pemrograman Web', NULL),
(4, 'Basis Data', NULL),
(5, 'Matematika', NULL),
(6, 'Bahasa Indonesia', NULL),
(7, 'Bahasa Inggris', NULL),
(8, 'Pendidikan Agama', NULL),
(9, 'PJOK', NULL),
(10, 'Sistem Jaringan', '2026-08-04 18:51:49'),
(11, 'Informatika', NULL),
(12, 'Bahasa Jawa', NULL),
(13, 'Seni Budaya', NULL),
(14, 'Bahasa Jepang', NULL),
(15, 'IPAS', '2026-08-12 10:51:19'),
(16, 'PPKN', NULL),
(17, 'test', '2026-08-12 10:16:48'),
(18, 'Bahasa Korea', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_05_013912_add_soft_deletes_to_master_tables', 2),
(5, '2026_08_06_000000_add_role_to_users_table', 3),
(6, '2026_08_06_100000_create_guru_mapel_table', 4),
(7, '2026_08_07_000000_add_id_guru_to_users_table', 5),
(8, '2026_08_11_000001_update_roles_in_users_table', 6),
(9, '2026_08_11_000002_create_jam_pelajaran_table', 6),
(10, '2026_08_11_000003_add_id_guru_wali_to_kelas_table', 6),
(11, '2026_08_11_000004_extend_detail_ketidakhadiran_table', 6),
(12, '2026_08_11_000005_create_permohonan_izin_and_approval_logs_tables', 6),
(13, '2026_08_12_011630_add_super_admin_to_users_table', 7),
(14, '2026_08_12_163453_add_ruangan_to_jadwal_pelajaran_table', 8),
(15, '2026_08_15_000001_add_hari_kategori_to_jam_pelajaran_table', 9),
(16, '2026_08_15_000002_create_pengaturan_jam_sekolah_table', 9),
(17, '2026_08_16_081416_add_bisa_diisi_mapel_to_jam_pelajaran_table', 10),
(18, '2026_08_16_090000_add_berlaku_hari_to_jam_pelajaran_table', 11),
(19, '2026_08_18_000001_add_avatar_to_users_table', 12),
(20, '2026_08_18_000001_create_ruangan_table', 13),
(21, '2026_08_24_000001_add_break_settings_to_pengaturan_jam_sekolah_table', 14),
(22, '2026_08_24_000002_add_flexible_break_mode_to_pengaturan_jam_sekolah_table', 15),
(23, '2026_08_24_000003_add_variadic_kbm_duration_to_pengaturan_jam_sekolah_table', 16),
(24, '2026_08_24_000004_create_surat_dispensasi_table', 17),
(25, '2026_08_24_000005_create_izin_guru_table', 18),
(26, '2026_08_24_145807_add_no_telepon_to_siswa_table', 19),
(27, '2026_08_26_192800_add_jenis_kelamin_to_siswa_table', 20);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_jam_sekolah`
--

CREATE TABLE `pengaturan_jam_sekolah` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hari_kategori` varchar(255) NOT NULL,
  `durasi_per_jam` int(11) NOT NULL DEFAULT 40,
  `mode_durasi_kbm` varchar(255) NOT NULL DEFAULT 'seragam',
  `durasi_jam_utama` int(11) DEFAULT NULL,
  `sampai_jam_ke` int(11) DEFAULT NULL,
  `durasi_jam_setelahnya` int(11) DEFAULT NULL,
  `jam_masuk` time NOT NULL DEFAULT '07:00:00',
  `jam_pulang` time NOT NULL DEFAULT '14:30:00',
  `durasi_istirahat_1` int(11) DEFAULT NULL,
  `setelah_jam_ke_1` int(11) DEFAULT NULL,
  `mode_istirahat_1` varchar(255) NOT NULL DEFAULT 'durasi',
  `jam_mulai_istirahat_1` time DEFAULT NULL,
  `jam_selesai_istirahat_1` time DEFAULT NULL,
  `durasi_istirahat_2` int(11) DEFAULT NULL,
  `setelah_jam_ke_2` int(11) DEFAULT NULL,
  `mode_istirahat_2` varchar(255) NOT NULL DEFAULT 'durasi',
  `jam_mulai_istirahat_2` time DEFAULT NULL,
  `jam_selesai_istirahat_2` time DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengaturan_jam_sekolah`
--

INSERT INTO `pengaturan_jam_sekolah` (`id`, `hari_kategori`, `durasi_per_jam`, `mode_durasi_kbm`, `durasi_jam_utama`, `sampai_jam_ke`, `durasi_jam_setelahnya`, `jam_masuk`, `jam_pulang`, `durasi_istirahat_1`, `setelah_jam_ke_1`, `mode_istirahat_1`, `jam_mulai_istirahat_1`, `jam_selesai_istirahat_1`, `durasi_istirahat_2`, `setelah_jam_ke_2`, `mode_istirahat_2`, `jam_mulai_istirahat_2`, `jam_selesai_istirahat_2`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'Senin-Kamis', 40, 'variatif', 40, 4, 35, '07:00:00', '15:00:00', 20, 4, 'durasi', '09:40:00', '10:00:00', 60, 7, 'durasi', '12:00:00', '12:30:00', NULL, '2026-08-15 03:36:41', '2026-08-24 05:11:24'),
(2, 'Jumat', 30, 'seragam', 30, 8, 15, '07:00:00', '15:30:00', 20, 5, 'durasi', '08:30:00', '08:45:00', NULL, NULL, 'pukul', '11:20:00', '13:00:00', NULL, '2026-08-15 03:36:41', '2026-08-24 05:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `permohonan_izin`
--

CREATE TABLE `permohonan_izin` (
  `id_permohonan` int(11) NOT NULL,
  `tipe_pemohon` enum('guru','siswa') NOT NULL,
  `id_guru` int(11) DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `jenis_izin` varchar(100) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `alasan` text NOT NULL,
  `bukti_surat` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved_piket','approved_waka','approved_waka_sdm','approved_kepsek','rejected') NOT NULL DEFAULT 'pending',
  `catatan_revisi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permohonan_izin`
--

INSERT INTO `permohonan_izin` (`id_permohonan`, `tipe_pemohon`, `id_guru`, `id_siswa`, `jenis_izin`, `tanggal_mulai`, `tanggal_selesai`, `alasan`, `bukti_surat`, `status`, `catatan_revisi`, `created_at`, `updated_at`) VALUES
(3, 'siswa', NULL, 35, 'Sakit', '2026-08-26', '2026-08-26', 'Pemeriksaan dokter RSUD', 'surat_dokter_megan.pdf', 'approved_piket', NULL, '2026-08-26 00:36:06', '2026-08-26 01:03:37'),
(4, 'siswa', NULL, 36, 'Izin', '2026-08-26', '2026-08-26', 'Acara keluarga', 'surat_orang_tua_bella.pdf', 'approved_waka', NULL, '2026-08-26 00:36:06', '2026-08-26 01:03:37'),
(5, 'siswa', NULL, 37, 'Sakit', '2026-08-26', '2026-08-26', 'Sakit Flu & Batuk', 'surat_dokter_canva.pdf', 'approved_piket', NULL, '2026-08-26 00:36:06', '2026-08-26 01:03:37'),
(6, 'siswa', NULL, 38, 'Izin', '2026-08-26', '2026-08-26', 'Kepentingan keluarga', 'surat_orang_tua_ilona.pdf', 'approved_piket', NULL, '2026-08-26 00:36:06', '2026-08-26 01:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id_ruangan` bigint(20) UNSIGNED NOT NULL,
  `nama_ruangan` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id_ruangan`, `nama_ruangan`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'R. 57', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(2, 'R. 58', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(3, 'R. 59', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(4, 'R. 60', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(5, 'R. 61', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(6, 'R. 62', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(7, 'R. 66', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(8, 'Lab. RPL 1', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(9, 'Lab. RPL 2', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(10, 'Lab. TKJ 1', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(11, 'Lab. TKJ 2', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(12, 'Lab. Multimedia', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(14, 'Ruang Teori 1', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(15, 'Ruang Teori 2', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(16, 'Perpustakaan', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(17, 'Aula', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58'),
(18, 'LAP', NULL, '2026-08-18 07:01:58', '2026-08-18 07:01:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('JamiB3ImzN4aAEdtV751cp0A3mFsQlM3J1GOtxSf', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:154.0) Gecko/20100101 Firefox/154.0', 'eyJfdG9rZW4iOiJJeHF4SWtIVXFuQWVadG9JY3c2MFk1TTUyRzUwY0JQdTZldzVCUXltIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==', 1788160317);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nisn` varchar(10) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `id_kelas` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nisn`, `nama_siswa`, `jenis_kelamin`, `no_telepon`, `id_kelas`, `deleted_at`) VALUES
(1, '0051234561', 'Albert', NULL, NULL, 1, '2026-08-04 20:32:55'),
(2, '0051234562', 'Semmar Wicaksono', NULL, NULL, 1, NULL),
(3, '0051234563', 'Citra Ayu', NULL, NULL, 1, NULL),
(4, '0051234564', 'Doni Firmansyah', NULL, NULL, 1, NULL),
(5, '0051234565', 'Eka Putri', NULL, NULL, 2, NULL),
(6, '0051234566', 'Fajar Nugroho', NULL, NULL, 2, NULL),
(7, '0051234567', 'Gita Ramadhani', NULL, NULL, 3, NULL),
(8, '0051234568', 'Hilman Syahputra', NULL, NULL, 3, NULL),
(9, '0051234569', 'Indah Permata', NULL, NULL, 4, NULL),
(10, '0051234570', 'Joko Widodo', NULL, NULL, 4, NULL),
(20, '0987676986', 'dani', NULL, NULL, 3, NULL),
(22, '0000009878', 'jaki', NULL, NULL, 1, NULL),
(23, '0056789001', 'Ahmad Fauzi', 'L', NULL, 1, NULL),
(24, '0056789002', 'Bagus Pratama', NULL, NULL, 5, NULL),
(25, '0056789003', 'Citra Kirana', NULL, NULL, 12, NULL),
(26, '0056789004', 'Dinda Permata', NULL, NULL, 13, NULL),
(27, '0056789005', 'Eko Prasetyo', NULL, NULL, 14, NULL),
(28, '0056789006', 'Fani Rahmawati', NULL, NULL, 1, NULL),
(29, '0056789007', 'Gilang Ramadhan', NULL, NULL, 5, NULL),
(30, '0056789008', 'Hany Saputri', NULL, NULL, 12, NULL),
(31, '0056789009', 'Indra Wijaya', NULL, NULL, 13, NULL),
(32, '0056789010', 'Jasmine Aulia', NULL, NULL, 14, NULL),
(33, '0056789101', 'Azzura Atasya', NULL, NULL, 1, NULL),
(34, '0056789102', 'Felix Fernandez', NULL, NULL, 1, NULL),
(35, '0056789103', 'Megan Fernita', NULL, NULL, 1, NULL),
(36, '0056789104', 'Bella Sutanto', NULL, NULL, 1, NULL),
(37, '0056789105', 'Canva Narendra', NULL, NULL, 1, NULL),
(38, '0056789106', 'Ilona Lovita', NULL, NULL, 1, NULL),
(39, '1111111111', 'Aziz', NULL, '0812345678901', 16, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `surat_dispensasi`
--

CREATE TABLE `surat_dispensasi` (
  `id_dispen` int(11) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `tipe_pemohon` enum('siswa','guru') NOT NULL DEFAULT 'siswa',
  `id_siswa` int(11) DEFAULT NULL,
  `id_guru` int(11) DEFAULT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `lokasi_kegiatan` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `alasan_dispensasi` text NOT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `status_approval` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending',
  `disetujui_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `barcode_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_dispensasi`
--

INSERT INTO `surat_dispensasi` (`id_dispen`, `nomor_surat`, `tipe_pemohon`, `id_siswa`, `id_guru`, `id_kelas`, `nama_kegiatan`, `lokasi_kegiatan`, `tanggal_mulai`, `tanggal_selesai`, `jam_mulai`, `jam_selesai`, `alasan_dispensasi`, `file_surat`, `status_approval`, `disetujui_oleh`, `barcode_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DISPEN/2026/08/001', 'siswa', 2, NULL, 1, 'Lomba O2SN Tingkat Kota (Futsal)', 'GOR Tri Dharma', '2026-08-30', '2026-08-30', '08:00:00', '14:00:00', 'Mewakili sekolah dalam Kejuaraan O2SN 2026', NULL, 'pending', NULL, '8c308a9e-8b9f-4ff7-aa4c-64232d7de730', '2026-08-29 23:23:26', '2026-08-29 23:23:26', NULL),
(2, 'DISPEN/2026/08/002', 'siswa', 3, NULL, 1, 'Olimpiade Sains Nasional (OSN) Kebumian', 'SMA Negeri 1 Kota', '2026-08-30', '2026-08-30', '07:30:00', '12:00:00', 'Mengikuti babak final OSN Kebumian', NULL, 'disetujui', NULL, 'd190fdba-d3c7-4855-9ef5-a910980d1f56', '2026-08-29 23:23:26', '2026-08-29 23:23:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` enum('admin','super_admin','guru_mengajar','guru_piket','wali_kelas','kepala_sekolah','waka','waka_sdm','satpam') NOT NULL DEFAULT 'guru_mengajar',
  `id_guru` int(11) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `id_guru`, `avatar`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'root', 'super_admin', NULL, 'avatars/HfiOBW1jBsvLHrtp8tYzXRPgzBSc1jS8rVXeqNe6.jpg', '$2y$12$ZKH5IjOu3eb0Mkiz3Nl8Leyon3xmJh9SxvJsRasZ6wT/qY9Ao58Lq', NULL, '2026-08-06 03:59:25', '2026-08-24 06:54:14'),
(2, 'Guru Mengajar', 'guru_mengajar', NULL, NULL, '$2y$12$nnpL6quxgWRzJiIV.v4mSuLlAGXIc27McF8VzNx1GVdNTuc9SrNbu', NULL, '2026-08-06 03:59:25', '2026-08-11 00:32:24'),
(3, 'Wali Kelas', 'wali_kelas', NULL, NULL, '$2y$12$LnLe1M/U4fJz3wSJDA/m6eC7MiJ5RR14qfIHLNz5.MmCUmdW/L9dG', NULL, '2026-08-06 03:59:25', '2026-08-11 00:32:24'),
(4, 'Guru Piket', 'guru_piket', NULL, NULL, '$2y$12$LHseJcifIrIXyc73AUTh4uPts02BMYAMUy7UJN3meuIcVAP20Qf8a', NULL, '2026-08-06 03:59:25', '2026-08-11 00:32:24'),
(5, 'Trisno Wibowo (Guru)', 'guru_mengajar', 11, NULL, '$2y$12$7LmJJyWUMhvU0tqMBlVZ3e.gERzJ3ydL5OXeNkJBYPRBWm2oc.ZS.', NULL, '2026-08-11 00:33:02', '2026-08-11 00:33:36'),
(6, 'Kurnila (Wali Kelas)', 'wali_kelas', 12, NULL, '$2y$12$hfKXokJZ/IbTwgoLMLJxTuR1nakfSNT2OlQd37xQSB9rgiAZta0CC', NULL, '2026-08-11 00:33:02', '2026-08-11 00:33:36'),
(7, 'Budi Santoso (Guru Piket)', 'guru_piket', 13, NULL, '$2y$12$pB4avB1IgjSaQG1Xjjwu1OCzEUfu9eR8DO1WyHQzOKhjYzO4dmKSi', NULL, '2026-08-11 00:33:02', '2026-08-11 00:33:36'),
(8, 'Kepala Sekolah', 'kepala_sekolah', NULL, NULL, '$2y$12$3NlFnHhDJS9nSXrTPJ4F/e6cTROUEaNcbs/iVrRiCif7KS.d6gHsW', NULL, '2026-08-11 00:33:02', '2026-08-31 06:19:59'),
(9, 'Waka SDM', 'waka_sdm', NULL, NULL, '$2y$12$jmU3XTLP2RF0idhg658LWeUAdxEdj04rv1G3/SBRrcl4f2JCuh2mK', NULL, '2026-08-11 00:33:02', '2026-08-31 06:19:59'),
(10, 'Satpam Gerbang', 'satpam', NULL, NULL, '$2y$12$U1R1NRAmxAuo6OskIeWdpOuhnlWu4hdqD8wfeSgJBHqqNa.5UNXZC', NULL, '2026-08-11 00:33:02', '2026-08-11 00:33:36'),
(11, 'Pandy', 'guru_mengajar', 20, NULL, '$2y$12$KeR2SALHp96zkb0ITeCVj.84rIkzCufz.Ei4Z379AVgzHQ3qP8uHy', NULL, '2026-08-31 05:02:46', '2026-08-31 05:33:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approval_logs`
--
ALTER TABLE `approval_logs`
  ADD PRIMARY KEY (`id_approval`),
  ADD KEY `approval_logs_id_permohonan_foreign` (`id_permohonan`),
  ADD KEY `approval_logs_id_user_approver_foreign` (`id_user_approver`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `detail_ketidakhadiran`
--
ALTER TABLE `detail_ketidakhadiran`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_jurnal` (`id_jurnal`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `detail_ketidakhadiran_id_guru_piket_foreign` (`id_guru_piket`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `nip` (`nuptk`);

--
-- Indexes for table `guru_mapel`
--
ALTER TABLE `guru_mapel`
  ADD PRIMARY KEY (`id_guru`,`id_mapel`),
  ADD KEY `guru_mapel_id_mapel_foreign` (`id_mapel`);

--
-- Indexes for table `izin_guru`
--
ALTER TABLE `izin_guru`
  ADD PRIMARY KEY (`id_izin_guru`),
  ADD KEY `izin_guru_id_guru_foreign` (`id_guru`),
  ADD KEY `izin_guru_disetujui_oleh_foreign` (`disetujui_oleh`);

--
-- Indexes for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD UNIQUE KEY `uniq_jadwal` (`id_kelas`,`hari`,`jam_ke`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_mapel` (`id_mapel`),
  ADD KEY `jadwal_pelajaran_id_jam_foreign` (`id_jam`);

--
-- Indexes for table `jadwal_piket`
--
ALTER TABLE `jadwal_piket`
  ADD PRIMARY KEY (`id_piket`);

--
-- Indexes for table `jam_pelajaran`
--
ALTER TABLE `jam_pelajaran`
  ADD PRIMARY KEY (`id_jam`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurnal_mengajar`
--
ALTER TABLE `jurnal_mengajar`
  ADD PRIMARY KEY (`id_jurnal`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `id_guru_pengganti` (`id_guru_pengganti`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`),
  ADD UNIQUE KEY `kode_jurusan` (`kode_jurusan`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD UNIQUE KEY `uniq_kelas` (`tingkat`,`id_jurusan`,`rombel`),
  ADD KEY `id_jurusan` (`id_jurusan`),
  ADD KEY `kelas_id_guru_wali_foreign` (`id_guru_wali`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pengaturan_jam_sekolah`
--
ALTER TABLE `pengaturan_jam_sekolah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengaturan_jam_sekolah_hari_kategori_unique` (`hari_kategori`);

--
-- Indexes for table `permohonan_izin`
--
ALTER TABLE `permohonan_izin`
  ADD PRIMARY KEY (`id_permohonan`),
  ADD KEY `permohonan_izin_id_guru_foreign` (`id_guru`),
  ADD KEY `permohonan_izin_id_siswa_foreign` (`id_siswa`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id_ruangan`),
  ADD UNIQUE KEY `ruangan_nama_ruangan_unique` (`nama_ruangan`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `nisn` (`nisn`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `surat_dispensasi`
--
ALTER TABLE `surat_dispensasi`
  ADD PRIMARY KEY (`id_dispen`),
  ADD UNIQUE KEY `surat_dispensasi_nomor_surat_unique` (`nomor_surat`),
  ADD UNIQUE KEY `surat_dispensasi_barcode_token_unique` (`barcode_token`),
  ADD KEY `surat_dispensasi_id_siswa_foreign` (`id_siswa`),
  ADD KEY `surat_dispensasi_id_guru_foreign` (`id_guru`),
  ADD KEY `surat_dispensasi_id_kelas_foreign` (`id_kelas`),
  ADD KEY `surat_dispensasi_disetujui_oleh_foreign` (`disetujui_oleh`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id_guru_foreign` (`id_guru`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approval_logs`
--
ALTER TABLE `approval_logs`
  MODIFY `id_approval` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_ketidakhadiran`
--
ALTER TABLE `detail_ketidakhadiran`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `izin_guru`
--
ALTER TABLE `izin_guru`
  MODIFY `id_izin_guru` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `jadwal_piket`
--
ALTER TABLE `jadwal_piket`
  MODIFY `id_piket` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jam_pelajaran`
--
ALTER TABLE `jam_pelajaran`
  MODIFY `id_jam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=737;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurnal_mengajar`
--
ALTER TABLE `jurnal_mengajar`
  MODIFY `id_jurnal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `pengaturan_jam_sekolah`
--
ALTER TABLE `pengaturan_jam_sekolah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permohonan_izin`
--
ALTER TABLE `permohonan_izin`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id_ruangan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `surat_dispensasi`
--
ALTER TABLE `surat_dispensasi`
  MODIFY `id_dispen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approval_logs`
--
ALTER TABLE `approval_logs`
  ADD CONSTRAINT `approval_logs_id_permohonan_foreign` FOREIGN KEY (`id_permohonan`) REFERENCES `permohonan_izin` (`id_permohonan`) ON DELETE CASCADE,
  ADD CONSTRAINT `approval_logs_id_user_approver_foreign` FOREIGN KEY (`id_user_approver`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_ketidakhadiran`
--
ALTER TABLE `detail_ketidakhadiran`
  ADD CONSTRAINT `detail_ketidakhadiran_ibfk_1` FOREIGN KEY (`id_jurnal`) REFERENCES `jurnal_mengajar` (`id_jurnal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_ketidakhadiran_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_ketidakhadiran_id_guru_piket_foreign` FOREIGN KEY (`id_guru_piket`) REFERENCES `guru` (`id_guru`) ON DELETE SET NULL;

--
-- Constraints for table `guru_mapel`
--
ALTER TABLE `guru_mapel`
  ADD CONSTRAINT `guru_mapel_id_guru_foreign` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE,
  ADD CONSTRAINT `guru_mapel_id_mapel_foreign` FOREIGN KEY (`id_mapel`) REFERENCES `mapel` (`id_mapel`) ON DELETE CASCADE;

--
-- Constraints for table `izin_guru`
--
ALTER TABLE `izin_guru`
  ADD CONSTRAINT `izin_guru_disetujui_oleh_foreign` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `izin_guru_id_guru_foreign` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_2` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_3` FOREIGN KEY (`id_mapel`) REFERENCES `mapel` (`id_mapel`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_pelajaran_id_jam_foreign` FOREIGN KEY (`id_jam`) REFERENCES `jam_pelajaran` (`id_jam`) ON DELETE SET NULL;

--
-- Constraints for table `jurnal_mengajar`
--
ALTER TABLE `jurnal_mengajar`
  ADD CONSTRAINT `jurnal_mengajar_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_pelajaran` (`id_jadwal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jurnal_mengajar_ibfk_2` FOREIGN KEY (`id_guru_pengganti`) REFERENCES `guru` (`id_guru`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`) ON UPDATE CASCADE,
  ADD CONSTRAINT `kelas_id_guru_wali_foreign` FOREIGN KEY (`id_guru_wali`) REFERENCES `guru` (`id_guru`) ON DELETE SET NULL;

--
-- Constraints for table `permohonan_izin`
--
ALTER TABLE `permohonan_izin`
  ADD CONSTRAINT `permohonan_izin_id_guru_foreign` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE,
  ADD CONSTRAINT `permohonan_izin_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `surat_dispensasi`
--
ALTER TABLE `surat_dispensasi`
  ADD CONSTRAINT `surat_dispensasi_disetujui_oleh_foreign` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `surat_dispensasi_id_guru_foreign` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE,
  ADD CONSTRAINT `surat_dispensasi_id_kelas_foreign` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `surat_dispensasi_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_guru_foreign` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
