<?php

if(eregi("^lastspam (.+)$", $message, $arr)) 
{

	$orgname = $arr[1];
	unset($orgbots);

	$db->query("SELECT * FROM spamorgbot");
	while($row = $db->fObject() )
	{
		$orgbots[] = $row->orgbot;   
	}

	$db->query("SELECT * FROM spamhistory ORDER BY `settime` DESC LIMIT 0, 1");
	$row = $db->fObject();

	$db->query("SELECT * FROM spamlastseen WHERE `lastseen` >= $row->settime ORDER BY `name` ASC");
	$countonline = $db->numrows();

	$msg = "\nOrganization search: <green>$orgname<end>";
	$msg .= "\nMessage type: <green>$row->type<end>  \n\n";
	$counter = 0;
	$matches = 0;

	while($row = $db->fObject() )
	{
		$whois = Player::get_by_name($row->name);
		if(!in_array($row->name,$orgbots))
		{
			$spamlist[$counter]["name"] = "$whois->name";
			$spamlist[$counter]["level"] = "$whois->level";
			$spamlist[$counter]["org"] = "$whois->guild";
			$counter++;
		}
	}

	foreach($spamlist as $key => $gotspammed)
	{
		$matchfound = 0;
		$matchfound = substr_count(strtolower($gotspammed["org"]),strtolower($orgname));
		if( (strtolower($gotspammed["org"]) == strtolower($orgname)) || ($matchfound > 0) )
		{
			$spammedname = $gotspammed["name"];
			$spammedlevel = $gotspammed["level"];
			$spammedorg = $gotspammed["org"];
			$msg .= "$spammedname - $spammedlevel - $spammedorg\n";
		}
	}

	$orgname = ucwords($orgname);
	$msg = bot::makeLink("Lastspam Statistics for search: {$orgname}", $msg);

	bot::send($msg,$sender);  
} else if(eregi("^lastspam$", $message, $arr)) {

	unset($orgbots);

	$db->query("SELECT * FROM spamorgbot");
	while($row = $db->fObject() )
	{
		$orgbots[] = $row->orgbot;   
	}

	$db->query("SELECT * FROM spamhistory ORDER BY `settime` DESC LIMIT 0, 1");
	$row = $db->fObject();

	$msg .= "<white>Time: <green>".gmdate("l F d, Y - H:i", $row->settime)."(GMT)\n";
	$msg .= "\n$row->message<end>\n\n";

	$db->query("SELECT * FROM spamlastseen WHERE `lastseen` >= $row->settime ORDER BY `name` ASC");
	$countonline = $db->numrows();

	$msg = "\nNumber that received last spam: <green>$countonline<end>\n" . $msg;

	$counter = 0;

	while($row = $db->fObject() )
	{
		$whois = Player::get_by_name($row->name);
		if(!in_array($row->name,$orgbots))
		{
			$msg .= "$whois->name - $whois->level - $whois->guild\n";	
		}
	}

	$msg = bot::makeLink("Lastspam Statistics", $msg);

	bot::send($msg,$sender);  
}

?>