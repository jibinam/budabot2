CREATE TABLE IF NOT EXISTS reputation (`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT, `name` text not null, `charid` int not null, `reputation` text not null, `comment` text not null, `by` text not null, `by_charid` int not null, `dt` INT NOT NULL);