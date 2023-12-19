use audience;
-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 12 mars 2023 à 22:12
-- Version du serveur :  5.7.20
-- Version de PHP : 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `audience`
--

-- --------------------------------------------------------

--
-- Structure de la table `ABSENTS`
--

CREATE TABLE `ABSENTS` (
  `ID` int(11) NOT NULL,
  `ID_PERSONNE` int(11) NOT NULL,
  `AUDIENCE` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `ABSENTS`
--

INSERT INTO `ABSENTS` (`ID`, `ID_PERSONNE`, `AUDIENCE`) VALUES
(1, 6, 1),
(2, 7, 1),
(3, 3, 2),
(4, 4, 2),
(5, 1, 4),
(6, 2, 4),
(7, 3, 4),
(8, 4, 4),
(9, 5, 4),
(10, 6, 4),
(11, 7, 4),
(12, 8, 4);

-- --------------------------------------------------------

--
-- Structure de la table `EXEMPTS`
--

CREATE TABLE `EXEMPTS` (
  `ID` int(11) NOT NULL,
  `ID_PERSONNE` int(11) NOT NULL,
  `AUDIENCE` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `EXEMPTS`
--

INSERT INTO `EXEMPTS` (`ID`, `ID_PERSONNE`, `AUDIENCE`) VALUES
(1, 2, 1),
(2, 1, 2),
(3, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `PERSONNES`
--

CREATE TABLE `PERSONNES` (
  `ID` int(11) NOT NULL,
  `NOM` varchar(50) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `PERSONNES`
--

INSERT INTO `PERSONNES` (`ID`, `NOM`) VALUES
(1, 'AUGUSTE'),
(2, 'CALIGULA'),
(3, 'CESAR'),
(4, 'CLAUDE'),
(5, 'HADRIEN'),
(6, 'NERON'),
(7, 'TIBERE'),
(8, 'TRAJAN');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ABSENTS`
--
ALTER TABLE `ABSENTS`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_PERSONNE` (`ID_PERSONNE`);

--
-- Index pour la table `EXEMPTS`
--
ALTER TABLE `EXEMPTS`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_PERSONNE` (`ID_PERSONNE`);

--
-- Index pour la table `PERSONNES`
--
ALTER TABLE `PERSONNES`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ABSENTS`
--
ALTER TABLE `ABSENTS`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `EXEMPTS`
--
ALTER TABLE `EXEMPTS`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `PERSONNES`
--
ALTER TABLE `PERSONNES`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ABSENTS`
--
ALTER TABLE `ABSENTS`
  ADD CONSTRAINT `absents_ibfk_1` FOREIGN KEY (`ID_PERSONNE`) REFERENCES `PERSONNES` (`ID`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `EXEMPTS`
--
ALTER TABLE `EXEMPTS`
  ADD CONSTRAINT `exempts_ibfk_1` FOREIGN KEY (`ID_PERSONNE`) REFERENCES `PERSONNES` (`ID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
