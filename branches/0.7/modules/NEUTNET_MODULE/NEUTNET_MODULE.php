<?php
	if ($this->vars['dimension'] == 2) {
		$MODULE_NAME = "NEUTNET_MODULE";

		// add neutnet bots to whitelist
		$NUM_BOTS = 14;
		for ($i = 0; $i <= $NUM_BOTS; $i++) {
			Whitelist::add($MODULE_NAME, "Neutnet$i");
		}

		Event::register("msg", $MODULE_NAME, "neutnet.php", 'none', 'Relays neutnet shopping messages to a channel/player');
		
		Help::register("neutnet", $MODULE_NAME, "neutnet.txt", ALL, "Shows the commands needed to register a bot with Neutnet");
	}
?>
