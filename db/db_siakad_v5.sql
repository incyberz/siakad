-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2023 at 03:58 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_siakad`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin_level`
--

CREATE TABLE `tb_admin_level` (
  `admin_level` tinyint(4) NOT NULL,
  `jenis_user` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin_level`
--

INSERT INTO `tb_admin_level` (`admin_level`, `jenis_user`) VALUES
(-4, 'Read Only Mode'),
(-3, 'Tendik Inactive'),
(-2, 'Dosen Inactive'),
(-1, 'Mhs Inactive'),
(0, 'Pengunjung'),
(1, 'Mahasiswa'),
(2, 'Lulusan'),
(3, 'Dosen'),
(4, 'Staf Front Office'),
(5, 'Staf BAK'),
(6, 'Staf Keuangan'),
(7, 'Staf Khusus'),
(8, 'Staf Umum'),
(9, 'Sekertaris Prodi'),
(10, 'Kaprodi'),
(11, 'Kabid Kemahasiswaan'),
(12, 'Kabid Tracer Study'),
(13, 'Kabid LPPM'),
(14, 'Kabid Perpustakaan'),
(15, 'Kabid BPM'),
(16, 'Admin E-Learning'),
(20, 'Bendahara'),
(21, 'Kepala Yayasan'),
(23, 'Wakil Ketua'),
(24, 'Sekretaris Ketua'),
(25, 'Pimpinan PT'),
(99, 'Super Admin');

-- --------------------------------------------------------

--
-- Table structure for table `tb_angkatan`
--

CREATE TABLE `tb_angkatan` (
  `angkatan` smallint(4) NOT NULL,
  `tgl_pembukaan` date NOT NULL,
  `tgl_penutupan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_angkatan`
--

INSERT INTO `tb_angkatan` (`angkatan`, `tgl_pembukaan`, `tgl_penutupan`) VALUES
(2020, '2020-02-03', '2020-12-30'),
(2021, '2021-01-01', '2021-12-31'),
(2022, '2022-01-01', '2023-01-01'),
(2023, '2023-07-01', '2024-06-30');

-- --------------------------------------------------------

--
-- Table structure for table `tb_assign_ruang`
--

CREATE TABLE `tb_assign_ruang` (
  `id` int(11) NOT NULL,
  `id_ruang` int(11) NOT NULL,
  `id_sesi_kuliah` int(11) NOT NULL,
  `id_tipe_sesi` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_assign_ruang`
--

INSERT INTO `tb_assign_ruang` (`id`, `id_ruang`, `id_sesi_kuliah`, `id_tipe_sesi`) VALUES
(1, 1, 65, 2),
(2, 2, 65, 2),
(3, 3, 65, 2),
(4, 4, 65, 2),
(5, 1, 66, 2),
(6, 2, 66, 2),
(7, 3, 66, 2),
(8, 4, 66, 2),
(9, 1, 67, 2),
(10, 2, 67, 2),
(11, 3, 67, 2),
(12, 4, 67, 2),
(13, 1, 68, 2),
(14, 2, 68, 2),
(15, 3, 68, 2),
(16, 4, 68, 2),
(17, 1, 69, 2),
(18, 2, 69, 2),
(19, 3, 69, 2),
(20, 4, 69, 2),
(21, 1, 70, 2),
(22, 2, 70, 2),
(23, 3, 70, 2),
(24, 4, 70, 2),
(25, 1, 71, 2),
(26, 2, 71, 2),
(27, 3, 71, 2),
(28, 4, 71, 2),
(29, 1, 72, 2),
(30, 2, 72, 2),
(31, 3, 72, 2),
(32, 4, 72, 2),
(33, 1, 73, 2),
(34, 2, 73, 2),
(35, 3, 73, 2),
(36, 4, 73, 2),
(37, 1, 74, 2),
(38, 2, 74, 2),
(39, 3, 74, 2),
(40, 4, 74, 2),
(41, 1, 75, 2),
(42, 2, 75, 2),
(43, 3, 75, 2),
(44, 4, 75, 2),
(45, 1, 76, 2),
(46, 2, 76, 2),
(47, 3, 76, 2),
(48, 4, 76, 2),
(49, 1, 77, 2),
(50, 2, 77, 2),
(51, 3, 77, 2),
(52, 4, 77, 2),
(53, 1, 78, 2),
(54, 2, 78, 2),
(55, 3, 78, 2),
(56, 4, 78, 2),
(57, 1, 79, 2),
(58, 2, 79, 2),
(59, 3, 79, 2),
(60, 4, 79, 2),
(61, 1, 80, 2),
(62, 2, 80, 2),
(63, 3, 80, 2),
(64, 4, 80, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tb_bk`
--

CREATE TABLE `tb_bk` (
  `id` smallint(6) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_bk`
--

INSERT INTO `tb_bk` (`id`, `nama`) VALUES
(1, 'Pemrograman Web'),
(2, 'Pemrograman Mobile'),
(3, 'Pemrograman Desktop'),
(4, 'OS Linux'),
(5, 'OS Mikrotik'),
(6, 'IOS Cisco'),
(7, 'IT Security'),
(8, 'Oracle'),
(9, 'Desain'),
(10, 'Animasi'),
(11, 'Game'),
(12, 'Artificial Intelegence'),
(13, 'Data Mining'),
(14, 'Machine Learning'),
(15, 'Deep Learning'),
(16, 'e-Accounting'),
(17, 'MKDU');

-- --------------------------------------------------------

--
-- Table structure for table `tb_config`
--

CREATE TABLE `tb_config` (
  `id` smallint(6) NOT NULL,
  `last_install` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_dosen`
--

CREATE TABLE `tb_dosen` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `homebase` int(11) DEFAULT NULL,
  `nidn` char(10) DEFAULT NULL,
  `nama` varchar(50) NOT NULL,
  `no_wa` varchar(14) DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `jabatan_akademik` varchar(20) DEFAULT NULL,
  `folder_uploads` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_dosen`
--

INSERT INTO `tb_dosen` (`id`, `id_user`, `homebase`, `nidn`, `nama`, `no_wa`, `status_aktif`, `jabatan_akademik`, `folder_uploads`) VALUES
(25, 25, 32, '0403078304', 'ABDUL AJIZ, S.T,M.Kom', NULL, 1, NULL, NULL),
(26, 26, 32, '8867401019', 'EDI TOHIDI, MM', NULL, 1, NULL, NULL),
(27, 27, 32, '0414036803', 'Drs. EDI WAHYUDIN, M.Pd', NULL, 1, 'Asisten Ahli', NULL),
(28, 28, 32, '0404076203', 'KASLANI, S.E.,M.M', NULL, 1, 'Asisten Ahli', NULL),
(29, 29, 32, '0403016302', 'Dra. NINING RAHANINGSIH, M.Si', NULL, 1, 'Lektor', NULL),
(30, 30, 32, '8927160022', 'WILLY PRIHARTONO, M.Kom', NULL, 1, NULL, NULL),
(31, 31, 31, '0402127701', 'DIAN ADE KURNIA, M.Kom', NULL, 1, 'Lektor', '_230409031237_331985'),
(32, 32, 31, '0416048305', 'MARTANTO, M.Kom', NULL, 1, 'Asisten Ahli', NULL),
(33, 33, 31, '0412049102', 'ODI NURDIAWAN, M.Kom', NULL, 1, 'Asisten Ahli', NULL),
(34, 34, 31, '0425108004', 'RADITYA DANAR DANA, M.Kom', NULL, 1, 'Asisten Ahli', NULL),
(35, 35, 31, '0417078204', 'SANDY EKA PERMANA', NULL, 1, NULL, NULL),
(36, 36, 42, '0401059402', 'ADE RIZKI RINALDI, M.Kom', NULL, 1, NULL, NULL),
(37, 37, 42, '0403049301', 'ARIF RINALDI DIKANANDA, M.Kom', NULL, 1, 'Asisten Ahli', NULL),
(38, 38, 42, '0413117701', 'CEP LUKMAN ROHMAT', NULL, 1, 'Asisten Ahli', NULL),
(39, 39, 42, '0401119103', 'FATHURROHMAN, M.Kom', NULL, 1, NULL, NULL),
(41, 41, 42, '0411068706', 'IIN, M.Kom', NULL, 1, 'Asisten Ahli', '_230409030355_032643'),
(42, 42, 42, '0406079401', 'IRFAN ALI, M.Kom', NULL, 1, 'Asisten Ahli', NULL),
(43, 43, 43, '0419017002', 'AGUS BAHTIAR, M.Kom', NULL, 1, 'Asisten Ahli', NULL),
(44, 44, 43, '0412129401', 'FADHIL MUHAMAD BASYSYAR, M.Kom', NULL, 1, 'Asisten Ahli', '_230410060715_600338'),
(45, 45, 43, '0401129402', 'GIFTHERA DWILESTARI', NULL, 1, 'Asisten Ahli', NULL),
(46, 46, 43, '0428066602', 'MULYAWAN, M.Kom', NULL, 1, NULL, NULL),
(47, 47, 43, '0401047103', 'YUDHISTIRA ARIE WIJAYA, M.Kom', NULL, 1, 'Asisten Ahli', NULL),
(48, 48, 41, '0429117402', 'ADE IRMA PURNAMA SARI, M.Kom', NULL, 1, 'Asisten Ahli', NULL),
(49, 49, 41, '0406038801', 'AHMAD FAQIH, M.Sc', NULL, 1, 'Asisten Ahli', NULL),
(50, 50, 41, '0418046301', 'Dr. DADANG SUDRAJAT, S.Si, M.Kom', NULL, 1, 'Lektor', NULL),
(51, 51, 41, '0415087407', 'DEDE ROHMAN', NULL, 1, NULL, NULL),
(52, 52, 41, '0421108006', 'DENDY INDRIYA EFENDI', NULL, 1, NULL, NULL),
(53, 53, 41, '8961210021', 'DODI SOLIHUDIN, S.T,M.T', NULL, 1, NULL, NULL),
(54, 54, 41, '0431058005', 'HELIYANTI SUSANA', NULL, 1, NULL, NULL),
(55, 55, 41, '0403127406', 'MUHAMAD SULAEMAN, M.Kom', NULL, 1, NULL, NULL),
(56, 56, 41, '0417126503', 'NANA SUARNA, M.Kom', NULL, 1, 'Lektor', NULL),
(57, 57, 41, '0405129203', 'NISA DIENWATI NURIS, M.Sos', NULL, 1, 'Asisten Ahli', '_230403075934_531864'),
(58, 58, 41, '0429128801', 'RIRI NARASATI, M.Hum', NULL, 1, 'Asisten Ahli', NULL),
(59, 59, 41, '0406027802', 'RULI HERDIANA, S.Kom,M.Kom', NULL, 1, NULL, NULL),
(60, 60, 41, '0418049201', 'RYAN HAMONANGAN', NULL, 1, NULL, NULL),
(61, 61, 41, '0414087803', 'SAEFUL ANWAR, M.Pd', NULL, 1, 'Lektor', NULL),
(62, 62, 41, '0411126604', 'TATI SUPRAPTI, M.Kom', NULL, 1, NULL, NULL),
(63, 63, 41, '0428086304', 'UMI HAYATI, M.Kom', NULL, 1, 'Asisten Ahli', NULL),
(64, 64, NULL, NULL, 'BAMBANG IRAWAN, MT', NULL, 1, NULL, NULL),
(65, 65, NULL, NULL, 'RINI ASTUTI, MT', NULL, 1, NULL, NULL),
(66, 66, NULL, NULL, 'IMAS MUFTI, MM', NULL, 1, NULL, NULL),
(67, 67, NULL, NULL, 'MASKURI, M.Ag', NULL, 1, NULL, NULL),
(68, 68, NULL, NULL, 'M. TAUFIK, M.Ag', NULL, 1, NULL, NULL),
(69, 69, NULL, NULL, 'BAMBANG SISWOYO,M.Si, M.Kom', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_event`
--

CREATE TABLE `tb_event` (
  `id_event` int(11) NOT NULL,
  `id_calon` int(11) DEFAULT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `id_file_name` varchar(30) DEFAULT NULL,
  `nama_event` varchar(50) DEFAULT NULL,
  `date_event` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipe_event` int(11) NOT NULL DEFAULT 1,
  `status_event` int(11) NOT NULL DEFAULT 0,
  `date_verif` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_fakultas`
--

CREATE TABLE `tb_fakultas` (
  `id` int(11) NOT NULL,
  `id_pt` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id_dekan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_fakultas`
--

INSERT INTO `tb_fakultas` (`id`, `id_pt`, `nama`, `id_dekan`) VALUES
(1, 1, 'Fakultas Komputer', NULL),
(2, 1, 'Fakultas Pertanian', NULL),
(3, 1, 'Fakultas Ekonomi', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_jadwal`
--

CREATE TABLE `tb_jadwal` (
  `id` int(11) NOT NULL,
  `id_kurikulum_mk` int(11) NOT NULL,
  `id_dosen` int(11) NOT NULL COMMENT 'Dosen Koordinator',
  `sesi_uts` tinyint(2) NOT NULL DEFAULT 8,
  `sesi_uas` tinyint(2) NOT NULL DEFAULT 16,
  `jumlah_sesi` tinyint(2) NOT NULL DEFAULT 16,
  `tanggal_jadwal` timestamp NOT NULL DEFAULT current_timestamp(),
  `menit_telat` tinyint(4) DEFAULT 30,
  `menit_stop_absen` tinyint(4) DEFAULT NULL,
  `id_status_jadwal` tinyint(4) DEFAULT NULL,
  `tanggal_approve_sesi` timestamp NULL DEFAULT current_timestamp() COMMENT 'by dosen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_jadwal`
--

INSERT INTO `tb_jadwal` (`id`, `id_kurikulum_mk`, `id_dosen`, `sesi_uts`, `sesi_uas`, `jumlah_sesi`, `tanggal_jadwal`, `menit_telat`, `menit_stop_absen`, `id_status_jadwal`, `tanggal_approve_sesi`) VALUES
(132, 154, 57, 8, 16, 16, '2023-04-01 01:25:56', 30, NULL, NULL, '2023-04-04 13:25:04'),
(133, 155, 34, 8, 16, 16, '2023-04-01 01:26:06', 30, NULL, NULL, NULL),
(134, 156, 67, 8, 16, 16, '2023-04-01 01:26:22', 30, NULL, NULL, NULL),
(135, 157, 31, 8, 16, 16, '2023-04-01 01:26:39', 30, NULL, NULL, NULL),
(136, 161, 49, 8, 16, 16, '2023-04-01 01:26:50', 30, NULL, NULL, NULL),
(137, 162, 56, 8, 16, 16, '2023-04-01 01:27:46', 30, NULL, NULL, NULL),
(138, 165, 26, 8, 16, 16, '2023-04-01 01:27:54', 30, NULL, NULL, NULL),
(139, 166, 58, 8, 16, 16, '2023-04-01 01:28:09', 30, NULL, NULL, NULL),
(140, 167, 58, 8, 16, 16, '2023-04-01 01:28:26', 30, NULL, NULL, NULL),
(141, 172, 49, 8, 16, 16, '2023-04-01 01:28:32', 30, NULL, NULL, NULL),
(142, 175, 49, 8, 16, 16, '2023-04-01 01:28:38', 30, NULL, NULL, NULL),
(143, 176, 31, 8, 16, 16, '2023-04-01 01:28:43', 30, NULL, NULL, NULL),
(144, 177, 48, 8, 16, 16, '2023-04-01 01:29:32', 30, NULL, NULL, NULL),
(145, 182, 32, 8, 16, 16, '2023-04-01 01:29:47', 30, NULL, NULL, NULL),
(146, 184, 57, 8, 16, 16, '2023-04-01 01:30:43', 30, NULL, NULL, NULL),
(147, 168, 48, 8, 16, 16, '2023-04-01 01:30:58', 30, NULL, NULL, NULL),
(148, 169, 34, 8, 16, 16, '2023-04-01 01:31:17', 30, NULL, NULL, NULL),
(149, 170, 49, 8, 16, 16, '2023-04-01 01:31:22', 30, NULL, NULL, NULL),
(150, 171, 32, 8, 16, 16, '2023-04-01 01:31:32', 30, NULL, NULL, NULL),
(151, 173, 44, 8, 16, 16, '2023-04-01 01:31:43', 30, NULL, NULL, NULL),
(152, 174, 31, 8, 16, 16, '2023-04-01 01:32:09', 30, NULL, NULL, NULL),
(153, 279, 32, 8, 16, 16, '2023-04-01 01:32:31', 30, NULL, NULL, NULL),
(154, 280, 56, 8, 16, 16, '2023-04-01 01:32:40', 30, NULL, NULL, NULL),
(155, 281, 49, 8, 16, 16, '2023-04-01 01:32:46', 30, NULL, NULL, NULL),
(156, 282, 43, 8, 16, 16, '2023-04-01 01:32:58', 30, NULL, NULL, NULL),
(157, 283, 32, 8, 16, 16, '2023-04-01 01:33:13', 30, NULL, NULL, NULL),
(158, 285, 48, 8, 16, 16, '2023-04-01 01:36:10', 30, NULL, NULL, NULL),
(159, 178, 47, 8, 16, 16, '2023-04-01 01:38:28', 30, NULL, NULL, NULL),
(160, 179, 32, 8, 16, 16, '2023-04-01 01:38:36', 30, NULL, NULL, NULL),
(161, 180, 47, 8, 16, 16, '2023-04-01 01:38:42', 30, NULL, NULL, NULL),
(162, 181, 47, 8, 16, 16, '2023-04-01 01:38:47', 30, NULL, NULL, NULL),
(163, 183, 31, 8, 16, 16, '2023-04-01 01:39:45', 30, NULL, NULL, NULL),
(164, 200, 31, 8, 16, 16, '2023-04-01 01:40:33', 30, NULL, NULL, NULL),
(165, 201, 33, 8, 16, 16, '2023-04-01 01:40:43', 30, NULL, NULL, NULL),
(166, 202, 44, 8, 16, 16, '2023-04-01 01:40:55', 30, NULL, NULL, NULL),
(167, 203, 31, 8, 16, 16, '2023-04-01 01:40:59', 30, NULL, NULL, NULL),
(168, 204, 47, 8, 16, 16, '2023-04-01 01:41:04', 30, NULL, NULL, NULL),
(169, 185, 31, 8, 16, 16, '2023-04-01 01:41:16', 30, NULL, NULL, NULL),
(170, 186, 50, 8, 16, 16, '2023-04-01 01:41:26', 30, NULL, NULL, NULL),
(171, 187, 31, 8, 16, 16, '2023-04-01 01:41:33', 30, NULL, NULL, NULL),
(172, 188, 57, 8, 16, 16, '2023-04-01 01:41:40', 30, NULL, NULL, NULL),
(173, 189, 64, 8, 16, 16, '2023-04-01 01:41:56', 30, NULL, NULL, NULL),
(174, 209, 50, 8, 16, 16, '2023-04-01 01:42:06', 30, NULL, NULL, NULL),
(175, 210, 45, 8, 16, 16, '2023-04-01 01:42:29', 30, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_jalur`
--

CREATE TABLE `tb_jalur` (
  `id` smallint(6) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `singkatan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_jalur`
--

INSERT INTO `tb_jalur` (`id`, `nama`, `singkatan`) VALUES
(1, 'Reguler Pagi', 'Reg'),
(2, 'Reguler Sore', 'RegS'),
(3, 'Beasiswa KIP Kuliah', 'KIP');

-- --------------------------------------------------------

--
-- Table structure for table `tb_jenjang`
--

CREATE TABLE `tb_jenjang` (
  `jenjang` char(2) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jumlah_semester` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_jenjang`
--

INSERT INTO `tb_jenjang` (`jenjang`, `nama`, `jumlah_semester`) VALUES
('D3', 'Diploma III', 6),
('S1', 'Strata I (Sarjana)', 8);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kalender`
--

CREATE TABLE `tb_kalender` (
  `id` smallint(6) NOT NULL,
  `angkatan` smallint(4) NOT NULL,
  `jenjang` char(2) NOT NULL DEFAULT 'S1',
  `tanggal_mulai` date NOT NULL DEFAULT '2020-01-01',
  `jumlah_semester` tinyint(1) NOT NULL,
  `jumlah_bulan_per_semester` tinyint(4) NOT NULL DEFAULT 6,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kalender`
--

INSERT INTO `tb_kalender` (`id`, `angkatan`, `jenjang`, `tanggal_mulai`, `jumlah_semester`, `jumlah_bulan_per_semester`, `nama`) VALUES
(7, 2023, 'S1', '2023-09-01', 8, 5, 'Kalender Induk TA.2023 Jenjang S1');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `kelas` varchar(20) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `angkatan` smallint(6) NOT NULL,
  `id_jalur` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kelas`
--

INSERT INTO `tb_kelas` (`kelas`, `id_prodi`, `angkatan`, `id_jalur`) VALUES
('BD-2020-KIP-P1', 32, 2020, 3),
('BD-2020-KIP-P2', 32, 2020, 3),
('BD-2020-KIP-P3', 32, 2020, 3),
('BD-2020-KIP-P4', 32, 2020, 3),
('BD-2020-REG-P1', 32, 2020, 1),
('BD-2020-REG-S', 32, 2020, 2),
('BD-2021-KIP-P1', 32, 2021, 3),
('BD-2021-KIP-P2', 32, 2021, 3),
('BD-2021-KIP-P3', 32, 2021, 3),
('BD-2021-KIP-P4', 32, 2021, 3),
('BD-2021-REG-P1', 32, 2021, 1),
('BD-2021-REG-S', 32, 2021, 2),
('BD-2022-KIP-P1', 32, 2022, 3),
('BD-2022-KIP-P2', 32, 2022, 3),
('BD-2022-KIP-P3', 32, 2022, 3),
('BD-2022-REG-P1', 32, 2022, 1),
('BD-2022-REG-S', 32, 2022, 2),
('BD-2023-KIP-P1', 32, 2023, 3),
('BD-2023-KIP-P2', 32, 2023, 3),
('BD-2023-KIP-P3', 32, 2023, 3),
('BD-2023-KIP-P4', 32, 2023, 3),
('BD-2023-KIP-P5', 32, 2023, 3),
('BD-2023-REG-P1', 32, 2023, 1),
('BD-2023-REG-S', 32, 2023, 2),
('MI-2020-KIP-P1', 31, 2020, 3),
('MI-2020-KIP-P2', 31, 2020, 3),
('MI-2020-KIP-P3', 31, 2020, 3),
('MI-2020-REG-S', 31, 2020, 2),
('MI-2021-KIP-P1', 31, 2021, 3),
('MI-2021-KIP-P2', 31, 2021, 3),
('MI-2021-REG-S', 31, 2021, 2),
('MI-2022-KIP-P1', 31, 2022, 3),
('TI-2020-KIP-P1', 41, 2020, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas_angkatan`
--

CREATE TABLE `tb_kelas_angkatan` (
  `id` int(11) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `id_mhs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kelas_angkatan`
--

INSERT INTO `tb_kelas_angkatan` (`id`, `kelas`, `id_mhs`) VALUES
(1, 'TI-2020-KIP-P1', 6920),
(2, 'MI-2021-KIP-P1', 6921),
(3, 'TI-2020-KIP-P1', 50),
(4, 'TI-2020-KIP-P1', 6922),
(5, 'TI-2020-KIP-P1', 22),
(6, 'TI-2020-KIP-P1', 6923),
(9, 'MI-2020-KIP-P1', 6924),
(10, 'MI-2020-KIP-P1', 6925),
(11, 'MI-2020-KIP-P1', 6926),
(12, 'MI-2020-KIP-P1', 44),
(13, 'MI-2020-KIP-P2', 15),
(14, 'MI-2020-KIP-P2', 6927),
(15, 'MI-2020-KIP-P2', 6928),
(16, 'MI-2020-KIP-P3', 42),
(17, 'MI-2020-KIP-P3', 6929),
(18, 'MI-2020-KIP-P3', 6930),
(19, 'MI-2020-KIP-P3', 6931),
(20, 'BD-2023-KIP-P1', 3),
(21, 'BD-2023-KIP-P1', 1),
(22, 'BD-2023-KIP-P1', 6),
(23, 'BD-2023-KIP-P1', 4),
(24, 'BD-2023-KIP-P1', 45),
(25, 'BD-2023-KIP-P2', 5),
(26, 'BD-2023-KIP-P2', 2),
(27, 'BD-2023-KIP-P2', 8),
(28, 'BD-2023-KIP-P2', 26),
(29, 'BD-2023-KIP-P3', 43),
(30, 'BD-2023-KIP-P3', 13),
(31, 'BD-2023-KIP-P3', 7),
(32, 'BD-2023-KIP-P3', 35);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas_peserta`
--

CREATE TABLE `tb_kelas_peserta` (
  `id` int(11) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `id_kurikulum_mk` int(11) NOT NULL,
  `id_dosen` int(11) NOT NULL COMMENT 'Dosen Pengampu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kelas_peserta`
--

INSERT INTO `tb_kelas_peserta` (`id`, `kelas`, `id_kurikulum_mk`, `id_dosen`) VALUES
(1, 'TI-2020-KIP-P1', 154, 57),
(2, 'TI-2020-KIP-P1', 155, 34),
(3, 'MI-2020-KIP-P1', 156, 57),
(4, 'MI-2020-KIP-P2', 156, 57),
(5, 'MI-2020-KIP-P3', 156, 57),
(6, 'MI-2020-REG-S', 156, 57),
(7, 'MI-2021-KIP-P1', 156, 57),
(8, 'MI-2021-KIP-P2', 156, 57),
(9, 'MI-2021-REG-S', 156, 57),
(11, 'BD-2023-KIP-P1', 168, 48),
(12, 'BD-2023-KIP-P2', 168, 48),
(13, 'BD-2023-KIP-P3', 168, 48),
(14, 'MI-2020-KIP-P1', 279, 32),
(15, 'MI-2020-KIP-P2', 279, 32),
(16, 'MI-2020-KIP-P3', 279, 32),
(17, 'MI-2020-REG-S', 279, 32),
(18, 'MI-2020-KIP-P1', 173, 44),
(19, 'MI-2020-KIP-P2', 173, 44),
(20, 'MI-2020-KIP-P3', 173, 44),
(21, 'MI-2020-REG-S', 173, 44),
(22, 'MI-2020-KIP-P1', 178, 47),
(23, 'MI-2020-KIP-P2', 178, 47),
(24, 'MI-2020-KIP-P3', 178, 47),
(25, 'MI-2020-REG-S', 178, 47),
(26, 'MI-2020-KIP-P1', 200, 31),
(27, 'MI-2020-KIP-P2', 200, 31),
(28, 'MI-2020-KIP-P3', 200, 31),
(29, 'MI-2020-REG-S', 200, 31),
(30, 'MI-2020-KIP-P1', 185, 31),
(31, 'MI-2020-KIP-P2', 185, 31),
(32, 'MI-2020-KIP-P3', 185, 31),
(33, 'MI-2020-REG-S', 185, 31);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kesalahan`
--

CREATE TABLE `tb_kesalahan` (
  `id` int(11) NOT NULL,
  `link` varchar(300) NOT NULL,
  `hal` varchar(100) NOT NULL,
  `isi` varchar(1000) NOT NULL,
  `report_by` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `handle_by` int(11) DEFAULT NULL,
  `date_handle` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kesalahan`
--

INSERT INTO `tb_kesalahan` (`id`, `link`, `hal`, `isi`, `report_by`, `date`, `handle_by`, `date_handle`, `status`) VALUES
(6, '?manage_kelas&id_jadwal=135', 'kelas_peserta masih kosong.', 'Yth. Petugas Akademik%0a%0aDengan ini saya beritahukan bahwa terdapat kesalahan perihal: *kelas_peserta masih kosong.*. Mohon segera ditindaklanjuti. Terimakasih.%0a%0aDari: Nisa Dienwati Nuris, M.sos [2023-04-10 01:13:07 ~ SIAKAD System]%0a%0aLink: http://localhost/siakad/akademik/?manage_kelas&id_jadwal=135', 57, '2023-04-09 18:13:12', NULL, NULL, NULL),
(7, '?manage_kelas&id_jadwal=146', 'kelas_peserta masih kosong.', 'Yth. Petugas Akademik%0a%0aDengan ini saya beritahukan bahwa terdapat kesalahan perihal: *kelas_peserta masih kosong.*. Mohon segera ditindaklanjuti. Terimakasih.%0a%0aDari: Nisa Dienwati Nuris, M.sos [2023-04-10 01:20:37 ~ SIAKAD System]%0a%0aLink: http://localhost/siakad/akademik/?manage_kelas&id_jadwal=146', 57, '2023-04-09 18:20:42', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_konsentrasi`
--

CREATE TABLE `tb_konsentrasi` (
  `id_konsentrasi` smallint(6) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `nama_konsentrasi` varchar(100) NOT NULL,
  `singkatan_konsentrasi` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kurikulum`
--

CREATE TABLE `tb_kurikulum` (
  `id` int(11) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `id_kalender` smallint(6) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `basis` varchar(50) DEFAULT NULL,
  `is_publish` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal_buat` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_penetapan` date DEFAULT NULL,
  `ditetapkan_oleh` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kurikulum`
--

INSERT INTO `tb_kurikulum` (`id`, `id_prodi`, `id_kalender`, `nama`, `basis`, `is_publish`, `tanggal_buat`, `tanggal_penetapan`, `ditetapkan_oleh`) VALUES
(12, 41, 7, 'Kurikulum 2023 - S1 Prodi TI', NULL, 0, '2023-03-17 07:25:17', '2023-01-01', NULL),
(14, 42, 7, 'Kurikulum 2023 - S1 Prodi RPL', NULL, 0, '2023-03-17 07:48:15', '2020-01-01', NULL),
(15, 43, 7, 'Kurikulum 2023 - S1 Prodi SI', NULL, 0, '2023-03-17 08:10:39', '2020-01-01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kurikulum_mk`
--

CREATE TABLE `tb_kurikulum_mk` (
  `id` int(11) NOT NULL,
  `id_semester` int(11) NOT NULL,
  `id_mk` int(11) NOT NULL,
  `id_kurikulum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kurikulum_mk`
--

INSERT INTO `tb_kurikulum_mk` (`id`, `id_semester`, `id_mk`, `id_kurikulum`) VALUES
(154, 72, 137, 12),
(155, 72, 138, 12),
(156, 72, 139, 12),
(157, 72, 140, 12),
(161, 72, 144, 12),
(162, 72, 145, 12),
(165, 72, 148, 12),
(166, 72, 149, 12),
(167, 73, 150, 12),
(168, 74, 151, 12),
(169, 74, 152, 12),
(170, 74, 153, 12),
(171, 74, 154, 12),
(172, 73, 155, 12),
(173, 74, 156, 12),
(174, 74, 157, 12),
(175, 73, 158, 12),
(176, 73, 159, 12),
(177, 73, 160, 12),
(178, 76, 161, 12),
(179, 76, 162, 12),
(180, 76, 163, 12),
(181, 76, 164, 12),
(182, 73, 165, 12),
(183, 76, 166, 12),
(184, 73, 167, 12),
(185, 78, 168, 12),
(186, 78, 169, 12),
(187, 78, 170, 12),
(188, 78, 171, 12),
(189, 78, 172, 12),
(193, 72, 137, 14),
(196, 72, 138, 14),
(197, 72, 139, 14),
(199, 72, 140, 14),
(200, 77, 179, 12),
(201, 77, 180, 12),
(202, 77, 181, 12),
(203, 77, 182, 12),
(204, 77, 183, 12),
(205, 72, 144, 14),
(206, 72, 145, 14),
(207, 72, 148, 14),
(208, 72, 149, 14),
(209, 79, 184, 12),
(210, 79, 185, 12),
(214, 74, 151, 14),
(215, 74, 152, 14),
(216, 74, 153, 14),
(218, 74, 190, 14),
(220, 73, 150, 14),
(221, 73, 155, 14),
(222, 73, 158, 14),
(223, 73, 159, 14),
(224, 73, 160, 14),
(225, 73, 165, 14),
(227, 73, 167, 14),
(235, 77, 179, 14),
(236, 77, 180, 14),
(237, 74, 156, 14),
(239, 77, 181, 14),
(240, 74, 157, 14),
(241, 77, 182, 14),
(242, 77, 183, 14),
(243, 79, 184, 14),
(244, 76, 195, 14),
(245, 79, 185, 14),
(246, 76, 196, 14),
(247, 76, 197, 14),
(248, 76, 198, 14),
(249, 76, 199, 14),
(250, 78, 168, 14),
(251, 78, 169, 14),
(252, 75, 200, 14),
(254, 78, 170, 14),
(255, 78, 171, 14),
(256, 78, 172, 14),
(263, 75, 207, 14),
(265, 72, 137, 15),
(266, 72, 138, 15),
(267, 72, 139, 15),
(268, 75, 176, 14),
(269, 72, 140, 15),
(270, 72, 144, 15),
(271, 75, 177, 14),
(272, 72, 145, 15),
(273, 75, 209, 14),
(274, 72, 148, 15),
(275, 72, 149, 15),
(276, 74, 151, 15),
(277, 75, 201, 14),
(279, 75, 173, 12),
(280, 75, 174, 12),
(281, 75, 175, 12),
(282, 75, 176, 12),
(283, 75, 177, 12),
(285, 75, 178, 12);

-- --------------------------------------------------------

--
-- Table structure for table `tb_mhs`
--

CREATE TABLE `tb_mhs` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_pmb` int(11) DEFAULT NULL,
  `nim` char(8) NOT NULL,
  `password` varchar(200) DEFAULT NULL,
  `nama` varchar(50) NOT NULL,
  `no_wa` varchar(14) DEFAULT NULL,
  `status_mhs` tinyint(1) NOT NULL DEFAULT 0,
  `folder_uploads` varchar(100) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_mhs`
--

INSERT INTO `tb_mhs` (`id`, `id_user`, `id_pmb`, `nim`, `password`, `nama`, `no_wa`, `status_mhs`, `folder_uploads`, `gender`) VALUES
(1, 101, NULL, '44220001', NULL, 'MUTIARA SHALSA NABILA DUMMY', '6287729007318', 1, NULL, 'P'),
(2, 102, NULL, '44220002', NULL, 'NABILA AFRILISSIA DUMMY', '62817658761', 1, NULL, 'P'),
(3, 103, NULL, '44220003', NULL, 'NABIL PRASETIYA DUMMY', '62817658762', 1, NULL, 'L'),
(4, 104, NULL, '44220004', NULL, 'MUHAMMAD NAFAL RAMADHAN PRI DUMMY', '62817658763', 1, NULL, 'L'),
(5, 105, NULL, '44220005', NULL, 'LULUAH NAFISAH ULUM DUMMY', '62817658764', 1, NULL, 'P'),
(6, 106, NULL, '44220006', NULL, 'MUHAMMAD NAFIS MUFLIH DUMMY', '62817658765', 1, NULL, 'L'),
(7, 107, NULL, '44220007', NULL, 'NAILA NURFALAH DUMMY', '62817658766', 1, NULL, 'P'),
(8, 108, NULL, '44220008', NULL, 'NALENDRO AGUNG PRASOJO DUMMY', '62817658767', 1, NULL, 'L'),
(9, 109, NULL, '44220009', NULL, 'NASAKH DUMMY', '62817658768', 1, NULL, 'L'),
(10, 110, NULL, '44220010', NULL, 'NASIKHUN AMIN PRI  DUMMY', '62817658769', 1, NULL, 'L'),
(11, 111, NULL, '44220011', NULL, 'NAUFAL AL FARIS  DUMMY', '628176587610', 1, NULL, 'L'),
(12, 112, NULL, '44220012', NULL, 'NAUFAL FEBRIANO PRI DUMMY', '628176587611', 1, NULL, 'L'),
(13, 113, NULL, '44220013', NULL, 'NABILAH DUMMY', '628176587612', 1, NULL, 'P'),
(14, 114, NULL, '44220014', NULL, 'NINIH ZAHROTUL UMAMI DUMMY', '628176587613', 1, NULL, 'P'),
(15, 115, NULL, '44220015', NULL, 'FITRIA DUMMY', '628176587614', 1, '_47180600_1627217658', 'P'),
(16, 116, NULL, '44220016', NULL, 'NIDA KHAERUNISA DUMMY', '628176587615', 1, NULL, 'P'),
(17, 117, NULL, '44220017', NULL, 'NIKE NURZANAH  DUMMY', '628176587616', 1, NULL, 'P'),
(18, 118, NULL, '44220018', NULL, 'NILAM SARI DUMMY', '628176587617', 1, NULL, 'P'),
(19, 119, NULL, '44220019', NULL, 'NINING SUKMAWATI DUMMY', '628176587618', 1, NULL, 'P'),
(20, 120, NULL, '44220020', NULL, 'NIYA DUMMY', '628176587619', 1, NULL, 'P'),
(21, 121, NULL, '44220021', NULL, 'NOER ICHWAN ALIM DUMMY', '628176587620', 1, NULL, 'L'),
(22, 122, NULL, '44220022', NULL, 'CITRA NUR OKTAVIANI  DUMMY', '628176587621', 1, NULL, 'P'),
(23, 123, NULL, '44220023', NULL, 'NOVIANTY  DUMMY', '628176587622', 1, NULL, 'P'),
(24, 124, NULL, '44220024', NULL, 'NOVITASARI PRI DUMMY', '628176587623', 1, NULL, 'P'),
(25, 125, NULL, '44220025', NULL, 'NOVITA SAFITRI DUMMY', '628176587624', 1, NULL, 'P'),
(26, 126, NULL, '44220026', NULL, 'NADHIRA SYAFAATUN NISSA DUMMY', '628176587625', 1, NULL, 'P'),
(27, 127, NULL, '44220027', NULL, 'NUCHBATUL FIKRI PRI DUMMY', '628176587626', 1, NULL, 'L'),
(28, 128, NULL, '44220028', NULL, 'NURAFNI PUTRI DUMMY', '628176587627', 1, NULL, 'P'),
(29, 129, NULL, '44220029', NULL, 'PWN DUMMY', '628176587628', 1, NULL, 'L'),
(30, 130, NULL, '44220030', NULL, 'NURFADILLAH RAHAYU DUMMY', '628176587629', 1, NULL, 'L'),
(31, 131, NULL, '44220031', NULL, 'NURFINA NOVIYANA DUMMY', '628176587630', 1, NULL, 'P'),
(32, 132, NULL, '44220032', NULL, 'NURHAKIM BANI DUMMY', '628176587631', 1, NULL, 'L'),
(33, 133, NULL, '44220033', NULL, 'NUROH AYUNI SHOLIHAH  DUMMY', '628176587632', 1, NULL, 'P'),
(34, 134, NULL, '44220034', NULL, 'NUR SEFTIANAH PRI DUMMY', '628176587633', 1, NULL, 'P'),
(35, 135, NULL, '44220035', NULL, 'NAILA NURUL ANISAH PRI DUMMY', '628176587634', 1, NULL, 'P'),
(36, 136, NULL, '44220036', NULL, 'NURUL FAKIAH DUMMY', '628176587635', 1, NULL, 'P'),
(37, 137, NULL, '44220037', NULL, 'NURUL SYIFA KHAIRINA PRI DUMMY', '628176587636', 1, NULL, 'P'),
(38, 138, NULL, '44220038', NULL, 'NURYADI DUMMY', '628176587637', 1, NULL, 'L'),
(39, 139, NULL, '44220039', NULL, 'NAZWA PUTRI NINDYA  DUMMY', '628176587638', 1, NULL, 'P'),
(40, 140, NULL, '44220040', NULL, 'OKTAVIA ALVI AULIANA DUMMY', '628176587639', 1, NULL, 'L'),
(41, 141, NULL, '44220041', NULL, 'OKTAVIA RAMADANI  DUMMY', '628176587640', 1, NULL, 'L'),
(42, 142, NULL, '44220042', NULL, 'ILHAM MAULANA  DUMMY', '628176587641', 1, NULL, 'L'),
(43, 143, NULL, '44220043', NULL, 'MUHAMAD SATRIA SAHID RAMADHAN PRI DUMMY', '628176587642', 1, NULL, 'L'),
(44, 144, NULL, '44220044', NULL, 'FAUZI DUMMY', '628176587643', 1, NULL, 'L'),
(45, 145, NULL, '44220045', NULL, 'MUHAMMAD DUDHY SETIAWAN DUMMY', '628176587644', 1, NULL, 'L'),
(46, 146, NULL, '44220046', NULL, 'PHILIE PRI DUMMY', '628176587645', 1, NULL, 'L'),
(47, 147, NULL, '44220047', NULL, 'PANDU PRAMANA DUMMY', '628176587646', 1, NULL, 'L'),
(48, 148, NULL, '44220048', NULL, 'PUPUT MELINDA  DUMMY', '628176587647', 1, NULL, 'P'),
(49, 149, NULL, '44220049', NULL, 'PUTRI RAHMAWATI DUMMY', '628176587648', 1, NULL, 'P'),
(50, 150, NULL, '44220050', NULL, 'AZIZAH PUTRI DEVANIE PRI DUMMY', '628176587649', 1, NULL, 'P'),
(6920, 7020, 1, '31229996', NULL, 'Ahmad Firdaus dummy', '6287729007318', 1, '_ahmad230307225033', 'L'),
(6921, 7021, NULL, '31313131', NULL, 'Anak MI Sore dummy', '08896434567', 1, NULL, 'L'),
(6922, 7022, NULL, '41414142', NULL, 'Budi dummy', '08654567', 1, NULL, 'L'),
(6923, 7023, NULL, '41414143', NULL, 'Charli dummy', NULL, 0, NULL, 'L'),
(6924, 7024, NULL, '41414144', NULL, 'Deni dummy', NULL, 0, NULL, 'L'),
(6925, 7025, NULL, '41414145', NULL, 'Erwin dummy', NULL, 0, NULL, 'L'),
(6926, 7026, NULL, '41414146', NULL, 'Fajar dummy', NULL, 0, NULL, 'L'),
(6927, 7027, NULL, '41414147', NULL, 'Gilang dummy', NULL, 0, NULL, 'L'),
(6928, 7028, NULL, '41414148', NULL, 'Haris dummy', NULL, 0, NULL, 'L'),
(6929, 7029, NULL, '41414149', NULL, 'Ira dummy', NULL, 0, NULL, 'P'),
(6930, 7030, NULL, '41414150', NULL, 'Joko dummy', NULL, 0, NULL, 'L'),
(6931, 7031, NULL, '41414151', NULL, 'Lilis dummy', NULL, 0, NULL, 'P'),
(6932, 7032, NULL, '31229995', NULL, 'Salwa Fatimah Dummy', NULL, 1, NULL, 'P');

-- --------------------------------------------------------

--
-- Table structure for table `tb_mk`
--

CREATE TABLE `tb_mk` (
  `id` int(11) NOT NULL,
  `id_bk` smallint(6) DEFAULT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `singkatan` varchar(15) NOT NULL,
  `bobot_teori` tinyint(1) NOT NULL DEFAULT 0,
  `bobot_praktik` tinyint(1) NOT NULL DEFAULT 0,
  `is_publish` tinyint(1) NOT NULL DEFAULT 0,
  `prasyarat` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_mk`
--

INSERT INTO `tb_mk` (`id`, `id_bk`, `kode`, `nama`, `singkatan`, `bobot_teori`, `bobot_praktik`, `is_publish`, `prasyarat`) VALUES
(62, NULL, 'MKDU-001', 'BAHASA INDONESIA', 'SINGKATAN-MK', 2, 0, -1, NULL),
(63, NULL, 'MK116466', 'NEW-MK SM1 PROD41 KUR1 KAL1', 'SINGKATAN-MK', 0, 0, -1, NULL),
(64, NULL, 'MK2542766', 'NEW-MK SM2 PROD41 KUR1 KAL1', 'SINGKATAN-MK', 0, 0, -1, NULL),
(65, NULL, 'MK134668', 'NEW-MK SM1 PROD41 KUR1 KAL1', 'SINGKATAN-MK', 0, 0, -1, NULL),
(66, NULL, 'MK2565062', 'NEW-MK SM2 PROD41 KUR1 KAL1', 'SINGKATAN-MK', 0, 0, -1, NULL),
(67, NULL, 'MK3424894', 'NEW-MK SM1 PROD31 KUR2 KAL2', 'SINGKATAN-MK', 0, 0, -1, NULL),
(68, NULL, 'MK3498972', 'NEW-MK SM1 PROD31 KUR2 KAL2', 'SINGKATAN-MK', 0, 0, -1, NULL),
(69, NULL, 'MK3596591', 'NEW-MK SM2 PROD31 KUR2 KAL2', 'SINGKATAN-MK', 0, 0, -1, NULL),
(70, NULL, 'MK3672267', 'NEW-MK SM3 PROD31 KUR2 KAL2', 'SINGKATAN-MK', 0, 0, -1, NULL),
(71, NULL, 'MK3647454', 'NEW-MK SM3 PROD31 KUR2 KAL2', 'SINGKATAN-MK', 0, 0, -1, NULL),
(72, NULL, 'MK4151956', 'BAHASA JEPANG', 'SINGKATAN-MK', 0, 0, -1, NULL),
(73, NULL, 'MK4193925', 'PPKN', 'SINGKATAN-MK', 3, 0, -1, NULL),
(74, NULL, 'MK4147950', 'BAHASA INGGRIS', 'SINGKATAN-MK', 0, 0, -1, NULL),
(75, NULL, 'MK4177284', 'AGAMA', 'SINGKATAN-MK', 0, 0, -1, NULL),
(76, NULL, 'MK4295118', 'PENGENALAN TEKNOLOGI INFORMATIKA', 'SINGKATAN-MK', 0, 0, -1, NULL),
(77, NULL, 'MK4269073', 'KALKULUS I', 'SINGKATAN-MK', 0, 0, -1, NULL),
(78, NULL, 'MK4267132', 'PEMROGRAMAN WEB DASAR', 'SINGKATAN-MK', 0, 0, -1, NULL),
(79, NULL, 'MK4285094', 'DASAR-DASAR AI', 'SINGKATAN-MK', 0, 0, -1, NULL),
(80, NULL, 'MK4221011', 'IOT PROGRAMMING DASAR', 'SINGKATAN-MK', 0, 0, -1, NULL),
(81, NULL, 'MK4378092', 'PEMROGRAMAN WEB LANJUT', 'SINGKATAN-MK', 0, 0, -1, NULL),
(82, NULL, 'MK4486814', 'SECURE PROGRAMMING', 'SINGKATAN-MK', 0, 0, -1, NULL),
(83, NULL, 'MK4558520', 'METODE PENETILIAN', 'SINGKATAN-MK', 0, 0, -1, NULL),
(84, NULL, 'MK4692615', 'TUGAS AKHIR', 'SINGKATAN-MK', 0, 0, -1, NULL),
(85, NULL, 'MK5698038', 'PENDIDIKAN AGAMA 1', 'SINGKATAN-MK', 1, 0, -1, NULL),
(86, NULL, 'MK5640710', 'MEDIA SOSIAL', 'SINGKATAN-MK', 2, 1, -1, NULL),
(87, NULL, 'MK5612715', 'PENGANTAR ILMU EKONOMI', 'SINGKATAN-MK', 2, 1, -1, NULL),
(88, NULL, 'MK5673640', 'PENGANTAR BISNIS', 'SINGKATAN-MK', 2, 1, -1, NULL),
(89, NULL, 'MK5609307', 'PENGANTAR MANAJEMEN', 'SINGKATAN-MK', 2, 1, -1, NULL),
(90, NULL, 'MK5655301', 'PENGANTAR AKUNTANSI', 'SINGKATAN-MK', 2, 1, -1, NULL),
(91, NULL, 'MK5778808', 'BAHASA INGGRIS 1', 'SINGKATAN-MK', 3, 0, -1, NULL),
(92, NULL, 'MK5750526', 'PEMROGRAMAN KOMPUTER 1', 'SINGKATAN-MK', 1, 3, -1, NULL),
(93, NULL, 'MK5714546', 'PEMODELAN BISNIS', 'SINGKATAN-MK', 3, 0, -1, NULL),
(94, NULL, 'MK5745807', 'PROBABILITAS DAN STATISTIK', 'SINGKATAN-MK', 3, 0, -1, NULL),
(95, NULL, 'MK5716922', 'PERILAKU ORGANISASI', 'SINGKATAN-MK', 3, 0, -1, NULL),
(96, NULL, 'MK5755917', 'MANAJEMEN PEMASARAN', 'SINGKATAN-MK', 3, 0, -1, NULL),
(97, NULL, 'MK5825517', 'KEPEMIMPINAN 1', 'SINGKATAN-MK', 3, 0, -1, NULL),
(98, NULL, 'MK5898226', 'BAHASA INGGRIS 2', 'SINGKATAN-MK', 2, 1, -1, NULL),
(99, NULL, 'MK5822418', 'PEMROGRAMAN KOMPUTER 2', 'SINGKATAN-MK', 2, 2, -1, NULL),
(100, NULL, 'MK5842959', 'PEMIKIRAN DESAIN', 'SINGKATAN-MK', 3, 0, -1, NULL),
(101, NULL, 'MK5803903', 'ANALISA STATISTIK', 'SINGKATAN-MK', 3, 0, -1, NULL),
(102, NULL, 'MK5876321', 'ETIKA BISNIS', 'SINGKATAN-MK', 3, 0, -1, NULL),
(103, NULL, 'MK5961643', 'PENGANGGARAN', 'SINGKATAN-MK', 2, 1, -1, NULL),
(104, NULL, 'MK5926129', 'ANALISA KEBIASAAN PELANGGAN', 'SINGKATAN-MK', 3, 0, -1, NULL),
(105, NULL, 'MK5955237', 'PEMASARAN DIGITAL', 'SINGKATAN-MK', 2, 1, -1, NULL),
(106, NULL, 'MK5931760', 'PERANCANGAN WEB INTERAKTIF', 'SINGKATAN-MK', 2, 2, -1, NULL),
(107, NULL, 'MK5919254', 'BASIS DATA', 'SINGKATAN-MK', 2, 2, -1, NULL),
(108, NULL, 'MK5929792', 'DATA MINING', 'SINGKATAN-MK', 2, 1, -1, NULL),
(109, NULL, 'MK6067168', 'METODOLOGI PENELITIAN BISNIS', 'SINGKATAN-MK', 4, 0, -1, NULL),
(110, NULL, 'MK6096522', 'KEBIJAKAN EKONOMI', 'SINGKATAN-MK', 3, 0, -1, NULL),
(111, NULL, 'MK6058272', 'HUKUM INFORMASI DAN TEKNOLOGI ELEKTRONIK', 'SINGKATAN-MK', 3, 0, -1, NULL),
(112, NULL, 'MK6072833', 'HAK KEKAYAAN INTELEKTUAL', 'SINGKATAN-MK', 3, 0, -1, NULL),
(113, NULL, 'MK6048615', 'STRATEGI RENCANA KERJA', 'SINGKATAN-MK', 3, 0, -1, NULL),
(114, NULL, 'MK6032878', 'BISNIS ANALIS', 'SINGKATAN-MK', 3, 0, -1, NULL),
(115, NULL, 'MK6039231', 'MANAJEMEN HUBUNGAN PELANGGAN', 'SINGKATAN-MK', 3, 0, -1, NULL),
(117, NULL, 'MK6122966', 'TECHNOPRENEURSHIP', 'SINGKATAN-MK', 3, 0, -1, NULL),
(118, NULL, 'MK6153668', 'PENGABDIAN MASYARAKAT', 'SINGKATAN-MK', 0, 3, -1, NULL),
(119, NULL, 'MK6147883', 'KREATIFITAS DAN INOVASI 1', 'SINGKATAN-MK', 3, 0, -1, NULL),
(120, NULL, 'MK6121393', 'KEWIRAUSAHAAN', 'SINGKATAN-MK', 3, 0, -1, NULL),
(121, NULL, 'MK6160665', 'KOMUNIKASI', 'SINGKATAN-MK', 3, 0, -1, NULL),
(122, NULL, 'MK6169068', 'PIDATO DAN NEGOSIASI', 'SINGKATAN-MK', 3, 0, -1, NULL),
(123, NULL, 'MK6185935', 'MANAJEMEN PROYEK SISTEM INFORMASI', 'SINGKATAN-MK', 1, 2, -1, NULL),
(124, NULL, 'MK6295120', 'KECERDASAN BUATAN', 'SINGKATAN-MK', 3, 0, -1, NULL),
(125, NULL, 'MK6203003', 'STUDI KELAYAKAN BISNIS', 'SINGKATAN-MK', 3, 0, -1, NULL),
(126, NULL, 'MK6282751', 'PELAYANAN PRIMA', 'SINGKATAN-MK', 3, 0, -1, NULL),
(127, NULL, 'MK6240141', 'JARINGAN BISNIS', 'SINGKATAN-MK', 3, 0, -1, NULL),
(128, NULL, 'MK6216993', 'PENELITIAN BERSAMA', 'SINGKATAN-MK', 4, 2, -1, NULL),
(129, NULL, 'MK6289786', 'PEMASARAN DAN PROMOSI', 'SINGKATAN-MK', 3, 0, -1, NULL),
(130, NULL, 'MK6276007', 'MANAJEMEN RANTAI PASOK', 'SINGKATAN-MK', 3, 0, -1, NULL),
(131, NULL, 'MK6354794', 'STUDI KELAYAKAN BISNIS 2', 'SINGKATAN-MK', 2, 0, -1, NULL),
(132, NULL, 'MK6347393', 'SEMINAR INTERNASIONAL', 'SINGKATAN-MK', 3, 0, -1, NULL),
(133, NULL, 'MK6330316', 'SIMULASI BISNIS', 'SINGKATAN-MK', 3, 0, -1, NULL),
(134, NULL, 'MK6317154', 'MK PILIHAN BISNIS DIGITAL', 'SINGKATAN-MK', 3, 0, -1, NULL),
(135, NULL, 'MK2500959', 'NEW-MK SM2 PROD42 KUR3 KAL1', 'SINGKATAN-MK', 0, 0, -1, NULL),
(136, NULL, 'MK2574235', 'NEW-MK SM2 PROD42 KUR3 KAL1', 'SINGKATAN-MK', 0, 0, -1, NULL),
(137, NULL, 'MKU-0001', 'PANCASILA', 'SINGKATAN-MK', 2, 0, -1, NULL),
(138, NULL, 'MKD-0002', 'DASAR-DASAR ARTIFICAL INTELLIGENCE', 'SINGKATAN-MK', 2, 1, -1, NULL),
(139, NULL, 'MKU-0003', 'PENDIDIKAN AGAMA', 'SINGKATAN-MK', 2, 0, -1, NULL),
(140, NULL, 'MKD-0004', 'ALGORITMA DAN PEMROGRAMAN', 'SINGKATAN-MK', 2, 1, -1, NULL),
(144, NULL, 'MKD-0005', 'KALKULUS', 'SINGKATAN-MK', 3, 0, -1, NULL),
(145, NULL, 'MKD-0006', 'DATA MANAJEMEN STAFF', 'SINGKATAN-MK', 2, 1, -1, NULL),
(148, NULL, 'MKL-0007', 'PENGENALAN BUDAYA CIREBON', 'SINGKATAN-MK', 2, 0, -1, NULL),
(149, NULL, 'MKU-0008', 'BAHASA INGGRIS', 'SINGKATAN-MK', 2, 0, -1, NULL),
(150, NULL, 'MKU-0101', 'BAHASA INDONESIA', 'SINGKATAN-MK', 2, 0, -1, NULL),
(151, NULL, 'MDK-0301', 'PEMROGRAMAN SQL', 'SINGKATAN-MK', 3, 1, -1, NULL),
(152, NULL, 'MDK-0302', 'REKAYASA PERANGKAT LUNAK', 'SINGKATAN-MK', 4, 0, -1, NULL),
(153, NULL, 'MDK-0303', 'MATEMATIKA DISKRIT', 'SINGKATAN-MK', 2, 1, -1, NULL),
(154, NULL, 'TDK-0304', 'JARINGAN KOMPUTER ADVANCED', 'SINGKATAN-MK', 2, 1, -1, NULL),
(155, NULL, 'MKD-0102', 'STATISTIKA (R STUDIO)', 'SINGKATAN-MK', 2, 1, -1, NULL),
(156, NULL, 'MDK-0305', 'PEMROGRAMAN WEB ', 'SINGKATAN-MK', 2, 1, -1, NULL),
(157, NULL, 'MDK-0306', 'DATA SCIENCE', 'SINGKATAN-MK', 3, 0, -1, NULL),
(158, NULL, 'MKD-0103', 'ALJABAR LINEAR', 'SINGKATAN-MK', 3, 0, -1, NULL),
(159, NULL, 'MKD-0104', 'ALGORITMA DAN PEMROGRAMAN LANJUT', 'SINGKATAN-MK', 3, 1, -1, NULL),
(160, NULL, 'MKD-0105', 'STRUKTUR DATA', 'SINGKATAN-MK', 3, 0, -1, NULL),
(161, NULL, 'TKK-0501', 'CLOUD COMPUTING', 'SINGKATAN-MK', 3, 1, -1, NULL),
(162, NULL, 'MKK-0502', 'KEAMANAN JARINGAN (CYBER OPS)', 'SINGKATAN-MK', 3, 1, -1, NULL),
(163, NULL, 'TKK-0503', 'TEXT MINING', 'SINGKATAN-MK', 3, 1, -1, NULL),
(164, NULL, 'TKK-0504', 'SISTEM OPERASI (LINUX)', 'SINGKATAN-MK', 3, 1, -1, NULL),
(165, NULL, 'MKD-0106', 'JARINGAN KOMPUTER', 'SINGKATAN-MK', 2, 1, -1, NULL),
(166, NULL, 'TKK-0505', 'DEEP LEARNING DASAR', 'SINGKATAN-MK', 3, 1, -1, NULL),
(167, NULL, 'MKU-0107', 'PENDIDIKAN KEWARGANEGARAAN', 'SINGKATAN-MK', 2, 0, -1, NULL),
(168, NULL, 'MKK-0701', 'METODE PENELITIAN', 'SINGKATAN-MK', 4, 0, -1, NULL),
(169, NULL, 'MKK-0702', 'LITERATURE REVIEW', 'SINGKATAN-MK', 4, 0, -1, NULL),
(170, NULL, 'MKK-0703', 'PROPOSAL SKRIPSI', 'SINGKATAN-MK', 2, 0, -1, NULL),
(171, NULL, 'MKK-0704', 'ETIKA PROFESI', 'SINGKATAN-MK', 2, 0, -1, NULL),
(172, NULL, 'MKK-0705', 'IT ENTREPRENEURSHIP', 'SINGKATAN-MK', 1, 1, -1, NULL),
(173, NULL, 'TDK-0401', 'JARINGAN KOMPUTER EXPERT', 'SINGKATAN-MK', 3, 1, -1, NULL),
(174, NULL, 'MDK-0402', 'INTERAKSI MANUSIA KOMPUTER', 'SINGKATAN-MK', 3, 0, -1, NULL),
(175, NULL, 'TDK-0403', 'METODE NUMERIK', 'SINGKATAN-MK', 3, 0, -1, NULL),
(176, NULL, 'MDK-0404', 'DATA MINING', 'SINGKATAN-MK', 3, 1, -1, NULL),
(177, NULL, 'MDK-0405', 'INTERNET OF THINGS', 'SINGKATAN-MK', 2, 1, -1, NULL),
(178, NULL, 'TDK-0406', 'BASIS DATA', 'SINGKATAN-MK', 3, 0, -1, NULL),
(179, NULL, 'TKK-0601', 'DEEP LEARNING LANJUT', 'SINGKATAN-MK', 3, 1, -1, NULL),
(180, NULL, 'TKK-0602', 'MANAJEMEN PROYEK DATA SCIENCE', 'SINGKATAN-MK', 3, 1, -1, NULL),
(181, NULL, 'TKK-0603', 'BIG DATA ANALYTIC', 'SINGKATAN-MK', 3, 1, -1, NULL),
(182, NULL, 'TKK-0604', 'COMPUTER VISION', 'SINGKATAN-MK', 3, 1, -1, NULL),
(183, NULL, 'TKK-0605', 'ROBOTIC', 'SINGKATAN-MK', 3, 1, -1, NULL),
(184, NULL, 'MKK-0801', 'SISTEMATIC LITERATURE REVIEW', 'SINGKATAN-MK', 4, 0, -1, NULL),
(185, NULL, 'MKK-0802', 'SKRIPSI', 'SINGKATAN-MK', 6, 0, -1, NULL),
(190, NULL, 'MDK-0304', 'PEMROGRAMAN BERORIENTASI OBJEK DASAR', 'SINGKATAN-MK', 2, 1, -1, NULL),
(191, NULL, 'MK7409424', 'PEMROGRAMAN WEB DASAR (LARAVEL)', 'SINGKATAN-MK', 0, 0, -1, NULL),
(192, NULL, 'MK7424689', 'PEMROGRAMAN WEB DASAR (LARAVEL)', 'SINGKATAN-MK', 0, 0, -1, NULL),
(193, NULL, 'MK7400908', 'PEMROGRAMAN WEB DASAR', 'SINGKATAN-MK', 0, 0, -1, NULL),
(194, NULL, 'MK7421495', 'NEW-MK SM3 PROD42 KUR14 KAL7', 'SINGKATAN-MK', 0, 0, -1, NULL),
(195, NULL, 'RKK-0501', 'AUGMENTED REALITY DASAR', 'SINGKATAN-MK', 3, 1, -1, NULL),
(196, NULL, 'RKK-0502', 'PEMOGRAMAN MOBILE DASAR', 'SINGKATAN-MK', 3, 1, -1, NULL),
(197, NULL, 'RKK-0503', 'MULTIMEDIA DASAR', 'SINGKATAN-MK', 3, 1, -1, NULL),
(198, NULL, 'RKK-0504', 'SECURE PROGRAMMING', 'SINGKATAN-MK', 3, 1, -1, NULL),
(199, NULL, 'RKK-0505', 'DESAIN WEB (UI/UX)', 'SINGKATAN-MK', 3, 1, -1, NULL),
(200, NULL, 'MDK-0401', 'Pemrograman Web Lanjut (Framework Laravel)', 'SINGKATAN-MK', 3, 1, -1, NULL),
(201, NULL, 'MK7555249', 'Interaksi Manusia dan Komputer', 'SINGKATAN-MK', 0, 0, -1, NULL),
(203, NULL, 'MK7591762', 'NEW-MK SM4 PROD41 KUR12 KAL7', 'SINGKATAN-MK', 0, 0, -1, NULL),
(204, NULL, 'MK7550492', 'NEW-MK SM4 PROD41 KUR12 KAL7', 'SINGKATAN-MK', 0, 0, -1, NULL),
(205, NULL, 'MK7558667', 'NEW-MK SM4 PROD41 KUR12 KAL7', 'SINGKATAN-MK', 0, 0, -1, NULL),
(206, NULL, 'MK7500258', 'NEW-MK SM4 PROD41 KUR12 KAL7', 'SINGKATAN-MK', 0, 0, -1, NULL),
(207, NULL, 'RDK-0403', 'Pemrograman Beroerintasi Objek Advanced', 'SINGKATAN-MK', 2, 1, -1, NULL),
(209, NULL, 'RDK-0406', 'Analisis kebutuhan dan Desain Perangkat Lunak', 'SINGKATAN-MK', 3, 0, -1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_output_pmb`
--

CREATE TABLE `tb_output_pmb` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `dll_zzz` int(11) DEFAULT NULL,
  `last_sync` timestamp NOT NULL DEFAULT current_timestamp(),
  `gender` char(1) NOT NULL,
  `alamat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_output_pmb`
--

INSERT INTO `tb_output_pmb` (`id`, `nama`, `dll_zzz`, `last_sync`, `gender`, `alamat`) VALUES
(1, 'Ahmad Firdaus', NULL, '2023-02-25 18:31:54', 'L', 'Ciwaringin');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pegawai`
--

CREATE TABLE `tb_pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `id_kec` char(6) DEFAULT NULL,
  `id_pegawai_detail` int(11) DEFAULT NULL,
  `nik_pegawai` varchar(20) DEFAULT NULL,
  `tempat_lahir_pegawai` varchar(50) DEFAULT NULL,
  `tanggal_lahir_pegawai` date DEFAULT NULL,
  `nama_pegawai` varchar(30) DEFAULT NULL,
  `gelar_pegawai` varchar(20) DEFAULT NULL,
  `status_pernikahan` varchar(50) DEFAULT NULL,
  `jumlah_anak` tinyint(4) DEFAULT NULL,
  `pendidikan_pegawai` varchar(50) DEFAULT NULL,
  `lulusan_pegawai` varchar(50) DEFAULT NULL,
  `jabatan_pegawai` varchar(20) DEFAULT NULL,
  `divisi_pegawai` varchar(50) DEFAULT NULL,
  `email_pegawai` varchar(50) DEFAULT NULL,
  `no_wa_pegawai` varchar(13) NOT NULL,
  `alamat_pegawai` varchar(100) DEFAULT NULL,
  `admin_level` tinyint(4) NOT NULL DEFAULT 1,
  `email_pegawai_encrypt` varchar(200) DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `status_pegawai` tinyint(4) NOT NULL DEFAULT 1,
  `folder_uploads` char(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_presensi`
--

CREATE TABLE `tb_presensi` (
  `id` int(11) NOT NULL,
  `id_sesi_kuliah` int(11) NOT NULL,
  `id_mhs` int(11) NOT NULL,
  `timestamp_masuk` timestamp NOT NULL DEFAULT current_timestamp(),
  `timestamp_keluar` timestamp NULL DEFAULT NULL,
  `poin_presensi` smallint(6) DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_presensi_dosen`
--

CREATE TABLE `tb_presensi_dosen` (
  `id` int(11) NOT NULL,
  `id_sesi_kuliah` int(11) NOT NULL,
  `timestamp_masuk` timestamp NOT NULL DEFAULT current_timestamp(),
  `timestamp_keluar` timestamp NULL DEFAULT NULL,
  `id_dosen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_presensi_dosen`
--

INSERT INTO `tb_presensi_dosen` (`id`, `id_sesi_kuliah`, `timestamp_masuk`, `timestamp_keluar`, `id_dosen`) VALUES
(2, 67, '2023-04-03 18:53:05', NULL, 57),
(3, 113, '2023-04-04 14:19:47', NULL, 57);

-- --------------------------------------------------------

--
-- Table structure for table `tb_prodi`
--

CREATE TABLE `tb_prodi` (
  `id` int(11) NOT NULL,
  `id_fakultas` int(11) NOT NULL,
  `id_kaprodi` int(11) DEFAULT NULL,
  `kode_nim` char(2) DEFAULT NULL,
  `kode_pdpt` char(5) DEFAULT NULL,
  `jenjang` char(2) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `singkatan` varchar(3) NOT NULL,
  `akred` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_prodi`
--

INSERT INTO `tb_prodi` (`id`, `id_fakultas`, `id_kaprodi`, `kode_nim`, `kode_pdpt`, `jenjang`, `nama`, `singkatan`, `akred`) VALUES
(31, 1, 1, NULL, NULL, 'D3', 'Manajemen Informatika', 'MI', 'B'),
(32, 1, 1, '32', NULL, 'S1', 'Komputerisasi Akuntansi', 'DB', 'A'),
(41, 1, NULL, NULL, NULL, 'S1', 'Teknik Informatika', 'TI', 'C'),
(42, 1, NULL, NULL, NULL, 'S1', 'Rekayasa Perangkat Lunak', 'RPL', 'B'),
(43, 1, 1, '43', NULL, 'S1', 'Sistem Informasi', 'SI', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pt`
--

CREATE TABLE `tb_pt` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id_rektor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pt`
--

INSERT INTO `tb_pt` (`id`, `nama`, `id_rektor`) VALUES
(1, 'STMIK IKMI Cirebon', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_ruang`
--

CREATE TABLE `tb_ruang` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `kapasitas` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_ruang`
--

INSERT INTO `tb_ruang` (`id`, `nama`, `kapasitas`) VALUES
(1, 'Zoom', 999),
(2, 'R.101', 30),
(3, 'R.102', 30),
(4, 'R.103', 30),
(5, 'R.104', 30),
(6, 'R.105', 30),
(7, 'R.106', 30),
(8, 'R.107', 30),
(9, 'R.201', 30),
(10, 'R.202', 30),
(11, 'R.203', 30),
(12, 'R.204', 30),
(13, 'R.205', 30),
(14, 'R.206', 30),
(15, 'R.207', 30),
(16, 'R.301', 30),
(17, 'R.302', 30),
(18, 'R.303', 30),
(19, 'R.304', 30),
(20, 'R.305', 30),
(21, 'R.306', 30),
(22, 'R.AULA', 50);

-- --------------------------------------------------------

--
-- Table structure for table `tb_sekolah_asal`
--

CREATE TABLE `tb_sekolah_asal` (
  `id_sekolah` int(11) NOT NULL,
  `jenis_sekolah` varchar(1) NOT NULL,
  `nama_sekolah` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_semester`
--

CREATE TABLE `tb_semester` (
  `id` int(11) NOT NULL,
  `id_kalender` smallint(6) NOT NULL,
  `nomor` tinyint(1) NOT NULL,
  `tanggal_awal` date DEFAULT NULL,
  `tanggal_akhir` date DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `awal_bayar` date DEFAULT NULL,
  `akhir_bayar` date DEFAULT NULL,
  `awal_krs` date DEFAULT NULL,
  `akhir_krs` date DEFAULT NULL,
  `awal_kuliah_uts` date DEFAULT NULL,
  `awal_kuliah_uas` date DEFAULT NULL,
  `awal_uts` date DEFAULT NULL,
  `awal_uas` date DEFAULT NULL,
  `akhir_kuliah_uts` date DEFAULT NULL,
  `akhir_kuliah_uas` date DEFAULT NULL,
  `akhir_uts` date DEFAULT NULL,
  `akhir_uas` date DEFAULT NULL,
  `last_update` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_semester`
--

INSERT INTO `tb_semester` (`id`, `id_kalender`, `nomor`, `tanggal_awal`, `tanggal_akhir`, `keterangan`, `awal_bayar`, `akhir_bayar`, `awal_krs`, `akhir_krs`, `awal_kuliah_uts`, `awal_kuliah_uas`, `awal_uts`, `awal_uas`, `akhir_kuliah_uts`, `akhir_kuliah_uas`, `akhir_uts`, `akhir_uas`, `last_update`) VALUES
(72, 7, 1, '2023-09-01', '2024-01-31', 'Semester 1 pada Kalender Induk TA.2023 Jenjang S1', '2023-09-04', '2023-09-18', '2023-09-19', '2023-09-23', '2023-09-25', '2023-11-27', '2023-11-20', '2024-01-22', '2023-11-18', '2024-01-20', '2023-11-25', '2024-01-27', '2023-03-17 07:23:42'),
(73, 7, 2, '2024-02-01', '2024-06-30', 'Semester 2 pada Kalender Induk TA.2023 Jenjang S1', '2024-02-05', '2024-02-19', '2024-02-20', '2024-02-24', '2024-02-26', '2024-04-29', '2024-04-22', '2024-06-24', '2024-04-20', '2024-06-22', '2024-04-27', '2024-06-29', '2023-03-17 07:23:50'),
(74, 7, 3, '2024-07-01', '2024-11-30', 'Semester 3 pada Kalender Induk TA.2023 Jenjang S1', '2024-07-01', '2024-07-15', '2024-07-16', '2024-07-20', '2024-07-22', '2024-09-23', '2024-09-16', '2024-11-18', '2024-09-14', '2024-11-16', '2024-09-21', '2024-11-23', '2023-03-17 07:23:56'),
(75, 7, 4, '2024-12-01', '2025-04-30', 'Semester 4 pada Kalender Induk TA.2023 Jenjang S1', '2024-12-02', '2024-12-16', '2024-12-17', '2024-12-21', '2024-12-23', '2025-02-24', '2025-02-17', '2025-04-21', '2025-02-15', '2025-04-19', '2025-02-22', '2025-04-26', '2023-03-17 07:24:06'),
(76, 7, 5, '2025-05-01', '2025-09-30', 'Semester 5 pada Kalender Induk TA.2023 Jenjang S1', '2025-05-05', '2025-05-19', '2025-05-20', '2025-05-24', '2025-05-26', '2025-07-28', '2025-07-21', '2025-09-22', '2025-07-19', '2025-09-20', '2025-07-26', '2025-09-27', '2023-03-17 07:24:15'),
(77, 7, 6, '2025-10-01', '2026-02-28', 'Semester 6 pada Kalender Induk TA.2023 Jenjang S1', '2025-10-06', '2025-10-20', '2025-10-21', '2025-10-25', '2025-10-27', '2025-12-29', '2025-12-22', '2026-02-23', '2025-12-20', '2026-02-21', '2025-12-27', '2026-02-28', '2023-03-17 07:24:29'),
(78, 7, 7, '2026-03-01', '2026-07-31', 'Semester 7 pada Kalender Induk TA.2023 Jenjang S1', '2026-03-02', '2026-03-16', '2026-03-17', '2026-03-21', '2026-03-23', '2026-05-25', '2026-05-18', '2026-07-20', '2026-05-16', '2026-07-18', '2026-05-23', '2026-07-25', '2023-03-17 07:24:37'),
(79, 7, 8, '2026-08-01', '2026-12-31', 'Semester 8 pada Kalender Induk TA.2023 Jenjang S1', '2026-08-03', '2026-08-17', '2026-08-18', '2026-08-22', '2026-08-24', '2026-10-26', '2026-10-19', '2026-12-21', '2026-10-17', '2026-12-19', '2026-10-24', '2026-12-26', '2023-03-17 07:24:44');

-- --------------------------------------------------------

--
-- Table structure for table `tb_sesi_kuliah`
--

CREATE TABLE `tb_sesi_kuliah` (
  `id` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `pertemuan_ke` tinyint(4) NOT NULL,
  `tanggal_sesi` timestamp NOT NULL DEFAULT current_timestamp(),
  `stop_sesi` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_dosen` int(11) NOT NULL COMMENT 'Pengajar',
  `nama` varchar(50) DEFAULT NULL,
  `id_status_sesi` tinyint(4) DEFAULT NULL COMMENT 'null=blm, 1=terlaksana, 0=tidak',
  `materi` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_sesi_kuliah`
--

INSERT INTO `tb_sesi_kuliah` (`id`, `id_jadwal`, `pertemuan_ke`, `tanggal_sesi`, `stop_sesi`, `id_dosen`, `nama`, `id_status_sesi`, `materi`) VALUES
(65, 132, 1, '2023-04-03 13:00:00', '2023-04-03 14:00:00', 57, 'P1 PENGENALAN PANCASILA DASAR', NULL, NULL),
(66, 132, 2, '2023-04-03 15:00:00', '2023-04-03 16:55:00', 57, 'P2 MAKNA KEBHINEKAAN', NULL, NULL),
(67, 132, 3, '2023-04-03 18:30:00', '2023-04-03 20:40:00', 57, 'P3 HAK DAN KEWAJIBAN WARGA NEGARA', NULL, NULL),
(68, 132, 4, '2023-04-05 01:00:00', '2023-04-05 02:40:00', 57, 'P4 SOP WARNA NEGARA YANG BAIK', NULL, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
(69, 132, 5, '2023-04-10 01:00:00', '2023-04-10 02:40:00', 57, 'P5 PANCASILA', NULL, NULL),
(70, 132, 6, '2023-10-30 01:00:00', '2023-10-30 02:40:00', 57, 'P6 PANCASILA', NULL, NULL),
(71, 132, 7, '2023-11-06 01:00:00', '2023-11-06 02:40:00', 57, 'P7 PANCASILA', NULL, NULL),
(72, 132, 8, '2023-11-13 01:00:00', '2023-11-13 02:40:00', 57, 'UTS', NULL, NULL),
(73, 132, 9, '2023-11-20 01:00:00', '2023-11-20 02:40:00', 57, 'P9 PANCASILA', NULL, NULL),
(74, 132, 10, '2023-11-27 01:00:00', '2023-11-27 02:40:00', 57, 'P10 PANCASILA', NULL, NULL),
(75, 132, 11, '2023-12-04 01:00:00', '2023-12-04 02:40:00', 57, 'P11 PANCASILA', NULL, NULL),
(76, 132, 12, '2023-12-11 01:00:00', '2023-12-11 02:40:00', 57, 'P12 PANCASILA', NULL, NULL),
(77, 132, 13, '2023-12-18 01:00:00', '2023-12-18 02:40:00', 57, 'P13 PANCASILA', NULL, NULL),
(78, 132, 14, '2023-12-25 01:00:00', '2023-12-25 02:40:00', 57, 'P14 PANCASILA', NULL, NULL),
(79, 132, 15, '2024-01-01 01:00:00', '2024-01-01 02:40:00', 57, 'P15 PANCASILA', NULL, NULL),
(80, 132, 16, '2024-01-08 01:00:00', '2024-01-08 02:40:00', 57, 'UAS', NULL, NULL),
(81, 133, 1, '2023-09-25 01:00:00', '2023-09-25 03:30:00', 34, 'NEW P1', NULL, NULL),
(82, 133, 2, '2023-10-02 01:00:00', '2023-10-02 03:30:00', 34, 'NEW P2', NULL, NULL),
(83, 133, 3, '2023-10-09 01:00:00', '2023-10-09 03:30:00', 34, 'NEW P3', NULL, NULL),
(84, 133, 4, '2023-10-16 01:00:00', '2023-10-16 03:30:00', 34, 'NEW P4', NULL, NULL),
(85, 133, 5, '2023-10-23 01:00:00', '2023-10-23 03:30:00', 34, 'NEW P5', NULL, NULL),
(86, 133, 6, '2023-10-30 01:00:00', '2023-10-30 03:30:00', 34, 'NEW P6', NULL, NULL),
(87, 133, 7, '2023-11-06 01:00:00', '2023-11-06 03:30:00', 34, 'NEW P7', NULL, NULL),
(88, 133, 8, '2023-11-13 01:00:00', '2023-11-13 03:30:00', 34, 'UTS', NULL, NULL),
(89, 133, 9, '2023-11-20 01:00:00', '2023-11-20 03:30:00', 34, 'NEW P9', NULL, NULL),
(90, 133, 10, '2023-11-27 01:00:00', '2023-11-27 03:30:00', 34, 'NEW P10', NULL, NULL),
(91, 133, 11, '2023-12-04 01:00:00', '2023-12-04 03:30:00', 34, 'NEW P11', NULL, NULL),
(92, 133, 12, '2023-12-11 01:00:00', '2023-12-11 03:30:00', 34, 'NEW P12', NULL, NULL),
(93, 133, 13, '2023-12-18 01:00:00', '2023-12-18 03:30:00', 34, 'NEW P13', NULL, NULL),
(94, 133, 14, '2023-12-25 01:00:00', '2023-12-25 03:30:00', 34, 'NEW P14', NULL, NULL),
(95, 133, 15, '2024-01-01 01:00:00', '2024-01-01 03:30:00', 34, 'NEW P15', NULL, NULL),
(96, 133, 16, '2024-01-08 01:00:00', '2024-01-08 03:30:00', 34, 'UAS', NULL, NULL),
(113, 134, 1, '2023-04-04 03:00:00', '2023-04-04 04:40:00', 57, 'NEW P1', NULL, NULL),
(114, 134, 2, '2023-10-02 03:00:00', '2023-10-02 04:40:00', 57, 'NEW P2', NULL, NULL),
(115, 134, 3, '2023-10-09 03:00:00', '2023-10-09 04:40:00', 57, 'NEW P3', NULL, NULL),
(116, 134, 4, '2023-10-16 03:00:00', '2023-10-16 04:40:00', 57, 'NEW P4', NULL, NULL),
(117, 134, 5, '2023-10-23 03:00:00', '2023-10-23 04:40:00', 57, 'NEW P5', NULL, NULL),
(118, 134, 6, '2023-10-30 03:00:00', '2023-10-30 04:40:00', 57, 'NEW P6', NULL, NULL),
(119, 134, 7, '2023-11-06 03:00:00', '2023-11-06 04:40:00', 57, 'NEW P7', NULL, NULL),
(120, 134, 8, '2023-11-13 03:00:00', '2023-11-13 04:40:00', 57, 'UTS', NULL, NULL),
(121, 134, 9, '2023-11-20 03:00:00', '2023-11-20 04:40:00', 57, 'NEW P9', NULL, NULL),
(122, 134, 10, '2023-11-27 03:00:00', '2023-11-27 04:40:00', 57, 'NEW P10', NULL, NULL),
(123, 134, 11, '2023-12-04 03:00:00', '2023-12-04 04:40:00', 57, 'NEW P11', NULL, NULL),
(124, 134, 12, '2023-12-11 03:00:00', '2023-12-11 04:40:00', 57, 'NEW P12', NULL, NULL),
(125, 134, 13, '2023-12-18 03:00:00', '2023-12-18 04:40:00', 57, 'NEW P13', NULL, NULL),
(126, 134, 14, '2023-12-25 03:00:00', '2023-12-25 04:40:00', 57, 'NEW P14', NULL, NULL),
(127, 134, 15, '2024-01-01 03:00:00', '2024-01-01 04:40:00', 57, 'NEW P15', NULL, NULL),
(128, 134, 16, '2024-01-08 03:00:00', '2024-01-08 04:40:00', 57, 'UAS', NULL, NULL),
(129, 151, 1, '2024-07-26 06:30:00', '2024-07-26 09:00:00', 44, 'NEW P1', NULL, NULL),
(130, 151, 2, '2024-08-02 06:30:00', '2024-08-02 09:00:00', 44, 'NEW P2', NULL, NULL),
(131, 151, 3, '2024-08-09 06:30:00', '2024-08-09 09:00:00', 44, 'NEW P3', NULL, NULL),
(132, 151, 4, '2024-08-16 06:30:00', '2024-08-16 09:00:00', 44, 'NEW P4', NULL, NULL),
(133, 151, 5, '2024-08-23 06:30:00', '2024-08-23 09:00:00', 44, 'NEW P5', NULL, NULL),
(134, 151, 6, '2024-08-30 06:30:00', '2024-08-30 09:00:00', 44, 'NEW P6', NULL, NULL),
(135, 151, 7, '2024-09-06 06:30:00', '2024-09-06 09:00:00', 44, 'NEW P7', NULL, NULL),
(136, 151, 8, '2024-09-13 06:30:00', '2024-09-13 09:00:00', 44, 'UTS', NULL, NULL),
(137, 151, 9, '2024-09-20 06:30:00', '2024-09-20 09:00:00', 44, 'NEW P9', NULL, NULL),
(138, 151, 10, '2024-09-27 06:30:00', '2024-09-27 09:00:00', 44, 'NEW P10', NULL, NULL),
(139, 151, 11, '2024-10-04 06:30:00', '2024-10-04 09:00:00', 44, 'NEW P11', NULL, NULL),
(140, 151, 12, '2024-10-11 06:30:00', '2024-10-11 09:00:00', 44, 'NEW P12', NULL, NULL),
(141, 151, 13, '2024-10-18 06:30:00', '2024-10-18 09:00:00', 44, 'NEW P13', NULL, NULL),
(142, 151, 14, '2024-10-25 06:30:00', '2024-10-25 09:00:00', 44, 'NEW P14', NULL, NULL),
(143, 151, 15, '2024-11-01 06:30:00', '2024-11-01 09:00:00', 44, 'NEW P15', NULL, NULL),
(144, 151, 16, '2024-11-08 06:30:00', '2024-11-08 09:00:00', 44, 'UAS', NULL, NULL),
(145, 147, 1, '2024-07-25 03:30:00', '2024-07-25 06:50:00', 48, 'NEW P1', NULL, NULL),
(146, 147, 2, '2024-08-01 03:30:00', '2024-08-01 06:50:00', 48, 'NEW P2', NULL, NULL),
(147, 147, 3, '2024-08-08 03:30:00', '2024-08-08 06:50:00', 48, 'NEW P3', NULL, NULL),
(148, 147, 4, '2024-08-15 03:30:00', '2024-08-15 06:50:00', 48, 'NEW P4', NULL, NULL),
(149, 147, 5, '2024-08-22 03:30:00', '2024-08-22 06:50:00', 48, 'NEW P5', NULL, NULL),
(150, 147, 6, '2024-08-29 03:30:00', '2024-08-29 06:50:00', 48, 'NEW P6', NULL, NULL),
(151, 147, 7, '2024-09-05 03:30:00', '2024-09-05 06:50:00', 48, 'NEW P7', NULL, NULL),
(152, 147, 8, '2024-09-12 03:30:00', '2024-09-12 06:50:00', 48, 'UTS', NULL, NULL),
(153, 147, 9, '2024-09-19 03:30:00', '2024-09-19 06:50:00', 48, 'NEW P9', NULL, NULL),
(154, 147, 10, '2024-09-26 03:30:00', '2024-09-26 06:50:00', 48, 'NEW P10', NULL, NULL),
(155, 147, 11, '2024-10-03 03:30:00', '2024-10-03 06:50:00', 48, 'NEW P11', NULL, NULL),
(156, 147, 12, '2024-10-10 03:30:00', '2024-10-10 06:50:00', 48, 'NEW P12', NULL, NULL),
(157, 147, 13, '2024-10-17 03:30:00', '2024-10-17 06:50:00', 48, 'NEW P13', NULL, NULL),
(158, 147, 14, '2024-10-24 03:30:00', '2024-10-24 06:50:00', 48, 'NEW P14', NULL, NULL),
(159, 147, 15, '2024-10-31 03:30:00', '2024-10-31 06:50:00', 48, 'NEW P15', NULL, NULL),
(160, 147, 16, '2024-11-07 03:30:00', '2024-11-07 06:50:00', 48, 'UAS', NULL, NULL),
(161, 153, 1, '2024-12-26 01:00:00', '2024-12-26 04:20:00', 32, 'NEW P1', NULL, NULL),
(162, 153, 2, '2025-01-02 01:00:00', '2025-01-02 04:20:00', 32, 'NEW P2', NULL, NULL),
(163, 153, 3, '2025-01-09 01:00:00', '2025-01-09 04:20:00', 32, 'NEW P3', NULL, NULL),
(164, 153, 4, '2025-01-16 01:00:00', '2025-01-16 04:20:00', 32, 'NEW P4', NULL, NULL),
(165, 153, 5, '2025-01-23 01:00:00', '2025-01-23 04:20:00', 32, 'NEW P5', NULL, NULL),
(166, 153, 6, '2025-01-30 01:00:00', '2025-01-30 04:20:00', 32, 'NEW P6', NULL, NULL),
(167, 153, 7, '2025-02-06 01:00:00', '2025-02-06 04:20:00', 32, 'NEW P7', NULL, NULL),
(168, 153, 8, '2025-02-13 01:00:00', '2025-02-13 04:20:00', 32, 'UTS', NULL, NULL),
(169, 153, 9, '2025-02-20 01:00:00', '2025-02-20 04:20:00', 32, 'NEW P9', NULL, NULL),
(170, 153, 10, '2025-02-27 01:00:00', '2025-02-27 04:20:00', 32, 'NEW P10', NULL, NULL),
(171, 153, 11, '2025-03-06 01:00:00', '2025-03-06 04:20:00', 32, 'NEW P11', NULL, NULL),
(172, 153, 12, '2025-03-13 01:00:00', '2025-03-13 04:20:00', 32, 'NEW P12', NULL, NULL),
(173, 153, 13, '2025-03-20 01:00:00', '2025-03-20 04:20:00', 32, 'NEW P13', NULL, NULL),
(174, 153, 14, '2025-03-27 01:00:00', '2025-03-27 04:20:00', 32, 'NEW P14', NULL, NULL),
(175, 153, 15, '2025-04-03 01:00:00', '2025-04-03 04:20:00', 32, 'NEW P15', NULL, NULL),
(176, 153, 16, '2025-04-10 01:00:00', '2025-04-10 04:20:00', 32, 'UAS', NULL, NULL),
(177, 159, 1, '2025-05-27 01:00:00', '2025-05-27 04:20:00', 47, 'NEW P1', NULL, NULL),
(178, 159, 2, '2025-06-03 01:00:00', '2025-06-03 04:20:00', 47, 'NEW P2', NULL, NULL),
(179, 159, 3, '2025-06-10 01:00:00', '2025-06-10 04:20:00', 47, 'NEW P3', NULL, NULL),
(180, 159, 4, '2025-06-17 01:00:00', '2025-06-17 04:20:00', 47, 'NEW P4', NULL, NULL),
(181, 159, 5, '2025-06-24 01:00:00', '2025-06-24 04:20:00', 47, 'NEW P5', NULL, NULL),
(182, 159, 6, '2025-07-01 01:00:00', '2025-07-01 04:20:00', 47, 'NEW P6', NULL, NULL),
(183, 159, 7, '2025-07-08 01:00:00', '2025-07-08 04:20:00', 47, 'NEW P7', NULL, NULL),
(184, 159, 8, '2025-07-15 01:00:00', '2025-07-15 04:20:00', 47, 'UTS', NULL, NULL),
(185, 159, 9, '2025-07-22 01:00:00', '2025-07-22 04:20:00', 47, 'NEW P9', NULL, NULL),
(186, 159, 10, '2025-07-29 01:00:00', '2025-07-29 04:20:00', 47, 'NEW P10', NULL, NULL),
(187, 159, 11, '2025-08-05 01:00:00', '2025-08-05 04:20:00', 47, 'NEW P11', NULL, NULL),
(188, 159, 12, '2025-08-12 01:00:00', '2025-08-12 04:20:00', 47, 'NEW P12', NULL, NULL),
(189, 159, 13, '2025-08-19 01:00:00', '2025-08-19 04:20:00', 47, 'NEW P13', NULL, NULL),
(190, 159, 14, '2025-08-26 01:00:00', '2025-08-26 04:20:00', 47, 'NEW P14', NULL, NULL),
(191, 159, 15, '2025-09-02 01:00:00', '2025-09-02 04:20:00', 47, 'NEW P15', NULL, NULL),
(192, 159, 16, '2025-09-09 01:00:00', '2025-09-09 04:20:00', 47, 'UAS', NULL, NULL),
(193, 164, 1, '2025-10-29 01:00:00', '2025-10-29 04:20:00', 31, 'NEW P1', NULL, NULL),
(194, 164, 2, '2025-11-05 01:00:00', '2025-11-05 04:20:00', 31, 'NEW P2', NULL, NULL),
(195, 164, 3, '2025-11-12 01:00:00', '2025-11-12 04:20:00', 31, 'NEW P3', NULL, NULL),
(196, 164, 4, '2025-11-19 01:00:00', '2025-11-19 04:20:00', 31, 'NEW P4', NULL, NULL),
(197, 164, 5, '2025-11-26 01:00:00', '2025-11-26 04:20:00', 31, 'NEW P5', NULL, NULL),
(198, 164, 6, '2025-12-03 01:00:00', '2025-12-03 04:20:00', 31, 'NEW P6', NULL, NULL),
(199, 164, 7, '2025-12-10 01:00:00', '2025-12-10 04:20:00', 31, 'NEW P7', NULL, NULL),
(200, 164, 8, '2025-12-17 01:00:00', '2025-12-17 04:20:00', 31, 'UTS', NULL, NULL),
(201, 164, 9, '2025-12-24 01:00:00', '2025-12-24 04:20:00', 31, 'NEW P9', NULL, NULL),
(202, 164, 10, '2025-12-31 01:00:00', '2025-12-31 04:20:00', 31, 'NEW P10', NULL, NULL),
(203, 164, 11, '2026-01-07 01:00:00', '2026-01-07 04:20:00', 31, 'NEW P11', NULL, NULL),
(204, 164, 12, '2026-01-14 01:00:00', '2026-01-14 04:20:00', 31, 'NEW P12', NULL, NULL),
(205, 164, 13, '2026-01-21 01:00:00', '2026-01-21 04:20:00', 31, 'NEW P13', NULL, NULL),
(206, 164, 14, '2026-01-28 01:00:00', '2026-01-28 04:20:00', 31, 'NEW P14', NULL, NULL),
(207, 164, 15, '2026-02-04 01:00:00', '2026-02-04 04:20:00', 31, 'NEW P15', NULL, NULL),
(208, 164, 16, '2026-02-11 01:00:00', '2026-02-11 04:20:00', 31, 'UAS', NULL, NULL),
(209, 169, 1, '2026-03-26 07:00:00', '2026-03-26 10:20:00', 31, 'NEW P1', NULL, NULL),
(210, 169, 2, '2026-04-02 07:00:00', '2026-04-02 10:20:00', 31, 'NEW P2', NULL, NULL),
(211, 169, 3, '2026-04-09 07:00:00', '2026-04-09 10:20:00', 31, 'NEW P3', NULL, NULL),
(212, 169, 4, '2026-04-16 07:00:00', '2026-04-16 10:20:00', 31, 'NEW P4', NULL, NULL),
(213, 169, 5, '2026-04-23 07:00:00', '2026-04-23 10:20:00', 31, 'NEW P5', NULL, NULL),
(214, 169, 6, '2026-04-30 07:00:00', '2026-04-30 10:20:00', 31, 'NEW P6', NULL, NULL),
(215, 169, 7, '2026-05-07 07:00:00', '2026-05-07 10:20:00', 31, 'NEW P7', NULL, NULL),
(216, 169, 8, '2026-05-14 07:00:00', '2026-05-14 10:20:00', 31, 'UTS', NULL, NULL),
(217, 169, 9, '2026-05-21 07:00:00', '2026-05-21 10:20:00', 31, 'NEW P9', NULL, NULL),
(218, 169, 10, '2026-05-28 07:00:00', '2026-05-28 10:20:00', 31, 'NEW P10', NULL, NULL),
(219, 169, 11, '2026-06-04 07:00:00', '2026-06-04 10:20:00', 31, 'NEW P11', NULL, NULL),
(220, 169, 12, '2026-06-11 07:00:00', '2026-06-11 10:20:00', 31, 'NEW P12', NULL, NULL),
(221, 169, 13, '2026-06-18 07:00:00', '2026-06-18 10:20:00', 31, 'NEW P13', NULL, NULL),
(222, 169, 14, '2026-06-25 07:00:00', '2026-06-25 10:20:00', 31, 'NEW P14', NULL, NULL),
(223, 169, 15, '2026-07-02 07:00:00', '2026-07-02 10:20:00', 31, 'NEW P15', NULL, NULL),
(224, 169, 16, '2026-07-09 07:00:00', '2026-07-09 10:20:00', 31, 'UAS', NULL, NULL),
(225, 174, 1, '2026-08-29 01:00:00', '2026-08-29 04:20:00', 50, 'NEW P1', NULL, NULL),
(226, 174, 2, '2026-09-05 01:00:00', '2026-09-05 04:20:00', 50, 'NEW P2', NULL, NULL),
(227, 174, 3, '2026-09-12 01:00:00', '2026-09-12 04:20:00', 50, 'NEW P3', NULL, NULL),
(228, 174, 4, '2026-09-19 01:00:00', '2026-09-19 04:20:00', 50, 'NEW P4', NULL, NULL),
(229, 174, 5, '2026-09-26 01:00:00', '2026-09-26 04:20:00', 50, 'NEW P5', NULL, NULL),
(230, 174, 6, '2026-10-03 01:00:00', '2026-10-03 04:20:00', 50, 'NEW P6', NULL, NULL),
(231, 174, 7, '2026-10-10 01:00:00', '2026-10-10 04:20:00', 50, 'NEW P7', NULL, NULL),
(232, 174, 8, '2026-10-17 01:00:00', '2026-10-17 04:20:00', 50, 'UTS', NULL, NULL),
(233, 174, 9, '2026-10-24 01:00:00', '2026-10-24 04:20:00', 50, 'NEW P9', NULL, NULL),
(234, 174, 10, '2026-10-31 01:00:00', '2026-10-31 04:20:00', 50, 'NEW P10', NULL, NULL),
(235, 174, 11, '2026-11-07 01:00:00', '2026-11-07 04:20:00', 50, 'NEW P11', NULL, NULL),
(236, 174, 12, '2026-11-14 01:00:00', '2026-11-14 04:20:00', 50, 'NEW P12', NULL, NULL),
(237, 174, 13, '2026-11-21 01:00:00', '2026-11-21 04:20:00', 50, 'NEW P13', NULL, NULL),
(238, 174, 14, '2026-11-28 01:00:00', '2026-11-28 04:20:00', 50, 'NEW P14', NULL, NULL),
(239, 174, 15, '2026-12-05 01:00:00', '2026-12-05 04:20:00', 50, 'NEW P15', NULL, NULL),
(240, 174, 16, '2026-12-12 01:00:00', '2026-12-12 04:20:00', 50, 'UAS', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_status_jadwal`
--

CREATE TABLE `tb_status_jadwal` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_status_jadwal`
--

INSERT INTO `tb_status_jadwal` (`id`, `nama`) VALUES
(1, 'Sedang Berlangsung'),
(2, 'Selesai UAS');

-- --------------------------------------------------------

--
-- Table structure for table `tb_status_mhs`
--

CREATE TABLE `tb_status_mhs` (
  `id` tinyint(1) NOT NULL,
  `status_mhs` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_status_mhs`
--

INSERT INTO `tb_status_mhs` (`id`, `status_mhs`) VALUES
(0, 'Aktif - Belum KRS'),
(1, 'Aktif - Sudah KRS');

-- --------------------------------------------------------

--
-- Table structure for table `tb_status_mk`
--

CREATE TABLE `tb_status_mk` (
  `id` tinyint(4) NOT NULL,
  `status_mk` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_status_mk`
--

INSERT INTO `tb_status_mk` (`id`, `status_mk`) VALUES
(-1, 'Nonaktif'),
(0, 'Draft MK'),
(1, 'Lengkap');

-- --------------------------------------------------------

--
-- Table structure for table `tb_status_sesi`
--

CREATE TABLE `tb_status_sesi` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_status_sesi`
--

INSERT INTO `tb_status_sesi` (`id`, `nama`) VALUES
(1, 'Terlaksana'),
(2, 'Tidak Sesuai Jadwal');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tipe_sesi`
--

CREATE TABLE `tb_tipe_sesi` (
  `id` tinyint(4) NOT NULL,
  `nama` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_tipe_sesi`
--

INSERT INTO `tb_tipe_sesi` (`id`, `nama`) VALUES
(1, 'Teleconference'),
(2, 'Hybrid-Zoom'),
(3, 'Hybrid-Offline'),
(4, 'Offline');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `nama`, `username`, `password`, `date_created`, `last_login`, `role`) VALUES
(25, 'ABDUL AJIZ, S.T,M.Kom', 'abdul', '82027888c5bb8fc395411cb6804a066c', '2023-04-09 07:40:05', NULL, 2),
(26, 'EDI TOHIDI, MM', 'editohidi', '33d0364b580e33f6d608ea889361fc92', '2023-04-09 07:40:05', NULL, 2),
(27, 'Drs. EDI WAHYUDIN, M.Pd', 'ediwahyudin', '37da3158c07c7fa1349ce900e98cf8c4', '2023-04-09 07:40:05', NULL, 2),
(28, 'KASLANI, S.E.,M.M', 'kaslani', '7c1171f130b1c7ff8e4b8b486202bcf9', '2023-04-09 07:40:05', NULL, 2),
(29, 'Dra. NINING RAHANINGSIH, M.Si', 'nining', 'd844d7002741826f01a93f58e67effa1', '2023-04-09 07:40:05', NULL, 2),
(30, 'WILLY PRIHARTONO, M.Kom', 'willy', 'e7236697824fb37763235980f1061218', '2023-04-09 07:40:05', NULL, 2),
(31, 'DIAN ADE KURNIA, M.Kom', 'dian', 'f97de4a9986d216a6e0fea62b0450da9', '2023-04-09 07:40:05', NULL, 2),
(32, 'MARTANTO, M.Kom', 'martanto', '400bc33739afe674cfdf86e2d28a1101', '2023-04-09 07:40:05', NULL, 2),
(33, 'ODI NURDIAWAN, M.Kom', 'odi', '95734b47c7b7d7c3be6da6d9aac3a7a1', '2023-04-09 07:40:05', NULL, 2),
(34, 'RADITYA DANAR DANA, M.Kom', 'raditya', 'cdf6b37f50728ed655037ac8edfa658d', '2023-04-09 07:40:05', NULL, 2),
(35, 'SANDY EKA PERMANA', 'sandy', 'd686a53fb86a6c31fa6faa1d9333267e', '2023-04-09 07:40:05', NULL, 2),
(36, 'ADE RIZKI RINALDI, M.Kom', 'aderizki', 'bb677ea2a628438828e20c6c00e4091c', '2023-04-09 07:40:05', NULL, 2),
(37, 'ARIF RINALDI DIKANANDA, M.Kom', 'arif', '0ff6c3ace16359e41e37d40b8301d67f', '2023-04-09 07:40:05', NULL, 2),
(38, 'CEP LUKMAN ROHMAT', 'cep', '6629091da97f1e713af6b5399eeb9846', '2023-04-09 07:40:05', NULL, 2),
(39, 'FATHURROHMAN, M.Kom', 'fathurrohman', '5bd84d701090254a047e2f864302a6a8', '2023-04-09 07:40:05', NULL, 2),
(41, 'IIN, M.Kom', 'iin', 'f6a2e5ede47e66c7212ffaa258b7f5c8', '2023-04-09 07:40:05', NULL, 2),
(42, 'IRFAN ALI, M.Kom', 'irfan', '24b90bc48a67ac676228385a7c71a119', '2023-04-09 07:40:05', NULL, 2),
(43, 'AGUS BAHTIAR, M.Kom', 'agus', 'fdf169558242ee051cca1479770ebac3', '2023-04-09 07:40:05', NULL, 2),
(44, 'FADHIL MUHAMAD BASYSYAR, M.Kom', 'fadhil', 'fc646ab58bc3535f15cebaf9caa144e6', '2023-04-09 07:40:05', NULL, 2),
(45, 'GIFTHERA DWILESTARI', 'gifthera', '452421d8d9cfdaf92dbc7e17b4518f08', '2023-04-09 07:40:05', NULL, 2),
(46, 'MULYAWAN, M.Kom', 'mulyawan', 'a02e897a8bf778cec7e554dbd0132895', '2023-04-09 07:40:05', NULL, 2),
(47, 'YUDHISTIRA ARIE WIJAYA, M.Kom', 'yudhistira', '8e4ce768b5e2182b5b49baa1a1fb3604', '2023-04-09 07:40:05', NULL, 2),
(48, 'ADE IRMA PURNAMA SARI, M.Kom', 'adeirma', 'bb880d0ecd0901257f69e71e47ac8e1a', '2023-04-09 07:40:05', NULL, 2),
(49, 'AHMAD FAQIH, M.Sc', 'ahmad', '61243c7b9a4022cb3f8dc3106767ed12', '2023-04-09 07:40:05', NULL, 2),
(50, 'Dr. DADANG SUDRAJAT, S.Si, M.Kom', 'dadang', '0037bb978d51e84d1ad5478e85430f26', '2023-04-09 07:40:05', NULL, 2),
(51, 'DEDE ROHMAN', 'dede', 'b4be1c568a6dc02dcaf2849852bdb13e', '2023-04-09 07:40:05', NULL, 2),
(52, 'DENDY INDRIYA EFENDI', 'dendy', '43bb27fea7752340a3c1cd599ddf43e3', '2023-04-09 07:40:05', NULL, 2),
(53, 'DODI SOLIHUDIN, S.T,M.T', 'dodi', 'dc82a0e0107a31ba5d137a47ab09a26b', '2023-04-09 07:40:05', NULL, 2),
(54, 'HELIYANTI SUSANA', 'heliyanti', 'b5bc41d0d736c3e52c6f5b1596cea31d', '2023-04-09 07:40:05', NULL, 2),
(55, 'MUHAMAD SULAEMAN, M.Kom', 'muhamad', 'cebd50a2aac0ee7ad9d6094e67d4e421', '2023-04-09 07:40:05', NULL, 2),
(56, 'NANA SUARNA, M.Kom', 'nana', '518d5f3401534f5c6c21977f12f60989', '2023-04-09 07:40:05', NULL, 2),
(57, 'NISA DIENWATI NURIS, M.Sos', 'nisa', '5fad30428811fe378fd389cd7659a33c', '2023-04-09 07:40:05', NULL, 2),
(58, 'RIRI NARASATI, M.Hum', 'riri', 'c740d6848b6a342dcc26c177ea2c49fe', '2023-04-09 07:40:05', NULL, 2),
(59, 'RULI HERDIANA, S.Kom,M.Kom', 'ruli', '0570cf61102cebd52f556930814f14cb', '2023-04-09 07:40:05', NULL, 2),
(60, 'RYAN HAMONANGAN', 'ryan', '10c7ccc7a4f0aff03c915c485565b9da', '2023-04-09 07:40:05', NULL, 2),
(61, 'SAEFUL ANWAR, M.Pd', 'saeful', '781bf3e75a4de897247c1b4cb80d92d8', '2023-04-09 07:40:05', NULL, 2),
(62, 'TATI SUPRAPTI, M.Kom', 'tati', 'd6a9b920af25b1d240105bec4efe9c81', '2023-04-09 07:40:05', NULL, 2),
(63, 'UMI HAYATI, M.Kom', 'umi', 'e84f942d7f93ddc14d24b930d87e3da7', '2023-04-09 07:40:05', NULL, 2),
(64, 'BAMBANG IRAWAN, MT', 'bambangirawan', '554ff489fa84c3108052f8fda18ccc73', '2023-04-09 07:40:05', NULL, 2),
(65, 'RINI ASTUTI, MT', 'rini', 'b86872751de1e13c142d050acfd09842', '2023-04-09 07:40:05', NULL, 2),
(66, 'IMAS MUFTI, MM', 'imas', '633fb8c63e06dfd4b6f90a150d4d8b1c', '2023-04-09 07:40:05', NULL, 2),
(67, 'MASKURI, M.Ag', 'maskuri', '1a49a6aff7ed45a11b009806603684ea', '2023-04-09 07:40:05', NULL, 2),
(68, 'M. TAUFIK, M.Ag', 'taufik', 'd4305d7ed2ec97107cd6eb8dd4b6f6b7', '2023-04-09 07:40:05', NULL, 2),
(69, 'BAMBANG SISWOYO,M.Si, M.Kom', 'bambangsiswoyo', '6341880521cbe4037d32ce2282af6ee3', '2023-04-09 07:40:05', NULL, 2),
(101, 'MUTIARA SHALSA NABILA DUMMY', '44220001', '44220001', '2023-04-09 10:49:51', NULL, 1),
(102, 'NABILA AFRILISSIA DUMMY', '44220002', '44220002', '2023-04-09 10:49:51', NULL, 1),
(103, 'NABIL PRASETIYA DUMMY', '44220003', '44220003', '2023-04-09 10:49:51', NULL, 1),
(104, 'MUHAMMAD NAFAL RAMADHAN PRI DUMMY', '44220004', '44220004', '2023-04-09 10:49:51', NULL, 1),
(105, 'LULUAH NAFISAH ULUM DUMMY', '44220005', '44220005', '2023-04-09 10:49:51', NULL, 1),
(106, 'MUHAMMAD NAFIS MUFLIH DUMMY', '44220006', '44220006', '2023-04-09 10:49:51', NULL, 1),
(107, 'NAILA NURFALAH DUMMY', '44220007', '44220007', '2023-04-09 10:49:51', NULL, 1),
(108, 'NALENDRO AGUNG PRASOJO DUMMY', '44220008', '44220008', '2023-04-09 10:49:51', NULL, 1),
(109, 'NASAKH DUMMY', '44220009', '44220009', '2023-04-09 10:49:51', NULL, 1),
(110, 'NASIKHUN AMIN PRI  DUMMY', '44220010', '44220010', '2023-04-09 10:49:51', NULL, 1),
(111, 'NAUFAL AL FARIS  DUMMY', '44220011', '44220011', '2023-04-09 10:49:51', NULL, 1),
(112, 'NAUFAL FEBRIANO PRI DUMMY', '44220012', '44220012', '2023-04-09 10:49:51', NULL, 1),
(113, 'NABILAH DUMMY', '44220013', '44220013', '2023-04-09 10:49:51', NULL, 1),
(114, 'NINIH ZAHROTUL UMAMI DUMMY', '44220014', '44220014', '2023-04-09 10:49:51', NULL, 1),
(115, 'FITRIA DUMMY', '44220015', '44220015', '2023-04-09 10:49:51', NULL, 1),
(116, 'NIDA KHAERUNISA DUMMY', '44220016', '44220016', '2023-04-09 10:49:51', NULL, 1),
(117, 'NIKE NURZANAH  DUMMY', '44220017', '44220017', '2023-04-09 10:49:51', NULL, 1),
(118, 'NILAM SARI DUMMY', '44220018', '44220018', '2023-04-09 10:49:51', NULL, 1),
(119, 'NINING SUKMAWATI DUMMY', '44220019', '44220019', '2023-04-09 10:49:51', NULL, 1),
(120, 'NIYA DUMMY', '44220020', '44220020', '2023-04-09 10:49:51', NULL, 1),
(121, 'NOER ICHWAN ALIM DUMMY', '44220021', '44220021', '2023-04-09 10:49:51', NULL, 1),
(122, 'CITRA NUR OKTAVIANI  DUMMY', '44220022', '44220022', '2023-04-09 10:49:51', NULL, 1),
(123, 'NOVIANTY  DUMMY', '44220023', '44220023', '2023-04-09 10:49:51', NULL, 1),
(124, 'NOVITASARI PRI DUMMY', '44220024', '44220024', '2023-04-09 10:49:51', NULL, 1),
(125, 'NOVITA SAFITRI DUMMY', '44220025', '44220025', '2023-04-09 10:49:51', NULL, 1),
(126, 'NADHIRA SYAFAATUN NISSA DUMMY', '44220026', '44220026', '2023-04-09 10:49:51', NULL, 1),
(127, 'NUCHBATUL FIKRI PRI DUMMY', '44220027', '44220027', '2023-04-09 10:49:51', NULL, 1),
(128, 'NURAFNI PUTRI DUMMY', '44220028', '44220028', '2023-04-09 10:49:51', NULL, 1),
(129, 'PWN DUMMY', '44220029', '44220029', '2023-04-09 10:49:51', NULL, 1),
(130, 'NURFADILLAH RAHAYU DUMMY', '44220030', '44220030', '2023-04-09 10:49:51', NULL, 1),
(131, 'NURFINA NOVIYANA DUMMY', '44220031', '44220031', '2023-04-09 10:49:51', NULL, 1),
(132, 'NURHAKIM BANI DUMMY', '44220032', '44220032', '2023-04-09 10:49:51', NULL, 1),
(133, 'NUROH AYUNI SHOLIHAH  DUMMY', '44220033', '44220033', '2023-04-09 10:49:51', NULL, 1),
(134, 'NUR SEFTIANAH PRI DUMMY', '44220034', '44220034', '2023-04-09 10:49:51', NULL, 1),
(135, 'NAILA NURUL ANISAH PRI DUMMY', '44220035', '44220035', '2023-04-09 10:49:51', NULL, 1),
(136, 'NURUL FAKIAH DUMMY', '44220036', '44220036', '2023-04-09 10:49:51', NULL, 1),
(137, 'NURUL SYIFA KHAIRINA PRI DUMMY', '44220037', '44220037', '2023-04-09 10:49:51', NULL, 1),
(138, 'NURYADI DUMMY', '44220038', '44220038', '2023-04-09 10:49:51', NULL, 1),
(139, 'NAZWA PUTRI NINDYA  DUMMY', '44220039', '44220039', '2023-04-09 10:49:51', NULL, 1),
(140, 'OKTAVIA ALVI AULIANA DUMMY', '44220040', '44220040', '2023-04-09 10:49:51', NULL, 1),
(141, 'OKTAVIA RAMADANI  DUMMY', '44220041', '44220041', '2023-04-09 10:49:51', NULL, 1),
(142, 'ILHAM MAULANA  DUMMY', '44220042', '44220042', '2023-04-09 10:49:51', NULL, 1),
(143, 'MUHAMAD SATRIA SAHID RAMADHAN PRI DUMMY', '44220043', '44220043', '2023-04-09 10:49:51', NULL, 1),
(144, 'FAUZI DUMMY', '44220044', '44220044', '2023-04-09 10:49:51', NULL, 1),
(145, 'MUHAMMAD DUDHY SETIAWAN DUMMY', '44220045', '44220045', '2023-04-09 10:49:51', NULL, 1),
(146, 'PHILIE PRI DUMMY', '44220046', '44220046', '2023-04-09 10:49:51', NULL, 1),
(147, 'PANDU PRAMANA DUMMY', '44220047', '44220047', '2023-04-09 10:49:51', NULL, 1),
(148, 'PUPUT MELINDA  DUMMY', '44220048', '44220048', '2023-04-09 10:49:51', NULL, 1),
(149, 'PUTRI RAHMAWATI DUMMY', '44220049', '44220049', '2023-04-09 10:49:51', NULL, 1),
(150, 'AZIZAH PUTRI DEVANIE PRI DUMMY', '44220050', '44220050', '2023-04-09 10:49:51', NULL, 1),
(7020, 'Ahmad Firdaus dummy', '31229996', '31229996', '2023-04-09 10:49:51', NULL, 1),
(7021, 'Anak MI Sore dummy', '31313131', '31313131', '2023-04-09 10:49:51', NULL, 1),
(7022, 'Budi dummy', '41414142', '41414142', '2023-04-09 10:49:51', NULL, 1),
(7023, 'Charli dummy', '41414143', '41414143', '2023-04-09 10:49:51', NULL, 1),
(7024, 'Deni dummy', '41414144', '41414144', '2023-04-09 10:49:51', NULL, 1),
(7025, 'Erwin dummy', '41414145', '41414145', '2023-04-09 10:49:51', NULL, 1),
(7026, 'Fajar dummy', '41414146', '41414146', '2023-04-09 10:49:51', NULL, 1),
(7027, 'Gilang dummy', '41414147', '41414147', '2023-04-09 10:49:51', NULL, 1),
(7028, 'Haris dummy', '41414148', '41414148', '2023-04-09 10:49:51', NULL, 1),
(7029, 'Ira dummy', '41414149', '41414149', '2023-04-09 10:49:51', NULL, 1),
(7030, 'Joko dummy', '41414150', '41414150', '2023-04-09 10:49:51', NULL, 1),
(7031, 'Lilis dummy', '41414151', '41414151', '2023-04-09 10:49:51', NULL, 1),
(7032, 'Salwa Fatimah Dummy', '31229995', '31229995', '2023-04-09 10:49:51', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin_level`
--
ALTER TABLE `tb_admin_level`
  ADD PRIMARY KEY (`admin_level`);

--
-- Indexes for table `tb_angkatan`
--
ALTER TABLE `tb_angkatan`
  ADD PRIMARY KEY (`angkatan`);

--
-- Indexes for table `tb_assign_ruang`
--
ALTER TABLE `tb_assign_ruang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ruang` (`id_ruang`),
  ADD KEY `id_sesi_kuliah` (`id_sesi_kuliah`),
  ADD KEY `id_tipe_sesi` (`id_tipe_sesi`);

--
-- Indexes for table `tb_bk`
--
ALTER TABLE `tb_bk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_config`
--
ALTER TABLE `tb_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_dosen`
--
ALTER TABLE `tb_dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nidn` (`nidn`),
  ADD KEY `homebase` (`homebase`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_event`
--
ALTER TABLE `tb_event`
  ADD PRIMARY KEY (`id_event`),
  ADD UNIQUE KEY `id_file_name` (`id_file_name`),
  ADD KEY `id_calon` (`id_calon`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `tb_fakultas`
--
ALTER TABLE `tb_fakultas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dekan` (`id_dekan`),
  ADD KEY `id_pt` (`id_pt`);

--
-- Indexes for table `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kurikulum_semester_mk` (`id_kurikulum_mk`),
  ADD KEY `id_dosen` (`id_dosen`),
  ADD KEY `id_status_jadwal` (`id_status_jadwal`);

--
-- Indexes for table `tb_jalur`
--
ALTER TABLE `tb_jalur`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_jenjang`
--
ALTER TABLE `tb_jenjang`
  ADD PRIMARY KEY (`jenjang`);

--
-- Indexes for table `tb_kalender`
--
ALTER TABLE `tb_kalender`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_angkatan` (`angkatan`),
  ADD KEY `jenjang` (`jenjang`);

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`kelas`),
  ADD KEY `id_prodi` (`id_prodi`),
  ADD KEY `angkatan` (`angkatan`),
  ADD KEY `id_jalur` (`id_jalur`);

--
-- Indexes for table `tb_kelas_angkatan`
--
ALTER TABLE `tb_kelas_angkatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas` (`kelas`),
  ADD KEY `id_mhs` (`id_mhs`);

--
-- Indexes for table `tb_kelas_peserta`
--
ALTER TABLE `tb_kelas_peserta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas` (`kelas`),
  ADD KEY `id_kurikulum_mk` (`id_kurikulum_mk`),
  ADD KEY `id_dosen` (`id_dosen`);

--
-- Indexes for table `tb_kesalahan`
--
ALTER TABLE `tb_kesalahan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_konsentrasi`
--
ALTER TABLE `tb_konsentrasi`
  ADD PRIMARY KEY (`id_konsentrasi`),
  ADD KEY `id_prodi` (`id_prodi`);

--
-- Indexes for table `tb_kurikulum`
--
ALTER TABLE `tb_kurikulum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_prodi` (`id_prodi`),
  ADD KEY `id_kalender` (`id_kalender`);

--
-- Indexes for table `tb_kurikulum_mk`
--
ALTER TABLE `tb_kurikulum_mk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mk` (`id_mk`),
  ADD KEY `id_semester` (`id_semester`),
  ADD KEY `id_kurikulum` (`id_kurikulum`);

--
-- Indexes for table `tb_mhs`
--
ALTER TABLE `tb_mhs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `status_mhs` (`status_mhs`),
  ADD KEY `id_pmb` (`id_pmb`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_mk`
--
ALTER TABLE `tb_mk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_mk` (`kode`),
  ADD KEY `status_mk` (`is_publish`),
  ADD KEY `id_bk` (`id_bk`);

--
-- Indexes for table `tb_output_pmb`
--
ALTER TABLE `tb_output_pmb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_pegawai`
--
ALTER TABLE `tb_pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD UNIQUE KEY `no_wa` (`no_wa_pegawai`),
  ADD UNIQUE KEY `nik_pegawai` (`nik_pegawai`),
  ADD UNIQUE KEY `email_pegawai` (`email_pegawai`),
  ADD KEY `admin_level` (`admin_level`),
  ADD KEY `id_kec` (`id_kec`),
  ADD KEY `id_pegawai_detail` (`id_pegawai_detail`);

--
-- Indexes for table `tb_presensi`
--
ALTER TABLE `tb_presensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sesi_kuliah` (`id_sesi_kuliah`),
  ADD KEY `id_mhs` (`id_mhs`);

--
-- Indexes for table `tb_presensi_dosen`
--
ALTER TABLE `tb_presensi_dosen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sesi_kuliah` (`id_sesi_kuliah`),
  ADD KEY `id_dosen` (`id_dosen`);

--
-- Indexes for table `tb_prodi`
--
ALTER TABLE `tb_prodi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kaprodi` (`id_kaprodi`),
  ADD KEY `id_fakultas` (`id_fakultas`),
  ADD KEY `jenjang` (`jenjang`);

--
-- Indexes for table `tb_pt`
--
ALTER TABLE `tb_pt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rektor` (`id_rektor`);

--
-- Indexes for table `tb_ruang`
--
ALTER TABLE `tb_ruang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_sekolah_asal`
--
ALTER TABLE `tb_sekolah_asal`
  ADD PRIMARY KEY (`id_sekolah`);

--
-- Indexes for table `tb_semester`
--
ALTER TABLE `tb_semester`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kalender` (`id_kalender`);

--
-- Indexes for table `tb_sesi_kuliah`
--
ALTER TABLE `tb_sesi_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `pengajar` (`id_dosen`),
  ADD KEY `status` (`id_status_sesi`);

--
-- Indexes for table `tb_status_jadwal`
--
ALTER TABLE `tb_status_jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_status_mhs`
--
ALTER TABLE `tb_status_mhs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_status_mk`
--
ALTER TABLE `tb_status_mk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_status_sesi`
--
ALTER TABLE `tb_status_sesi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_tipe_sesi`
--
ALTER TABLE `tb_tipe_sesi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_assign_ruang`
--
ALTER TABLE `tb_assign_ruang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tb_bk`
--
ALTER TABLE `tb_bk`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_dosen`
--
ALTER TABLE `tb_dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `tb_event`
--
ALTER TABLE `tb_event`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_fakultas`
--
ALTER TABLE `tb_fakultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `tb_jalur`
--
ALTER TABLE `tb_jalur`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_kalender`
--
ALTER TABLE `tb_kalender`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_kelas_angkatan`
--
ALTER TABLE `tb_kelas_angkatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tb_kelas_peserta`
--
ALTER TABLE `tb_kelas_peserta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tb_kesalahan`
--
ALTER TABLE `tb_kesalahan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_konsentrasi`
--
ALTER TABLE `tb_konsentrasi`
  MODIFY `id_konsentrasi` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_kurikulum`
--
ALTER TABLE `tb_kurikulum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tb_kurikulum_mk`
--
ALTER TABLE `tb_kurikulum_mk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- AUTO_INCREMENT for table `tb_mhs`
--
ALTER TABLE `tb_mhs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6933;

--
-- AUTO_INCREMENT for table `tb_mk`
--
ALTER TABLE `tb_mk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `tb_output_pmb`
--
ALTER TABLE `tb_output_pmb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_pegawai`
--
ALTER TABLE `tb_pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_presensi`
--
ALTER TABLE `tb_presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_presensi_dosen`
--
ALTER TABLE `tb_presensi_dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_prodi`
--
ALTER TABLE `tb_prodi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tb_pt`
--
ALTER TABLE `tb_pt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_ruang`
--
ALTER TABLE `tb_ruang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tb_sekolah_asal`
--
ALTER TABLE `tb_sekolah_asal`
  MODIFY `id_sekolah` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_semester`
--
ALTER TABLE `tb_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `tb_sesi_kuliah`
--
ALTER TABLE `tb_sesi_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7033;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_assign_ruang`
--
ALTER TABLE `tb_assign_ruang`
  ADD CONSTRAINT `tb_assign_ruang_ibfk_1` FOREIGN KEY (`id_sesi_kuliah`) REFERENCES `tb_sesi_kuliah` (`id`),
  ADD CONSTRAINT `tb_assign_ruang_ibfk_2` FOREIGN KEY (`id_ruang`) REFERENCES `tb_ruang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_assign_ruang_ibfk_3` FOREIGN KEY (`id_tipe_sesi`) REFERENCES `tb_tipe_sesi` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_dosen`
--
ALTER TABLE `tb_dosen`
  ADD CONSTRAINT `tb_dosen_ibfk_1` FOREIGN KEY (`homebase`) REFERENCES `tb_prodi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_dosen_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`);

--
-- Constraints for table `tb_fakultas`
--
ALTER TABLE `tb_fakultas`
  ADD CONSTRAINT `tb_fakultas_ibfk_1` FOREIGN KEY (`id_pt`) REFERENCES `tb_pt` (`id`);

--
-- Constraints for table `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  ADD CONSTRAINT `tb_jadwal_ibfk_1` FOREIGN KEY (`id_kurikulum_mk`) REFERENCES `tb_kurikulum_mk` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_jadwal_ibfk_2` FOREIGN KEY (`id_dosen`) REFERENCES `tb_dosen` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_jadwal_ibfk_3` FOREIGN KEY (`id_status_jadwal`) REFERENCES `tb_status_jadwal` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_kalender`
--
ALTER TABLE `tb_kalender`
  ADD CONSTRAINT `tb_kalender_ibfk_1` FOREIGN KEY (`angkatan`) REFERENCES `tb_angkatan` (`angkatan`),
  ADD CONSTRAINT `tb_kalender_ibfk_2` FOREIGN KEY (`jenjang`) REFERENCES `tb_jenjang` (`jenjang`);

--
-- Constraints for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD CONSTRAINT `tb_kelas_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `tb_prodi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_kelas_ibfk_2` FOREIGN KEY (`angkatan`) REFERENCES `tb_angkatan` (`angkatan`),
  ADD CONSTRAINT `tb_kelas_ibfk_3` FOREIGN KEY (`id_jalur`) REFERENCES `tb_jalur` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_kelas_angkatan`
--
ALTER TABLE `tb_kelas_angkatan`
  ADD CONSTRAINT `tb_kelas_angkatan_ibfk_1` FOREIGN KEY (`id_mhs`) REFERENCES `tb_mhs` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_kelas_angkatan_ibfk_2` FOREIGN KEY (`kelas`) REFERENCES `tb_kelas` (`kelas`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_kelas_peserta`
--
ALTER TABLE `tb_kelas_peserta`
  ADD CONSTRAINT `tb_kelas_peserta_ibfk_1` FOREIGN KEY (`kelas`) REFERENCES `tb_kelas` (`kelas`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_kelas_peserta_ibfk_2` FOREIGN KEY (`id_kurikulum_mk`) REFERENCES `tb_kurikulum_mk` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_kelas_peserta_ibfk_3` FOREIGN KEY (`id_dosen`) REFERENCES `tb_dosen` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_kurikulum`
--
ALTER TABLE `tb_kurikulum`
  ADD CONSTRAINT `tb_kurikulum_ibfk_2` FOREIGN KEY (`id_prodi`) REFERENCES `tb_prodi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_kurikulum_ibfk_3` FOREIGN KEY (`id_kalender`) REFERENCES `tb_kalender` (`id`);

--
-- Constraints for table `tb_kurikulum_mk`
--
ALTER TABLE `tb_kurikulum_mk`
  ADD CONSTRAINT `tb_kurikulum_mk_ibfk_2` FOREIGN KEY (`id_mk`) REFERENCES `tb_mk` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_kurikulum_mk_ibfk_3` FOREIGN KEY (`id_semester`) REFERENCES `tb_semester` (`id`),
  ADD CONSTRAINT `tb_kurikulum_mk_ibfk_4` FOREIGN KEY (`id_kurikulum`) REFERENCES `tb_kurikulum` (`id`);

--
-- Constraints for table `tb_mhs`
--
ALTER TABLE `tb_mhs`
  ADD CONSTRAINT `tb_mhs_ibfk_2` FOREIGN KEY (`id_pmb`) REFERENCES `tb_output_pmb` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_mhs_ibfk_4` FOREIGN KEY (`status_mhs`) REFERENCES `tb_status_mhs` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_mhs_ibfk_5` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_mk`
--
ALTER TABLE `tb_mk`
  ADD CONSTRAINT `tb_mk_ibfk_1` FOREIGN KEY (`id_bk`) REFERENCES `tb_bk` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_mk_ibfk_2` FOREIGN KEY (`is_publish`) REFERENCES `tb_status_mk` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_presensi`
--
ALTER TABLE `tb_presensi`
  ADD CONSTRAINT `tb_presensi_ibfk_1` FOREIGN KEY (`id_sesi_kuliah`) REFERENCES `tb_sesi_kuliah` (`id`),
  ADD CONSTRAINT `tb_presensi_ibfk_2` FOREIGN KEY (`id_mhs`) REFERENCES `tb_mhs` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_presensi_dosen`
--
ALTER TABLE `tb_presensi_dosen`
  ADD CONSTRAINT `tb_presensi_dosen_ibfk_1` FOREIGN KEY (`id_dosen`) REFERENCES `tb_dosen` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_presensi_dosen_ibfk_2` FOREIGN KEY (`id_sesi_kuliah`) REFERENCES `tb_sesi_kuliah` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_prodi`
--
ALTER TABLE `tb_prodi`
  ADD CONSTRAINT `tb_prodi_ibfk_1` FOREIGN KEY (`id_fakultas`) REFERENCES `tb_fakultas` (`id`),
  ADD CONSTRAINT `tb_prodi_ibfk_2` FOREIGN KEY (`jenjang`) REFERENCES `tb_jenjang` (`jenjang`);

--
-- Constraints for table `tb_semester`
--
ALTER TABLE `tb_semester`
  ADD CONSTRAINT `tb_semester_ibfk_1` FOREIGN KEY (`id_kalender`) REFERENCES `tb_kalender` (`id`);

--
-- Constraints for table `tb_sesi_kuliah`
--
ALTER TABLE `tb_sesi_kuliah`
  ADD CONSTRAINT `tb_sesi_kuliah_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `tb_jadwal` (`id`),
  ADD CONSTRAINT `tb_sesi_kuliah_ibfk_3` FOREIGN KEY (`id_dosen`) REFERENCES `tb_dosen` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_sesi_kuliah_ibfk_4` FOREIGN KEY (`id_status_sesi`) REFERENCES `tb_status_sesi` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
