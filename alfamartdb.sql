-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2016 at 06:59 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alfamartdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cabangtbl`
--

CREATE TABLE `cabangtbl` (
  `id_cabang` varchar(4) NOT NULL,
  `nama_cabang` varchar(30) NOT NULL,
  `alamat_cabang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cabangtbl`
--

INSERT INTO `cabangtbl` (`id_cabang`, `nama_cabang`, `alamat_cabang`) VALUES
('1AZ1', 'DC. SAT PEKANBARU', '   Jln. MH Thamrin No. 9 Pekanbaru'),
('1DZ1', ' DC. SAT JAMBI', 'Jln. MH Thamrin No. 9 Jambi'),
('1GZ1', 'DC. SAT BANJARMASIN', '  Jln. MH Thamrin No. 9 Banjarmasin'),
('1MZ1', '  DC. SAT PARUNG', '       Jln. MH Thamrin No. 9 Parung'),
('1PZ1', 'DC. SAT PONTIANAK', 'Jln. MH Thamrin No. 9 Pontianak'),
('1VZ1', '   DC. SAT KOTABUMI', 'Jln. MH Thamrin No. 9 Kotabumi'),
('2AZ1', '   DC. SAT REMBANG', '        Jln. MH Thamrin No. 9 Rembang'),
('BZ01', '   DC. SAT BANDUNG', 'Jln. MH Thamrin No. 9 Bandung'),
('CZ01', 'DC. SAT BEKASI', '        Jln. MH Thamrin No. 9 Bekasi'),
('EZ01', '   DC. SAT CILEUNGSI', '        Jln. MH Thamrin No. 9 Cileungsi'),
('HZ01', '  DC. SAT PARUNG', '       Jln. MH Thamrin No. 9 Parung'),
('IZ01', '  DC. SAT PARUNG', '       Jln. MH Thamrin No. 9 Parung'),
('JZ01', '  DC. SAT PARUNG', '       Jln. MH Thamrin No. 9 Parung'),
('KZ01', '  DC. SAT PARUNG', '       Jln. MH Thamrin No. 9 Parung'),
('LZ01', '  DC. SAT PARUNG', '       Jln. MH Thamrin No. 9 Parung'),
('NZ01', '  DC. SAT PARUNG', '       Jln. MH Thamrin No. 9 Parung');

-- --------------------------------------------------------

--
-- Table structure for table `indikatortbl`
--

CREATE TABLE `indikatortbl` (
  `id_indikator` int(2) NOT NULL,
  `indikator` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `indikatortbl`
--

INSERT INTO `indikatortbl` (`id_indikator`, `indikator`) VALUES
(4, '  Koneksi jaringan lancar, tidak sering mengalami gangguan'),
(5, 'Hardware yang dipasang oleh provider baik, tidak sering mengalami kerusakan.'),
(6, 'Provider menyikapi keluhan service dengan tepat.'),
(7, 'Provider mudah dihubingi.'),
(8, 'Provider menyelesaikan malasah dengan tepat dan dalam waktu yang dijanjikan.'),
(9, 'Kendala yang telah diselesaikan tidak muncul kembali dalam waktu yang telah disepakati.'),
(10, 'Provider memberikan tanggapan dengan cepat terhadap keluhan yang disampaikan.');

-- --------------------------------------------------------

--
-- Table structure for table `komentartbl`
--

CREATE TABLE `komentartbl` (
  `id_komentar` int(2) NOT NULL,
  `id_toko` varchar(4) NOT NULL,
  `komentar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mappingprovidertbl`
--

CREATE TABLE `mappingprovidertbl` (
  `id_map` int(2) NOT NULL,
  `id_toko` varchar(4) NOT NULL,
  `id_provider` int(2) NOT NULL,
  `status_berlangganan` varchar(10) NOT NULL,
  `berlangganan` date NOT NULL,
  `berakhir` date NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mappingprovidertbl`
--

