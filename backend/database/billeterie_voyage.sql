-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2026 at 11:55 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billeterie_voyage`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrateur`
--

CREATE TABLE `administrateur` (
  `Id_Admin` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_alerte`
--

CREATE TABLE `admin_alerte` (
  `Id_Admin` int NOT NULL,
  `Id_Alerte` int NOT NULL,
  `Date_consultation` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alerte_antifraude`
--

CREATE TABLE `alerte_antifraude` (
  `Id_Alerte` int NOT NULL,
  `Nom_Alerte` char(100) NOT NULL,
  `Score_Alerte` int NOT NULL,
  `motifGeo` tinyint(1) DEFAULT '0',
  `motifVitesse` tinyint(1) DEFAULT '0',
  `motifAchatFrenetique` tinyint(1) DEFAULT '0',
  `TempsDeSaisie` int DEFAULT NULL,
  `PaysCarteDetecte` varchar(50) DEFAULT NULL,
  `NbreCartesUtilises` int DEFAULT '0',
  `Statut` varchar(20) NOT NULL,
  `Id_Transaction` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paiement`
--

CREATE TABLE `paiement` (
  `Id_Transaction` int NOT NULL,
  `Nom_Banque` char(80) NOT NULL,
  `Date_transaction` date NOT NULL,
  `Nom_titulaire_carte` char(100) NOT NULL,
  `Date_exp_carte` date NOT NULL,
  `Cryptogramme` int NOT NULL,
  `Statut` varchar(20) NOT NULL,
  `Date_capture` date DEFAULT NULL,
  `id_reservation` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int NOT NULL,
  `Date_reservation` date NOT NULL,
  `Reference` varchar(50) NOT NULL,
  `Statut` varchar(20) NOT NULL,
  `Num_passport` varchar(20) NOT NULL,
  `Id_voyage` int NOT NULL,
  `id_ut` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_ut` int NOT NULL,
  `Nom` char(50) NOT NULL,
  `Prenom` char(50) NOT NULL,
  `Adresse` varchar(150) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Statut` varchar(20) NOT NULL,
  `Num_passport` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voyage`
--

CREATE TABLE `voyage` (
  `Id_reservation` int NOT NULL,
  `titre` varchar(150) DEFAULT NULL,
  `Destination` char(100) NOT NULL,
  `pays` varchar(80) DEFAULT NULL,
  `Prix` float NOT NULL,
  `capacite_max` int NOT NULL DEFAULT 50,
  `image_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voyageur`
--

CREATE TABLE `voyageur` (
  `Num_passport` varchar(20) NOT NULL,
  `Nom` char(50) NOT NULL,
  `Prenom` char(50) NOT NULL,
  `Age` int NOT NULL,
  `Sexe` char(1) DEFAULT NULL,
  `Adresse` varchar(150) DEFAULT NULL,
  `Temps_saisie` int DEFAULT NULL
) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`Id_Admin`);

--
-- Indexes for table `admin_alerte`
--
ALTER TABLE `admin_alerte`
  ADD PRIMARY KEY (`Id_Admin`,`Id_Alerte`),
  ADD KEY `fk_aa_alerte` (`Id_Alerte`);

--
-- Indexes for table `alerte_antifraude`
--
ALTER TABLE `alerte_antifraude`
  ADD PRIMARY KEY (`Id_Alerte`),
  ADD KEY `fk_alerte_paiement` (`Id_Transaction`);

--
-- Indexes for table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`Id_Transaction`),
  ADD KEY `fk_pai_reservation` (`id_reservation`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD UNIQUE KEY `Reference` (`Reference`),
  ADD KEY `fk_res_voyageur` (`Num_passport`),
  ADD KEY `fk_res_voyage` (`Id_voyage`),
  ADD KEY `fk_res_utilisateur` (`id_ut`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_ut`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `fk_ut_voyageur` (`Num_passport`);

--
-- Indexes for table `voyage`
--
ALTER TABLE `voyage`
  ADD PRIMARY KEY (`Id_reservation`);

--
-- Indexes for table `voyageur`
--
ALTER TABLE `voyageur`
  ADD PRIMARY KEY (`Num_passport`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `Id_Admin` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alerte_antifraude`
--
ALTER TABLE `alerte_antifraude`
  MODIFY `Id_Alerte` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `Id_Transaction` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_ut` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voyage`
--
ALTER TABLE `voyage`
  MODIFY `Id_reservation` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_alerte`
--
ALTER TABLE `admin_alerte`
  ADD CONSTRAINT `fk_aa_admin` FOREIGN KEY (`Id_Admin`) REFERENCES `administrateur` (`Id_Admin`),
  ADD CONSTRAINT `fk_aa_alerte` FOREIGN KEY (`Id_Alerte`) REFERENCES `alerte_antifraude` (`Id_Alerte`);

--
-- Constraints for table `alerte_antifraude`
--
ALTER TABLE `alerte_antifraude`
  ADD CONSTRAINT `fk_alerte_paiement` FOREIGN KEY (`Id_Transaction`) REFERENCES `paiement` (`Id_Transaction`);

--
-- Constraints for table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `fk_pai_reservation` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`id_reservation`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_res_utilisateur` FOREIGN KEY (`id_ut`) REFERENCES `utilisateur` (`id_ut`),
  ADD CONSTRAINT `fk_res_voyage` FOREIGN KEY (`Id_voyage`) REFERENCES `voyage` (`Id_reservation`),
  ADD CONSTRAINT `fk_res_voyageur` FOREIGN KEY (`Num_passport`) REFERENCES `voyageur` (`Num_passport`);

--
-- Constraints for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `fk_ut_voyageur` FOREIGN KEY (`Num_passport`) REFERENCES `voyageur` (`Num_passport`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
