-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 13 juil. 2023 à 20:11
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `nakamu`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id_comm` bigint(20) NOT NULL,
  `who` bigint(20) UNSIGNED NOT NULL,
  `which` bigint(20) UNSIGNED NOT NULL,
  `date_com` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id_post` bigint(20) UNSIGNED NOT NULL,
  `msg` text DEFAULT NULL,
  `img` text DEFAULT NULL,
  `video` text DEFAULT NULL,
  `publisher` bigint(20) UNSIGNED NOT NULL,
  `post_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `username` varchar(7) NOT NULL,
  `mdp` varchar(12) NOT NULL,
  `question` text NOT NULL,
  `reponse` text NOT NULL,
  `profile_pic` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `prenom`, `nom`, `mail`, `username`, `mdp`, `question`, `reponse`, `profile_pic`) VALUES
(1, 'Talla', 'Yattara', 'tallayattara@gmail.com', 'tallaym', 'tryAGAIN19', 'perso', 'ragnar', ''),
(12, 'Talla ', 'Yattara', 'tallayattara@gmail.com', 'talladf', '$2y$10$y9QLk', 'phobie', 'zvvzv', ''),
(13, ' fjnnnl', 'cnckjdcnk', 'ceclkenjnc@cjcjc.com', 'jdjqlkf', '$2y$10$YfVrS', 'perso', 'fbe', ''),
(14, 'SDCVD', 'VDVFVZ', 'tallayattara@hotmail.com', 'VRZVRZV', '$2y$10$DoEqB', 'perso', 'ZVRZZ', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comm`),
  ADD KEY `who` (`who`),
  ADD KEY `which` (`which`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`),
  ADD UNIQUE KEY `id_post` (`id_post`),
  ADD KEY `who_own_it` (`publisher`) USING BTREE;

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD UNIQUE KEY `mail` (`mail`,`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comm` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`who`) REFERENCES `users` (`id_user`);

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `comments` (`which`),
  ADD CONSTRAINT `who_own_it` FOREIGN KEY (`publisher`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
