-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 31, 2026 at 01:55 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `workshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int NOT NULL,
  `judul` varchar(150) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `tahun_terbit` smallint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `judul`, `penulis`, `tahun_terbit`, `created_at`) VALUES
(2, 'Sistem Informasi Akademik', 'Rina Kurniawati', 2026, '2026-10-24 12:58:38'),
(3, 'Algoritma dan Pemrograman', 'Dewi Lestari', 2023, '2026-10-24 12:58:54'),
(4, 'Pengembangan Web', 'Andi Pratama', 2024, '2026-10-24 12:59:07'),
(5, 'Dasar-Dasar Basis Data', 'Siti Aminah', 2025, '2026-10-24 12:59:17');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int NOT NULL,
  `nim` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `prodi` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `email`, `prodi`, `created_at`) VALUES
(3, '25001', 'Andi Pratama', 'andi@gmail.com\r\n', 'Teknik Informatika', '2026-10-24 13:21:56'),
(4, '25002', 'Siti Aminah', 'aminah@gmail.com', 'Sistem Informasi', '2026-10-24 13:22:08'),
(5, '25003', 'Budi Santoso', 'budi@gmail.com', 'Teknik Informatika', '2026-10-24 13:22:18'),
(6, '122345678', 'Dewi Lestari', 'dewi@gmail.com', 'Manajemen Informatika', '2026-10-24 13:22:28'),
(7, '123456789', 'Azira Mutia Gani', 'azira@gmail.com', 'Teknik Informatika', '2026-10-25 12:23:12'),
(8, '987654321', 'Azira Mutia Gani', NULL, 'Teknik Informatika', '2026-10-25 19:17:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'uji_email', 'uji@mail.com', '$2y$10$DarXmJ77EoLqDz1iuO9b4u57msvk7JimkiG2eKOgv7LChH54mvCqW', 'user', '2026-10-25 16:10:08'),
(2, 'Azira Mutia Gani', 'azira@gmail.com', '$2y$10$9XGPGSzpoft0/jMRjoRjM.FTamOHZPx9dRXevEpW4/azY7DHyP.D2', 'user', '2026-10-25 16:34:28'),
(3, 'Ariana Grande', 'ariana@gmail.com', '$2y$10$ETjxfliWglCFkhEhqPAkru3kgVHHyyyiVxOxyP3zOzlx/sQ3tcjp2', 'user', '2026-10-25 18:44:04'),
(4, 'Billie elish', 'billie@gmail.com', '$2y$10$CWkLQfA2M8IjsDf6GXDiLu9ur2d5B4RNV9YFNkA8vIgUos9OkDnau', 'user', '2026-10-25 18:54:40'),
(5, 'anya', 'anya@gmail.com', '$2y$10$4BFXYq8AT5qRgdzPTg9iceYQlF5kBdoiHgZcTdT7BSB118r2wtxSS', 'user', '2026-10-26 01:08:33'),
(6, 'andi', 'andi@gmail.com', '$2y$10$hsMfWraBaRKVPtn3GcUEUe1PmSSP7jmlsGqWFvM.DU1RvK.gxVDkW', 'user', '2026-10-30 05:58:19'),
(7, 'Rasta', 'rasta@gmail.com', '$2y$10$rSnCXKGb2Rb.DEUKV2VfEuKdV2IDv1LhjCkRXF8ak5DDZ6zpT78iq', 'user', '2026-10-31 11:55:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
