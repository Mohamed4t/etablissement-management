-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2026 at 10:33 PM
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
-- Database: `etablissement`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `idAdmin` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`idAdmin`, `nom`, `email`, `login`, `password`) VALUES
(1, 'Meed', 'meed@gmail.com', 'meed', '$2y$10$8FnHv0VAS8IIgOf4wcT95OGz.kzAgs.i0vb8jw/j2g99hqQTOgUJq');

-- --------------------------------------------------------

--
-- Table structure for table `cours`
--

CREATE TABLE `cours` (
  `idCours` int(11) NOT NULL,
  `langue` varchar(100) DEFAULT NULL,
  `niveau` varchar(100) DEFAULT NULL,
  `prix` int(11) DEFAULT NULL,
  `dateDebut` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL,
  `placesTotal` int(11) DEFAULT NULL,
  `placesRestantes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cours`
--

INSERT INTO `cours` (`idCours`, `langue`, `niveau`, `prix`, `dateDebut`, `dateFin`, `placesTotal`, `placesRestantes`) VALUES
(1, 'Anglais', 'Débutant', 1000, '2026-09-09', '2026-10-09', 20, 16),
(2, 'Francais', 'Intermédiaire', 1200, '2026-09-09', '2026-11-09', 20, 17),
(3, 'Espagnol', 'Avancé', 1500, '2026-09-09', '2026-11-09', 20, 17),
(4, 'Anglais', 'Avancé', 1500, '2026-09-09', '2026-11-09', 20, 17);

-- --------------------------------------------------------

--
-- Table structure for table `etudiant`
--

CREATE TABLE `etudiant` (
  `idEtudiant` int(11) NOT NULL,
  `cin` varchar(50) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `login` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `etudiant`
--

INSERT INTO `etudiant` (`idEtudiant`, `cin`, `nom`, `email`, `tel`, `login`, `pass`) VALUES
(1, 'TEST001', 'Etudiant 1', 'etudiant1@test.com', '0600000001', 'etudiant1', '...'),
(2, 'TEST002', 'Etudiant 2', 'etudiant2@test.com', '0600000002', 'etudiant2', '...'),
(3, 'TEST003', 'Etudiant 3', 'etudiant3@test.com', '0600000003', 'etudiant3', '...'),
(4, 'TEST004', 'Etudiant 4', 'etudiant4@test.com', '0600000004', 'etudiant4', '...');

-- --------------------------------------------------------

--
-- Table structure for table `inscription`
--

CREATE TABLE `inscription` (
  `idInscription` int(11) NOT NULL,
  `idEtudiant` int(11) NOT NULL,
  `idCours` int(11) NOT NULL,
  `dateInscription` date NOT NULL,
  `statut` enum('en attente','confirmé','annulé') DEFAULT 'en attente',
  `paiement` enum('payé','non payé') DEFAULT 'non payé'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inscription`
--

INSERT INTO `inscription` (`idInscription`, `idEtudiant`, `idCours`, `dateInscription`, `statut`, `paiement`) VALUES
(1, 1, 1, '2026-06-11', '', 'non payé'),
(2, 1, 2, '2026-06-11', '', 'non payé'),
(3, 1, 4, '2026-06-11', 'confirmé', ''),
(4, 1, 3, '2026-06-11', 'confirmé', 'payé'),
(5, 2, 1, '2026-06-11', 'confirmé', 'payé'),
(6, 2, 2, '2026-06-11', 'confirmé', 'payé'),
(7, 2, 3, '2026-06-11', 'annulé', 'payé'),
(8, 2, 4, '2026-06-11', 'confirmé', 'payé'),
(9, 3, 1, '2026-06-15', 'confirmé', 'payé'),
(10, 3, 2, '2026-06-15', 'confirmé', 'payé'),
(11, 3, 3, '2026-06-15', 'confirmé', 'payé'),
(12, 3, 4, '2026-06-15', 'confirmé', 'payé'),
(13, 4, 1, '2026-10-07', 'confirmé', 'non payé');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idAdmin`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Indexes for table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`idCours`);

--
-- Indexes for table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`idEtudiant`);

--
-- Indexes for table `inscription`
--
ALTER TABLE `inscription`
  ADD PRIMARY KEY (`idInscription`),
  ADD KEY `idEtudiant` (`idEtudiant`),
  ADD KEY `idCours` (`idCours`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cours`
--
ALTER TABLE `cours`
  MODIFY `idCours` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `idEtudiant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inscription`
--
ALTER TABLE `inscription`
  MODIFY `idInscription` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `inscription_ibfk_1` FOREIGN KEY (`idEtudiant`) REFERENCES `etudiant` (`idEtudiant`),
  ADD CONSTRAINT `inscription_ibfk_2` FOREIGN KEY (`idCours`) REFERENCES `cours` (`idCours`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
