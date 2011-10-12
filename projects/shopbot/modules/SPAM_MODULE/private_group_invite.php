<?php

if (strtolower($sender) == strtolower(Setting::get('shopbot_master'))) {
	$chatBot->privategroup_join($sender);
}

?>