<?php
/*
** Author: CerYn (RK2)
** Description: spambot
** Version: 1.0
**
*/

/*
**
**  Spam General
**
*/


if (($sender == $this->settings["tarabotname"]) && preg_match("/^spam (.+)$/i", $message, $arr)) {
	$delimiter = " ";
	$msg = "";
	$msg = explode($delimiter, $arr[1], 2);
	$msgsender = trim($msg[0]);
	$msgtosend = trim($msg[1]);

	$spamtype = "Tarabot";
	$spammessage = "<white>[<end><green>$spamtype<end><white>]<end> <orange>$msgtosend<end> <white>[<green>$msgsender<white>]<end>";

	$msgtosend = str_replace("'", "''", $msgtosend);
	$db->query("INSERT INTO spamhistory (`name` , `type` , `message`, `settime`) VALUES ('$msgsender', '$spamtype', '$msgtosend', '".time()."')");
	
	$msg = "$spamtype $spammessage";

	send_spam_to_slavebots($msg);
	send_spam_to_orgbots($spammessage);
} else if (($sender == $this->settings["twinknetname"]) && preg_match("/^spam (.+)$/i", $message, $arr)) {
	$delimiter = " ";
	$msg = "";
	$msg = explode($delimiter, $arr[1], 2);
	$msgsender = trim($msg[0]);
	$msgtosend = trim($msg[1]);

	$spamtype = "Twinknet";
	$spammessage = "<white>[<end><green>$spamtype<end><white>]<end> <orange>$msgtosend<end> <white>[<green>$msgsender<white>]<end>";

	$msgtosend = str_replace("'", "''", $msgtosend);
	$db->query("INSERT INTO spamhistory (`name` , `type` , `message`, `settime`) VALUES ('$sender','$spamtype','$msgtosend' ,'".time()."')");
	
	$msg = "$spamtype $spammessage";

	send_spam_to_slavebots($msg);
	send_spam_to_orgbots($spammessage);
} else if (($sender == $this->settings["pvpnetname"]) && preg_match("/^spam (.+)$/i", $message, $arr)) {
	$delimiter = " "; 
	$msg = "";
	$msg = explode($delimiter, $arr[1], 2);
	$msgsender = trim($msg[0]);
	$msgtosend = trim($msg[1]);

	$spamtype = "Pvpnet";
	$spammessage = "<white>[<end><green>$spamtype<end><white>]<end> <orange>$msgtosend<end> <white>[<green>$msgsender<white>]<end>";
	
	$msgtosend = str_replace("'", "''", $msgtosend);
	$db->query("INSERT INTO spamhistory (`name` , `type` , `message`, `settime`) VALUES ('$sender','$spamtype','$msgtosend' ,'".time()."')");
	
	$msg = "$spamtype $spammessage";
	
	send_spam_to_slavebots($msg);
	send_spam_to_orgbots($spammessage);
} else if(eregi("^spam general (.+)$", $message, $arr)) {
	$msgtosend = $arr[1];

	if ($this->settings["general"] == "0")
	{
		bot::send("This channel has been closed until further notice.",$sender);
		return;
	}

	// Alts
	$main = $sender;
	// Check if $sender is hisself the main
	$db->query("SELECT * FROM alts WHERE `main` = '$sender'");
	if($db->numrows() == 0)
	{
		// Check if $sender is an alt
		$db->query("SELECT * FROM alts WHERE `alt` = '$sender'");
		if($db->numrows() != 0)
		{
			$row = $db->fObject();
			$main = $row->main;
		}
	}

	if ($this->banlist["$sender"]["name"] == "$main")
	{
		bot::send("You are currently banned from using this bot",$sender);
		return;
	}

	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0){	
		bot::send("You are not registered with this bot.",$sender);
		return;
	}

	$db->query("SELECT * FROM spamhistory WHERE type = 'General' AND name = '$sender' ORDER BY `settime` DESC");
	if ($db->numrows() != 0)
	{
		$row = $db->fObject();
		$timediff = time() - $row->settime;
		$remaining = 600 - $timediff;
		if ($remaining >= 1)
		{
			bot::send("Please wait another <red>$remaining<end> seconds before spamming this channel again.",$sender);
			return;
		}	

	}

	$spamtype = "General";
	$spammessage = "<white>[<end><green>$spamtype<end><white>]<end> <blue>$msgtosend<end> <white>[<green>$sender<white>]<end>";
	
	$msgtosend = str_replace("'", "''", $msgtosend);
	$db->query("INSERT INTO spamhistory (`name` , `type` , `message`, `settime`) VALUES ('$sender','$spamtype','$msgtosend' ,'".time()."')");
	bot::send("Message Sent.", $sendto);

	$msg = "$spamtype $spammessage";
	
	send_spam_to_slavebots($msg);
	send_spam_to_orgbots($spammessage);

}

