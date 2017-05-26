-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 26 Mai 2017 à 09:18
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `myrecipes`
--
CREATE DATABASE IF NOT EXISTS `myrecipes` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `myrecipes`;

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE IF NOT EXISTS `commentaires` (
  `IdCommentaire` int(11) NOT NULL AUTO_INCREMENT,
  `TitreCommentaire` varchar(30) NOT NULL,
  `Commentaire` varchar(100) NOT NULL,
  `IdUtilisateur` int(11) NOT NULL,
  `IdRecette` int(11) NOT NULL,
  PRIMARY KEY (`IdCommentaire`),
  KEY `IdUtilisateur` (`IdUtilisateur`),
  KEY `IdRecette` (`IdRecette`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

CREATE TABLE IF NOT EXISTS `favoris` (
  `IdUtilisateur` int(11) NOT NULL,
  `IdRecette` int(11) NOT NULL,
  PRIMARY KEY (`IdUtilisateur`,`IdRecette`),
  KEY `IdRecette` (`IdRecette`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `recettes`
--

CREATE TABLE IF NOT EXISTS `recettes` (
  `IdRecette` int(11) NOT NULL AUTO_INCREMENT,
  `Titre` varchar(40) NOT NULL,
  `Ingredient` text NOT NULL,
  `Description` longtext NOT NULL,
  `Valider` tinyint(1) NOT NULL,
  `NomFichierImg` varchar(30) NOT NULL,
  `IdUtilisateur` int(11) NOT NULL,
  `IdType` int(11) NOT NULL,
  PRIMARY KEY (`IdRecette`),
  KEY `IdUtilisateur` (`IdUtilisateur`),
  KEY `IdType` (`IdType`),
  KEY `Titre` (`Titre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `recettes`
--

INSERT INTO `recettes` (`IdRecette`, `Titre`, `Ingredient`, `Description`, `Valider`, `NomFichierImg`, `IdUtilisateur`, `IdType`) VALUES
(1, 'Houmous', ' - 1 boîte de pois-chiches en conserve - 1 petite gousse d''ail - 1 cas de tahini - 1 citron - Huile d''olive', 'Égouttez et placez les pois chiches dans un robot culinaire.\nÉpluchez et ajoutez l''ail, puis ajoutez le tahini, un filet de jus de citron et 1 cuillère à soupe d''huile.\nAssaisonnez avec une pincée de sel de mer, puis faites marcher le robot mixeur.\nUtilisez une spatule pour enlever le houmous sur les côtés du bol, puis recommencez à mixer jusqu''à ce que le houmous soit lisse.\nGoutez et ajoutez plus de jus de citron ou un peu d''eau si la mixture est trop compact, puis transférer dans un bol pour servir.\nServir avec des légumes croustillants en tranches, tels que des carottes, des concombres, des radis ou des poivrons, et des pains pita.\n', 1, '', 1, 1),
(2, 'Fondant banane et patate douce', ' - 260 grammes de chair de patates douces préalablement cuitent à la vapeur - 240 grammes de chair de bananes - 105 grammes de poudre d’amande - 30 grammes de farine de coco - 30 grammes d’arrow root (ou fécule de mais, version non paleo)- 15 grammes de coco rappée - ½ cuillère à café de bicarbonate de soude', 'Préchauffez votre four à 180°. Dans un saladier, mixez ensemble la chair de patates douces et de bananes jusqu’à l’obtention d’un mélange homogène. Ajoutez y le reste des ingrédients et mélangez. Versez la préparation dans votre moule préalablement huilé et enfournez entre 55 et 65 minutes (selon si vous le souhaitez fondant ou un peu plus cuit, les deux versions sont très bonnes. Testées et approuvées!). Laissez le refroidir quelques minutes avant de le démouler et saupoudrez le de coco rappée', 1, '', 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `IdType` int(11) NOT NULL AUTO_INCREMENT,
  `NomType` varchar(20) NOT NULL,
  PRIMARY KEY (`IdType`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `types`
--

INSERT INTO `types` (`IdType`, `NomType`) VALUES
(1, 'Starter'),
(2, 'Main'),
(3, 'Dessert');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `IdUtilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `IsAdmin` tinyint(1) NOT NULL,
  PRIMARY KEY (`IdUtilisateur`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`IdUtilisateur`, `Username`, `Password`, `IsAdmin`) VALUES
(1, 'Admin', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`IdUtilisateur`) REFERENCES `utilisateurs` (`IdUtilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`IdRecette`) REFERENCES `recettes` (`IdRecette`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`IdUtilisateur`) REFERENCES `utilisateurs` (`IdUtilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`IdRecette`) REFERENCES `recettes` (`IdRecette`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `recettes`
--
ALTER TABLE `recettes`
  ADD CONSTRAINT `recettes_ibfk_2` FOREIGN KEY (`IdType`) REFERENCES `types` (`IdType`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `recettes_ibfk_3` FOREIGN KEY (`IdUtilisateur`) REFERENCES `utilisateurs` (`IdUtilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
