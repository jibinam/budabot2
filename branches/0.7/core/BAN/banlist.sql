CREATE TABLE IF NOT EXISTS banlist_<myname> (name VARCHAR(25) NOT NULL PRIMARY KEY, banned_by VARCHAR(25), time VARCHAR(10), reason TEXT NOT NULL, banend INT);