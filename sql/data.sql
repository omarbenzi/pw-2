
INSERT INTO `image`
(`idimage`, `lien`) VALUES
(NULL, 'lientTest1'),
(NULL, 'lientTest2');

INSERT INTO `ville`
(`idVille`, `nomVille`, `codePostale`) VALUES
(NULL, 'Montreal', 'H2S3W2'),
(NULL, 'LAval', 'H2S3W2');



INSERT INTO `categorie`
(`idcategorie`, `nom`) VALUES
(NULL, 'AUTO'),
(NULL, 'MAISON'),
(NULL, 'outils'),
(NULL, 'moto');


INSERT INTO `sousCategorie`
(`id_sousCategorie`, `nom`, `id_categorie`) VALUES
(NULL, 'pieces', '1'),
(NULL, 'roue', '1'),
(NULL, 'cuisine', '2'),
(NULL, 'roue', '4'),
(NULL, 'moteur', '1'),
(NULL, 'jardin', '2'),
(NULL, 'chambre', '2'),
(NULL, 'vetement', '4'),
(NULL, 'auto', '1'),
(NULL, 'maison', '2'),
(NULL, 'moto', '4');


INSERT INTO `user` (`iduser`, `nom`, `email`, `telephone`, `dateInscription`, `password`, `admin`, `ville_idVille`, `image_idimage`) VALUES (NULL, 'Ammar', 'omar@gmail.com', '5141515245', NULL, 'passe', '1', '1', '1'), (NULL, 'Otmane', 'otmane@gmail.com', '5147737393', NULL, 'passsssssword', '0', '2', '2');

INSERT INTO `user` (`iduser`, `nom`, `email`, `telephone`, `dateInscription`, `password`, `admin`, `ville_idVille`, `image_idimage`) VALUES (NULL, 'saito', 'saito@gmail.com', '450', CURRENT_TIMESTAMP, '1234', '0', '1', '1'), (NULL, 'ammar', 'ammar@hotmail.com', '450', CURRENT_TIMESTAMP, '1234', '1', '2', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'civic', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '5000', '1', '1', '1', '1');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'maison', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '5000', '2', '0', '2', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'honda brz', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '25000', '1', '0', '5', '1');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'velo', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '900', '2', '1', '2', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'pneu auto', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '900', '2', '1', '2', '1');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'chambre a coucher', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '1900', '2', '1', '4', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'bmw', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '9100', '2', '1', '7', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'mercedes', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '100200', '2', '1', '5', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'pneu auto', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '2700', '2', '1', '6', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'pneu', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '30', '1', '0', '4', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, '350z', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '100', '2', '0', '9', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'duplex', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '100', '2', '0', '10', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'bangalo', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '100', '2', '0', '10', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'yamaha1000', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '100', '2', '0', '11', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'cbr250', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will', '100', '2', '0', '11', '2');
