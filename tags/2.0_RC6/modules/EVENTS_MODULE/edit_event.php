<?php
   /*
   ** Author: Legendadv (RK2)
   ** Description: Add/edit/delete in-game events to be stored by the bot
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://budabot.com)
   */

if (preg_match("/^event add (.+)$/i", $message, $arr)) {
	$db->exec("INSERT INTO events_<myname>_<dim> (`time_submitted`, `submitter_name`, `event_name`) VALUES (".time().", '".$sender."', '".addslashes($arr[1])."')");
	$event_id = $db->lastInsertId();
	$msg = "Event: '$arr[1]' was submitted [Event ID $event_id].";
} else if (preg_match("/^event (rem|remove|del|delete) ([0-9]+)$/i", $message, $arr)) {
	$db->exec("DELETE FROM events_<myname>_<dim> WHERE `id` = '$arr[2]'");
	$msg = "Event Deleted.";
} else if (preg_match("/^event setdesc ([0-9]+) (.+)$/i", $message, $arr)) {
	$db->exec("UPDATE events_<myname>_<dim> SET `event_desc` = '".addslashes($arr[2])."' WHERE `id` = '$arr[1]'");
	$msg = "Description Updated.";
} else if (preg_match("/^event setdate ([0-9]+) ([0-9]{4})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) ([0-1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9])$/i", $message, $arr)) {
	// yyyy-dd-mm hh:mm:ss GMT
	$eventDate = gmmktime($arr[5], $arr[6], 0, $arr[3], $arr[4], $arr[2]);
	$db->exec("UPDATE events_<myname>_<dim> SET `event_date` = '$eventDate' WHERE `id` = '$arr[1]'");
	$msg = "Date/Time Updated.";
} else {
	$msg = "There was an error in the syntax. Please do <i>/tell <myname> <symbol>help events</i>";
}

if ($msg) {
	$chatBot->send($msg, $sendto);
}
?>