DROP TABLE IF EXISTS leprocs;
CREATE TABLE leprocs ( profession varchar(20) NOT NULL, name varchar(50) NOT NULL, itemid INT NOT NULL, research_name VARCHAR(50), research_lvl INT NOT NULL, proc_type CHAR(6), chance VARCHAR(20), modifiers VARCHAR(255) NOT NULL, duration varchar(20) NOT NULL, description VARCHAR(255) NOT NULL);
INSERT INTO leprocs (profession, name, itemid, research_name, research_lvl, proc_type, chance, modifiers, duration, description) VALUES ('Bureaucrat', 'Inflation Adjustment', 263437, 'Process Theory', 1, 'Type 2', '5%', 'User Modify Nano attack damage modifier 50', '7s', 'In combat you will occasionally cause extra damage. Only one type 2 action can be selected at a time');
INSERT INTO leprocs (profession, name, itemid, research_name, research_lvl, proc_type, chance, modifiers, duration, description) VALUES ('Bureaucrat', 'Papercut', 263449, 'Market Awareness', 1, 'Type 2', '5%', 'Attacker Hit Health Cold -10 .. -23', '0s', 'In combat you will occasionally root your opponent. Only one type 1 action can be selected at a time');
INSERT INTO leprocs (profession, name, itemid, research_name, research_lvl, proc_type, chance, modifiers, duration, description) VALUES ('Bureaucrat', 'Social Services', 263460, 'Hostile Negotiations', 5, 'Type 2', '5%', 'Target Restrict Action Movement', '8s', 'In combat you will occasionally root your opponent.  Only one type 2 action can be selected at a time');
INSERT INTO leprocs (profession, name, itemid, research_name, research_lvl, proc_type, chance, modifiers, duration, description) VALUES ('Bureaucrat', 'Lost Paperwork', 263444, 'Professional Development', 4, 'Type 1', '5%', 'Attacker Hit Health Melee -264 .. -532', '0s', 'In combat you will occasionally cause extra damage. Only one type 1 action can be selected at a time');
INSERT INTO leprocs (profession, name, itemid, research_name, research_lvl, proc_type, chance, modifiers, duration, description) VALUES ('Bureaucrat', 'Next Window Over', 263464, 'Professional Development', 3, 'Type 2', '5%', 'User Modify Nano delta 30', '60s', 'In combat you will occasionally receive a bonus to your natural nano bot renewal. Only one type 2 action can be selected at a time');
INSERT INTO leprocs (profession, name, itemid, research_name, research_lvl, proc_type, chance, modifiers, duration, description) VALUES ('Bureaucrat', 'Deflation', 263440, 'Executive Decisions', 3, 'Type 2', '5%', 'User Modify Nano attack damage modifier 100', '7s', 'In combat you will occasionally increase your ability to do nanobot damage. Only one type 2 action can be selected at a time');
INSERT INTO leprocs (profession, name, itemid, research_name, research_lvl, proc_type, chance, modifiers, duration, description) VALUES ('Bureaucrat', 'Wait In That Queue', 263428, 'Process Theory', 2, 'Type 1', '5%', 'Target Restrict Action Movement', '5s', 'In combat you will occasionally root your opponent. Only one type 1 action can be selected at a time');
INSERT INTO leprocs (profession, name, itemid, research_name, research_lvl, proc_type, chance, modifiers, duration, description) VALUES ('Bureaucrat', 'Forms in Triplicate', 263426, 'Human Resources', 6, 'Type 1', '10%', 'User Modify Nano delta 60', '60s', 'In combat when struck by an opponent you will occassionally receive a bonus to your natural nano bot renewal. This is a Type 1 action ');

INSERT INTO leprocs (profession, name, itemid, research_name, research_lvl, proc_type, chance, modifiers, duration, description) VALUES ('Keeper', 'Righteous Strike', 266108, 'Wisdom', 1, 'Type 2', '5%', 'Self Modify [All dmg Types] damage modifier 2', '60s', 'In combat you will occasionally increase your damage output. Only one type 2 action can be selected at a time');
INSERT INTO leprocs (profession, name, itemid, research_name, research_lvl, proc_type, chance, modifiers, duration, description) VALUES ('Keeper', 'Faithful Reconstruction', 266156, 'Virtue', 1, 'Type 2', '5%', 'Team Hit Health 21 .. 42', '0s', 'In combat you will occasionally heal your team, including yourself. Only one type 2 action can be selected at a time');
INSERT INTO leprocs (profession, name, itemid, research_name, research_lvl, proc_type, chance, modifiers, duration, description) VALUES ('Keeper', 'Eschew the Faithless', 266147, 'Wisdom', 2, 'Type 1', '5%', 'User Modify Duck explosives 14 Dodge ranged 14 Evade close 50', '60s', 'In combat you will occasionally increase your evade skill. Only one type 1 action can be selected at a time');

