<<<<<<< HEAD
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 03 juil. 2026 à 10:46
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18
=======
-- Horizon-Secur — Schéma basé sur le diagramme de classes
-- Base : billeterie_voyage
>>>>>>> 5c37ab07e471b69292aaca1968c1069ba652acdd

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
<<<<<<< HEAD


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `billeterie_voyage`
--

-- --------------------------------------------------------

--
-- Structure de la table `agent_interne`
--

DROP TABLE IF EXISTS `agent_interne`;
CREATE TABLE IF NOT EXISTS `agent_interne` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `date_autorisation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_agent_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agent_interne`
--

INSERT INTO `agent_interne` (`id`, `nom`, `prenom`, `email`, `password`, `statut`, `date_autorisation`) VALUES
(1, 'Fokam', 'Milly', 'admin@horizon-secur.fr', '$2y$10$v5NOJY3TjvgabNmQAFRBOuPeq33zU/K9WILNkELM/cUwSfhxM.2ES', 'actif', '2026-07-03 10:43:39'),
(2, 'Martin', 'Sophie', 'agent@horizon-secur.fr', '$2y$10$v5NOJY3TjvgabNmQAFRBOuPeq33zU/K9WILNkELM/cUwSfhxM.2ES', 'actif', '2026-07-03 10:43:39');

-- --------------------------------------------------------

--
-- Structure de la table `alerte_fraude`
--

