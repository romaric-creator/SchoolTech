-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : Dim 30 mars 2025 à 12:46
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `my dashboard`
--

-- --------------------------------------------------------

--
-- Structure de la table `ordinateurs`
--

CREATE TABLE `ordinateurs` (
  `id_ordinateur` int(11) NOT NULL,
  `id_salle` int(11) NOT NULL,
  `nom_ordi` varchar(255) NOT NULL,
  `Systeme_E` varchar(255) NOT NULL,
  `proces` varchar(255) NOT NULL,
  `Disque` int(11) NOT NULL,
  `ranger` int(11) NOT NULL DEFAULT 1,
  `ram` int(11) NOT NULL,
  `date_maint` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `ordinateurs`
--

INSERT INTO `ordinateurs` (`id_ordinateur`, `id_salle`, `nom_ordi`, `Systeme_E`, `proces`, `Disque`, `ranger`, `ram`, `date_maint`) VALUES
(42, 1, 'Christian Romaric', 'windos 10 pro', 'intel i5', 852, 1, 74, '2025-03-30'),
(43, 1, 'rthfg', 'tugmkjh', 'Intel Core i5', 852, 3, 5, '2025-03-30'),
(45, 1, 'rthfg', 'tugmkjh', 'kmas', 7452, 1, 5, '2025-03-30'),
(46, 1, 'Christian Romaric', 'tugmkjh', 'intel i5', 98, 3, 96, '2025-03-30');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int(11) NOT NULL,
  `nom_us` varchar(100) NOT NULL,
  `date_res` date NOT NULL,
  `nom_salle` varchar(100) NOT NULL,
  `tel` int(11) NOT NULL,
  `debh` varchar(100) NOT NULL,
  `debf` varchar(100) NOT NULL,
  `status` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id_reservation`, `nom_us`, `date_res`, `nom_salle`, `tel`, `debh`, `debf`, `status`) VALUES
(2, 'klw', '2025-03-29', 'salle 2', 0, '17:57', '17:57', 'off'),
(3, 'Christian Romaric', '2025-03-01', 'salle 2', 44524654, '08:28', '08:32', 'on'),
(4, 'Christian Romaric', '2025-03-30', 'salle 2', 44524654, '12:15', '15:15', 'on');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `id_salle` int(11) NOT NULL,
  `nom_salle` varchar(100) NOT NULL,
  `capacite` int(11) NOT NULL,
  `disponibilite` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `nom_salle`, `capacite`, `disponibilite`) VALUES
(1, 'salle 1', 0, 'disponible'),
(2, 'salle 2', 0, 'disponible'),
(4, 'salle 3', 0, 'disponible'),
(5, 'dfdgrfd', 0, 'disponible');

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `nom_us` varchar(100) NOT NULL,
  `tel` int(11) NOT NULL,
  `date_service` date DEFAULT curdate(),
  `contenu` varchar(200) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '',
  `action` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id_service`, `nom_us`, `tel`, `date_service`, `contenu`, `status`, `action`) VALUES
(1, 'h', 0, '2025-03-29', 'y', 'off', 'effectuer'),
(2, 'oihewioj', 55, '2025-03-29', 'oijepojwpojfpowjopw', 'off', 'effectuer'),
(3, 'tenda', 6782641, '2025-03-29', 'jma souris ne fojnmctione polusjma souris ne fojnmctione polusjma souris ne fojnmctione polusjma souris ne fojnmctione polusjma souris ne fojnmctione polusjma souris ne fojnmctione polusjma souris ne ', 'off', 'effectuer'),
(4, 'Christian Romaric', 6782641, '2025-03-30', 'v.kjb', 'nouveau', 'effectuer'),
(5, 'Christian Romaric', 0, '2025-03-30', 'jknd;kls', 'nouveau', '');

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id_stock` int(11) NOT NULL,
  `nom_sordi` varchar(200) NOT NULL,
  `Systeme_E` varchar(200) NOT NULL,
  `proces` varchar(200) NOT NULL,
  `Disque` int(11) NOT NULL,
  `ram` int(11) NOT NULL,
  `date_ajout` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `nom` varchar(115) NOT NULL,
  `email` varchar(115) NOT NULL,
  `pass` varchar(115) NOT NULL,
  `numero` int(11) NOT NULL,
  `status` varchar(110) NOT NULL,
  `pp` varchar(115) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_users`, `nom`, `email`, `pass`, `numero`, `status`, `pp`) VALUES
(1, 'Tenda', 'christiantendainfo2006@gmail.com', '$2y$10$eebP553kLmv7oJKFKWUS4uclqte8nr95mrdEFbQ98dPjSPVh5QEVi', 678261699, 'admin', 'photo-1568901346375-23c9450c58cd.jpeg'),
(2, 'admin@gmail.com', 'admin@gmail.com', '$2y$10$RwOZYfzg6rQR7iv2B/MCKeVly6GkIxEbE80BXr58sSo97wzsxN98G', 0, 'editor', 'pexels-arts-1496373.jpg');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ordinateurs`
--
ALTER TABLE `ordinateurs`
  ADD PRIMARY KEY (`id_ordinateur`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`id_salle`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id_stock`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ordinateurs`
--
ALTER TABLE `ordinateurs`
  MODIFY `id_ordinateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `id_salle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
