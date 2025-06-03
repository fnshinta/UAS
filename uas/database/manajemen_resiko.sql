-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jun 2025 pada 05.06
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manajemen_resiko`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `mitigations`
--

CREATE TABLE `mitigations` (
  `id` int(11) NOT NULL,
  `risk_code` varchar(10) DEFAULT NULL,
  `inherent_likehood` tinyint(4) DEFAULT NULL,
  `inherent_impact` tinyint(4) DEFAULT NULL,
  `inherent_risk_level` int(11) DEFAULT NULL,
  `existing_control` enum('Yes','No') DEFAULT NULL,
  `control_quality` enum('Sufficient','Not Sufficient') DEFAULT NULL,
  `execution_status` enum('On Progress','Pending','Completed') DEFAULT NULL,
  `residual_likehood` tinyint(4) DEFAULT NULL,
  `residual_impact` tinyint(4) DEFAULT NULL,
  `residual_risk_level` int(11) DEFAULT NULL,
  `risk_treatment` enum('Accept','Share','Reduce','avoid') DEFAULT NULL,
  `mitigation_plan` text DEFAULT NULL,
  `after_mitigation_likehood` tinyint(4) DEFAULT NULL,
  `after_mitigation_impact` tinyint(4) DEFAULT NULL,
  `after_mitigation_risk_level` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mitigations`
--

