-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema bank_deposit
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `bank_deposit` ;

-- -----------------------------------------------------
-- Schema bank_deposit
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bank_deposit` ;
USE `bank_deposit` ;

-- -----------------------------------------------------
-- Table `bank_deposit`.`banks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bank_deposit`.`banks` ;

CREATE TABLE IF NOT EXISTS `bank_deposit`.`banks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bank_deposit`.`payments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bank_deposit`.`payments` ;

CREATE TABLE IF NOT EXISTS `bank_deposit`.`payments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `reference` INT NOT NULL DEFAULT 0,
  `amount` BIGINT NOT NULL DEFAULT 0,
  `description` VARCHAR(100) NULL,
  `bank_id` INT NULL,
  `customer_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_payments_bank_idx` (`bank_id` ASC),
  CONSTRAINT `fk_payments_bank`
    FOREIGN KEY (`bank_id`)
    REFERENCES `bank_deposit`.`banks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
