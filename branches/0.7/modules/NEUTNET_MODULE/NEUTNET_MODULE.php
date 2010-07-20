<?php

	$MODULE_NAME = "NEUTNET_MODULE";
	$PLUGIN_VERSION = 0.1;

	$this->event("msg", $MODULE_NAME, "neutnet.php", 'none', 'Relays neutnet shopping messages to a channel/player');
	
	Help::register("neutnet", $MODULE_NAME, "neutnet.txt", ALL, "Shows the commands needed to register a bot with Neutnet");
?>
