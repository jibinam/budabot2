<?php
// Timer Table
$data = $db->query("CREATE TABLE IF NOT EXISTS timers_<myname> (`name` VARCHAR(255), `owner` VARCHAR(25), `mode` VARCHAR(10), `timer` int, `settime` int)");

if (!isset($chatBot->vars["Timers"])) {
	//Upload timers to global vars
	$db->query("SELECT * FROM timers_<myname>");
	forEach ($data as $row) {
	  	$chatBot->vars["Timers"][] = array("name" => $row->name, "owner" => $row->owner, "mode" => $row->mode, "timer" => $row->timer, "settime" => $row->settime);
	}
}
?>