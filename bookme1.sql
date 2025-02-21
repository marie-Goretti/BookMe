-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 21 fév. 2025 à 00:34
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
-- Base de données : `bookme1`
--

-- --------------------------------------------------------

--
-- Structure de la table `auteur`
--

DROP TABLE IF EXISTS `auteur`;
CREATE TABLE IF NOT EXISTS `auteur` (
  `id_auteur` int NOT NULL AUTO_INCREMENT,
  `nom_auteur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prenom_auteur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `biographie` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_auteur`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `auteur`
--

INSERT INTO `auteur` (`id_auteur`, `nom_auteur`, `prenom_auteur`, `biographie`) VALUES
(1, 'Forna', 'Namina', ''),
(2, 'Rowlings', 'Joanne K.', 'Joanne Rowling, connue sous le pseudonyme de J.K. '),
(3, 'McFadden', 'freida', ''),
(4, 'Moncomble', 'Morgane', ''),
(6, 'Hoover', 'Colleen', ''),
(7, 'Barnes', 'Jennifer Lynn', ''),
(8, 'Jeneva', 'Rose', ''),
(9, 'Havendean', 'Cynthia', '');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `libele_categorie` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `libele_categorie`) VALUES
(1, 'thriller'),
(2, 'fantasy'),
(3, 'mystere');

-- --------------------------------------------------------

--
-- Structure de la table `emprunter`
--

