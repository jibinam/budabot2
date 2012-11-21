DROP TABLE IF EXISTS `buffitems`;
CREATE TABLE `buffitems` (item_name VARCHAR(100) NOT NULL, aliases TEXT NOT NULL, category VARCHAR(20) NOT NULL, boosts TEXT NOT NULL, ql_range VARCHAR(255) NOT NULL, acquisition VARCHAR(255) NOT NULL, buff_break_points TEXT NOT NULL);
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('White Sack', '', 'Armor', 'Bio Met', 'QL 5 only', 'Loot from j00 the leet', '5: +10');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Implant Disassembly Clinic', '', 'Utility', 'Treatment, fixer only', '1-200', 'Tradeskilled from portable surgery clinics and Implant disassembly unit from fixer shop', '1: +1\n7: +2\n17: +3\n28: +4\n38: +5\n29: +8\n49: +6\n59: +7\n70: +8\n80: +9\n91: +10\n101: +11\n111: +12\n122: +13\n132: +14\n143: +15\n153: +16\n164: +17\n174: +18\n185: +19\n195: +20');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Treatment And Pharmacy Library', '', 'Utility', 'Treatment, First Aid and Pharma Tech, doctor only', '1-200', 'Tradeskilled from portable surgery clinics and pharma tutoring devices', '1: +3 Treatm., +1 1st aid/pharm.tech.\n5: +4 Treatm., +1 1st aid/pharm.tech.\n11: +5 Treatm., +2 1st aid/pharm.tech.\n17: +6 Treatm., +2 1st aid/pharm.tech.\n23: +7 Treatm., +2 1st aid/pharm.tech.\n29: +8 Treatm., +3 1st aid/pharm.tech.\n35: +9 Treatm., +3 1st aid/pharm.tech.\n41: +10 Treatm., +3 1st aid/pharm.tech.\n47: +11 Treatm., +4 1st aid/pharm.tech.\n53: +12 Treatm., +4 1st aid/pharm.tech.\n59: +13 Treatm., +4 1st aid/pharm.tech.\n65: +14 Treatm., +5 1st aid/pharm.tech.\n71: +15 Treatm., +5 1st aid/pharm.tech.\n77: +16 Treatm., +5 1st aid/pharm.tech.\n83: +17 Treatm., +6 1st aid/pharm.tech.\n89: +18 Treatm., +6 1st aid/pharm.tech.\n95: +19 Treatm., +6 1st aid/pharm.tech.\n101: +20 Treatm., +7 1st aid/pharm.tech.\n107: +21 Treatm., +7 1st aid/pharm.tech.\n113: +22 Treatm., +7 1st aid/pharm.tech.\n119: +23 Treatm., +8 1st aid/pharm.tech.\n125: +24 Treatm., +8 1st aid/pharm.tech.\n131: +25 Treatm., +8 1st aid/pharm.tech.\n137: +26 Treatm., +9 1st aid/pharm.tech.\n143: +27 Treatm., +9 1st aid/pharm.tech.\n149: +28 Treatm., +9 1st aid/pharm.tech.\n155: +29 Treatm., +10 1st aid/pharm.tech.\n161: +30 Treatm., +10 1st aid/pharm.tech.\n167: +31 Treatm., +10 1st aid/pharm.tech.\n173: +32 Treatm., +11 1st aid/pharm.tech.\n179: +33 Treatm., +11 1st aid/pharm.tech.\n185: +34 Treatm., +11 1st aid/pharm.tech.\n191: +35 Treatm., +12 1st aid/pharm.tech.\n197: +36 Treatm., +12 1st aid/pharm.tech.');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('(normal) Treatment Library', '', 'Utility', 'Treatment and First Aid', '1-200', 'Tradeskilled from portable surgery clinics and pharma tutoring devices', '1: +2 Treatm., +1 1st aid\n8: +3 Treatm., +1 1st aid\n11: +3 Treatm., +2 1st aid\n20: +4 Treatm., +2 1st aid\n29: +4 Treatm., +3 1st aid\n33: +5 Treatm., +3 1st aid\n45: +6 Treatm., +3 1st aid\n47: +6 Treatm., +4 1st aid\n57: +7 Treatm., +4 1st aid\b65: +7 Treatm., +5 1st aid\n70: +8 Treatm., +5 1st aid\n82: +9 Treatm., +5 1st aid\n83: +9 Treatm., +6 1st aid\n95: +10 Treatm., +6 1st aid\n101: +10 Treatm., +7 1st aid\n107: +11 Treatm., +7 1st aid\n119: +11 Treatm., +8 1st aid\n120: +12 Treatm., +8 1st aid\n132: +13 Treatm., +8 1st aid\n137: +13 Treatm., +9 1st aid\n145: +14 Treatm., +9 1st aid\n155: +14 Treatm., +10 1st aid\n157: +15 Treatm., +10 1st aid\n169: +16 Treatm., +10 1st aid\n173: +16 Treatm., +11 1st aid\n182: +17 Treatm., +11 1st aid\n191: +17 Treatm., +12 1st aid\n194: +18 Treatm., +12 1st aid');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Ring Of Endurance', '', 'Armor', 'Stamina and Strength', '1-300 in theory, practically ~80-250', 'Rare loot from cyborg, mantis, drill and anvian mobs', '1: +1 sta, +1 str\n8: +2 sta, +1 str\n18: +2 sta, +2 str\n25: +3 sta, +2 str\n41: +4 sta, +2 str\n51: +4 sta, +3 str\n57: +5 sta, +3 str\n72: +6 sta, +3 str\n85: +6 sta, +4 str\n88: +7 sta, +4 str\n104: +8 sta, +4 str\n118: +8 sta, +5 str\n120: +9 sta, +5 str\n135: +10 sta, +5 str\n151: +11 sta, +6 str\n167: +12 sta, +6 str\n182: +13 sta, +6 str\n184: +13 sta, +7 str\n198: +14 sta, +7 str\n214: +15 sta, +7 str\n217: +15 sta, +8 str\n230: +16 sta, +8 str\n245: +17 sta, +8 str\n251: +17 sta, +9 str\n261: +18 sta, +9 str\n277: +19 sta, +9 str\n284: +19 sta, +10 str\n293: +20 sta, +10 str');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Ring Of Essence', '', 'Armor', 'Strength and Stamina', '1-300 in theory, practically ~80-250', 'Rare loot from cyborg, mantis, drill and anvian mobs', '1: +1 str, +1 sta", "8: +2 str, +1 sta", "18: +2 str, +2 sta", "25: +3 str, +2 sta", "41: +4 str, +2 sta", "51: +4 str, +3 sta", "57: +5 str, +3 sta", "72: +6 str, +3 sta", "85: +6 str, +4 sta", "88: +7 str, +4 sta", "104: +8 str, +4 sta", "118: +8 str, +5 sta", "120: +9 str, +5 sta", "135: +10 str, +5 sta", "151: +11 str, +6 sta", "167: +12 str, +6 sta", "182: +13 str, +6 sta", "184: +13 str, +7 sta", "198: +14 str, +7 sta", "214: +15 str, +7 sta", "217: +15 str, +8 sta", "230: +16 str, +8 sta", "245: +17 str, +8 sta", "251: +17 str, +9 sta", "261: +18 str, +9 sta", "277: +19 str, +9 sta", "284: +19 str, +10 sta", "293: +20 str, +10 sta');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Uncle Bazzit 22mm', 'Uncle Bazzit Custom 22mm\nUncle Bazzit (New) Custom 22mm\nUncle Bazzit Rusty 22mm', 'Weapon', 'Sense', '1-96', 'Mission reward', '1: +0\n36: +5\n96: +20');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('O.E.T. Co. Urban Sniper', '', 'Weapon', 'Various, depending on QL', '1-192', 'Mission reward, shop buyable at QL1-125', '1: no boost\n38: +14 Shotgun\n80: no boost\n192: +30 Agility');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('OT M50 Shotgun', 'OT M50-ACX Shotgun\nOT M50adc Shotgun\nOT M50atg Shotgun\nOT M50bbk Shotgun\nOT M50bhq Shotgun\nOT M50caw Shotgun\nRusty OT M50 Shotgun', 'Weapon', 'Shotgun, for hotswapping', '1-200', 'Mission reward, shop buyable at QL1-125', '1: no boost\n70: +15\n90: +20\n150: +25\n200: +30');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Bladestaff', 'Refitted Polearm\nTraining Bladestaff\nLong Slank Computer X-II\nEnhanced Polearm\nOmni-Tek Crowd Unit XI-Autotarget\nLong Slank\nNotum Bladestaff\nJobe-Made Bladestaff', 'Weapon', '2he, for hotswapping', '1-200', 'Mission reward, shop buyable at QL1-125', '1: +1\n24: +2\n47: +5\n70: +10\n93: +15\n116: +20\n139: +25\n162: +30\n185: +35\n200: +50');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Tripler', 'Rusty Tripler\nSecond-Hand Tripler\nImproved Tripler\nEnhanced Tripler\nOmni-Tek Manual Tripler\nMonofilament Tripler', 'Weapon', '1he, later on also Piercing', '1-200', 'Mission reward, shop buyable at QL1-125', '1: no boost\n70: +5 1he\n93: +10 1he\n116: +15 1he\n139: +20 1he, +5 Piercing\n162: +25 1he, +10 Piercing\n200: +35 1he, +30 Piercing');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Personalized Basic Robot Brain', '', 'Armor', 'Sense', '1-200', 'Tradeskilled from robot junk and some shop buyable components', '1: +3\n10: +4\n26: +5\n43: +6\n60: +7\n76: +8\n93: +9\n109: +10\n126: +11\n142: +12\n159: +13\n176: +14\n192: +15');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Combined Commando''s', '', 'Armor', 'All ranged and melee skills: pistol, shotgun, assault rifle, rife, bow, ranged energy, heavy weapons, grenade, fling shot, burst, full auto, Sharp Obj, aimed shot, bow special attack, multi ranged, 1he, 2he, 1hb, 2hb, piercing, melee energy, martial arts, brawl, dimach, parry, riposte, fast attack, sneak attack, multi melee', '1-300', 'Tradeskilled from lead bots dropped by Alien Generals/Admirals', '1: +1, AT 1 req\n7: +2\n17: +3\n27: +4\n38: +5\n48: +6\n58: +7\n69: +8\n76: +8, AT 2 req\n79: +9\n89: +10\n99: +11\n110: +12\n120: +13\n130: +14\n141: +15\n151: +16\n161: +17\n172: +18\n182: +19\n192: +20\n203: +21\n213: +22\n223: +23\n226: +23, AT 3 req\n233: +24\n244: +25\n254: +26\n264: +27\n275: +28\n285: +29\n295: +30');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Strong Armor', 'Combined Mercenary''s', 'Armor', 'All melee skills: 1he, 2he, 1hb, 2hb, piercing, melee energy, martial arts, brawl, dimach, parry, riposte, fast attack, sneak attack, multi melee', '1-300', 'Tradeskilled from lead bots dropped by Alien Generals/Admirals', '1: +1, AT 1 req\n7: +2\n17: +3\n27: +4\n38: +5\n48: +6\n58: +7\n69: +8\n76: +8, AT 2 req\n79: +9\n89: +10\n99: +11\n110: +12\n120: +13\n130: +14\n141: +15\n151: +16\n161: +17\n172: +18\n182: +19\n192: +20\n203: +21\n213: +22\n223: +23\n226: +23, AT 3 req\n233: +24\n244: +25\n254: +26\n264: +27\n275: +28\n285: +29\n295: +30');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Supple Armor', 'ombined Sharpshooter''s', 'Armor', 'All ranged skills: pistol, shotgun, assault rifle, rife, bow, ranged energy, heavy weapons, grenade, fling shot, burst, full auto, aimed shot, bow special attack, multi ranged, Sharp Obj', '1-300', 'Tradeskilled from lead bots dropped by Alien Generals/Admirals', '1: +1, AT 1 req\n7: +2\n17: +3\n27: +4\n38: +5\n48: +6\n58: +7\n69: +8\n76: +8, AT 2 req\n79: +9\n89: +10\n99: +11\n110: +12\n120: +13\n130: +14\n141: +15\n151: +16\n161: +17\n172: +18\n182: +19\n192: +20\n203: +21\n213: +22\n223: +23\n226: +23, AT 3 req\n233: +24\n244: +25\n254: +26\n264: +27\n275: +28\n285: +29\n295: +30');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Arithmetic Armor', 'Combined Scout''s\nCombined Officer''s', 'Armor', 'All Nanoskills and Tradskills: Bio Met, Mat Met, Mat Crea, Time Space, Sens Imp, Psycho Modi, Mech Eng, Elec Eng, Quantum FT, Weap Smith, Pharma Tech, Nano Prog, Chemistry, Psychology', '1-300, Alien Tech Perk locks', 'Tradskilled from lead bots dropped by Alien Generals/Admirals', '1:  +1 Nano Skills, +10 Tradeskills, AT 1 req\n  7:  +2 Nano Skills, +10 Tradeskills\n  9:  +2 Nano Skills, +11 Tradeskills\n 17:  +3 Nano Skills, +11 Tradeskills\n 24:  +3 Nano Skills, +12 Tradeskills\n 27:  +4 Nano Skills, +12 Tradeskills\n 38:  +5 Nano Skills, +12 Tradeskills\n 39:  +5 Nano Skills, +13 Tradeskills\n 48:  +6 Nano Skills, +13 Tradeskills\n 54:  +6 Nano Skills, +14 Tradeskills\n 58:  +7 Nano Skills, +14 Tradeskills\n 69:  +8 Nano Skills, +15 Tradeskills\n 76:  +8 Nano Skills, +15 Tradeskills, AT 2 req\n 79:  +9 Nano Skills, +15 Tradeskills\n 84:  +9 Nano Skills, +16 Tradeskills\n 89: +10 Nano Skills, +16 Tradeskills\n 99: +11 Nano Skills, +17 Tradeskills\n110: +12 Nano Skills, +17 Tradeskills\n114: +12 Nano Skills, +18 Tradeskills\n120: +13 Nano Skills, +18 Tradeskills\n129: +13 Nano Skills, +19 Tradeskills\n130: +14 Nano Skills, +19 Tradeskills\n141: +15 Nano Skills, +19 Tradeskills\n144: +15 Nano Skills, +20 Tradeskills\n151: +16 Nano Skills, +20 Tradeskills\n158: +16 Nano Skills, +21 Tradeskills\n161: +17 Nano Skills, +21 Tradeskills\n172: +18 Nano Skills, +21 Tradeskills\n173: +18 Nano Skills, +22 Tradeskills\n182: +19 Nano Skills, +22 Tradeskills\n188: +19 Nano Skills, +23 Tradeskills\n192: +20 Nano Skills, +23 Tradeskills\n203: +21 Nano Skills, +24 Tradeskills\n213: +22 Nano Skills, +24 Tradeskills\n218: +22 Nano Skills, +25 Tradeskills\n223: +23 Nano Skills, +25 Tradeskills\n226: +23 Nano Skills, +25 Tradeskills, AT 3 req\n233: +24 Nano Skills, +26 Tradeskills\n244: +25 Nano Skills, +26 Tradeskills\n248: +25 Nano Skills, +27 Tradeskills\n254: +26 Nano Skills, +27 Tradeskills\n263: +26 Nano Skills, +28 Tradeskills\n264: +27 Nano Skills, +28 Tradeskills\n275: +28 Nano Skills, +28 Tradeskills\n278: +28 Nano Skills, +29 Tradeskills\n285: +29 Nano Skills, +29 Tradeskills\n293: +29 Nano Skills, +30 Tradeskills\n295: +30 Nano Skills, +30 Tradeskills');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Galahad Inc. 055', 'Battered Galahad Inc. 055\nGalahad Inc. 055 Police Aegis\nGalahad Inc. 055 Police Big Mama\nGalahad Inc. 055 Police Chapman\nGalahad Inc. 055 Police Edition\nGalahad Inc. 055 Police Lobster\nGalahad Inc. 055 Police Penny\nGalahad Inc. 055 Police Phoenix', 'Weapon', 'Various skills dependingon QL', '1-200', 'Mission reward, shop buyable at QL1-125', '1: nothing relevant\n151: +30 Full Auto\n182: nothing relevant');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Belt Component Platform', '', 'Belt', 'Deck slots for NCU etc.', '1-200', 'Mission loot/reward, shop buyable at QL1-125', '1: free 1 Deck\n11: free 2 Decks\n30: free 3 Decks\n60: free 4 Decks\n100: free 5 Decks\n160: free 6 Decks');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('MTI SW500', 'Rusty MTI SW500\nMTI SW500 Chapman\nMTI SW500 Bernstock\nMTI SW500 Lux\nMTI SW500 Geyser', 'Weapon', 'Pistol, later on also Multi ranged.', '1-180', 'Mission reward, shop buyable at QL1-125', '1: no boost\n110: +20 Pistol\n180: +30 Pistol/Multi ranged.');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('BigBurger Inc.', 'BigBurger Inc. Chapman Max\nWorn BigBurger Inc.', 'Weapon', 'Burst (for hotswapping)', '1-200', 'Mission reward, shop buyable at QL1-125', '1: +0\n100: +30\n200: +40');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('MTI B-94', 'Rusty MTI B-94', 'Weapon', '% XP gain', '1-152', 'Mission reward, shop buyable at QL1-125', '1: +0%","12: +1%\n22: +2%\n42: +3%\n62: +4%\n152: +5%');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('OT-Windchaser M06 Rifle', 'OT-Windchaser M05 Rifle\nOT-Windchaser M06 Rifle\nOT-Windchaser M06 Quartz\nOT-Windchaser M06 Hematite\nOT-Windchaser M06 Onyx\nOT-Windchaser M06 Jasper\nOT-Windchaser M06 Emerald\nOT-Windchaser M06 Mother of Pearl\nOT-Windchaser M06 Ruby\nOT-Windchaser M06 Diamond', 'Weapon', 'Various, depending on QL', '1-170', 'Mission reward, shop buyable at QL1-125', '1: nothing\n22: +10 Treatment, +8 Sta\n26: +8 Agi, +15 Rifle\n30: nothing relevant\n38: +10 Sense\n41: +10 Int, +3 NCU\n50: +12 Aim.Shot, +10 Psychic, +4 NCU\n170: 20 Aimed Shot');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Platinum Filigree Ring Set with a perfectly cut Ruby Pearl/Almandine/Red Beryl', '', 'Armor', 'Ruby Pearl: +Sen/Int, Almandine: +Str/Agi, Red Beryl: +Sta/Int', '1-250', 'Crafted from Platinum Ingots and SL gems', '1: +1\n234: +5');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('G-Staff', 'Apprentice G-Staff\nJunior G-Staff\nSenior G-Staff\nMaster G-Staff', 'Weapon', 'Agility', '1-200', 'Mission Reward, Dyna boss loot', '1: +0 \n100: +20\n150: +40\n200: +60');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Eye of the Evening Star', '', 'Armor', 'Agility and Sense', '100-300, TL locks, nodrop', 'Drops from SL catacomb bosses and Lord of the Void', '100: +10 Agi/5 Sen\n105: +11 Agi/5 Sen\n110: +11 Agi/6 Sen\n115: +12 Agi/6 Sen\n125: +13 Agi/6 Sen\n130: +13 Agi/7 Sen\n135: +14 Agi/7 Sen\n145: +15 Agi/7 Sen\n150: +15 Agi/8 Sen\n155: +16 Agi/8 Sen\n165: +17 Agi/8 Sen\n170: +17 Agi/9 Sen\n175: +18 Agi/9 Sen\n185: +19 Agi/9 Sen\n190: +19 Agi/10 Sen\n195: +20 Agi/10 Sen\n205: +21 Agi/10 Sen\n210: +21 Agi/11 Sen\n215: +22 Agi/11 Sen\n225: +23 Agi/11 Sen\n230: +23 Agi/12 Sen\n235: +24 Agi/12 Sen\n245: +25 Agi/12 Sen\n250: +25 Agi/13 Sen\n255: +26 Agi/13 Sen\n265: +27 Agi/13 Sen\n270: +27 Agi/14 Sen\n275: +28 Agi/14 Sen\n285: +29 Agi/14 Sen\n290: +29 Agi/15 Sen\n295: +30 Agi/15 Sen');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Ring of Divine Teardrops', '', 'Armor', 'Sense and Agility', '100-300, TL locks', 'Drops from SL catacomb bosses and Lord of the Void', '100: +20 Sen/21 Agi\n110: +21 Sen/11 Agi\n130: +22 Sen/12 Agi\n150: +23 Sen/13 Agi\n170: +24 Sen/14 Agi\n190: +25 Sen/15 Agi\n210: +26 Sen/16 Agi\n230: +27 Sen/17 Agi\n250: +28 Sen/18 Agi\n270: +29 Sen/19 Agi\n290: +30 Sen/20 Agi');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Ring of Computing', '', 'Armor', 'Intelligence and Psychic', '100-300 (nodrop)', 'Drops from SL catacomb bosses and Lord of the Void', '100: +5\n105: +6\n115: +7\n125: +8\n135: +9\n145: +10\n155: +11\n165: +12\n175: +13\n185: +14\n195: +15\n205: +16\n215: +17\n225: +18\n235: +19\n245: +20\n255: +21\n265: +22\n275: +23\n285: +24\n295: +25');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Ring of Presence', '', 'Armor', 'Intelligence, Psychic, all nano skills, treatment, first aid, Comp Lit, Nano Prog, Psychology, Tutoring, Adventuring, Perception', '1-200, lvl locks', 'Dyna and mission boss loot', '1: +1\n35: +2\n100: +3\n167: +4');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Polychromatic Explosiv Pillows', '', 'Weapon', 'Can be switched between Agi/Sense, Str/Sta and Int/Psy', 'QL300 only', 'Made from applying APF nodrop onto Concrete Cushion', 'QL300: +10');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Kirch Kevlar', '', 'Armor', 'Agility, Psychic, Sense', '1-200', 'Dyna and mission boss loot', '1: +1\n26: +2",  "76: +3\n126: +4\n176: +5');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Sekutek Chilled Plasteel', '', 'Armor', 'Agility, Intelligence, Sense', '1-200', 'Dyna and mission boss loot', '1: +1\n26: +2",  "76: +3\n126: +4\n176: +5');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Nova Dillon', '', 'Armor', 'All base abilities', '1-200', 'Dyna and mission boss loot', '1: +1\n35: +2\n101: +3\n167: +4');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Biomech', 'Augmented Biomech\nBasic Biomech', 'Armor', 'Treatment, First aid', '75-200', 'Miss. reward, store buyable at QL75-125', '75: +4\n79: +5\n101: +6\n123: +7\n145: +8\n167: +9\n189: +10');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Concrete Cushion', 'Creviced Concrete Cushion\nExcellent Concrete Cushion', 'Weapon', 'Strength, Stamina', '1-160', 'Mission reward', '1: +2\n10: +8\n160: +20');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Tsakachumi PTO-HV Counter-Sniper Rifle', 'Tsakachumi PTO-HV Counter-Sniper Rifle\nTsakachumi PTO-HV.2 Counter-Sniper Rifle\nTsakachumi PTO-HV3a Counter-Sniper Rifle\nTsakachumi PTO-HV6 Counter-Sniper Rifle', 'Weapon', 'Agility, later on also Rifle and Aimed Shot', '1-175', 'Miss. reward, store buyable at QL1-125', '1: +4 Agi\n40: +20 Agi\n80: +25 Agi, +20 Rifle\n175: +30 Agi/Rifle/AS');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('ICC arms gun bag', 'ICC arms 2Q2B gun bag\nICC arms 2Q2C gun bag\nICC arms 2Q2C (u) gun bag\nICC arms 2Q2N-8 gun bag', 'Weapon', 'Sense, later on also Psychic', '1-145', 'Miss. reward, store buyable at QL1-125', '1: +0/0\n30: +10/0\n80: +20/10\n145: +30/20');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('O.E.T. pistol', 'Second-Hand Old English Trading Co.\nO.E.T. Co. Pelastio V2\nO.E.T. Co. Pelastio V3\nO.E.T. Co. Jess\nO.E.T. Co. Maharanee', 'Weapon', 'Intelligence, later on also Psychic', '1-200', 'Miss. reward, store buyable at QL1-125', '1: +5 Int\n70: +10 Int\n90: +15 Int\n100: +20 Int/Psy\n160: +20 Int/+25 Psy\n200: +25 Int/Psy');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Soft Pepper Pistol', 'Cheap Soft Pepper Pistol\nWorn Soft Pepper Pistol\nShining Soft Pepper Pistol\nMajestic Soft Pepper Pistol', 'Weapon', 'Bio Met, Mat Met, Mat Crea', '1-194', 'Tradeskilled from sealed weap. receptable (mission chest loot)', '1: +2\n51: +14\n101: +18\n151: +24\n194: +28');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Pillow with Important Stripes', 'Love-Filled Pillow with Important Stripes\nPerfumed Pillow with Important Stripes\nSoft Pillow with Important Stripes\nTear-soaked Pillow with Important Stripes', 'Weapon', 'Bio Met, Mat Met, Mat Crea', '1-194', 'Mission reward, store buyable in SL gardens', '1: +2\n51: +14\n101: +18\n151: +24\n194: +28');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Galahad Inc T70 pistol', 'Burned-Out Galahad Inc T70\nGalahad Inc T70 Service Pistol\nGalahad Inc T70 Salamanca\nGalahad Inc T70 Myre\nGalahad Inc T70 Beyer\nGalahad Inc T70 Zig Zag\nGalahad Inc T70 Tsuyoshi\nGalahad Inc T70 Beardsley\nGalahad Inc T70 Priscilla\nGalahad Inc T70 Khan', 'Weapon', 'Various, depending on QL', '1-200', 'Misssion reward, store buyable till QL125', '1: no boost\n33: +14 pistol\n44: +20 Comp Lit\n77: +20 Sense\n99: +20 Bio Met/Mat Met\n111: +25 Psychic/Psy Mod\n153: +20 Mat Crea\n200: +30 Mat Crea/Time Space/Mech Engi');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Spirit Infused Yutto''s Memory', '', 'NCU', 'Treatment', '160-300', 'Tradeskilled from Ancient Circuits and Yutto''s Memories', '160: +5\n174: +6\n202: +7\n230: +8\n258: +9\n286: +10');
INSERT INTO `buffitems` (item_name, aliases, category, boosts, ql_range, acquisition, buff_break_points) VALUES ('Freedom Arms 3927', 'Sparkling Freedom Arms 3927\nBattered Freedom Arms 3927\nFreedom Arms 3927 Notum\nFreedom Arms 3927 Chapman\nFreedom Arms 3927 Guerrilla\nFreedom Arms 3927 G2', 'Weapon', 'Various, depending on QL', '1-200', 'Halloween Uncle Pumpkinhead loot', '1: no boost\n100: +20 sta/str\n108: +20 sta/str, +10 Multi Ranged\n150: +25 Treatment, +20 pistol/Multi Ranged\n158: +20 sta, +20 Multi ranged, +25 Mat Met\n200: +30 str/sta, +30 Multi ranged');