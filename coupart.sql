-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le : ven. 02 fév. 2024 à 15:49
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `coupart`
--

-- --------------------------------------------------------

--
-- Structure de la table `allergen`
--

DROP TABLE IF EXISTS `allergen`;
CREATE TABLE IF NOT EXISTS `allergen` (
  `allergen_id` int NOT NULL AUTO_INCREMENT,
  `allergen_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`allergen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `allergen`
--

INSERT INTO `allergen` (`allergen_id`, `allergen_name`) VALUES
(1, 'Céréales'),
(2, 'Crustacés'),
(3, 'Oeufs'),
(4, 'Poissons'),
(5, 'Arachides'),
(6, 'Soja'),
(7, 'Produits laitiers');

-- --------------------------------------------------------

--
-- Structure de la table `diet`
--

DROP TABLE IF EXISTS `diet`;
CREATE TABLE IF NOT EXISTS `diet` (
  `diet_id` int NOT NULL AUTO_INCREMENT,
  `diet_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`diet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `diet`
--

INSERT INTO `diet` (`diet_id`, `diet_name`) VALUES
(1, 'Végétarien'),
(2, 'Végétalien'),
(3, 'Sans sel'),
(4, 'Végan'),
(5, 'Sans gluten'),
(6, 'Anticholestérol'),
(7, 'Sans sucre'),
(8, 'Sans lactose');

-- --------------------------------------------------------

--
-- Structure de la table `notice`
--

DROP TABLE IF EXISTS `notice`;
CREATE TABLE IF NOT EXISTS `notice` (
  `recipe_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `note` int DEFAULT NULL,
  `opinion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`recipe_id`),
  KEY `FK_NOTICE_USER` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `notice`
--

INSERT INTO `notice` (`recipe_id`, `user_id`, `note`, `opinion`) VALUES
(3, 3, 5, 'Superbe paella'),
(6, 2, 4, 'Omelette très bonne');

-- --------------------------------------------------------

--
-- Structure de la table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
CREATE TABLE IF NOT EXISTS `recipe` (
  `recipe_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `preparation_time` int NOT NULL,
  `break_time` int NOT NULL,
  `cooking_time` int NOT NULL,
  `ingredient` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `step` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `isVisible` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`recipe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `recipe`
--

INSERT INTO `recipe` (`recipe_id`, `title`, `description`, `preparation_time`, `break_time`, `cooking_time`, `ingredient`, `step`, `isVisible`) VALUES
(1, 'Soupe de poisson', 'Bonne soupe de poisson.', 20, 10, 60, 'du poisson, de la tomate et beaucoup d\'amour.', 'Pécher le poisson, cueillir les tomates, mélanger.', 1),
(2, 'Gâteau au yahourt', 'Délicieux gâteau au yahourt.', 10, 30, 30, 'Des œufs, du yahourt, du lait de la farine.', 'Mélanger le tout et mettez au four.', 1),
(3, 'Paella de la mer', 'Comme une paella ,mais de la mer.', 25, 5, 25, 'Du riz, des crustacés aux choix.', 'Cuire le riz, cuire les crustacé, mélangez le tout', 1),
(4, 'Tofu au beurre de cacahuète', 'Parfait au petit déjeuner pour impressionner vos \r\namis', 8, 10, 60, 'Farine, beurre, œuf, beurre de cacahuète.', 'Faites votre tofu, fourrez là de beurre de\r\ncacahuète.\r\n', 1),
(5, 'Sauté de nouille sauce soja', 'Un plat qui vous emportera au pays du soleil levan', 10, 5, 35, 'Nouille, petits légumes, pousse de soja.', 'Cuire les légumes, cuire les nouilles...', 1),
(6, 'Omelette ', 'Un plat rapide et efficace si on a des œufs.', 2, 0, 5, 'Des œufs, du beurre , du sel et encore des œufs', 'Cassez les œufs ,pas les cacahuètes.', 1),
(7, 'Recette de base 1', 'Recette de base numéro 1', 10, 10, 10, 'des ingredients', 'rien', 0),
(8, 'Recette de base 2', 'Recette de base numéro 2', 0, 0, 0, 'des ingredients', 'rien', 0),
(9, 'Recette de base 3', 'Recette de base numéro 3', 0, 0, 0, 'des ingrédients de base', 'rien', 0),
(10, 'Daurade à la vapeur', 'Superbe daurade cuite à la vapeur', 20, 0, 10, 'Daurade petits légumes', 'Pêcher la daurade l\'écaillé lever les filets et cuire', 1);

-- --------------------------------------------------------

--
-- Structure de la table `recipe_allergen`
--

DROP TABLE IF EXISTS `recipe_allergen`;
CREATE TABLE IF NOT EXISTS `recipe_allergen` (
  `recipe_id` int NOT NULL,
  `allergen_id` int NOT NULL,
  PRIMARY KEY (`recipe_id`,`allergen_id`),
  KEY `FK_RECIPE_ALLERGEN_ALLERGEN` (`allergen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `recipe_allergen`
--

INSERT INTO `recipe_allergen` (`recipe_id`, `allergen_id`) VALUES
(4, 1),
(1, 2),
(3, 2),
(2, 3),
(4, 3),
(6, 3),
(1, 4),
(3, 4),
(10, 4),
(4, 5),
(5, 6),
(2, 7),
(4, 7),
(6, 7);

-- --------------------------------------------------------

--
-- Structure de la table `recipe_diet`
--

DROP TABLE IF EXISTS `recipe_diet`;
CREATE TABLE IF NOT EXISTS `recipe_diet` (
  `recipe_id` int NOT NULL,
  `diet_id` int NOT NULL,
  PRIMARY KEY (`recipe_id`,`diet_id`),
  KEY `FK_RECIP_DIET_DIET` (`diet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `recipe_diet`
--

INSERT INTO `recipe_diet` (`recipe_id`, `diet_id`) VALUES
(4, 1),
(5, 1),
(6, 1),
(4, 2),
(2, 3),
(4, 4),
(3, 5),
(4, 5),
(5, 5),
(10, 5),
(10, 6),
(1, 7),
(5, 7),
(6, 7),
(3, 8);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`user_id`, `name`, `firstname`) VALUES
(1, 'coupart', 'sandrine'),
(2, 'doe', 'john'),
(3, 'rock', 'lola');

-- --------------------------------------------------------

--
-- Structure de la table `user_allergen`
--

DROP TABLE IF EXISTS `user_allergen`;
CREATE TABLE IF NOT EXISTS `user_allergen` (
  `user_id` int NOT NULL,
  `allergen_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`allergen_id`),
  KEY `FK_USER_ALLERGEN_ALLERGEN` (`allergen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user_allergen`
--

INSERT INTO `user_allergen` (`user_id`, `allergen_id`) VALUES
(2, 5),
(3, 7);

-- --------------------------------------------------------

--
-- Structure de la table `user_diet`
--

DROP TABLE IF EXISTS `user_diet`;
CREATE TABLE IF NOT EXISTS `user_diet` (
  `user_id` int NOT NULL,
  `diet_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`diet_id`),
  KEY `FK_USER_DIET_DIET` (`diet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user_diet`
--

INSERT INTO `user_diet` (`user_id`, `diet_id`) VALUES
(2, 1),
(2, 2),
(3, 5),
(3, 8);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `notice`
--
ALTER TABLE `notice`
  ADD CONSTRAINT `FK_NOTICE_RECIPE` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`recipe_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_NOTICE_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `recipe_allergen`
--
ALTER TABLE `recipe_allergen`
  ADD CONSTRAINT `FK_RECIPE_ALLERGEN_ALLERGEN` FOREIGN KEY (`allergen_id`) REFERENCES `allergen` (`allergen_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_RECIPE_ALLERGEN_RECIPE` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`recipe_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `recipe_diet`
--
ALTER TABLE `recipe_diet`
  ADD CONSTRAINT `FK_RECIP_DIET_DIET` FOREIGN KEY (`diet_id`) REFERENCES `diet` (`diet_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_RECIPE_DIET_RECIPE` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`recipe_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user_allergen`
--
ALTER TABLE `user_allergen`
  ADD CONSTRAINT `FK_USER_ALLERGEN_ALLERGEN` FOREIGN KEY (`allergen_id`) REFERENCES `allergen` (`allergen_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_USER_ALLERGEN_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user_diet`
--
ALTER TABLE `user_diet`
  ADD CONSTRAINT `FK_USER_DIET_DIET` FOREIGN KEY (`diet_id`) REFERENCES `diet` (`diet_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_USER_DIET_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
