-- MySQL Script generated by MySQL Workbench
-- Sun Mar  8 15:34:55 2020
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `mydb` ;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`ville`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`ville` ;

CREATE TABLE IF NOT EXISTS `mydb`.`ville` (
  `idVille` INT NOT NULL AUTO_INCREMENT,
  `nomVille` VARCHAR(255) NULL,
  `codePostale` VARCHAR(45) NULL,
  PRIMARY KEY (`idVille`));


-- -----------------------------------------------------
-- Table `mydb`.`image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`image` ;

CREATE TABLE IF NOT EXISTS `mydb`.`image` (
  `idimage` INT NOT NULL AUTO_INCREMENT ,
  `lien` VARCHAR(45) NULL,
  PRIMARY KEY (`idimage`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`user` ;

CREATE TABLE IF NOT EXISTS `mydb`.`user` (
  `iduser` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  `telephone` INT NULL,
  `dateInscription` TIMESTAMP ,
  `password` VARCHAR(45) NULL,
  `admin` INT NULL,
  `ville_idVille` INT NOT NULL,
  `image_idimage` INT NOT NULL,
  PRIMARY KEY (`iduser`),
  INDEX `fk_user_ville_idx` (`ville_idVille` ASC) ,
  INDEX `fk_user_image1_idx` (`image_idimage` ASC) ,
  CONSTRAINT `fk_user_ville`
    FOREIGN KEY (`ville_idVille`)
    REFERENCES `mydb`.`ville` (`idVille`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_image1`
    FOREIGN KEY (`image_idimage`)
    REFERENCES `mydb`.`image` (`idimage`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`categorie`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`categorie` ;

CREATE TABLE IF NOT EXISTS `mydb`.`categorie` (
  `idcategorie` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(45) NULL,
  PRIMARY KEY (`idcategorie`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`sous-categorie`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`sous-categorie` ;

CREATE TABLE IF NOT EXISTS `mydb`.`sous-categorie` (
  `idsous-categorie` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(45) NULL,
  `id_categorie` INT NOT NULL,
  PRIMARY KEY (`idsous-categorie`),
  INDEX `fk_sous-categorie_categorie1_idx` (`id_categorie` ASC) ,
  CONSTRAINT `fk_sous-categorie_categorie1`
    FOREIGN KEY (`id_categorie`)
    REFERENCES `mydb`.`categorie` (`idcategorie`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`annonce`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`annonce` ;

CREATE TABLE IF NOT EXISTS `mydb`.`annonce` (
  `idarticle` INT NOT NULL AUTO_INCREMENT,
  `titre` VARCHAR(45) NULL,
  `description` VARCHAR(255) NULL,
  `prix` INT NULL,
  `user_iduser` INT NOT NULL,
  `sponsorise` INT NULL,
  `sous-categorie_idsous-categorie` INT NOT NULL,
  `image_idimage` INT NOT NULL,
  PRIMARY KEY (`idarticle`, `user_iduser`, `sous-categorie_idsous-categorie`),
  INDEX `fk_annonce_user1_idx` (`user_iduser` ASC) ,
  INDEX `fk_annonce_sous-categorie1_idx` (`sous-categorie_idsous-categorie` ASC) ,
  INDEX `fk_annonce_image1_idx` (`image_idimage` ASC) ,
  CONSTRAINT `fk_annonce_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `mydb`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_annonce_sous-categorie1`
    FOREIGN KEY (`sous-categorie_idsous-categorie`)
    REFERENCES `mydb`.`sous-categorie` (`idsous-categorie`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_annonce_image1`
    FOREIGN KEY (`image_idimage`)
    REFERENCES `mydb`.`image` (`idimage`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`favoris`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`favoris` ;

CREATE TABLE IF NOT EXISTS `mydb`.`favoris` (
  `idfavoris` INT NOT NULL AUTO_INCREMENT,
  `user_iduser` INT NOT NULL,
  `annonce_idarticle` INT NOT NULL,
  PRIMARY KEY (`idfavoris`, `user_iduser`, `annonce_idarticle`),
  INDEX `fk_favoris_user1_idx` (`user_iduser` ASC) ,
  INDEX `fk_favoris_annonce1_idx` (`annonce_idarticle` ASC) ,
  CONSTRAINT `fk_favoris_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `mydb`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_favoris_annonce1`
    FOREIGN KEY (`annonce_idarticle`)
    REFERENCES `mydb`.`annonce` (`idarticle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`message` ;

CREATE TABLE IF NOT EXISTS `mydb`.`message` (
  `idmessage` INT NOT NULL AUTO_INCREMENT,
  `annonce_idarticle` INT NOT NULL,
  `user_iduser` INT NOT NULL,
  PRIMARY KEY (`idmessage`, `annonce_idarticle`, `user_iduser`),
  INDEX `fk_message_annonce1_idx` (`annonce_idarticle` ASC) ,
  INDEX `fk_message_user1_idx` (`user_iduser` ASC) ,
  CONSTRAINT `fk_message_annonce1`
    FOREIGN KEY (`annonce_idarticle`)
    REFERENCES `mydb`.`annonce` (`idarticle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_message_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `mydb`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `mydb` ;

-- -----------------------------------------------------
-- Placeholder table for view `mydb`.`view1`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`view1` (`id` INT);

-- -----------------------------------------------------
-- View `mydb`.`view1`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`view1`;
DROP VIEW IF EXISTS `mydb`.`view1` ;
USE `mydb`;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;



