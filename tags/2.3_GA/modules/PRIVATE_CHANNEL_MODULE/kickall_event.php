<?php

if (time() >= $chatBot->data["priv_kickall"]) {
	$chatBot->privategroup_kick_all();
	Event::deactivate("2sec", "PRIVATE_CHANNEL_MODULE/kickall_event.php");
	unset($chatBot->data["priv_kickall"]);
}

?>