-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
-- -----------------------------------------------------
-- Schema new_schema1
-- -----------------------------------------------------
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`droit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`droit` (
  `id` INT UNSIGNED NULL AUTO_INCREMENT,
  `lenom` VARCHAR(45) NULL,
  `laperm` SMALLINT(80) UNSIGNED NULL DEFAULT 2,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`utilisateur`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`utilisateur` (
  `id` INT UNSIGNED NULL AUTO_INCREMENT,
  `lelogin` VARCHAR(100) NULL,
  `lepass` VARCHAR(45) NULL,
  `lemail` VARCHAR(150) NULL,
  `lenom` VARCHAR(80) NULL,
  `valide` TINYINT(80) UNSIGNED NULL DEFAULT 1,
  `droit_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `lelogin_UNIQUE` (`lelogin` ASC),
  INDEX `fk_utilisateur_droit_idx` (`droit_id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`photo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`photo` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lenom` VARCHAR(50) NULL,
  `lextension` CHAR(3) NULL,
  `lepoids` INT UNSIGNED NULL,
  `lalargeur` INT UNSIGNED NULL,
  `lahauteur` INT UNSIGNED NULL,
  `letitre` VARCHAR(60) NULL,
  `ladesc` VARCHAR(500) NULL,
  `affiche` SMALLINT(80) UNSIGNED NULL DEFAULT 2,
  `utilisateur_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `lenom_UNIQUE` (`lenom` ASC),
  INDEX `fk_photo_utilisateur1_idx` (`utilisateur_id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`rubriques`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`rubriques` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lintitule` VARCHAR(60) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`photo_has_rubriques`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`photo_has_rubriques` (
  `photo_id` INT UNSIGNED NOT NULL,
  `rubriques_id` INT UNSIGNED NOT NULL,
  INDEX `fk_photo_has_rubriques_rubriques1_idx` (`rubriques_id` ASC),
  INDEX `fk_photo_has_rubriques_photo1_idx` (`photo_id` ASC))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
