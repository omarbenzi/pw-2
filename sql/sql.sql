-- MySQL Script generated by MySQL Workbench
-- Sun Mar  8 15:34:55 2020
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- DATABASE mydb
-- -----------------------------------------------------
DROP DATABASE IF EXISTS `mydb` ;

-- -----------------------------------------------------
-- DATABASE mydb
-- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`ville`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`ville` ;

CREATE TABLE IF NOT EXISTS `mydb`.`ville` (
  `idVille` INT NOT NULL AUTO_INCREMENT,
  `nomVille` VARCHAR(255) NULL,
  `codePostale` VARCHAR(45) NULL,
  PRIMARY KEY (`idVille`))
ENGINE = InnoDB;


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
  `telephone` VARCHAR(45) NULL,
  `dateInscription` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  `password` VARCHAR(2000) NULL,
  `admin` INT(1) NULL,
  `ville_idVille` INT NOT NULL,
  `image_idimage` INT NOT NULL,
  PRIMARY KEY (`iduser`),
    FOREIGN KEY (`ville_idVille`)
    REFERENCES `mydb`.`ville` (`idVille`),
    FOREIGN KEY (`image_idimage`)
    REFERENCES `mydb`.`image` (`idimage`)
  )
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
-- Table `mydb`.`sousCategorie`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`sousCategorie` ;

CREATE TABLE IF NOT EXISTS `mydb`.`sousCategorie` (
  `id_sousCategorie` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(45) NULL,
  `id_categorie` INT NOT NULL,
  PRIMARY KEY (`id_sousCategorie`),
    FOREIGN KEY (`id_categorie`)
    REFERENCES `mydb`.`categorie` (`idcategorie`)
    )
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
  `datePublication` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  `user_iduser` INT NOT NULL,
  `sponsorise` INT NULL,
  `sous_categorie_id` INT NOT NULL,
  `image_idimage` INT NOT NULL,
  PRIMARY KEY (`idarticle`, `user_iduser`, `sous_categorie_id`),
    FOREIGN KEY (`user_iduser`)
    REFERENCES `mydb`.`user` (`iduser`),
    FOREIGN KEY (`sous_categorie_id`)
    REFERENCES `mydb`.`sousCategorie` (`id_sousCategorie`),
    FOREIGN KEY (`image_idimage`)
    REFERENCES `mydb`.`image` (`idimage`)
)
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
    FOREIGN KEY (`user_iduser`)
    REFERENCES `mydb`.`user` (`iduser`),
    FOREIGN KEY (`annonce_idarticle`)
    REFERENCES `mydb`.`annonce` (`idarticle`)
)
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
    FOREIGN KEY (`annonce_idarticle`)
    REFERENCES `mydb`.`annonce` (`idarticle`),
    FOREIGN KEY (`user_iduser`)
    REFERENCES `mydb`.`user` (`iduser`)
    )
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

