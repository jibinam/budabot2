<?php
	$MODULE_NAME = "EXTRAHELPBOT_MODULE";
 
	Command::register("", $MODULE_NAME, "mobloot.php", "mobloot", ALL, "loot QL Infos ");
	Command::register("", $MODULE_NAME, "random.php", "random", ALL, "Random order");
	Command::register("", $MODULE_NAME, "cluster.php", "cluster", ALL, "cluster location");
	Command::register("", $MODULE_NAME, "buffitem.php", "buffitem", ALL, "buffitem look up");
	Command::register("", $MODULE_NAME, "whatbuffs.php", "whatbuffs", ALL, "find items that buff");
?>
