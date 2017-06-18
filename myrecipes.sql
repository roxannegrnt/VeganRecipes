-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 18 Juin 2017 à 11:56
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
  `Commentaire` varchar(100) NOT NULL,
  `IdUtilisateur` int(11) NOT NULL,
  `IdRecette` int(11) NOT NULL,
  PRIMARY KEY (`IdCommentaire`),
  KEY `IdUtilisateur` (`IdUtilisateur`),
  KEY `IdRecette` (`IdRecette`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=157 ;

--
-- Contenu de la table `commentaires`
--

INSERT INTO `commentaires` (`IdCommentaire`, `Commentaire`, `IdUtilisateur`, `IdRecette`) VALUES
(148, 'hello how are you', 1, 64),
(153, 'hello', 1, 73),
(155, 'hello', 1, 65),
(156, 'Great recipe', 2, 75);

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

CREATE TABLE IF NOT EXISTS `favoris` (
  `IdUtilisateur` int(11) NOT NULL,
  `IdRecette` int(11) NOT NULL,
  PRIMARY KEY (`IdUtilisateur`,`IdRecette`),
  KEY `favoris_ibfk_2` (`IdRecette`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `favoris`
--

INSERT INTO `favoris` (`IdUtilisateur`, `IdRecette`) VALUES
(1, 64),
(2, 64),
(1, 65),
(2, 65);

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
  `DateTimeInsert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IdUtilisateur` int(11) NOT NULL,
  `IdType` int(11) NOT NULL,
  PRIMARY KEY (`IdRecette`),
  KEY `IdUtilisateur` (`IdUtilisateur`),
  KEY `IdType` (`IdType`),
  KEY `Titre` (`Titre`),
  KEY `Titre_2` (`Titre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=151 ;

--
-- Contenu de la table `recettes`
--

INSERT INTO `recettes` (`IdRecette`, `Titre`, `Ingredient`, `Description`, `Valider`, `NomFichierImg`, `DateTimeInsert`, `IdUtilisateur`, `IdType`) VALUES
(64, 'Bulgur & lentil salad', '- 150 g dark bulgur wheat - 150 g green or yellow lentils - 2 cloves of garlic - 2 fresh bay leaves - 6 spring onions - 2 ripe tomatoes - 2 red peppers - 1 red onion - ½ a bunch of fresh flat-leaf parsley - ½ a bunch of fresh mint - ½ a bunch of fresh dill - 150 ml extra virgin olive oil - 4 tablespoons pomegranate molasses - 2 lemons - 1 teaspoon sumac', 'Place the bulgur in a bowl, cover with boiling water and leave to expand and cool for 30 to 45 minutes.\r\nPut the lentils, garlic and bay leaves in a saucepan over a medium heat. Cover with plenty of cold water and bring it to the boil.\r\nLower the heat and simmer, covered, for 20 to 30 minutes, or until tender. Drain in a colander, discard the garlic and bay leaves and place the lentils in a large mixing bowl.\r\nDrain the bulgur in a colander, then transfer to a clean tea towel, wrap it up and squeeze tightly to get rid of excess water. Tip it into the lentils.\r\nTrim and slice the spring onions. Peel, deseed and chop the tomatoes, deseed and slice the peppers, then peel and thinly slice the onion. Pick and roughly chop the parsley and mint leaves, and finely chop the dill fronds.\r\nAdd your prepped ingredients to the lentils, season with sea salt and black pepper, and mix well. Stir in the oil, pomegranate molasses, lemon zest and juice and mix until combined.\r\nLeave for 30 minutes to allow the flavours to infuse, then serve sprinkled with the sumac.', 1, 'FILE_594126ac874ad', '2017-06-14 12:06:04', 1, 2),
(65, 'Vegan winter rolls', '- 100 g vermicelli rice noodles or glass noodles - 75 g kale - 50 g beansprouts - 1 carrot - 1 tablespoon pickled ginger - 1 large pomegranate - 4 sprigs of fresh mint - 4 sprigs of fresh coriander - 1 tablespoon sesame oil - 12 medium round rice-paper wrappers - 1 spring onion - 1 fresh red chilli - 1 tablespoon pickled ginger - 2 limes , juice of - 4 tablespoons sweet chilli sauce - 2 teaspoons sesame oil - 2 teaspoons low-salt soy sauce', 'To make the dipping sauce, trim and finely chop the spring onion and chilli (scrape out the seeds if you can’t handle the heat), then finely chop the ginger. Place into a small bowl with the remaining sauce ingredients and 1 tablespoon of water, then mix well. Taste and adjust the flavours, if needed.\nPrepare the noodles according to the packet instructions. Drain, then leave to cool. Cut away any tough stalky bits from the kale, finely slice and place into a large bowl with the cooled noodles and beansprouts. Peel and slice the carrot into thin batons, roughly 5cm in length, then add to the bowl. Finely slice and add the ginger.\nCut the pomegranate in half, hold one half over the bowl, cut-side down, and bash the back of it with a wooden spoon so that all of the seeds come tumbling out. Repeat with the other half. Pick in the herb leaves and add the sesame oil, then toss well.\nDip one of the rice paper wrappers in a shallow bowl of warm water. Allow to soak for around 10 seconds until soft and pliable, drain on kitchen paper, then place onto a board. Spoon 1 heaped tablespoon of the filling onto the wrapper in a rough line, about 3cm from the edge nearest to you (be careful not to overfill them as they’ll be hard to roll).\nFold the edge nearest to you over the filling, then tightly roll it away from you, tucking in the left and right edges as you go, then press down to seal. Repeat with the remaining ingredients, halve each roll at an angle, then serve with the dipping sauce – enjoy!', 1, 'FILE_5941273ac8fae', '2017-06-14 12:08:26', 1, 1),
(73, 'Chocolate mousse', '- 150 g dairy-free dark chocolate , plus extra for serving - 2 large ripe avocados - 2 tablespoons cocoa powder - 2 teaspoons vanilla bean paste - 3 tablespoons maple syrup - 1 x 160 g tin of coconut cream', 'Place a heatproof bowl over a pan of simmering water, making sure the base doesn’t touch the water. Break the chocolate into the bowl and allow it to melt, then set aside to cool slightly.\r\nMeanwhile, halve and stone the avocados, then scoop the flesh into a food processor, discarding the skins. Add the remaining ingredients and pulse for a few seconds. Scrape down the sides with a spatula, then pulse again to combine.\r\nPour in the cooled chocolate, then pulse a final time until creamy and smooth. Divide the mixture between six small bowls, then pop in the fridge to chill for at least 30 minutes. Serve with an extra grating of chocolate and a fresh fruit salad.', 1, 'FILE_594148dcde446', '2017-06-14 14:31:56', 1, 3),
(75, 'Falafel', '- 1 cup dried chickpeas - 1/2 large onion, roughly chopped (about 1 cup) - 2 tablespoons finely chopped fresh parsley - 2 tablespoons finely chopped fresh cilantro - 1 teaspoon salt - 1/2-1 teaspoon dried hot red pepper - 4 cloves of garlic - 1 teaspoon cumin - 1 teaspoon baking powder - 4-6 tablespoons flour - Soybean or vegetable oil for frying - Chopped tomato for garnish - Diced onion for garnish - Diced green bell pepper for garnish - Tahina sauce - Pita bread', 'Put the chickpeas in a large bowl and add enough cold water to cover them by at least 2 inches. Let soak overnight, then drain. Or use canned chickpeas, drained.\r\nPlace the drained, uncooked chickpeas and the onions in the bowl of a food processor fitted with a steel blade. Add the parsley, cilantro, salt, hot pepper, garlic, and cumin. Process until blended but not pureed.\r\nSprinkle in the baking powder and 4 tablespoons of the flour, and pulse. You want to add enough bulgur or flour so that the dough forms a small ball and no longer sticks to your hands. Turn into a bowl and refrigerate, covered, for several hours.\r\nForm the chickpea mixture into balls about the size of walnuts, or use a falafel scoop, available in Middle-Eastern markets.\r\nHeat 3 inches of oil to 375ºF in a deep pot or wok and fry 1 ball to test. If it falls apart, add a little flour. Then fry about 6 balls at once for a few minutes on each side, or until golden brown. Drain on paper towels. Stuff half a pita with falafel balls, chopped tomatoes, onion, green pepper, and pickled turnips. Drizzle with tahina thinned with water.', 1, 'FILE_59424e99c7fac', '2017-06-15 09:08:41', 1, 1),
(148, 'Bulgur Vegetarian Chili', '- 1 medium yellow onion - 2 cloves garlic - 1 jalapeno seeds removed if desired - 1 tablespoon olive oil - 1 medium parsnip roughly 1/4 pound - 1/2 cup bulgur - 1 tablespoon chili powder - 2 teaspoons oregano - 2 teaspoons cumin - 1/2 teaspoons smoked paprika - Pinch of cloves - Salt to taste - 1 28- ounce can crushed tomatoes - 2 ounces bittersweet chocolate - 2 1/2 to 4 cups vegetable broth - 1 15- ounce can kidney beans drained and rinsed', 'Chop the onion, garlic, and jalapeno into large chunks. Place in a food processor and pulse until everything is minced. Drain any liquid that may have formed.\r\nHeat a stockpot over medium heat. Add olive oil and onion mixture. Cook until onions are fragrant and transparent, 5 to 6 minutes. Meanwhile, chop the parsnip into large chunks and pulse in the food processor until the pieces are the same size as the bulgur. Transfer to the pot with the onions.\r\nStir in the bulgur and spices, cooking for 1 to 2 minutes or until you can smell the spices. Add in the tomatoes, chocolate, and 2 1/2 cups of the vegetable broth. Bring to a boil, reduce to a simmer, cover, and let cook for 10 minutes.\r\nAfter 10 minutes, stir in the kidney beans, cover, and cook for another 10 to 15 minutes. Bulgur should be tender. Taste and adjust the salt/seasoning. Add more vegetable broth if a thinner consistency is desired.', 1, 'FILE_59462ef628d69', '2017-06-18 07:42:46', 1, 2),
(150, 'Cheesecake', '- 1 cup (120 g) raw cashews - 1 cup (240 g) coconut cream - 8 ounces (227 g) vegan cream cheese - 1 Tbsp (7 g) arrowroot or cornstarch - 1 tsp pure vanilla extract - 2/3 cup (160 ml) maple syrup, plus more to taste - 1 Tbsp (15 ml) melted coconut oil (for extra creaminess) - 2 tsp lemon zest - 1-2 Tbsp (15-30 ml) lemon juice, plus more to taste - 1/8th tsp sea salt - 3/4 cup (67 g) gluten-free rolled oats - 3/4 cup (84 g) raw almonds - 1/4 tsp sea salt - 2 Tbsp (24 g) coconut sugar, plus more to taste - 4 Tbsp (60 g) coconut oil, melted', 'Add raw cashews to a mixing bowl and cover with boiling hot water. Let rest for 1 hour (uncovered). Then drain thoroughly.\r\nIn the meantime, preheat oven to 350 degrees F (176 C) and line a standard loaf pan (or 8x8 inch baking dish) with parchment paper. Set aside.\r\nAdd oats, almonds, sea salt, and coconut sugar to a high speed blender and mix on high until a fine meal is achieved.\r\nRemove lid and add melted coconut oil, starting with 4 Tbsp (60 g) and adding more if it&#39;s too dry. Pulse/mix on low until a loose dough is formed, scraping down sides as needed. You should be able to squeeze the mixture between two fingers and form a dough instead of it crumbling. If too dry, add a bit more melted coconut oil.\r\nTransfer mixture to parchment-lined loaf pan and spread evenly to distribute. Then place parchment paper on top and use a flat-bottomed object, like a drinking glass, to press down firmly until it’s evenly distributed and well packed. Let it come up the sides a little, otherwise it can be too thick on the bottom.\r\nBake for 15 minutes, then increase heat to 375 F (190 C) and bake for 5-10 minutes more, or until the edges are golden brown and there is some browning on the surface. Remove from oven to cool slightly, then reduce oven heat to 325 degrees F (162 C).\r\nOnce cashews are soaked and drained, add to a high speed blender with coconut cream, vegan cream cheese, arrowroot starch, vanilla, maple syrup, coconut oil, lemon zest, lemon juice, and sea salt. Blend on high until very creamy and smooth, scraping down sides as needed.\r\nTaste and adjust flavor as needed, adding more lemon juice for acidity, lemon zest for tartness, salt for flavor balance, and maple syrup for sweetness.\r\nPour filling over the pre-baked crust and spread into an even layer. Tap on counter to remove air bubbles.\r\nBake for 50 minutes to 1 hour, until the edges look very slightly dry and the center appears only slightly “jiggly” but not liquidy. When you shake it, it will have some give to it, but it shouldn&#39;t all look liquid - only the center should jiggle.\r\nLet rest for 10 minutes at room temperature, then transfer to refrigerator to let cool completely (uncovered). Once cooled, cover (waiting until cool will prevent condensation) and continue refrigerating for a total of 5-6 hours, preferably overnight.\r\nTo serve, lift out of pan with parchment paper and cut into bars or triangles. (I carefully cut off the very end pieces because they weren&#39;t as creamy as the center pieces.)\r\nEnjoy as is or with coconut whipped cream and fresh berries. Store leftovers in the refrigerator, covered, up to 3-4 days, though best within the first 2 days.', 0, 'FILE_59463c989b4e6', '2017-06-18 08:40:56', 1, 3);

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
  PRIMARY KEY (`IdUtilisateur`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`IdUtilisateur`, `Username`, `Password`, `IsAdmin`) VALUES
(1, 'Admin', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 1),
(2, 'User', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 0);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`IdUtilisateur`) REFERENCES `utilisateurs` (`IdUtilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`IdRecette`) REFERENCES `recettes` (`IdRecette`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`IdUtilisateur`) REFERENCES `utilisateurs` (`IdUtilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`IdRecette`) REFERENCES `recettes` (`IdRecette`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `recettes`
--
ALTER TABLE `recettes`
  ADD CONSTRAINT `recettes_ibfk_2` FOREIGN KEY (`IdType`) REFERENCES `types` (`IdType`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `recettes_ibfk_3` FOREIGN KEY (`IdUtilisateur`) REFERENCES `utilisateurs` (`IdUtilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE;

  
GRANT USAGE ON *.* TO 'UserRecipes'@'127.0.0.1' IDENTIFIED BY PASSWORD '*A251DC97D72B0A694D2BEA7F929E06111B5E2026';
GRANT ALL PRIVILEGES ON `myrecipes`.* TO 'UserRecipes'@'127.0.0.1';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
