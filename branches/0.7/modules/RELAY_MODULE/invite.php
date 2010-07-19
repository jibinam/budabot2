<?php

if ($type == "extJoinPrivRequest" && Settings::get("relaytype") == 2 && strtolower($sender) == strtolower(Settings::get("relaybot"))) {
	$this->privategroup_join($sender);
}

?>