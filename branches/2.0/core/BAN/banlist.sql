CREATE TABLE IF NOT EXISTS banlist_<myname> (who INT NOT NULL PRIMARY KEY, banned_by INT, time INT, reason TEXT NOT NULL, banend INT);