DROP TABLE IF EXISTS `alerte_fraude`;
CREATE TABLE IF NOT EXISTS `alerte_fraude` (
  `id` int NOT NULL AUTO_INCREMENT,
  `score_suspicion` int NOT NULL DEFAULT '0',
  `motif_geo` tinyint(1) NOT NULL DEFAULT '0',
  `motif_vitesse` tinyint(1) NOT NULL DEFAULT '0',
  `motif_achat_frenetique` tinyint(1) NOT NULL DEFAULT '0',
  `pays_carte_detecte` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temps_de_saisie` int DEFAULT NULL,
  `nombre_cartes_utilise` int NOT NULL DEFAULT '0',
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ouverte',
  `date_detection` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_paiement` int NOT NULL,
  `id_agent_interne` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_alerte_paiement` (`id_paiement`),
  KEY `fk_alerte_agent` (`id_agent_interne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pays_emission_carte` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_autorisation` date DEFAULT NULL,
  `date_capture` date DEFAULT NULL,
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `montant` decimal(10,2) NOT NULL,
  `methode_paiement` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_transaction` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_reservation` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_paiement_reservation` (`id_reservation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reference` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `date_reservation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_utilisateur` int NOT NULL,
  `id_voyage` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_reservation_reference` (`reference`),
  KEY `fk_reservation_utilisateur` (`id_utilisateur`),
  KEY `fk_reservation_voyage` (`id_voyage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservation_voyageur`
--

DROP TABLE IF EXISTS `reservation_voyageur`;
CREATE TABLE IF NOT EXISTS `reservation_voyageur` (
  `id_reservation` int NOT NULL,
  `id_voyageur` int NOT NULL,
  PRIMARY KEY (`id_reservation`,`id_voyageur`),
  KEY `fk_rv_voyageur` (`id_voyageur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `date_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_utilisateur_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `voyage`
--

DROP TABLE IF EXISTS `voyage`;
CREATE TABLE IF NOT EXISTS `voyage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `destination` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_depart` date NOT NULL,
  `prix_par_personne` decimal(10,2) NOT NULL,
  `capacite_max` int NOT NULL DEFAULT '50',
  `titre` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_voyage_recherche` (`destination`,`date_depart`,`prix_par_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `voyage`
--

INSERT INTO `voyage` (`id`, `destination`, `date_depart`, `prix_par_personne`, `capacite_max`, `titre`, `pays`, `description`, `image_url`) VALUES
(1, 'Nairobi', '2026-08-15', 1299.00, 20, 'Safari au Kenya', 'Kenya', 'Decouverte des savanes et de la faune africaine.', '/images/kenya.jpg'),
(2, 'Denpasar', '2026-09-01', 899.00, 30, 'Escapade a Bali', 'Indonesie', 'Plages paradisiaques et temples.', '/images/bali.jpg'),
(3, 'Lisbonne', '2026-07-20', 450.00, 40, 'City break a Lisbonne', 'Portugal', 'Week-end culturel et gastronomie portugaise.', '/images/lisbonne.jpg'),
(4, 'Reykjavik', '2026-10-05', 1599.00, 15, 'Aventure en Islande', 'Islande', 'Aurores boreales et geysers.', '/images/islande.jpg'),
(5, 'Male', '2026-11-12', 2200.00, 10, 'Detente aux Maldives', 'Maldives', 'Sejour tout inclus sur lagon turquoise.', '/images/maldives.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `voyageur`
--

DROP TABLE IF EXISTS `voyageur`;
CREATE TABLE IF NOT EXISTS `voyageur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num_passport` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int NOT NULL,
  `sexe` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temps_saisie` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_voyageur_passport` (`num_passport`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `alerte_fraude`
--
ALTER TABLE `alerte_fraude`
  ADD CONSTRAINT `fk_alerte_agent` FOREIGN KEY (`id_agent_interne`) REFERENCES `agent_interne` (`id`),
  ADD CONSTRAINT `fk_alerte_paiement` FOREIGN KEY (`id_paiement`) REFERENCES `paiement` (`id`);

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `fk_paiement_reservation` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`id`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_reservation_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `fk_reservation_voyage` FOREIGN KEY (`id_voyage`) REFERENCES `voyage` (`id`);

--
-- Contraintes pour la table `reservation_voyageur`
--
ALTER TABLE `reservation_voyageur`
  ADD CONSTRAINT `fk_rv_reservation` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rv_voyageur` FOREIGN KEY (`id_voyageur`) REFERENCES `voyageur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
=======
SET NAMES utf8mb4;

-- --------------------------------------------------------
-- agent_interne (administrateur)
-- Diagramme : id
-- Champs ajoutés : identité, authentification
-- --------------------------------------------------------
CREATE TABLE `agent_interne` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `statut` varchar(20) NOT NULL DEFAULT 'actif',
  `date_autorisation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_agent_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- utilisateur
-- Diagramme : id, statut
-- Champs ajoutés : identité, contact, authentification
-- --------------------------------------------------------
CREATE TABLE `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `adresse` varchar(150) DEFAULT NULL,
  `statut` varchar(20) NOT NULL DEFAULT 'actif',
  `date_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_utilisateur_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- voyage
-- Diagramme : id, destination, date_depart, prix_par_personne
-- Champs ajoutés : capacité, présentation (M1)
-- --------------------------------------------------------
CREATE TABLE `voyage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `destination` varchar(100) NOT NULL,
  `date_depart` date NOT NULL,
  `prix_par_personne` decimal(10,2) NOT NULL,
  `capacite_max` int NOT NULL DEFAULT 50,
  `titre` varchar(150) DEFAULT NULL,
  `pays` varchar(80) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_voyage_recherche` (`destination`, `date_depart`, `prix_par_personne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- voyageur
-- Diagramme : id, num_passport, temps_saisie
-- Champs ajoutés : identité du passager
-- --------------------------------------------------------
CREATE TABLE `voyageur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num_passport` varchar(20) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `age` int NOT NULL,
  `sexe` char(1) DEFAULT NULL,
  `adresse` varchar(150) DEFAULT NULL,
  `temps_saisie` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_voyageur_passport` (`num_passport`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- reservation
-- Diagramme : id, reference, statut
-- Relation : utilisateur 1..1 — 0..*, voyage 1..1 — 0..*
-- Champs ajoutés : date de réservation
-- --------------------------------------------------------
CREATE TABLE `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reference` varchar(50) NOT NULL,
  `statut` varchar(20) NOT NULL DEFAULT 'en_attente',
  `date_reservation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_utilisateur` int NOT NULL,
  `id_voyage` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_reservation_reference` (`reference`),
  KEY `fk_reservation_utilisateur` (`id_utilisateur`),
  KEY `fk_reservation_voyage` (`id_voyage`),
  CONSTRAINT `fk_reservation_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`),
  CONSTRAINT `fk_reservation_voyage` FOREIGN KEY (`id_voyage`) REFERENCES `voyage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- reservation_voyageur
-- Relation : reservation 1..1 — 1..* voyageur
-- --------------------------------------------------------
CREATE TABLE `reservation_voyageur` (
  `id_reservation` int NOT NULL,
  `id_voyageur` int NOT NULL,
  PRIMARY KEY (`id_reservation`, `id_voyageur`),
  KEY `fk_rv_voyageur` (`id_voyageur`),
  CONSTRAINT `fk_rv_reservation` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rv_voyageur` FOREIGN KEY (`id_voyageur`) REFERENCES `voyageur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- paiement
-- Diagramme : id, pays_emission_carte, date_autorisation, date_capture, statut
-- Relation : reservation 1..1 — 1..*
-- Champs ajoutés : montant, méthode, date transaction
-- --------------------------------------------------------
CREATE TABLE `paiement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pays_emission_carte` varchar(50) NOT NULL,
  `date_autorisation` date DEFAULT NULL,
  `date_capture` date DEFAULT NULL,
  `statut` varchar(20) NOT NULL DEFAULT 'en_attente',
  `montant` decimal(10,2) NOT NULL,
  `methode_paiement` varchar(50) DEFAULT NULL,
  `date_transaction` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_reservation` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_paiement_reservation` (`id_reservation`),
  CONSTRAINT `fk_paiement_reservation` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- alerte_fraude
-- Diagramme : id, score_suspicion, motif_geo, motif_vitesse,
--             motif_achat_frenetique, pays_carte_detecte,
--             temps_de_saisie, nombre_cartes_utilise, statut
-- Relations : paiement 1..1 — 0..1, agent_interne 1..1 — 0..*
-- Champs ajoutés : date_detection
-- --------------------------------------------------------
CREATE TABLE `alerte_fraude` (
  `id` int NOT NULL AUTO_INCREMENT,
  `score_suspicion` int NOT NULL DEFAULT 0,
  `motif_geo` tinyint(1) NOT NULL DEFAULT 0,
  `motif_vitesse` tinyint(1) NOT NULL DEFAULT 0,
  `motif_achat_frenetique` tinyint(1) NOT NULL DEFAULT 0,
  `pays_carte_detecte` varchar(50) DEFAULT NULL,
  `temps_de_saisie` int DEFAULT NULL,
  `nombre_cartes_utilise` int NOT NULL DEFAULT 0,
  `statut` varchar(20) NOT NULL DEFAULT 'ouverte',
  `date_detection` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_paiement` int NOT NULL,
  `id_agent_interne` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_alerte_paiement` (`id_paiement`),
  KEY `fk_alerte_agent` (`id_agent_interne`),
  CONSTRAINT `fk_alerte_paiement` FOREIGN KEY (`id_paiement`) REFERENCES `paiement` (`id`),
  CONSTRAINT `fk_alerte_agent` FOREIGN KEY (`id_agent_interne`) REFERENCES `agent_interne` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
>>>>>>> 5c37ab07e471b69292aaca1968c1069ba652acdd
