<?php
	if ($chatBot->dimension == 2) {
		$MODULE_NAME = "NEUTNET_MODULE";

		// add neutnet bots to whitelist
		$NUM_BOTS = 14;
		for ($i = 1; $i <= $NUM_BOTS; $i++) {
			Whitelist::add("Neutnet$i", $MODULE_NAME);
		}

		Event::register("msg", $MODULE_NAME, "neutnet.php", 'Relays neutnet shopping messages to a channel/player');
		
		Help::register($MODULE_NAME, "neutnet.txt", "neutnet", ALL, "Shows the commands needed to register a bot with Neutnet");
	}
?>
