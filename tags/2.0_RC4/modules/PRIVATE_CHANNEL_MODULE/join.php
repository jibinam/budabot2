<?php
   
if (preg_match("/^join$/i", $message)) {
 	$db->query("SELECT name FROM members_<myname> WHERE `name` = '$sender' UNION SELECT name FROM org_members_<myname> WHERE `name` = '$sender'");

	// if user is an admin, member, or org member, or if manual join mode is open for everyone, then invite them
	if (isset($this->admins[$sender]) || $db->numrows() > 0 || $this->settings["guest_man_join"] == 0) {
		$chatBot->privategroup_kick($sender);
		$chatBot->privategroup_invite($sender);
	} else {
		$chatBot->send("You are not allowed to join the private channel, ask a member of the bot for an invite.", $sendto);
	}
} else {
	$syntax_error = true;
}

?>