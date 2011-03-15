DROP TABLE IF EXISTS raid_loot;
CREATE TABLE raid_loot (`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT, raid VARCHAR(30) NOT NULL, category VARCHAR(20) NOT NULL, lowid INT NOT NULL, highid INT NOT NULL, ql INT NOT NULL, name VARCHAR(255) NOT NULL, imageid INT NOT NULL, multiloot NOT NULL);

-- Vortexx
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'General', 277436, 277436, 300, 'Base NCU - Type 00 (0/6)', 276942, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'General', 281157, 281157, 300, 'Nanodeck Activation Device', 280784, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'General', 279447, 279447, 1, 'Multi Colored Xan Belt Tuning Device', 280987, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'General', 279446, 279446, 1, 'Green Xan Belt Tuning Device', 280988, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'General', 280786, 280786, 300, 'Xan Weapon Upgrade Device', 246391, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'General', 279440, 279440, 1, 'Xan Defense Merit Board Base', 279443, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'General', 279439, 279439, 1, 'Xan Combat Merit Board Base', 279442, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 278902, 278902, 300, 'Xan Waist Symbiant, Artillery Unit Beta', 215193, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279019, 279019, 300, 'Xan Waist Symbiant, Control Unit Beta', 215193, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279032, 279032, 300, 'Xan Waist Symbiant, Extermination Unit Beta', 215193, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279045, 279045, 300, 'Xan Waist Symbiant, Infantry Unit Beta', 215193, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279058, 279058, 300, 'Xan Waist Symbiant, Support Unit Beta', 215193, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 278896, 278896, 300, 'Xan Left Arm Symbiant, Artillery Unit Beta', 215179, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279013, 279013, 300, 'Xan Left Arm Symbiant, Control Unit Beta', 215179, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279026, 279026, 300, 'Xan Left Arm Symbiant, Extermination Unit Beta', 215179, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279039, 279039, 300, 'Xan Left Arm Symbiant, Infantry Unit Beta', 215179, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279052, 279052, 300, 'Xan Left Arm Symbiant, Support Unit Beta', 215179, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 278901, 278901, 300, 'Xan Right Wrist Symbiant, Artillery Unit Beta', 215170, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279018, 279018, 300, 'Xan Right Wrist Symbiant, Control Unit Beta', 215170, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279031, 279031, 300, 'Xan Right Wrist Symbiant, Extermination Unit Beta', 215170, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279044, 279044, 300, 'Xan Right Wrist Symbiant, Infantry Unit Beta', 215170, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279057, 279057, 300, 'Xan Right Wrist Symbiant, Support Unit Beta', 215170, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 278893, 278893, 300, 'Xan Ocular Symbiant, Artillery Unit Beta', 230980, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279010, 279010, 300, 'Xan Ocular Symbiant, Control Unit Beta', 230980, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279023, 279023, 300, 'Xan Ocular Symbiant, Extermination Unit Beta', 230980, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279036, 279036, 300, 'Xan Ocular Symbiant, Infantry Unit Beta', 230980, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Symbiants', 279049, 279049, 300, 'Xan Ocular Symbiant, Support Unit Beta', 230980, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279093, 279093, 250, 'Xan Spirit of Right Wrist Offence - Beta', 231006, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279094, 279094, 250, 'Xan Spirit of Right Wrist Weakness - Beta', 231006, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279079, 279079, 250, 'Xan Left Limb Spirit of Essence - Beta', 230995, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279080, 279080, 250, 'Xan Left Limb Spirit of Strength - Beta', 230995, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279081, 279081, 250, 'Xan Left Limb Spirit of Understanding - Beta', 230995, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279082, 279082, 250, 'Xan Left Limb Spirit of Weakness - Beta', 230995, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279095, 279095, 250, 'Xan Midriff Spirit of Essence - Beta', 230976, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279096, 279096, 250, 'Xan Midriff Spirit of Knowledge - Beta', 230976, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279097, 279097, 250, 'Xan Midriff Spirit of Strength - Beta', 230976, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279098, 279098, 250, 'Xan Midriff Spirit of Weakness - Beta', 230976, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279173, 279173, 250, 'Xan Spirit of Essence - Beta', 230988, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Vortexx', 'Spirits', 279071, 279071, 250, 'Xan Spirit of Discerning Weakness - Beta', 230988, 1);

-- Mitaar
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'General', 277436, 277436, 300, 'Base NCU - Type 00 (0/6)', 276942, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'General', 281157, 281157, 300, 'Nanodeck Activation Device', 280784, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'General', 279447, 279447, 1, 'Multi Colored Xan Belt Tuning Device', 280987, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'General', 279446, 279446, 1, 'Green Xan Belt Tuning Device', 280988, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'General', 280786, 280786, 300, 'Xan Weapon Upgrade Device', 246391, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'General', 279440, 279440, 1, 'Xan Defense Merit Board Base', 279443, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'General', 279439, 279439, 1, 'Xan Combat Merit Board Base', 279442, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 278892, 278892, 300, 'Xan Brain Symbiant, Artillery Unit Beta', 215189, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279009, 279009, 300, 'Xan Brain Symbiant, Control Unit Beta', 215189, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279022, 279022, 300, 'Xan Brain Symbiant, Extermination Unit Beta', 215189, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279035, 279035, 300, 'Xan Brain Symbiant, Infantry Unit Beta', 215189, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279048, 279048, 300, 'Xan Brain Symbiant, Support Unit Beta', 215189, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 278895, 278895, 300, 'Xan Chest Symbiant, Artillery Unit Beta', 215181, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279012, 279012, 300, 'Xan Chest Symbiant, Control Unit Beta', 215181, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279025, 279025, 300, 'Xan Chest Symbiant, Extermination Unit Beta', 215181, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279038, 279038, 300, 'Xan Chest Symbiant, Infantry Unit Beta', 215181, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279051, 279051, 300, 'Xan Chest Symbiant, Support Unit Beta', 215181, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 278897, 278897, 300, 'Xan Left Hand Symbiant, Artillery Unit Beta', 215171, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279014, 279014, 300, 'Xan Left Hand Symbiant, Control Unit Beta', 215171, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279027, 279027, 300, 'Xan Left Hand Symbiant, Extermination Unit Beta', 215171, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279040, 279040, 300, 'Xan Left Hand Symbiant, Infantry Unit Beta', 215171, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279053, 279053, 300, 'Xan Left Hand Symbiant, Support Unit Beta', 215171, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 278898, 278898, 300, 'Xan Left Wrist Symbiant, Artillery Unit Beta', 215198, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279015, 279015, 300, 'Xan Left Wrist Symbiant, Control Unit Beta', 215198, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279028, 279028, 300, 'Xan Left Wrist Symbiant, Extermination Unit Beta', 215198, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279041, 279041, 300, 'Xan Left Wrist Symbiant, Infantry Unit Beta', 215198, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Symbiants', 279054, 279054, 300, 'Xan Left Wrist Symbiant, Support Unit Beta', 215198, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279321, 279321, 250, 'Xan Brain Spirit of Computer Skill - Beta', 230992, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279068, 279068, 250, 'Xan Brain Spirit of Offence - Beta', 230992, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279069, 279069, 250, 'Xan Essence Brain Spirit - Beta', 230992, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279083, 279083, 250, 'Xan Left Hand Spirit of Defence - Beta', 230994, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279084, 279084, 250, 'Xan Left Hand Spirit of Strength - Beta', 230994, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279085, 279085, 250, 'Xan Spirit of Left Wrist Defense - Beta', 231000, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279086, 279086, 250, 'Xan Spirit of Left Wrist Strength - Beta', 231000, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279075, 279075, 250, 'Xan Heart Spirit of Essence - Beta', 230984, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279076, 279076, 250, 'Xan Heart Spirit of Knowledge - Beta', 230984, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279077, 279077, 250, 'Xan Heart Spirit of Strength - Beta', 230984, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279078, 279078, 250, 'Xan Heart Spirit of Weakness - Beta', 230984, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Mitaar', 'Spirits', 279070, 279070, 250, 'Xan Spirit of Clear Thought - Beta', 230992, 1);

-- 12 man
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'General', 281095, 281095, 1, 'Unknown Mixture', 281093, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'General', 281098, 281098, 1, 'A piece of cloth', 281096, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'General', 277436, 277436, 300, 'Base NCU - Type 00 (0/6)', 276942, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'General', 281157, 281157, 300, 'Nanodeck Activation Device', 280784, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'General', 279447, 279447, 1, 'Multi Colored Xan Belt Tuning Device', 280987, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'General', 279446, 279446, 1, 'Green Xan Belt Tuning Device', 280988, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'General', 280786, 280786, 300, 'Xan Weapon Upgrade Device', 246391, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 278899, 278899, 300, 'Xan Right Arm Symbiant, Artillery Unit Beta', 215176, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279016, 279016, 300, 'Xan Right Arm Symbiant, Control Unit Beta', 215176, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279029, 279029, 300, 'Xan Right Arm Symbiant, Extermination Unit Beta', 215176, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279042, 279042, 300, 'Xan Right Arm Symbiant, Infantry Unit Beta', 215176, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279055, 279055, 300, 'Xan Right Arm Symbiant, Support Unit Beta', 215176, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 278900, 278900, 300, 'Xan Right Hand Symbiant, Artillery Unit Beta', 215173, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279017, 279017, 300, 'Xan Right Hand Symbiant, Control Unit Beta', 215173, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279030, 279030, 300, 'Xan Right Hand Symbiant, Extermination Unit Beta', 215173, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279043, 279043, 300, 'Xan Right Hand Symbiant, Infantry Unit Beta', 215173, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279056, 279056, 300, 'Xan Right Hand Symbiant, Support Unit Beta', 215173, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 278904, 278904, 300, 'Xan Feet Symbiant, Artillery Unit Beta', 215184, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279021, 279021, 300, 'Xan Feet Symbiant, Control Unit Beta', 215184, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279034, 279034, 300, 'Xan Feet Symbiant, Extermination Unit Beta', 215184, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279047, 279047, 300, 'Xan Feet Symbiant, Infantry Unit Beta', 215184, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Symbiants', 279060, 279060, 300, 'Xan Feet Symbiant, Support Unit Beta', 215184, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279087, 279087, 250, 'Xan Right Limb Spirit of Essence - Beta', 231004, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279088, 279088, 250, 'Xan Right Limb Spirit of Strength - Beta', 231004, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279089, 279089, 250, 'Xan Right Limb Spirit of Weakness - Beta', 231004, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279090, 279090, 250, 'Xan Right Hand Defensive Spirit - Beta', 231002, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279091, 279091, 250, 'Xan Right Hand Strength Spirit - Beta', 231002, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279092, 279092, 250, 'Xan Spirit of Insight - Right Hand - Beta', 231002, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279101, 279101, 250, 'Xan Spirit of Feet Defense - Beta', 230990, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279102, 279102, 250, 'Xan Spirit of Feet Strength - Beta', 230990, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279099, 279099, 250, 'Xan Spirit of Defense - Beta', 230998, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279100, 279100, 250, 'Xan Spirit of Essence - Beta', 230998, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279072, 279072, 250, 'Xan Spirit of Essence Whispered - Beta', 230986, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279073, 279073, 250, 'Xan Spirit of Knowledge Whispered - Beta', 230986, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Spirits', 279074, 279074, 250, 'Xan Spirit of Strength Whispered - Beta', 230986, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281213, 281213, 1, 'Brute''s Gem (Enf)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281214, 281214, 1, 'Builder''s Gem (Engi)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281211, 281211, 1, 'Dictator''s Gem (Crat)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281210, 281210, 1, 'Explorer''s Gem (Adv)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281215, 281215, 1, 'Hacker''s Gem (Fix)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281212, 281212, 1, 'Healer''s Gem (Doc)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281217, 281217, 1, 'Master''s Gem (MA)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281222, 281222, 1, 'Merchant''s Gem (Trader)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281216, 281216, 1, 'Protecter''s Gem (Keeper)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281209, 281209, 1, 'Sniper''s Gem (Agent)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281220, 281220, 1, 'Spirit''s Gem (Shade)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281219, 281219, 1, 'Techno Wizard''s Gem (NT)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281221, 281221, 1, 'Warrior''s Gem (Soldier)', 281224, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('12 Man', 'Profession Gems', 281218, 281218, 1, 'Worshipper''s Gem (MP)', 281224, 1);

-- APF
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 13', 275909, 275909, 1, 'Gelatinous Lump', 275962, 3);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 13', 275916, 275916, 1, 'Biotech Matrix', 275972, 3);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 13', 257960, 257960, 250, 'Action Probability Estimator', 203502, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 13', 257962, 257962, 250, 'Dynamic Gas Redistribution Valves', 205508, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 13', 257533, 257533, 1, 'All Bounties', 218758, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 13', 257968, 257968, 1, 'All ICE', 257196, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 13', 257706, 257706, 1, 'Kyr''Ozch Helmet (2500 Token board)', 230855, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 28', 275912, 275912, 1, 'Crystaline Matrix', 275964, 3);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 28', 275914, 275914, 1, 'Kyr''Ozch Circuitry', 275966, 3);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 28', 257959, 257959, 250, 'Inertial Adjustment Processing Unit', 11618, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 28', 257963, 257963, 250, 'Notum Amplification Coil', 257195, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 28', 257533, 257533, 1, 'All Bounties', 218758, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 28', 257968, 257968, 1, 'All ICE', 257196, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 28', 257706, 257706, 1, 'Kyr''Ozch Helmet (2500 Token board)', 230855, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 35', 275918, 275918, 1, 'Alpha Program Chip', 275970, 3);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 35', 275919, 275919, 1, 'Beta Program Chip', 275969, 3);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 35', 275906, 275906, 1, 'Odd Kyr''Ozch Nanobots', 11750, 3);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 35', 275907, 275907, 1, 'Kyr''Ozch Processing Unit', 275960, 3);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 35', 257961, 257961, 250, 'Energy Redistribution Unit', 257197, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 35', 257964, 257964, 250, 'Visible Light Remodulation Device', 235270, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 35', 257533, 257533, 1, 'All Bounties', 218758, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 35', 257968, 257968, 1, 'All ICE', 257196, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('APF', 'Sector 35', 257706, 257706, 1, 'Kyr''Ozch Helmet (2500 Token board)', 230855, 1);

-- Albtraum
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Crystals & Crystalised Memories', 267737, 267737, 250, 'Inert Knowledge Crystal', 151030, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Crystals & Crystalised Memories', 267749, 267749, 250, 'Energy Infused Crystal', 156567, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Crystals & Crystalised Memories', 267704, 267704, 250, 'Crystalised Memories of a Sniper', 72771, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Crystals & Crystalised Memories', 267711, 267711, 250, 'Crystalised Memories of a Defender', 72771, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Crystals & Crystalised Memories', 267698, 267698, 250, 'Crystalised Memories of a Technician', 72770, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Crystals & Crystalised Memories', 267701, 267701, 250, 'Crystalised Memories of a Mechanic', 72771, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Crystals & Crystalised Memories', 267728, 267728, 250, 'Crystalised Memories of a Surgeon', 72771, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Crystals & Crystalised Memories', 267714, 267714, 250, 'Crystalised Memories of a Engineer', 72771, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Crystals & Crystalised Memories', 267710, 267710, 250, 'Crystalised Memories of an Instructor', 72771, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Crystals & Crystalised Memories', 267726, 267726, 250, 'Crystalised Memories of a Doctor', 72771, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Crystals & Crystalised Memories', 267697, 267697, 250, 'Crystalised Memories of a Warrior', 72771, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Samples', 267744, 267744, 250, 'Radioactive Gland Sample', 144705, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Samples', 267745, 267745, 250, 'Venom Gland Sample', 253010, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Samples', 267742, 267742, 250, 'Frost Gland Sample', 144702, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Samples', 267743, 267743, 250, 'Acid Gland Sample', 144703, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Samples', 267741, 267741, 250, 'Fire Gland Sample', 144705, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Pocket Boss Crystals', 267892, 267892, 250, 'Xan Essence Crystal - Summoned Terror', 235354, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Pocket Boss Crystals', 267904, 267904, 250, 'Xan Essence Crystal - Gruesome Misery', 235354, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Pocket Boss Crystals', 267880, 267880, 250, 'Xan Essence Crystal - Sister Pestilence', 235354, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Pocket Boss Crystals', 267799, 267799, 250, 'Xan Essence Crystal - Sister Merciless', 235354, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Pocket Boss Crystals', 267800, 267800, 250, 'Xan Essence Crystal - Divided Loyalty', 235354, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Rings and Preservation Units', 267625, 267625, 250, 'Ancient Speed Preservation Unit', 218753, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Rings and Preservation Units', 267626, 267626, 250, 'Ancient Vision Preservation Unit', 218752, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Rings and Preservation Units', 267905, 267905, 250, 'Ring of Divided Loyalty', 84067, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Rings and Preservation Units', 267906, 267906, 250, 'Ring of Gruesome Misery', 84067, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Rings and Preservation Units', 267909, 267909, 250, 'Ring of Sister Pestilence', 84067, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Rings and Preservation Units', 267907, 267907, 250, 'Ring of Sister Merciless', 84067, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Rings and Preservation Units', 267911, 267911, 250, 'Ring of Summoned Terror', 84067, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Ancients', 267794, 267794, 250, 'Dormant Ancient Circuit', 158233, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Ancients', 267709, 267709, 250, 'Empty Ancient Device', 218753, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Ancients', 267746, 267746, 250, 'Inactive Ancient Bracer', 84048, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Ancients', 267677, 267677, 250, 'Ancient Scrap of Spirit Knowledge', 163575, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Ancients', 267679, 267679, 250, 'Ancient Scrap of Saturated Spirit Knowledge', 163575, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Ancients', 267725, 267725, 250, 'Ancient Damage Generation Device', 218770, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Ancients', 267735, 267735, 250, 'Inactive Ancient Medical Device', 218774, 1);
INSERT INTO raid_loot (raid, category, lowid, highid, ql, name, imageid, multiloot) VALUES ('Albtraum', 'Ancients', 267748, 267748, 250, 'Inactive Ancient Engineering Device', 156094, 1);