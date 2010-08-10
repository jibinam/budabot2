<?php
   /*
   Bossloot Module Ver 1.1
   Written By Jaqueme
   For Budabot
   Database Adapted From One Originally 
   Compiled by Malosar For BeBot
   Boss Drop Table Database Module
   Written 5/11/07
   Last Modified 5/14/07
   */
	$MODULE_NAME = "BOSSLOOT_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, "boss_namedb");
	DB::loadSQLFile($MODULE_NAME, "boss_lootdb");
	
	Command::register($MODULE_NAME, "boss.php", "boss", ALL, "Bossloot Tables");
	Command::register($MODULE_NAME, "bossloot.php", "bossloot", ALL, "Bossloot Search");
	
	Help::register("Boss", $MODULE_NAME, "boss.txt", ALL, "Bossloot Tables", "Boss");
	
?>
