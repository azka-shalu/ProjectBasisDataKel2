-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Jun 2025 pada 03.32
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digikos`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar`
--

CREATE TABLE `kamar` (
  `id` int(11) NOT NULL,
  `nomor_kamar` varchar(10) NOT NULL,
  `tipe_kamar_id` int(11) NOT NULL,
  `status` enum('Kosong','Terisi') DEFAULT 'Kosong',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kamar`
--

INSERT INTO `kamar` (`id`, `nomor_kamar`, `tipe_kamar_id`, `status`, `created_at`, `updated_at`) VALUES
(1, '101', 1, 'Kosong', '2025-06-17 14:26:26', '2025-06-17 16:12:07'),
(2, '102', 1, 'Kosong', '2025-06-17 14:26:26', '2025-06-17 16:11:56'),
(3, '103', 1, 'Kosong', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(4, '201', 2, 'Terisi', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(5, '202', 2, 'Terisi', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(6, '203', 2, 'Kosong', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(7, '301', 3, 'Terisi', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(8, '302', 3, 'Terisi', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(9, '401', 4, 'Terisi', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(10, '402', 4, 'Kosong', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(13, '09', 7, 'Kosong', '2025-06-17 23:51:29', '2025-06-17 23:51:29'),
(14, '306', 7, 'Terisi', '2025-06-18 01:05:58', '2025-06-18 01:07:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontrak`
--

CREATE TABLE `kontrak` (
  `id` int(11) NOT NULL,
  `nomor_kontrak` varchar(20) NOT NULL,
  `penyewa_id` int(11) NOT NULL,
  `kamar_id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `biaya_sewa` decimal(12,2) NOT NULL,
  `status` enum('Aktif','Selesai','Dibatalkan') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kontrak`
--

INSERT INTO `kontrak` (`id`, `nomor_kontrak`, `penyewa_id`, `kamar_id`, `tanggal_mulai`, `tanggal_selesai`, `biaya_sewa`, `status`, `created_at`, `updated_at`) VALUES
(3, '001', 5, 13, '2025-06-17', '2025-06-18', 100000.00, 'Aktif', '2025-06-17 23:56:14', '2025-06-17 23:56:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `nomor_pembayaran` varchar(20) NOT NULL,
  `kontrak_id` int(11) NOT NULL,
  `jumlah` decimal(12,2) NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `nomor_pembayaran`, `kontrak_id`, `jumlah`, `tanggal_pembayaran`, `metode_pembayaran`, `keterangan`, `created_at`, `updated_at`) VALUES
(3, '1', 3, 100000.00, '2025-06-17', 'nontunai', 'lunas', '2025-06-17 23:57:05', '2025-06-17 23:57:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyewa`
--

CREATE TABLE `penyewa` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penyewa`
--

INSERT INTO `penyewa` (`id`, `nama`, `nik`, `no_hp`, `email`, `alamat`, `created_at`, `updated_at`) VALUES
(5, 'yori', '1231223123', '0812345678', 'yoritak@gmail.com', '', '2025-06-17 23:54:31', '2025-06-18 00:54:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tipe_kamar`
--

CREATE TABLE `tipe_kamar` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `fasilitas` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tipe_kamar`
--

INSERT INTO `tipe_kamar` (`id`, `nama`, `harga`, `fasilitas`, `created_at`, `updated_at`) VALUES
(1, 'Standard', 1500000.00, 'Kamar mandi dalam, AC, WiFi', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(2, 'Deluxe', 2000000.00, 'Kamar mandi dalam, AC, WiFi, TV', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(3, 'Premium', 2500000.00, 'Kamar mandi dalam, AC, WiFi, TV, Kulkas', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(4, 'Suite', 3000000.00, 'Kamar mandi dalam, AC, WiFi, TV, Kulkas, Dapur kecil', '2025-06-17 14:26:26', '2025-06-17 14:26:26'),
(7, 'Murah', 100000.00, 'Kasur,Kamar mandi luar', '2025-06-17 23:47:39', '2025-06-17 23:47:39');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_kamar` (`nomor_kamar`),
  ADD KEY `tipe_kamar_id` (`tipe_kamar_id`),
  ADD KEY `idx_kamar_status` (`status`);

--
-- Indeks untuk tabel `kontrak`
--
ALTER TABLE `kontrak`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_kontrak` (`nomor_kontrak`),
  ADD KEY `penyewa_id` (`penyewa_id`),
  ADD KEY `kamar_id` (`kamar_id`),
  ADD KEY `idx_kontrak_status` (`status`),
  ADD KEY `idx_kontrak_dates` (`tanggal_mulai`,`tanggal_selesai`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_pembayaran` (`nomor_pembayaran`),
  ADD KEY `kontrak_id` (`kontrak_id`),
  ADD KEY `idx_pembayaran_tanggal` (`tanggal_pembayaran`);

--
-- Indeks untuk tabel `penyewa`
--
ALTER TABLE `penyewa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indeks untuk tabel `tipe_kamar`
--
ALTER TABLE `tipe_kamar`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `kontrak`
--
ALTER TABLE `kontrak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `penyewa`
--
ALTER TABLE `penyewa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tipe_kamar`
--
ALTER TABLE `tipe_kamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD CONSTRAINT `kamar_ibfk_1` FOREIGN KEY (`tipe_kamar_id`) REFERENCES `tipe_kamar` (`id`);

--
-- Ketidakleluasaan untuk tabel `kontrak`
--
ALTER TABLE `kontrak`
  ADD CONSTRAINT `kontrak_ibfk_1` FOREIGN KEY (`penyewa_id`) REFERENCES `penyewa` (`id`),
  ADD CONSTRAINT `kontrak_ibfk_2` FOREIGN KEY (`kamar_id`) REFERENCES `kamar` (`id`);

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`kontrak_id`) REFERENCES `kontrak` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
