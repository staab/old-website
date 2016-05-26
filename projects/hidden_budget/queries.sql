CREATE TABLE `items` (
	`ItemId` INT(10) NOT NULL AUTO_INCREMENT,
	`Amount` FLOAT NOT NULL DEFAULT '0',
	`Category` VARCHAR(50) NOT NULL DEFAULT 'Uncategoized',
	`Note` TEXT NULL DEFAULT NULL,
	`Date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`ItemId`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;
SELECT `DEFAULT_COLLATION_NAME` FROM `information_schema`.`SCHEMATA` WHERE `SCHEMA_NAME`='budet';

CREATE TABLE `item_categories` (
	`CategoryId` INT(10) NOT NULL AUTO_INCREMENT,
	`Name` VARCHAR(50) NULL,
	PRIMARY KEY (`CategoryId`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

ALTER TABLE `items`
	CHANGE COLUMN `Category` `CategoryId` INT(50) NOT NULL DEFAULT '0' AFTER `Amount`;