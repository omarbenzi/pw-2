
INSERT INTO `mydb`.`image`
(`idimage`, `lien`) VALUES
(NULL, 'lientTest1'),
(NULL, 'lientTest2');

INSERT INTO `mydb`.`ville`
(`idVille`, `nomVille`, `codePostale`) VALUES
(NULL, 'Montreal', 'H2S3W2');



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