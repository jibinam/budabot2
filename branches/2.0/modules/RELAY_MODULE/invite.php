<?php

if ($type == "extJoinPrivRequest" && Settings::get("relaytype") == 2 && strtolower($sender) == strtolower(Settings::get("relaybot"))) {
	$chatBot->privategroup_join($sender);
}

?>