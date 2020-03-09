
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
(NULL, 'MAISON');


INSERT INTO `mydb`.`sous-categorie`
(`idsous-categorie`, `nom`, `id_categorie`) VALUES
(NULL, 'pieces', '1'),
(NULL, 'roue', '1'),
(NULL, 'cuisine', '2'),
(NULL, 'jardin', '2');


INSERT INTO `mydb`.`user` (`iduser`, `nom`, `email`, `telephone`, `dateInscription`, `password`, `admin`, `ville_idVille`, `image_idimage`) VALUES (NULL, 'Ammar', 'omar@gmail.com', '5141515245', NULL, 'passe', '1', '1', '1'), (NULL, 'Otmane', 'otmane@gmail.com', '5147737393', NULL, 'passsssssword', '0', '2', '2');
