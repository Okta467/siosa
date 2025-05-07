-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 11:26 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sipmen`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang`
--

CREATE TABLE `tbl_barang` (
  `id_barang` int(10) UNSIGNED NOT NULL,
  `kode_barang` varchar(10) NOT NULL,
  `nama_barang` varchar(128) NOT NULL,
  `satuan_barang` enum('dus','box','pcs') NOT NULL,
  `harga_barang` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_barang`
--

INSERT INTO `tbl_barang` (`id_barang`, `kode_barang`, `nama_barang`, `satuan_barang`, `harga_barang`, `created_at`, `updated_at`) VALUES
(1, '300175', 'Nabati RCO 43g GT (60pcs) PKU', 'pcs', 5000, '2024-07-29 19:06:49', '2025-04-27 17:10:30'),
(2, '300090', 'Nabati RCE 43g GT (60pcs) PKU', 'pcs', 5000, '2024-07-29 19:10:40', '2025-04-27 17:10:30'),
(3, '300360', 'Nabati PLV 39g GT (60pcs)', 'pcs', 5000, '2024-07-29 19:10:40', '2025-04-27 17:10:30'),
(4, '300982', 'Nabati GGM 39g GT (60pcs)', 'pcs', 5000, '2024-07-29 19:10:40', '2025-04-27 17:10:30'),
(5, '300089', 'Nabati RCE 17g GT (10pcs x 12bal) PKU', 'pcs', 5000, '2024-07-29 19:10:40', '2025-04-27 17:10:30'),
(6, '302020', 'Rolls RCE 6g GT (21pcs x 6ib) PKU', 'dus', 20000, '2024-07-29 19:36:37', '2025-05-04 13:36:38'),
(9, '300175', 'Abcd', 'pcs', 5000, '2024-08-02 14:42:22', '2025-04-27 17:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `id_customer` int(10) UNSIGNED NOT NULL,
  `id_pengguna` int(10) UNSIGNED DEFAULT NULL,
  `nama_customer` varchar(128) NOT NULL,
  `jenis_kelamin` enum('l','p') NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `tempat_lahir` varchar(64) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `path_to_foto_profil` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`id_customer`, `id_pengguna`, `nama_customer`, `jenis_kelamin`, `alamat`, `tempat_lahir`, `tanggal_lahir`, `path_to_foto_profil`, `created_at`, `updated_at`) VALUES
(18, 21, 'Okta Alfiansyah', 'l', 'Jln. KH Wahid Hasyim 1197, Lrg. Juwita, kec. Seberang Ulu 1, Kel. Jakabaring, Kota Palembang, Sumatera Selatan, 30257', 'Palembang', '1999-01-01', 'a492387eea87aa852c6a4639361511dbdce1df96d41dd84213c57c30e5b4949b.jpg', '2025-05-04 04:23:32', '2025-05-04 20:18:31'),
(21, 30, 'Bima Satria', 'l', 'Gang Duren', 'Semende', '1999-09-09', '', '2025-05-04 05:40:46', '2025-05-04 13:02:57'),
(22, 35, 'tes', 'l', 'Kertapati', 'Sem12321ende', '1999-01-01', '', '2025-05-04 19:18:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_informasi`
--

CREATE TABLE `tbl_informasi` (
  `id_informasi` int(10) UNSIGNED NOT NULL,
  `judul_informasi` varchar(128) NOT NULL,
  `isi_informasi` varchar(5000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_informasi`
--

INSERT INTO `tbl_informasi` (`id_informasi`, `judul_informasi`, `isi_informasi`, `created_at`, `updated_at`) VALUES
(2, 'Promo Diskon untuk Barang VXYZ', 'Kabar gembira bagi sobat Auto 2000 karena di tanggal 5 Mei ini akan ada banyak diskon untuk beberapa barang tertentu. Untuk list barang yang diskon bisa dicek di bawah, ya, sobat!\r\n1. Barang V\r\n2. Barang X\r\n3. Barang Y\r\n4. Barang Z', '2025-05-04 11:16:36', '2025-05-04 11:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengguna`
--

CREATE TABLE `tbl_pengguna` (
  `id_pengguna` int(10) UNSIGNED NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(128) NOT NULL,
  `hak_akses` enum('admin','customer','supervisor') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pengguna`
--

INSERT INTO `tbl_pengguna` (`id_pengguna`, `username`, `password`, `hak_akses`, `created_at`, `last_login`) VALUES
(9, 'admin', '$2y$10$r6i9ouw57cTTevcboVpfxuaaeGE.LqvH0ivtFunGnpjhus3jtxu1q', 'admin', '2024-06-10 14:42:24', '2025-05-04 15:10:52'),
(21, 'okta99', '$2y$10$0HgF2X3fvRoHinFuPtaLuOcaEKFP1Nt3b7ftnmvzWHds6wTN1mNLa', 'customer', '2025-05-04 04:23:32', '2025-05-04 15:36:12'),
(30, 'bima99', '$2y$10$SSoZDPXvWnCBfR4Et9qZHOiDH2wm9nGZNjJA8FmPG53lZ8avfiiJW', 'customer', '2025-05-04 05:40:46', NULL),
(31, 'nadya99', '$2y$10$l4sjXYt8ow1XYuPMt.gX6O97LU04lAtS2l.0RQgKr6eDf7MZC03/6', 'supervisor', '2025-05-04 05:41:20', '2025-05-04 15:09:54'),
(35, 'tess', '$2y$10$3N7nozsZbAuvEsdxRijZEOEQyx3WKbh0tiQGA57PLkh1PUTEtyyya', 'customer', '2025-05-04 19:18:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pesanan`
--

CREATE TABLE `tbl_pesanan` (
  `id_pesanan` int(11) UNSIGNED NOT NULL,
  `id_barang` int(10) UNSIGNED NOT NULL,
  `id_customer` int(10) UNSIGNED NOT NULL,
  `tanggal_pesanan` date NOT NULL,
  `jumlah_pesanan` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_pesanan`
--

INSERT INTO `tbl_pesanan` (`id_pesanan`, `id_barang`, `id_customer`, `tanggal_pesanan`, `jumlah_pesanan`, `created_at`, `updated_at`) VALUES
(1, 6, 21, '2024-07-30', 2, '2024-07-29 20:26:41', '2025-05-04 12:10:14'),
(3, 5, 21, '2024-07-30', 3, '2024-07-29 20:29:12', '2025-05-04 12:10:14'),
(4, 4, 21, '2024-07-30', 3, '2024-07-29 20:29:23', '2025-05-04 12:10:14'),
(6, 4, 21, '2024-07-30', 6, '2024-08-02 06:14:47', '2025-05-04 12:10:14'),
(8, 5, 21, '2024-07-30', 1, '2024-08-02 06:20:04', '2025-05-04 12:10:14'),
(9, 5, 21, '2024-07-30', 1, '2024-08-02 06:31:00', '2025-05-04 12:10:14'),
(10, 4, 21, '2024-07-30', 3, '2024-08-02 07:12:17', '2025-05-04 12:10:14'),
(16, 6, 18, '2025-05-04', 5, '2024-08-02 14:47:29', '2025-05-04 13:36:54'),
(17, 3, 18, '2025-05-04', 4, '2025-05-04 13:14:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supervisor`
--

CREATE TABLE `tbl_supervisor` (
  `id_supervisor` int(10) UNSIGNED NOT NULL,
  `id_pengguna` int(10) UNSIGNED DEFAULT NULL,
  `nama_supervisor` varchar(128) NOT NULL,
  `jenis_kelamin` enum('l','p') NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `tempat_lahir` varchar(64) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `path_to_foto_profil` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_supervisor`
--

INSERT INTO `tbl_supervisor` (`id_supervisor`, `id_pengguna`, `nama_supervisor`, `jenis_kelamin`, `alamat`, `tempat_lahir`, `tanggal_lahir`, `path_to_foto_profil`, `created_at`, `updated_at`) VALUES
(4, 31, 'Nadya', 'p', 'Ilir Barat 3', 'Paga', '2003-04-04', '57f1b1bea24c4f19f3bc6236c9b0fcab6a3ac62e8637fa91f9191ac67d656a6a.jpg', '2025-05-04 05:41:20', '2025-05-04 19:33:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_barang`
--
ALTER TABLE `tbl_barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`id_customer`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `tbl_informasi`
--
ALTER TABLE `tbl_informasi`
  ADD PRIMARY KEY (`id_informasi`);

--
-- Indexes for table `tbl_pengguna`
--
ALTER TABLE `tbl_pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tbl_pesanan`
--
ALTER TABLE `tbl_pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indexes for table `tbl_supervisor`
--
ALTER TABLE `tbl_supervisor`
  ADD PRIMARY KEY (`id_supervisor`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_barang`
--
ALTER TABLE `tbl_barang`
  MODIFY `id_barang` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `id_customer` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_informasi`
--
ALTER TABLE `tbl_informasi`
  MODIFY `id_informasi` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_pengguna`
--
ALTER TABLE `tbl_pengguna`
  MODIFY `id_pengguna` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_pesanan`
--
ALTER TABLE `tbl_pesanan`
  MODIFY `id_pesanan` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_supervisor`
--
ALTER TABLE `tbl_supervisor`
  MODIFY `id_supervisor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD CONSTRAINT `tbl_customer_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `tbl_pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_pesanan`
--
ALTER TABLE `tbl_pesanan`
  ADD CONSTRAINT `tbl_pesanan_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `tbl_barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_pesanan_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `tbl_customer` (`id_customer`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_supervisor`
--
ALTER TABLE `tbl_supervisor`
  ADD CONSTRAINT `tbl_supervisor_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `tbl_pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
