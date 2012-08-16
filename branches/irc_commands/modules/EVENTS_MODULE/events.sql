CREATE TABLE IF NOT EXISTS `events` (`id` INTEGER PRIMARY KEY AUTO_INCREMENT, `time_submitted` INT, `submitter_name` VARCHAR(25), `event_name` VARCHAR(25), `event_date` VARCHAR(25), `event_desc` TEXT, `event_attendees` TEXT);