DROP TABLE IF EXISTS `emprunter`;
CREATE TABLE IF NOT EXISTS `emprunter` (
  `id_emprunt` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_livre` int NOT NULL,
  `date_emprunt` date NOT NULL,
  `date_retour` date NOT NULL,
  PRIMARY KEY (`id_emprunt`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_livre` (`id_livre`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `emprunter`
--

INSERT INTO `emprunter` (`id_emprunt`, `id_utilisateur`, `id_livre`, `date_emprunt`, `date_retour`) VALUES
(15, 10, 13, '2025-02-12', '2025-02-26'),
(14, 10, 12, '2025-02-12', '2025-02-26'),
(18, 11, 14, '2025-02-10', '2025-02-20'),
(11, 6, 11, '2025-02-11', '2025-02-25'),
(16, 10, 16, '2025-02-13', '2025-02-14'),
(19, 6, 20, '2025-02-12', '2025-02-25'),
(21, 12, 17, '2025-02-21', '2025-03-22'),
(22, 12, 5, '2025-02-21', '2025-03-31');

-- --------------------------------------------------------

--
-- Structure de la table `historique_emprunt`
--

DROP TABLE IF EXISTS `historique_emprunt`;
CREATE TABLE IF NOT EXISTS `historique_emprunt` (
  `id_historique` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_livre` int NOT NULL,
  `date_emprunt` date NOT NULL,
  `date_retour_v` date NOT NULL,
  `statut` varchar(50) NOT NULL DEFAULT 'en cours',
  PRIMARY KEY (`id_historique`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_livre` (`id_livre`),
  KEY `statut` (`statut`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `historique_emprunt`
--

INSERT INTO `historique_emprunt` (`id_historique`, `id_utilisateur`, `id_livre`, `date_emprunt`, `date_retour_v`, `statut`) VALUES
(1, 3, 5, '2025-02-08', '2025-02-09', 'retourné'),
(2, 3, 10, '2025-02-09', '2025-02-09', 'en cours'),
(3, 3, 5, '2025-02-08', '2025-02-09', 'en cours'),
(4, 3, 6, '2025-02-09', '2025-02-10', 'en cours'),
(5, 3, 7, '2025-02-09', '2025-02-10', 'en cours'),
(6, 3, 9, '2025-02-09', '2025-02-11', 'en cours'),
(7, 5, 5, '2025-02-09', '2025-02-11', 'en cours'),
(8, 6, 6, '2025-02-11', '2025-02-11', 'en cours'),
(9, 6, 5, '2025-02-11', '2025-02-12', 'en cours'),
(10, 10, 16, '2025-01-08', '2025-01-11', 'en cours'),
(11, 10, 5, '2025-01-06', '2025-02-11', 'en cours'),
(12, 11, 6, '2025-02-03', '2025-02-13', 'en cours'),
(13, 11, 21, '2025-02-12', '2025-02-20', 'en cours');

-- --------------------------------------------------------

--
-- Structure de la table `livre`
--

DROP TABLE IF EXISTS `livre`;
CREATE TABLE IF NOT EXISTS `livre` (
  `id_livre` int NOT NULL AUTO_INCREMENT,
  `nom_livre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `resume_livre` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_publication` date NOT NULL,
  `isbn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_categorie` int NOT NULL,
  `id_auteur` int NOT NULL,
  `image_livre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `statut` enum('disponible','non disponible') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'disponible',
  PRIMARY KEY (`id_livre`),
  KEY `livre_categorie_FK` (`id_categorie`),
  KEY `livre_auteur_FK` (`id_auteur`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `livre`
--

INSERT INTO `livre` (`id_livre`, `nom_livre`, `resume_livre`, `date_publication`, `isbn`, `id_categorie`, `id_auteur`, `image_livre`, `statut`) VALUES
(5, 'Les immortelles, Tome 1', 'Bannies par le sang. Guerrières par choix.\r\n\r\nDéka a 16 ans. Elle vit dans un royaume où le destin de chaque femme est déterminé dès l\'adolescence par la couleur de leur sang lors du rituel de pureté. S\'il est rouge, elles seront intégrées dans la société et s\'il est doré, elles seront considérées comme des démones. Malheureusement, celui de Déka a la mauvaise teinte et elle est promise à un sort pire que la mort.\r\n\r\nEmprisonnée, elle reçoit la visite d\'une mystérieuse femme qui lui lance un ultimatum : rester dans son village et se soumettre à sa destinée ou partir se battre pour l\'empereur auprès de guerrières comme elle – les alakis – des quasi-immortelles aux dons rares, les seules capables de stopper la terrible menace qui pèse sur le royaume.\r\n\r\nConsciente des dangers qui l\'attendent tout en souhaitant être acceptée, Déka décide de quitter la seule vie qu\'elle n\'ait jamais connue. Mais alors qu\'elle se rend à la capitale pour se préparer à la plus grande bataille de son existence, elle va découvrir que la vil', '2025-02-02', '235-654-54654-211-5665', 2, 1, 'uploads/img3.jpg', 'non disponible'),
(6, 'la femme de menage', 'Chaque jour, Millie fait le ménage dans la belle maison des Winchester, une riche famille new-yorkaise. Elle récupère aussi leur fille à l\'école et prépare les repas avant d\'aller se coucher dans sa chambre, au grenier. Pour la jeune femme, ce nouveau travail est une chance inespérée. L\'occasion de repartir de zéro. Mais, sous des dehors respectables, sa patronne se montre de plus en plus instable et toxique. Et puis il y a aussi cette rumeur dérangeante qui court dans le quartier : madame Winchester aurait tenté de noyer sa fille il y a quelques années. Heureusement, le gentil et séduisant monsieur Winchester est là pour rendre la situation supportable. Mais le danger se tapit parfois sous des apparences trompeuses. Et lorsque Millie découvre que la porte de sa chambre mansardée ne ferme que de l\'extérieur, il est peut-être déjà trop tard...', '2025-02-06', '235-654-54654-211-5665', 1, 3, 'uploads/im1.jpg', 'disponible'),
(9, 'Harry Potter à l\'école des Sorciers', 'Le jour de ses onze ans, Harry Potter, un orphelin élevé par un oncle et une tante qui le détestent, voit son existence bouleversée. Un géant vient le chercher pour l\'emmener à Poudlard, la célèbre école de sorcellerie où une place l\'attend depuis toujours. Voler sur des balais, jeter des sorts, combattre les Trolls : Harry Potter se révèle un sorcier vraiment doué. Mais quel mystère entoure donc sa naissance et qui est l\'effroyable V..., le mage dont personne n\'ose prononcer le nom ?', '2025-01-27', '235-654-54654-211-5665', 2, 2, 'uploads/img4.jpg', 'disponible'),
(11, 'harry potter et le prizonier d\\\'azkaban', 'Une rentrée fracassante en voiture volante, une étrange malédiction qui s\'abat sur les élèves, cette deuxième année à l\'école des sorciers ne s\'annonce pas de tout repos ! Entre les cours de potions magiques, les matches de Quidditch et les combats de mauvais sorts, Harry et ses amis Ron et Hermione trouveront-ils le temps de percer le mystère de la Chambre des Secrets ? Le deuxième volume des aventures de Harry Potter : un livre magique pour sorciers confirmés.', '2025-01-27', '235-654-54654-211-5665', 2, 2, 'uploads/img6.jpeg', 'non disponible'),
(12, 'Un automne pour te pardonner', 'Avocate en devenir, Camelia est passionnée de crimes non résolus. Ça tombe bien, Rory Cavendish, son ancien bourreau, vient de mourir mystérieusement. Le présumé coupable du meurtre, c’est lui.\r\n\r\nLou McAllister.\r\n\r\nLe meilleur ami de Rory.\r\n\r\nLe garçon qui l’a humiliée il y a dix ans et qu’elle n’a jamais oublié depuis.\r\n\r\nCamelia saisit cette chance pour résoudre sa première enquête... et satisfaire sa soif de vengeance.\r\n\r\nLou a toujours été le mouton noir de sa famille, mais il ne s’attendait pas à finir en prison pour meurtre. Du jour au lendemain, le voilà abandonné par tous ceux en qui il avait confiance. La seule personne à pouvoir le sortir de là, c’est elle.\r\n\r\nCamelia O’Brien.\r\n\r\nLa première victime de Rory.\r\n\r\nLa fille qu’il a blessée plus jeune et qui hante ses pensées depuis.\r\n\r\nLou est prêt à tout pour se faire pardonner ses péchés... et repartir de zéro.', '2023-06-07', '235-654-54654-211-5665', 3, 4, 'uploads/img2.jpg', 'non disponible'),
(13, 'Verity', 'Lowen Ashleigh perd le gout de la vie, à force de voir ses finances, ses écrits et ses amours ne jamais décollés. Mais une occasion des plus incongrus se présente à elle: devenir la ghostwriter de Verity, la célèbre écrivaine qui enchaine les best sellers. Alors que Lowen se plonge dans les récits inachevés de Verity, le portrait de la malade devient de plus en plus angoissant. Lowen se sent menacée, et se rapprocher du mari de Verity n’est peut être pas l’idée du siècle…', '2025-01-28', '235-654-54654-211-5665', 1, 6, 'uploads/67acf4fd3e6ba.jpg', 'non disponible'),
(14, 'Les secrets de la femme de ménage', 'C’est une chance inespérée pour Millie d’avoir décroché un nouveau travail. Chez les Garrick, un couple fortuné qui possède un somptueux appartement avec vue sur New York, elle fait le ménage et prépare les repas dans la magnifique cuisine.\\r\\n\\r\\nCela paraît trop beau pour être vrai. Et effectivement, la femme de ménage ne tarde pas à déceler quelques ombres au tableau… Son patron, Douglas Garrick, est d’humeur de plus en plus changeante. Et pourquoi sa femme Wendy reste-t-elle toujours enfermée dans la chambre d’amis ?\\r\\n\\r\\nLe jour où Millie découvre du sang sur une chemise de nuit, elle ne peut plus rester les bras croisés. Quelque chose se trame dans cette maison. Une situation à laquelle Millie n’est pas préparée et qui pourrait bien se retourner contre elle si elle continue de vouloir découvrir les secrets des autres…\\r\\n\\r\\n', '2025-02-15', '235-654-54654-211-5665', 1, 3, 'uploads/67ad029f85e5c.jpg', 'non disponible'),
(15, 'Inheritance games, Tome 3', 'Depuis bientôt un an, Avery Grambs vit dans la demeure des Hawthorne. A présent majeure, elle va officiellement devenir milliardaire.\\r\\n\\r\\nAlors que le monde a les yeux rivés sur elle, s\\\'interrogeant sur la façon dont une si jeune femme va gérer une fortune aussi importante, une nouvelle venue prend ses quartiers entre les murs de la maison Hawthorne et apporte avec elle son lot de tensions. Serait-elle la pièce manquante de l\\\'héritage d\\\'Avery ?\\r\\n\\r\\nAvery et les petits-fils Hawthorne ont une dernière énigme à résoudre, une partie décisive contre un adversaire inconnu et puissant... qui pourrait tout remettre en jeu.', '2025-01-28', '235-654-54654-211-5665', 3, 7, 'uploads/67ad04c68edb8.jpg', 'disponible'),
(16, 'Inheritance games, Tome 1', 'Avery Grambs, lycéenne sans histoire et sans le sou, rêve d’une bourse d’études pour entrer à l’université. Son destin bascule soudain quand Tobias Hawthorne, un célèbre milliardaire, lui lègue sa fortune. Cet argent tombe à pic, mais il y a un problème : Avery n’a jamais entendu parler de cet homme !\\r\\n\\r\\nPour toucher sa part d’héritage, elle doit néanmoins emménager dans la mystérieuse demeure des Hawthorne. Elle y côtoie les quatre petits-fils du défunt, tous aussi insondables que séduisants… et surtout bien décidés à l’empêcher de subtiliser leur dû !\\r\\n\\r\\nHappée par un tourbillon de manigances, d’énigmes et de trahisons, Avery va devoir se prêter à un inquiétant jeu de dupes qui pourrait bouleverser sa vie à jamais…', '2024-03-08', '235-654-54654-211-5665', 3, 7, 'uploads/67ad05d5c51e3.jpg', 'non disponible'),
(17, 'Inheritance games, Tome 2', 'Que feriez-vous si vous receviez l\\\'héritage d\\\'un inconnu milliardaire convoité par ses sulfureux petits-fils ?\\r\\n\\r\\nDu jour au lendemain, Avery Grambs est passée de lycéenne fauchée à milliardaire. Elle évolue désormais dans un monde foisonnant d\\\'énigmes, de richesses infinies, de danger et de secrets de famille, et bénéficie d\\\'une protection rapprochée. Mais pourquoi Tobias Hawthorne, le milliardaire excentrique, a-t-il décidé de lui léguer toute sa fortune au détriment de ses propres filles et de ses quatre petits-fils ? Avery se lance à la poursuite de la seule personne susceptible de répondre à cette question. Tandis qu\\\'elle tente de résoudre énigme après énigme, accompagnée de Grayson et Jameson, elle a de plus en plus de mal à savoir qui sont ses alliés et qui est prêt à l\\\'éliminer... par tous les moyens ! Parviendra-t-elle à résoudre le mystère de son destin à temps ?', '2024-11-08', '235-654-54654-211-5665', 3, 7, 'uploads/67ae1b70e3108.jpg', 'non disponible'),
(18, 'harry potter et la chambre des secrets ', 'Une rentrée fracassante en voiture volante, une étrange malédiction qui s\\\'abat sur les élèves, cette deuxième année à l\\\'école des sorciers ne s\\\'annonce pas de tout repos ! Entre les cours de potions magiques, les matches de Quidditch et les combats de mauvais sorts, Harry et ses amis Ron et Hermione trouveront-ils le temps de percer le mystère de la Chambre des Secrets ? Le deuxième volume des aventures de Harry Potter : un livre magique pour sorciers confirmés.', '2025-01-28', '235-654-54654-211-5665', 2, 2, 'uploads/67ae1dd8e32a0.jpeg', 'disponible'),
(20, 'La femme de ménage voit tout', 'Après avoir été au service des autres en tant que femme de ménage, Millie s’est enfin construit une vie à elle. Elle vient même d’emménager dans une belle maison, dans une petite impasse chic et tranquille, avec son mari et ses deux enfants.\\r\\n\\r\\nMais son rêve d’une vie paisible est rapidement terni par la rencontre de ses voisins. Il y a Suzette, bien trop snob et aguicheuse, son insipide mari, mais surtout leur terrifiante femme de ménage au regard perçant et au comportement plus que suspect.\\r\\n\\r\\nLes craintes de Millie montent d’un cran lorsque des bruits étranges se font entendre la nuit dans sa propre maison. Pire : elle éprouve un étrange malaise et se sent épiée. C’est certain, quelque chose ne tourne pas rond dans cette rue si tranquille. Mais est-elle prête à en découvrir les secrets ? Et surtout, le temps de comprendre ce qui ne va pas, tout peut arriver…', '2024-06-08', '235-654-54654-211-5665', 1, 3, 'uploads/la-femme-de-menage-tome-3-la-femme-de-menage-voit-tout-5481565.jpg', 'non disponible'),
(21, 'Monster', 'Je m’appelle Seyvanna, je suis la fille de Barron Pavlenski, le parrain de la mafia russe. Mikhaïl, mon frère, va bientôt prendre le flambeau de la famille. Cible parfaite pour les gangs de rue, je suis protégée par mon frère qui a toujours eu un amour possessif, obsessionnel et malsain envers moi. Il me considère comme sa chose. Aucun homme ne peut poser la main sur sa sœur sans y trouver la mort. Mais Jonas Somber Jann en a décidé autrement et a enfreint les règles. J’ai fait l’erreur de me laisser séduire par l’interdit.\\r\\n\\r\\nL’alliance entre Mikhaïl et Jonas est désormais rompue : les deux associés sont à présent des rivaux et mon frère entend bien récolter la tête de mon nouvel amant. Seulement, il ne s’attendait pas à affronter une bête plus dangereuse que lui…', '2024-08-24', '235-654-54654-211-5665', 1, 9, 'uploads/67ae22667e513.jpg', 'disponible'),
(22, 'Les immortelles, Tome 2', 'Elle a le pouvoir de sauver le monde... ou de le détruire.\\r\\n\\r\\nSix mois ont passés depuis que Deka a libéré les déesses de l\\\'ancien royaume d\\\'Otera et découvert qui elle est vraiment, mais la guerre fait rage dans tout le royaume et la véritable bataille ne fait que commencer. Une force obscure se développe à Otera, une puissance impitoyable que Deka et son armée doivent arrêter.\\r\\n\\r\\nPourtant, des secrets cachés menacent de détruire tout ce que Deka a connu. Et avec ses propres dons qui changent, elle doit découvrir si elle détient la clé pour sauver Otera... ou si elle pourrait en être sa plus grande menace.', '2025-02-01', '235-654-54654-211-5665', 2, 1, 'uploads/67ae794e52d2c.jpg', 'disponible');

-- --------------------------------------------------------

--
-- Structure de la table `livre_auteur`
--

DROP TABLE IF EXISTS `livre_auteur`;
CREATE TABLE IF NOT EXISTS `livre_auteur` (
  `id_livre` int NOT NULL,
  `id_auteur` int NOT NULL,
  PRIMARY KEY (`id_livre`,`id_auteur`),
  KEY `id_auteur` (`id_auteur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `livre_auteur`
--

INSERT INTO `livre_auteur` (`id_livre`, `id_auteur`) VALUES
(0, 0),
(2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `publier`
--

DROP TABLE IF EXISTS `publier`;
CREATE TABLE IF NOT EXISTS `publier` (
  `id_auteur` int NOT NULL,
  `id_livre` int NOT NULL,
  `date_publication` date NOT NULL,
  PRIMARY KEY (`id_auteur`,`id_livre`),
  KEY `publier_livre0_FK` (`id_livre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom__utilisateur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prenom_utilisateur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_utilisateur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `statut` enum('actif','bloque') COLLATE utf8mb4_general_ci DEFAULT 'actif',
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom__utilisateur`, `prenom_utilisateur`, `email_utilisateur`, `mot_de_passe`, `role`, `statut`) VALUES
(6, 'Céline', 'AMOUSSOU', 'celine@gmail.com', '1234', 'user', 'actif'),
(9, 'admin', 'admin', 'administrateur@gmail.com', 'admin123', 'admin', 'actif'),
(10, 'xavier', 'dbe', 'xavier@gmail.com', '123456789', 'user', 'actif'),
(11, 'goretti', 'Dissiran', 'gorettimgboouna@gmail.com', '123456789', 'user', 'actif'),
(12, 'stecy', 'cassandra', 'stecy@gmail.com', '1234', 'user', 'actif');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `livre`
--
ALTER TABLE `livre`
  ADD CONSTRAINT `livre_auteur_FK` FOREIGN KEY (`id_auteur`) REFERENCES `auteur` (`id_auteur`),
  ADD CONSTRAINT `livre_categorie_FK` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`);

--
-- Contraintes pour la table `publier`
--
ALTER TABLE `publier`
  ADD CONSTRAINT `publier_auteur_FK` FOREIGN KEY (`id_auteur`) REFERENCES `auteur` (`id_auteur`),
  ADD CONSTRAINT `publier_livre0_FK` FOREIGN KEY (`id_livre`) REFERENCES `livre` (`id_livre`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