INSERT INTO `mitigations` (`id`, `risk_code`, `inherent_likehood`, `inherent_impact`, `inherent_risk_level`, `existing_control`, `control_quality`, `execution_status`, `residual_likehood`, `residual_impact`, `residual_risk_level`, `risk_treatment`, `mitigation_plan`, `after_mitigation_likehood`, `after_mitigation_impact`, `after_mitigation_risk_level`, `staff_id`, `is_completed`) VALUES
(6, 'R2', 2, 1, 2, 'Yes', 'Sufficient', 'On Progress', 2, 1, 2, 'Accept', 'set', 4, 4, 16, 6, 0),
(7, 'R3', 1, 1, 1, 'No', 'Sufficient', 'On Progress', 5, 5, 25, 'Accept', 'a', 1, 1, 1, 6, 0),
(8, 'R4', 5, 3, 15, 'Yes', 'Not Sufficient', 'On Progress', 1, 3, 3, 'Reduce', 'b', 5, 5, 25, 1, 1),
(9, 'R5', 1, 1, 1, 'Yes', 'Not Sufficient', 'On Progress', 1, 1, 1, 'Accept', 'w', 1, 1, 1, 1, 1),
(10, 'R8', 1, 1, 1, 'Yes', 'Sufficient', 'On Progress', 1, 1, 1, 'Accept', 'tak tau', 1, 1, 1, 6, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `monitoring`
--

CREATE TABLE `monitoring` (
  `id` int(11) NOT NULL,
  `risk_code` varchar(255) DEFAULT NULL,
  `risk_event` text NOT NULL,
  `mitigation_plan` text NOT NULL,
  `month` varchar(10) DEFAULT NULL,
  `status` enum('rencana','pelaksanaan') DEFAULT NULL,
  `evidence` text DEFAULT NULL,
  `pic` text DEFAULT NULL,
  `month_status` text DEFAULT NULL,
  `likelihood` int(11) NOT NULL DEFAULT 1,
  `impact` int(11) NOT NULL DEFAULT 1,
  `staff_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `monitoring`
--

INSERT INTO `monitoring` (`id`, `risk_code`, `risk_event`, `mitigation_plan`, `month`, `status`, `evidence`, `pic`, `month_status`, `likelihood`, `impact`, `staff_id`) VALUES
(4, 'R2', 'set', '', NULL, NULL, 'set', 'set', '{\"Jan\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Feb\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Mar\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Apr\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"May\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Jun\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Jul\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Aug\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Sep\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Oct\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Nov\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Dec\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"}}', 1, 1, 6),
(5, 'R3', 'a', '', NULL, NULL, 'a', 'a', '{\"Jan\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Feb\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Mar\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Apr\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"May\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Jun\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Jul\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Aug\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Sep\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Oct\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Nov\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Dec\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"}}', 1, 1, 6),
(6, 'R4', 'b', '', NULL, NULL, 'b', 'b', '{\"Jan\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Feb\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Mar\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Apr\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"May\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Jun\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Jul\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Aug\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Sep\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Oct\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Nov\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Dec\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"}}', 1, 1, 1),
(7, 'R5', 'w', '', NULL, NULL, 'w', 'w', '{\"Jan\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Feb\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Mar\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Apr\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"May\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Jun\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Jul\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Aug\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Sep\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Oct\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Nov\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Dec\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"}}', 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `risks`
--

CREATE TABLE `risks` (
  `id` int(11) NOT NULL,
  `risk_code` varchar(20) NOT NULL,
  `objective` text DEFAULT NULL,
  `process_business` enum('Akademik','Finansial','Kepegawaian') DEFAULT NULL,
  `risk_event` text DEFAULT NULL,
  `risk_category` enum('Strategic','Financial','Operational') DEFAULT NULL,
  `risk_cause` text DEFAULT NULL,
  `risk_source` enum('Internal','External') DEFAULT NULL,
  `qualitative` text DEFAULT NULL,
  `quantitative` varchar(50) DEFAULT NULL,
  `risk_owner` text DEFAULT NULL,
  `department` text DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `risks`
--

INSERT INTO `risks` (`id`, `risk_code`, `objective`, `process_business`, `risk_event`, `risk_category`, `risk_cause`, `risk_source`, `qualitative`, `quantitative`, `risk_owner`, `department`, `staff_id`, `created_by`) VALUES
(9, 'R2', 'tes', 'Akademik', 'set', 'Strategic', 'set', 'Internal', 'set', '10000000000', 'set', 'set', 6, 1),
(10, 'R3', 'aa', 'Akademik', 'a', 'Strategic', 'a', 'Internal', 'a', '10000000000', 'a', 'a', 6, 1),
(11, 'R4', 'b', 'Akademik', 'b', 'Strategic', 'b', 'External', 'b', '10000000000', 'b', 'b', 1, 2),
(12, 'R5', 'w', 'Akademik', 'w', 'Strategic', 'w', 'Internal', 'w', '12345', 'w', 'w', 1, 2),
(14, 'R6', 'tes', 'Akademik', 'tes', 'Strategic', 'tes', 'Internal', 'tes', '12900', 'tes', 'tes', 6, 1),
(15, 'R7', 'a', 'Finansial', 'a', 'Strategic', 'a', 'Internal', 'a', '1321442', 'a', 'a', 6, 1),
(17, 'R8', 'a', 'Akademik', 'as', 'Strategic', 'a', 'Internal', 'a', '1212121', 'a', 'aaaad', 6, 1),
(18, 'R9', 'a', 'Finansial', 'a', 'Strategic', 'a', 'External', 'a', '12424', 'a', 'as', 6, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `staffs`
--

CREATE TABLE `staffs` (
  `id` int(11) NOT NULL,
  `staff_name` varchar(100) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `staffs`
--

INSERT INTO `staffs` (`id`, `staff_name`, `nama_lengkap`) VALUES
(1, 'Staff IT', 'IT'),
(2, 'Staff Manajemen', 'Manajemen'),
(3, 'Staff  Pengajar', 'Pengajar'),
(4, 'Staff Administrasi', 'Administrasi'),
(5, 'Staff Konten', 'Konten'),
(6, 'Admin', 'Admin RM');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','sub-admin','user') NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `total_logins` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `staff_id`, `last_login`, `total_logins`) VALUES
(1, 'admin', '$2y$10$a15VmhmaBbWqlHXpf9vcVOxPGmDtWFRK79ie0B2SCLpi3JaWNmy/.', 'admin', 6, '2025-06-03 10:05:28', 23),
(2, 'staff.it@gmail.com', '$2y$10$nf/KlYGr9R7g5QFkYh5Ukeoe9FvYRgpWWDInpdrEFWyrBDdYjc/0u', 'sub-admin', 1, '2025-06-02 18:01:40', 8),
(3, 'magang.it@gmail.com', '$2y$10$QjZ0mAcjqZapeK9livSnTuuGcDVQZfCYoyWzW/AwZjhZG3ArWXl.K', 'user', 1, '2025-06-02 14:52:50', 8);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `mitigations`
--
ALTER TABLE `mitigations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `risk_code` (`risk_code`);

--
-- Indeks untuk tabel `monitoring`
--
ALTER TABLE `monitoring`
  ADD PRIMARY KEY (`id`),
  ADD KEY `st_risk_code_monitoring` (`risk_code`);

--
-- Indeks untuk tabel `risks`
--
ALTER TABLE `risks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `risk_code` (`risk_code`),
  ADD KEY `st_staff_id` (`staff_id`),
  ADD KEY `st_created_by` (`created_by`);

--
-- Indeks untuk tabel `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `mitigations`
--
ALTER TABLE `mitigations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `monitoring`
--
ALTER TABLE `monitoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `risks`
--
ALTER TABLE `risks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `staffs`
--
ALTER TABLE `staffs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
