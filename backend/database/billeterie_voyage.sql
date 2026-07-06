-- Horizon-Secur — Schéma basé sur le diagramme de classes
-- Base : billeterie_voyage

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
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
