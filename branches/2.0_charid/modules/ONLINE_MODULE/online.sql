CREATE TABLE IF NOT EXISTS online (`charid` INT NOT NULL, `afk` VARCHAR(255) DEFAULT '', `channel` CHAR(50), `channel_type` CHAR(10) NOT NULL, `added_by` CHAR(25) NOT NULL, `dt` INT NOT NULL);
DELETE FROM online WHERE `added_by` = '<myname>';