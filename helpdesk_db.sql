-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 16, 2026 at 02:42 PM
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
-- Database: `helpdesk_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Listrik', 'Kerusakan instalasi atau peralatan listrik'),
(2, 'Konstruksi', 'Kerusakan bangunan, pintu, jendela, atau meja'),
(3, 'AC', 'Kerusakan pendingin ruangan'),
(4, 'Peralatan Kelas', 'Kerusakan fasilitas pendukung pembelajaran');

-- --------------------------------------------------------

--
-- Table structure for table `penugasan`
--

CREATE TABLE `penugasan` (
  `id_penugasan` int(11) NOT NULL,
  `id_tiket` int(11) NOT NULL,
  `id_teknisi` int(11) NOT NULL,
  `tanggal_tugas` datetime NOT NULL DEFAULT current_timestamp(),
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_status`
--

CREATE TABLE `riwayat_status` (
  `id_riwayat` int(11) NOT NULL,
  `id_tiket` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `status_sebelumnya` varchar(30) DEFAULT NULL,
  `status_baru` varchar(30) NOT NULL,
  `catatan` text DEFAULT NULL,
  `waktu_perubahan` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_status`
--

INSERT INTO `riwayat_status` (`id_riwayat`, `id_tiket`, `id_user`, `status_sebelumnya`, `status_baru`, `catatan`, `waktu_perubahan`) VALUES
(1, 1, 1, NULL, 'diajukan', 'Pengaduan berhasil dibuat oleh siswa.', '2026-11-16 19:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `tiket`
--

CREATE TABLE `tiket` (
  `id_tiket` int(11) NOT NULL,
  `id_pelapor` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `deskripsi` text NOT NULL,
  `lokasi` varchar(150) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'diajukan',
  `tanggal_lapor` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiket`
--

INSERT INTO `tiket` (`id_tiket`, `id_pelapor`, `id_kategori`, `judul`, `deskripsi`, `lokasi`, `foto`, `status`, `tanggal_lapor`) VALUES
(1, 1, 1, 'LIstrik meledak', 'csaffasf', 'Kelas PPLG 2', NULL, 'diajukan', '2026-11-16 19:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('siswa','teknisi','sarpras') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Afgha', 'afgha', '$2y$10$viNNjxvShyEove5QNXHMKOGRfuwbe4MCbdraNPqgysQnaKbzdtC0O', 'siswa'),
(2, 'admin_sarpras', 'admin_sarpras', '$2y$10$zdav7hfBuNh8s/y3KTnU8.qPIgy96lZPvXBb1crb0P77afaiKGP9.', 'sarpras'),
(4, 'Teknisi_tapos', 'teknisi_tapos', '$2y$10$Tz0ZtP9kaaP3Uh8.Wc2siukLVU7KaKRyqt1fEcEKwGNTcNB.cMeL.', 'teknisi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `penugasan`
--
ALTER TABLE `penugasan`
  ADD PRIMARY KEY (`id_penugasan`),
  ADD KEY `fk_penugasan_tiket` (`id_tiket`),
  ADD KEY `fk_penugasan_teknisi` (`id_teknisi`);

--
-- Indexes for table `riwayat_status`
--
ALTER TABLE `riwayat_status`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `fk_riwayat_tiket` (`id_tiket`),
  ADD KEY `fk_riwayat_user` (`id_user`);

--
-- Indexes for table `tiket`
--
ALTER TABLE `tiket`
  ADD PRIMARY KEY (`id_tiket`),
  ADD KEY `fk_tiket_pelapor` (`id_pelapor`),
  ADD KEY `fk_tiket_kategori` (`id_kategori`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `penugasan`
--
ALTER TABLE `penugasan`
  MODIFY `id_penugasan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_status`
--
ALTER TABLE `riwayat_status`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tiket`
--
ALTER TABLE `tiket`
  MODIFY `id_tiket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `penugasan`
--
ALTER TABLE `penugasan`
  ADD CONSTRAINT `fk_penugasan_teknisi` FOREIGN KEY (`id_teknisi`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penugasan_tiket` FOREIGN KEY (`id_tiket`) REFERENCES `tiket` (`id_tiket`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `riwayat_status`
--
ALTER TABLE `riwayat_status`
  ADD CONSTRAINT `fk_riwayat_tiket` FOREIGN KEY (`id_tiket`) REFERENCES `tiket` (`id_tiket`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_riwayat_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tiket`
--
ALTER TABLE `tiket`
  ADD CONSTRAINT `fk_tiket_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiket_pelapor` FOREIGN KEY (`id_pelapor`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
