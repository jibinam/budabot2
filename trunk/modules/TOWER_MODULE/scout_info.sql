CREATE TABLE IF NOT EXISTS `scout_info` ( `playfield_id` int(11) NOT NULL, `site_number` smallint(6) NOT NULL, `scouted_on` datetime NOT NULL, `scouted_by` VARCHAR(20) NOT NULL, `ct_ql` smallint(6) NOT NULL, `guild_name` VARCHAR(50) NOT NULL, `faction` VARCHAR(7) NOT NULL DEFAULT '', `close_time` int(11) NOT NULL, `is_current` tinyint(1) NOT NULL DEFAULT '1', PRIMARY KEY (`playfield_id`,`site_number`));