<?php

global $maxslaves;

if(eregi("^statistics$", $message, $arr)) 
{

    $db->query("SELECT * FROM spammembers");
    $counter = $db->numrows();
    $msg = "\nNumber of registered users: <green>$counter<end> ";


    $db->query("SELECT * FROM spamhistory ORDER BY `settime` DESC LIMIT 0, 1");
    $row = $db->fObject();
    $lasttime = $row->settime;
    $msg .= "\nLast message type: <green>$row->type<end> ";
    $db->query("SELECT * FROM spamlastseen WHERE `lastseen` > '$lasttime'");
    $countonline = $db->numrows();

    $db->query("SELECT * FROM alts");
    $counter = $db->numrows();
    $msg .= "\nNumber of Alts: <green>$counter<end> ";

    $msg .= "\nNumber connected at last spam: <green>$countonline<end> ";

    $counter = 0;

	$targetlist = $db->fObject('all');
	foreach($targetlist as $key => $value)
	{
		$db->query("SELECT * FROM spammembers WHERE `name` = '$value' ");
		if ($db->numrows() > 0)
		{
			$counter = $counter +1;	
		}
	}	

    $msg .= "\nNumber of Alts Registered: <green>$counter<end> ";
    $db->query("SELECT * FROM spamorgbot");
    $counter = $db->numrows();
    $msg .= "\nNumber of Org Bots: <green>$counter<end> ";

	for($bot = 1; $bot <= $maxslaves; $bot++)
	{
	    $slavename = "Linknet".$bot;
	    $db->query("SELECT * FROM spammembers WHERE `server` = '$bot'");
	    $assigned = $db->numrows();
	    $msg .= "\nRegistered Slave: <green>$slavename<end> - Number of assigned users: <green>$assigned<end>";
	}

	$msg = bot::makeLink("Network Statistics", $msg);

    bot::send($msg,$sender);  
}

?>