CREATE TABLE IF NOT EXISTS `tower_info` ( `playfield_id` int(11) NOT NULL, `site_number` smallint(6) NOT NULL, `x_rally` smallint(6) NOT NULL, `y_rally` smallint(6) NOT NULL, `rally_playfield_id` int(11) NOT NULL, `topic_by` VARCHAR(20) NOT NULL, `topic` varchar(50) NOT NULL, PRIMARY KEY (`playfield_id`,`site_number`));