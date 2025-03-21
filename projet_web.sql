-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 21 mars 2025 à 08:19
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet_web`
--

-- --------------------------------------------------------

--
-- Structure de la table `ajouter_à_la_wishlist`
--

DROP TABLE IF EXISTS `ajouter_à_la_wishlist`;
CREATE TABLE IF NOT EXISTS `ajouter_à_la_wishlist` (
  `Id_Offres` int NOT NULL,
  `Id_Compte` int NOT NULL,
  PRIMARY KEY (`Id_Offres`,`Id_Compte`),
  KEY `Id_Compte` (`Id_Compte`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

DROP TABLE IF EXISTS `candidatures`;
CREATE TABLE IF NOT EXISTS `candidatures` (
  `Id_Candidatures` int NOT NULL AUTO_INCREMENT,
  `Cv_Candidature` varchar(255) DEFAULT NULL,
  `Lettre_Motivation_Candidature` text,
  `Date_Candidature` datetime DEFAULT NULL,
  `Id_Compte` int NOT NULL,
  `Id_Offres` int NOT NULL,
  PRIMARY KEY (`Id_Candidatures`),
  KEY `Id_Compte` (`Id_Compte`),
  KEY `Id_Offres` (`Id_Offres`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comptes`
--

DROP TABLE IF EXISTS `comptes`;
CREATE TABLE IF NOT EXISTS `comptes` (
  `Id_Compte` int NOT NULL AUTO_INCREMENT,
  `Nom_Compte` varchar(100) DEFAULT NULL,
  `Prenom_Compte` varchar(50) DEFAULT NULL,
  `Courriel_Compte` varchar(255) DEFAULT NULL,
  `Mot_de_passe_Compte` varchar(255) DEFAULT NULL,
  `Image_Compte` varchar(255) DEFAULT NULL,
  `Description_Compte` text,
  `Adresse_Compte` varchar(255) DEFAULT NULL,
  `Telephone_Compte` int DEFAULT NULL,
  `Etudes_Compte` varchar(255) DEFAULT NULL,
  `Id_Roles` int NOT NULL,
  PRIMARY KEY (`Id_Compte`),
  KEY `Id_Roles` (`Id_Roles`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `comptes`
--

INSERT INTO `comptes` (`Id_Compte`, `Nom_Compte`, `Prenom_Compte`, `Courriel_Compte`, `Mot_de_passe_Compte`, `Image_Compte`, `Description_Compte`, `Adresse_Compte`, `Telephone_Compte`, `Etudes_Compte`, `Id_Roles`) VALUES
(16, NULL, NULL, 'utilisateur.test@plateforme-demo.com', 'Thaomas', NULL, NULL, NULL, NULL, NULL, 0),
(15, NULL, NULL, 'compte.exemple@service-fictif.org', 'Bernadard', NULL, NULL, NULL, NULL, NULL, 0),
(14, NULL, NULL, 'test.technique@entreprise-demo.fr', 'Lefebwe', NULL, NULL, NULL, NULL, NULL, 0),
(13, NULL, NULL, 'jean.dupont2025@mail-test.net', 'aass', NULL, NULL, NULL, NULL, NULL, 0),
(12, NULL, NULL, 'contact.test@domaine-fictif.com', 'Maaa', NULL, NULL, NULL, NULL, NULL, 0),
(11, NULL, NULL, 'utilisateur1@exemple.fr', '123', NULL, NULL, NULL, NULL, NULL, 0),
(17, NULL, NULL, 'info.factice@projet-test.fr', 'Robedart', NULL, NULL, NULL, NULL, NULL, 0),
(18, NULL, NULL, 'demo.compte@mail-temporaire.net', 'Ricdawhard', NULL, NULL, NULL, NULL, NULL, 0),
(19, NULL, NULL, 'test.application@domaine-exemple.com', 'Petdawkit', NULL, NULL, NULL, NULL, NULL, 0),
(20, NULL, NULL, 'essai.technique@mail-fictif.fr', 'Duranwwaod', NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `contenir`
--

DROP TABLE IF EXISTS `contenir`;
CREATE TABLE IF NOT EXISTS `contenir` (
  `Id_Roles` int NOT NULL,
  `Id_Permissions` int NOT NULL,
  PRIMARY KEY (`Id_Roles`,`Id_Permissions`),
  KEY `Id_Permissions` (`Id_Permissions`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

DROP TABLE IF EXISTS `entreprises`;
CREATE TABLE IF NOT EXISTS `entreprises` (
  `Id_Entreprise` int NOT NULL AUTO_INCREMENT,
  `Nom_Entreprise` varchar(50) DEFAULT NULL,
  `Image_Entreprise` varchar(255) DEFAULT NULL,
  `Courriel_Entreprise` varchar(50) DEFAULT NULL,
  `Adresse_Entreprise` varchar(100) DEFAULT NULL,
  `Description_Entreprise` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Id_Entreprise`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `Id_Notes` int NOT NULL AUTO_INCREMENT,
  `Note` int DEFAULT NULL,
  `Commentaire` varchar(50) DEFAULT NULL,
  `Id_Compte` int NOT NULL,
  `Id_Entreprise` int NOT NULL,
  PRIMARY KEY (`Id_Notes`),
  KEY `Id_Compte` (`Id_Compte`),
  KEY `Id_Entreprise` (`Id_Entreprise`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `offres`
--

DROP TABLE IF EXISTS `offres`;
CREATE TABLE IF NOT EXISTS `offres` (
  `Id_Offres` int NOT NULL AUTO_INCREMENT,
  `Titre_Offre` varchar(50) DEFAULT NULL,
  `Competence_Offre` varchar(255) DEFAULT NULL,
  `Adresse_Offre` varchar(255) DEFAULT NULL,
  `Date_Offre` datetime DEFAULT NULL,
  `Secteur_Activité_Offre` varchar(50) DEFAULT NULL,
  `Salaire_Offre` decimal(19,4) DEFAULT NULL,
  `Description_Offre` text,
  `Id_Entreprise` int NOT NULL,
  PRIMARY KEY (`Id_Offres`),
  KEY `Id_Entreprise` (`Id_Entreprise`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `Id_Permissions` int NOT NULL AUTO_INCREMENT,
  `Description_Permission` text,
  PRIMARY KEY (`Id_Permissions`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `Id_Roles` int NOT NULL AUTO_INCREMENT,
  `Type_Role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id_Roles`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