/*
**
**  Spam PvP
**
*/


elseif(eregi("^spam pvp (.+)$", $message, $arr)) 
{
	$msgtosend = $arr[1];

	// Alts
	$main = $sender;
	// Check if $sender is hisself the main
	$db->query("SELECT * FROM alts WHERE `main` = '$sender'");
	if($db->numrows() == 0)
	{
		// Check if $sender is an alt
		$db->query("SELECT * FROM alts WHERE `alt` = '$sender'");
		if($db->numrows() != 0)
		{
			$row = $db->fObject();
			$main = $row->main;
		}
	}



	if ($this->banlist["$sender"]["name"] == "$main")
	{
		bot::send("You are currently banned from using this bot", $sender);
		return;
	}

	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if ($db->numrows() == 0){	
		bot::send("You are not registered with this bot.", $sender);
		return;
	}


	$db->query("SELECT * FROM spamhistory WHERE type = 'Pvp' AND name = '$sender' ORDER BY `settime` DESC");
	if ($db->numrows() != 0)
	{
		$row = $db->fObject();
		$timediff = time() - $row->settime;
		$remaining = 60 - $timediff;
		if ($remaining >= 1)
		{
			bot::send("Please wait another <red>$remaining<end> seconds before spamming this channel again.", $sender);
			return;
		}	

	}

	$spamtype = "Pvp";
	$spammessage = "<white>[<end><cyan>$spamtype<end><white>]<end> <cyan>$msgtosend<end> <white>[<green>$sender<white>]<end>";
	
	$msgtosend = str_replace("'", "''", $msgtosend);
	$db->query("INSERT INTO spamhistory (`name` , `type` , `message`, `settime`) VALUES ('$sender','$spamtype','$msgtosend' ,'".time()."')");
	bot::send("Message Sent.", $sendto);

	$msg = "$spamtype $spammessage";
	
	send_spam_to_slavebots($msg);
	send_spam_to_orgbots($spammessage);
}

/*
** 
** Spam Raids
**
*/

elseif(eregi("^spam raids (.+)$", $message, $arr)) 
{
	$msgtosend = $arr[1];

	if ($this->settings["raids"] == "0")
	{
		bot::send("This channel has been closed until further notice.",$sender);
		return;
	}
	// Alts
	$main = $sender;
	// Check if $sender is hisself the main
	$db->query("SELECT * FROM alts WHERE `main` = '$sender'");
	if($db->numrows() == 0)
	{
		// Check if $sender is an alt
		$db->query("SELECT * FROM alts WHERE `alt` = '$sender'");
		if($db->numrows() != 0)
		{
			$row = $db->fObject();
			$main = $row->main;
		}
	}


	if ($this->banlist["$sender"]["name"] == "$main")
	{
		bot::send("You are currently banned from using this bot",$sender);
		return;
	}

	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0){	
		bot::send("You are not registered with this bot.",$sender);
		return;
	}


	$db->query("SELECT * FROM spamhistory WHERE type = 'Raids' AND name = '$sender' ORDER BY `settime` DESC");
	if ($db->numrows() != 0)
	{
		$row = $db->fObject();
		$timediff = time() - $row->settime;
		$remaining = 180 - $timediff;
		if ($remaining >= 1)
		{
			bot::send("Please wait another <red>$remaining<end> seconds before spamming this channel again.",$sender);
			return;
		}	

	}
	
	$spamtype = "Raids";
	$spammessage = "<white>[<end><green>$spamtype<end><white>]<end> <blue>$msgtosend<end> <white>[<green>$sender<white>]<end>";
	
	$msgtosend = str_replace("'", "''", $msgtosend);
	$db->query("INSERT INTO spamhistory (`name` , `type` , `message`, `settime`) VALUES ('$sender','$spamtype','$msgtosend' ,'".time()."')");
	bot::send("Message Sent.", $sendto);

	$msg = "$spamtype $spammessage";
	
	send_spam_to_slavebots($msg);
	send_spam_to_orgbots($spammessage);
}