INSERT INTO `mappingprovidertbl` (`id_map`, `id_toko`, `id_provider`, `status_berlangganan`, `berlangganan`, `berakhir`, `keterangan`) VALUES
(1, 'k3hz', 2, 'aktif', '2016-06-16', '2016-06-30', NULL),
(2, '123e', 33, 'Aktif', '2016-12-01', '2017-02-18', NULL),
(3, 'T361', 31, 'Aktif', '2016-11-15', '2018-11-15', NULL),
(4, 'T367', 31, 'Aktif', '2016-12-15', '2017-12-15', NULL),
(5, 'T428', 31, 'Aktif', '2016-10-15', '2018-10-15', NULL),
(6, 'T496', 31, 'Aktif', '2016-06-15', '2018-06-15', NULL),
(7, 'T544', 31, 'Aktif', '2016-04-15', '2018-04-15', NULL),
(8, 'A060', 32, 'Aktif', '2016-12-15', '2017-12-15', NULL),
(9, 'A073', 32, 'Aktif', '2016-08-15', '2017-08-15', NULL),
(10, 'A077', 32, 'Aktif', '2016-01-15', '2017-01-15', NULL),
(11, 'A084', 32, 'Aktif', '2017-01-15', '2018-01-15', NULL),
(12, 'C511', 32, 'Aktif', '2016-09-15', '2018-09-15', NULL),
(13, 'C521', 32, 'Aktif', '2016-07-15', '2017-07-15', NULL),
(14, 'qw23', 33, 'Aktif', '2016-12-15', '2016-12-15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mappingtbl`
--

CREATE TABLE `mappingtbl` (
  `id_mapping` int(4) NOT NULL,
  `cabang` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `penilaiantbl`
--

CREATE TABLE `penilaiantbl` (
  `id_penilaian` int(10) NOT NULL,
  `id_provider` varchar(4) NOT NULL,
  `id_indikator` int(2) NOT NULL,
  `nilai` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penilaiantbl`
--

INSERT INTO `penilaiantbl` (`id_penilaian`, `id_provider`, `id_indikator`, `nilai`) VALUES
(14, '1', 1, 4),
(15, '1', 2, 4),
(16, '8', 1, 2),
(17, '8', 2, 3),
(18, '1', 1, 1),
(19, '1', 2, 0),
(20, '31', 1, 4),
(21, '31', 2, 2),
(22, '31', 3, 4),
(23, '33', 4, 4),
(24, '33', 5, 5),
(25, '33', 6, 4),
(26, '33', 7, 3),
(27, '33', 8, 4),
(28, '33', 9, 3),
(29, '33', 10, 3);

-- --------------------------------------------------------

--
-- Table structure for table `prov-cabangtbl`
--

CREATE TABLE `prov-cabangtbl` (
  `id_prov-cabang` int(2) NOT NULL,
  `id_cabang` varchar(4) NOT NULL,
  `id_provider` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `providertbl`
--

CREATE TABLE `providertbl` (
  `id_provider` int(2) NOT NULL,
  `nama_provider` varchar(30) NOT NULL,
  `periode_kontrak` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `providertbl`
--

INSERT INTO `providertbl` (`id_provider`, `nama_provider`, `periode_kontrak`) VALUES
(31, ' BIZNET', '2 Tahun'),
(32, ' ICON PLUS+', 'Bebas Kontrak'),
(33, 'INDOSAT BROADBAND', '2 Tahun'),
(34, 'INDOSAT MPLS', '3 Tahun'),
(35, 'SMARTFREN', '1 Tahun'),
(36, 'SPEEDY', '1 Tahun'),
(37, 'TELKOMSEL', '3 Tahun'),
(38, 'VPN LITE', 'Bebas Kontrak'),
(39, 'XL BROADBAND', '2 Tahun');

-- --------------------------------------------------------

--
-- Table structure for table `tagihantbl`
--

CREATE TABLE `tagihantbl` (
  `id_tagihan` int(3) NOT NULL,
  `id_toko` varchar(4) NOT NULL,
  `id_provider` int(2) NOT NULL,
  `invoice_number` varchar(10) NOT NULL,
  `bandwith` int(10) NOT NULL,
  `jumlah_tagihan` varchar(10) NOT NULL,
  `periode_tagihan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tagihantbl`
--

INSERT INTO `tagihantbl` (`id_tagihan`, `id_toko`, `id_provider`, `invoice_number`, `bandwith`, `jumlah_tagihan`, `periode_tagihan`) VALUES
(8, '123e', 33, '123456789', 6, '600000', '2016-12-10'),
(9, 'T361', 31, '1245232', 10, '500000', '2016-12-15'),
(10, 'T428', 31, '1123d', 5, '500000', '2016-12-29');

-- --------------------------------------------------------

--
-- Table structure for table `tokotbl`
--

CREATE TABLE `tokotbl` (
  `id_no` int(5) NOT NULL,
  `id_toko` varchar(4) NOT NULL,
  `nama_toko` varchar(30) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `id_cabang` varchar(4) NOT NULL,
  `status_toko` varchar(10) NOT NULL,
  `buka_toko` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tokotbl`
--

INSERT INTO `tokotbl` (`id_no`, `id_toko`, `nama_toko`, `alamat`, `id_cabang`, `status_toko`, `buka_toko`) VALUES
(9, 'T361', 'Teluk Naga', 'Jln. MH Thamrin No. 9 Teluk Naga', '1DZ1', 'Buka', '2016-01-01'),
(10, 'T367', 'Binong 3', 'Jln. MH Thamrin No. 9 Binong 3', '1AZ1', 'Buka', '2015-11-01'),
(11, 'T428', 'Raya Pandeglang', 'Jln. MH Thamrin No. 9 Pandeglang', '1AZ1', 'Buka', '2016-04-01'),
(12, 'T496', 'Pasar Mancak', 'Jln. MH Thamrin No. 9 Pasar Mancak', '1AZ1', 'Buka', '2015-09-01'),
(13, 'T544', 'Cibaliung', 'Jln. MH Thamrin No. 9 Cibaliung', '1AZ1', 'Buka', '2016-02-01'),
(14, 'A060', 'Griya Hijau Raya', 'Jln. MH Thamrin No. 9 Griya Hijau Raya', '1MZ1', 'Buka', '2016-06-01'),
(15, 'A073', 'Rempoa', 'Jln. MH Thamrin No. 9 Rempoa', '1MZ1', 'Buka', '2016-05-01'),
(16, 'A077', 'WR Supratman', 'Jln. MH Thamrin WR Supratman', '1MZ1', 'Buka', '2015-12-01'),
(17, 'A084', 'KH. Dewantara', 'Jln. MH Thamrin No. 9 KH Dewantara', '1MZ1', 'Buka', '2016-10-01'),
(18, 'C511', 'Permata Bekasi Regency', 'Jln. MH Thamrin No. 9 Permata Bekasi', 'CZ01', 'Buka', '2016-11-01'),
(19, 'C521', 'Pasir Gombong', 'Jln. MH Thamrin No. 9 Pasri Gombong', 'CZ01', 'Buka', '2016-06-01'),
(20, 'qw23', 'Alfamart', 'Jalan Gebang', '1GZ1', 'Buka', '2016-12-17');

-- --------------------------------------------------------

--
-- Table structure for table `usertbl`
--

CREATE TABLE `usertbl` (
  `id_pegawai` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `password` varchar(10) NOT NULL,
  `user_level` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usertbl`
--

INSERT INTO `usertbl` (`id_pegawai`, `nama`, `password`, `user_level`) VALUES
('1157293621', 'Nur Hikmah', 'hikmah', 'cabang'),
('1231111223', 'pUTRI', 'PPLL', 'ho'),
('145637892', 'Widya Anchara', 'anchara', 'ho'),
('5212100016', 'putri', 'putri', 'toko');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cabangtbl`
--
ALTER TABLE `cabangtbl`
  ADD PRIMARY KEY (`id_cabang`);

--
-- Indexes for table `indikatortbl`
--
ALTER TABLE `indikatortbl`
  ADD PRIMARY KEY (`id_indikator`);

--
-- Indexes for table `komentartbl`
--
ALTER TABLE `komentartbl`
  ADD PRIMARY KEY (`id_komentar`);

--
-- Indexes for table `mappingprovidertbl`
--
ALTER TABLE `mappingprovidertbl`
  ADD PRIMARY KEY (`id_map`);

--
-- Indexes for table `penilaiantbl`
--
ALTER TABLE `penilaiantbl`
  ADD PRIMARY KEY (`id_penilaian`);

--
-- Indexes for table `prov-cabangtbl`
--
ALTER TABLE `prov-cabangtbl`
  ADD PRIMARY KEY (`id_prov-cabang`);

--
-- Indexes for table `providertbl`
--
ALTER TABLE `providertbl`
  ADD PRIMARY KEY (`id_provider`);

--
-- Indexes for table `tagihantbl`
--
ALTER TABLE `tagihantbl`
  ADD PRIMARY KEY (`id_tagihan`);

--
-- Indexes for table `tokotbl`
--
ALTER TABLE `tokotbl`
  ADD PRIMARY KEY (`id_no`);

--
-- Indexes for table `usertbl`
--
ALTER TABLE `usertbl`
  ADD PRIMARY KEY (`id_pegawai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `indikatortbl`
--
ALTER TABLE `indikatortbl`
  MODIFY `id_indikator` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `komentartbl`
--
ALTER TABLE `komentartbl`
  MODIFY `id_komentar` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mappingprovidertbl`
--
ALTER TABLE `mappingprovidertbl`
  MODIFY `id_map` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `penilaiantbl`
--
ALTER TABLE `penilaiantbl`
  MODIFY `id_penilaian` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `prov-cabangtbl`
--
ALTER TABLE `prov-cabangtbl`
  MODIFY `id_prov-cabang` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `providertbl`
--
ALTER TABLE `providertbl`
  MODIFY `id_provider` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `tagihantbl`
--
ALTER TABLE `tagihantbl`
  MODIFY `id_tagihan` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tokotbl`
--
ALTER TABLE `tokotbl`
  MODIFY `id_no` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
