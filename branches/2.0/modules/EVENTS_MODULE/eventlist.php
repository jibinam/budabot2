<?php
   /*
   ** Author: Legendadv (RK2)
   ** Description: Add/edit/delete in-game events to be stored by the bot
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://budabot.com)
   */

if (preg_match("/^eventlist ([0-9]+)$/i", $message, $arr)) {
	$row = $db->query("SELECT event_attendees FROM events_<myname>_<dim> WHERE `id` = '$arr[1]'", true);
	if ($db->numrows() != 0) {
		
		$link .= Text::make_link("Join this event", "/tell <myname> joinEvent $arr[1]", "chatcmd")."\n";
		$link .= Text::make_link("Leave this event", "/tell <myname> leaveEvent $arr[1]", "chatcmd")."\n\n";
	
		$eventlist = explode(",", $row->event_attendees);
		sort($eventlist);
		if ($row->event_attendees != "") {
			forEach ($eventlist as $key => $value) {
				$row = $db->query("SELECT * FROM org_members_<myname> WHERE `name` = '$value'", true);
				if ($db->numrows() != 0) {
					$level = $row->level;
					$prof = $row->profession;
					$info = ", level $level $prof";
				}
				
				$row1 = $db->query("SELECT * FROM alts WHERE `alt` = '$value'", true);
				if ($db->numrows() == 0) {
					$alt = "<highlight>::<end> <a href='chatcmd:///tell <myname> alts $value'>Alts</a>";
				} else {
					$alt = "<highlight>::<end> <a href='chatcmd:///tell <myname> alts $value'>Alts of $row1->main</a>";
				}
				
				$link .= trim($value)."$info $alt\n";
			}
			$msg = Text::make_blob("Eventlist", $link);
		} else {
			$msg = "Eventlist is empty";
		}
	} else {
		$msg = "That event doesn't exist";
	}
}

if($msg) {
	$chatBot->send($msg, $sendto);
}
?>