/*
**
**  Spam Shopping
**
*/


elseif(eregi("^spam shopping (.+)$", $message, $arr)) 
{
	$msgtosend = $arr[1];

	if ($this->settings["shopping"] == "0")
	{
		bot::send("This channel has been closed until further notice.",$sender);
		return;
	}


	// Alts
	$main = $sender;
	// Check if $sender is hisself the main
	$db->query("SELECT * FROM alts WHERE `main` = '$sender'");
	if($db->numrows() == 0)
	{
		// Check if $sender is an alt
		$db->query("SELECT * FROM alts WHERE `alt` = '$sender'");
		if($db->numrows() != 0)
		{
			$row = $db->fObject();
			$main = $row->main;
		}
	}


	if ($this->banlist["$sender"]["name"] == "$main")
	{
		bot::send("You are currently banned from using this bot",$sender);
		return;
	}

	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0){	
		bot::send("You are not registered with this bot.",$sender);
		return;
	}

	$db->query("SELECT * FROM spamhistory WHERE type = 'Shopping' AND name = '$sender' ORDER BY `settime` DESC");
	if ($db->numrows() != 0)
	{
		$row = $db->fObject();
		$timediff = time() - $row->settime;
		$remaining = 6000 - $timediff;
		if ($remaining >= 1)
		{
			bot::send("Please wait another <red>$remaining<end> seconds before spamming this channel again.",$sender);
			return;
		}	

	}

	$spamtype = "Shopping";
	$spammessage = "<white>[<end><green>$spamtype<end><white>]<end> <blue>$msgtosend<end> <white>[<green>$sender<white>]<end>";

	$msgtosend = str_replace("'", "''", $msgtosend);
	$db->query("INSERT INTO spamhistory (`name` , `type` , `message`, `settime`) VALUES ('$sender','$spamtype','$msgtosend' ,'".time()."')");
	bot::send("Message Sent.", $sendto);

	$msg = "$spamtype $spammessage";
	
	send_spam_to_slavebots($msg);
	send_spam_to_orgbots($spammessage);
}


/*
**
**  Spam Admin
**
*/


elseif(eregi("^spam admin (.+)$", $message, $arr)) 
{
	$msgtosend = $arr[1];

	if($this->admins[$sender]["level"] < 2)
	{
		bot::send("You do not have access rights to this channel.",$sender);
		return;
	}

	$spamtype = "Admin";
	$spammessage = "<white>[<end><red>$spamtype<end><white>]<end> <yellow>$msgtosend<end> <white>[<green>$sender<white>]<end>";

	$msgtosend = str_replace("'", "''", $msgtosend);
	$db->query("INSERT INTO spamhistory (`name` , `type` , `message`, `settime`) VALUES ('$sender','$spamtype','$msgtosend' ,'".time()."')");
	bot::send("Message Sent.", $sendto);

	$msg = "$spamtype $spammessage";
	
	send_spam_to_slavebots($msg);
	send_spam_to_orgbots($spammessage);
}

/*
**
**  Spam Orgbots
**
*/


elseif(eregi("^spam orgs (.+)$", $message, $arr)) 
{
	$msgtosend = $arr[1];

	if($this->admins[$sender]["level"] < 2)
	{
		bot::send("You do not have access rights to this channel.",$sender);
		return;
	}

	$spamtype = "Organizations";
	$spammessage = "<white>[<end><cyan>$spamtype<end><white>]<end> <yellow>$msgtosend<end> <white>[<green>$sender<white>]<end>";

	$msgtosend = str_replace("'", "''", $msgtosend);
	$db->query("INSERT INTO spamhistory (`name` , `type` , `message`, `settime`) VALUES ('$sender','$spamtype','$msgtosend' ,'".time()."')");

	send_spam_to_orgbots($spammessage);
	bot::send("Message Sent.", $sender);
} else if (eregi("^spam (.+)$", $message, $arr))  {
	$syntax_error = true;
}

?>