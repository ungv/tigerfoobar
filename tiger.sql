SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `tiger` DEFAULT CHARACTER SET latin1 ;
USE `tiger` ;

-- -----------------------------------------------------
-- Table `tiger`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tiger`.`User` ;

CREATE  TABLE IF NOT EXISTS `tiger`.`User` (
  `UserID` INT NOT NULL AUTO_INCREMENT ,
  `Name` VARCHAR(20) NULL ,
  `Email` VARCHAR(45) NULL ,
  `Password` VARCHAR(45) NULL ,
  `Time` TIMESTAMP NULL ,
  PRIMARY KEY (`UserID`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `tiger`.`Company`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tiger`.`Company` ;

CREATE  TABLE IF NOT EXISTS `tiger`.`Company` (
  `CompanyID` INT NOT NULL ,
  `Name` VARCHAR(45) NULL ,
  `Score` FLOAT NULL ,
  `Time` TIMESTAMP NULL ,
  PRIMARY KEY (`CompanyID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tiger`.`CompanyProductService`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tiger`.`CompanyProductService` ;

CREATE  TABLE IF NOT EXISTS `tiger`.`CompanyProductService` (
  `ProductID` INT NOT NULL ,
  `Name` VARCHAR(45) NULL ,
  `Score` FLOAT NULL ,
  `CompanyID` INT NULL ,
  `Time` TIMESTAMP NULL ,
  PRIMARY KEY (`ProductID`) ,
  INDEX `fk_CompanyProductService_Company1_idx` (`CompanyID` ASC) ,
  CONSTRAINT `fk_CompanyProductService_Company1`
    FOREIGN KEY (`CompanyID` )
    REFERENCES `tiger`.`Company` (`CompanyID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tiger`.`Claim`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tiger`.`Claim` ;

CREATE  TABLE IF NOT EXISTS `tiger`.`Claim` (
  `ClaimID` INT NOT NULL ,
  `Title` VARCHAR(45) NULL ,
  `Link` VARCHAR(45) NULL ,
  `Description` VARCHAR(45) NULL ,
  `Score` FLOAT NULL ,
  `UserID` INT NULL ,
  `ProductServiceID` INT NULL ,
  `CompanyID` INT NULL ,
  `Time` TIMESTAMP NULL ,
  PRIMARY KEY (`ClaimID`) ,
  INDEX `fk_Claim_Company1_idx` (`CompanyID` ASC) ,
  INDEX `fk_Claim_CompanyProductService1_idx` (`ProductServiceID` ASC) ,
  INDEX `fk_Claim_User1_idx` (`UserID` ASC) ,
  CONSTRAINT `fk_Claim_Company1`
    FOREIGN KEY (`CompanyID` )
    REFERENCES `tiger`.`Company` (`CompanyID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Claim_CompanyProductService1`
    FOREIGN KEY (`ProductServiceID` )
    REFERENCES `tiger`.`CompanyProductService` (`ProductID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Claim_User1`
    FOREIGN KEY (`UserID` )
    REFERENCES `tiger`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tiger`.`Discussion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tiger`.`Discussion` ;

CREATE  TABLE IF NOT EXISTS `tiger`.`Discussion` (
  `DiscussionID` INT NOT NULL ,
  `UserID` INT NULL ,
  `PostID` INT NULL ,
  `Post` TEXT NULL ,
  `ParentPostID` INT NULL ,
  `Time` TIMESTAMP NULL ,
  PRIMARY KEY (`DiscussionID`) ,
  INDEX `fk_Disscussion_User1_idx` (`UserID` ASC) ,
  CONSTRAINT `fk_Disscussion_Claim1`
    FOREIGN KEY (`DiscussionID` )
    REFERENCES `tiger`.`Claim` (`ClaimID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Disscussion_User1`
    FOREIGN KEY (`UserID` )
    REFERENCES `tiger`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tiger`.`Tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tiger`.`Tags` ;

CREATE  TABLE IF NOT EXISTS `tiger`.`Tags` (
  `TagsID` INT NOT NULL ,
  `Name` VARCHAR(45) NULL ,
  `Type` VARCHAR(45) NULL ,
  `Time` TIMESTAMP NULL ,
  PRIMARY KEY (`TagsID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tiger`.`Tags_has_Claim`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tiger`.`Tags_has_Claim` ;

CREATE  TABLE IF NOT EXISTS `tiger`.`Tags_has_Claim` (
  `Tags_TagsID` INT NOT NULL ,
  `Claim_ClaimID` INT NOT NULL ,
  PRIMARY KEY (`Tags_TagsID`, `Claim_ClaimID`) ,
  INDEX `fk_Tags_has_Claim_Claim1_idx` (`Claim_ClaimID` ASC) ,
  INDEX `fk_Tags_has_Claim_Tags1_idx` (`Tags_TagsID` ASC) ,
  CONSTRAINT `fk_Tags_has_Claim_Tags1`
    FOREIGN KEY (`Tags_TagsID` )
    REFERENCES `tiger`.`Tags` (`TagsID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Tags_has_Claim_Claim1`
    FOREIGN KEY (`Claim_ClaimID` )
    REFERENCES `tiger`.`Claim` (`ClaimID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tiger`.`Claim_has_Tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tiger`.`Claim_has_Tags` ;

CREATE  TABLE IF NOT EXISTS `tiger`.`Claim_has_Tags` (
  `Claim_ClaimID` INT NOT NULL ,
  `Tags_TagsID` INT NOT NULL ,
  `Time` TIMESTAMP NULL ,
  PRIMARY KEY (`Claim_ClaimID`, `Tags_TagsID`) ,
  INDEX `fk_Claim_has_Tags_Tags1_idx` (`Tags_TagsID` ASC) ,
  INDEX `fk_Claim_has_Tags_Claim1_idx` (`Claim_ClaimID` ASC) ,
  CONSTRAINT `fk_Claim_has_Tags_Claim1`
    FOREIGN KEY (`Claim_ClaimID` )
    REFERENCES `tiger`.`Claim` (`ClaimID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Claim_has_Tags_Tags1`
    FOREIGN KEY (`Tags_TagsID` )
    REFERENCES `tiger`.`Tags` (`TagsID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tiger`.`Company_has_Tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tiger`.`Company_has_Tags` ;

CREATE  TABLE IF NOT EXISTS `tiger`.`Company_has_Tags` (
  `Company_CompanyID` INT NOT NULL ,
  `Tags_TagsID` INT NOT NULL ,
  `Time` TIMESTAMP NULL ,
  PRIMARY KEY (`Company_CompanyID`, `Tags_TagsID`) ,
  INDEX `fk_Company_has_Tags_Tags1_idx` (`Tags_TagsID` ASC) ,
  INDEX `fk_Company_has_Tags_Company1_idx` (`Company_CompanyID` ASC) ,
  CONSTRAINT `fk_Company_has_Tags_Company1`
    FOREIGN KEY (`Company_CompanyID` )
    REFERENCES `tiger`.`Company` (`CompanyID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Company_has_Tags_Tags1`
    FOREIGN KEY (`Tags_TagsID` )
    REFERENCES `tiger`.`Tags` (`TagsID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tiger`.`Rating`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tiger`.`Rating` ;

CREATE  TABLE IF NOT EXISTS `tiger`.`Rating` (
  `RatingsID` INT NOT NULL ,
  `Value` INT NULL ,
  `UserID` INT NULL ,
  `ClaimID` INT NULL ,
  `Time` TIMESTAMP NULL ,
  PRIMARY KEY (`RatingsID`) ,
  INDEX `fk_Rating_User1_idx` (`UserID` ASC) ,
  INDEX `fk_Rating_Claim1_idx` (`ClaimID` ASC) ,
  CONSTRAINT `fk_Rating_User1`
    FOREIGN KEY (`UserID` )
    REFERENCES `tiger`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Rating_Claim1`
    FOREIGN KEY (`ClaimID` )
    REFERENCES `tiger`.`Claim` (`ClaimID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tiger`.`Vote`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tiger`.`Vote` ;

CREATE  TABLE IF NOT EXISTS `tiger`.`Vote` (
  `VoteID` INT NOT NULL ,
  `Value` TINYINT(1) NULL ,
  `UserID` INT NULL ,
  `Time` TIMESTAMP NULL ,
  PRIMARY KEY (`VoteID`) ,
  INDEX `fk_Vote_Discussion1_idx` (`UserID` ASC) ,
  CONSTRAINT `fk_Vote_Discussion1`
    FOREIGN KEY (`UserID` )
    REFERENCES `tiger`.`Discussion` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Vote_User1`
    FOREIGN KEY (`UserID` )
    REFERENCES `tiger`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `tiger` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
