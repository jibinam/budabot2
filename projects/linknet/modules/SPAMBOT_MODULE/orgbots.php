<?php

if(eregi("^addbot (.+)$", $message, $arr)) 
{
    $uid = AoChat::get_uid($arr[1]);
    $name = ucfirst(strtolower($arr[1]));
	$whois = Player::get_by_name($name);

	if(!$uid) 
	{
		$msg = "<red>".$name."<end> does not exist.";
		bot::send($msg,$sender);
		return;
	}

	if(($this->settings["priv_req_faction"] == "Omni" || $this->settings["priv_req_faction"] == "Clan" || $this->settings["priv_req_faction"] == "Neutral") && $this->settings["priv_req_faction"] != $whois->faction) 
	{
		bot::send("<red>The bot you are trying to add, appears to be of the wrong faction.<end>",$sender);
		return;
	}

	$this->add_buddy($name, 'spambot');

	$db->query("SELECT * FROM spamorgbot WHERE `orgbot` = '$name'");
	if($db->numrows() == 0)
	{
		$db->query("INSERT INTO spamorgbot (`name`,`orgbot`,`orgname`) VALUES ('$sender','$name', '$whois->guild')");
		bot::send("<green>$name<end> has been successfully added as an Org Bot.",$sender);
	}
	else
		bot::send("<green>$name<end> is already listed as an Org Bot.",$sender);
		
}
elseif(eregi("^rembot (.+)$", $message, $arr)) 
{
    $uid = AoChat::get_uid($arr[1]);
    $name = ucfirst(strtolower($arr[1]));

	if(!$uid) 
	{
		$msg = "<red>".$name."<end> does not exist.";
		bot::send($msg,$sender);
		return;
	}

	$this->remove_buddy($name, 'spambot');
	$db->query("SELECT * FROM spamorgbot WHERE `orgbot` = '$name'");
	if($db->numrows() == 0)
	{
		bot::send("<green>$name<end> is not on the list.",$sender);		
	}
	else
	{
		$db->query("DELETE FROM spamorgbot WHERE `orgbot` = '$name'");
		bot::send("<green>$name<end> has been successfully removed.",$sender);
	}
}
elseif(eregi("^listbots$", $message, $arr)) 
{
	$list = "<header> ::::: Org bots in the relay ::::: <end>\n\n";

 	$db->query("SELECT * FROM spamorgbot");
	if($db->numrows() != 0)
	{
		while($row = $db->fObject() )
		{
		$list .= "<blue>$row->orgbot<end> added by <blue>$row->name<end> for <blue>$row->orgname<end>.\n";
		}

		$msg = bot::makeLink("Org bots in the relay", $list);

		bot::send($msg,$sender);
	}
	else
		bot::send("No bots recorded yet.",$sender);

}

?>