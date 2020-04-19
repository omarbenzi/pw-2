
INSERT INTO `mydb`.`image`
(`idimage`, `lien`) VALUES
(NULL, 'lientTest1'),
(NULL, 'lientTest2');

INSERT INTO `mydb`.`ville`
(`idVille`, `nomVille`, `codePostale`) VALUES
(NULL, 'Montreal', 'H2S3W2'),
(NULL, 'LAval', 'H2S3W2');



INSERT INTO `mydb`.`categorie`
(`idcategorie`, `nom`) VALUES
(NULL, 'AUTO'),
(NULL, 'MAISON'),
(NULL, 'outils'),
(NULL, 'moto');


INSERT INTO `mydb`.`sousCategorie`
(`id_sousCategorie`, `nom`, `id_categorie`) VALUES
(NULL, 'pieces', '1'),
(NULL, 'roue', '1'),
(NULL, 'cuisine', '2'),
(NULL, 'roue', '4'),
(NULL, 'moteur', '1'),
(NULL, 'jardin', '2');


INSERT INTO `mydb`.`user` (`iduser`, `nom`, `email`, `telephone`, `dateInscription`, `password`, `admin`, `ville_idVille`, `image_idimage`) VALUES (NULL, 'Ammar', 'omar@gmail.com', '5141515245', NULL, 'passe', '1', '1', '1'), (NULL, 'Otmane', 'otmane@gmail.com', '5147737393', NULL, 'passsssssword', '0', '2', '2');

INSERT INTO `user` (`iduser`, `nom`, `email`, `telephone`, `dateInscription`, `password`, `admin`, `ville_idVille`, `image_idimage`) VALUES (NULL, 'saito', 'saito@gmail.com', '450', CURRENT_TIMESTAMP, '1234', '0', '1', '1'), (NULL, 'ammar', 'ammar@hotmail.com', '450', CURRENT_TIMESTAMP, '1234', '1', '2', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'civic', 'mas asmas pasas lloas knas paslmla', '5000', '1', '1', '1', '1');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'maison', 'mas asmas pasas lloas knas paslmla', '5000', '2', '0', '2', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'honda brz', 'mas asmas pasas lloas knas paslmla', '25000', '1', '0', '5', '1');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'pneu auto', 'mas asmas pasas lloas knas paslmla', '900', '2', '1', '2', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'pneu', 'mas asmas pasas lloas knas paslmla', '30', '1', '0', '3', '2');

INSERT INTO `annonce` (`idarticle`, `titre`, `description`, `prix`, `user_iduser`, `sponsorise`, `sous_categorie_id`, `image_idimage`) VALUES (NULL, 'patio', 'mas asmas pasas lloas knas paslmla', '100', '2', '0', '6', '2');
