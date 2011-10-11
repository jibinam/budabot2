<?php

if(eregi("^dbconvert$", $message, $arr)) 
{
	$nextserver = 1;
	$db->query("SELECT * FROM spammembers");
	$targetlist = $db->fObject('all');

	foreach($targetlist as $key => $value)
	{
		$confirm = "yes";
		$db->query("UPDATE spammembers SET `server` = '$nextserver' WHERE `name` = '$value' ");
		$nextserver = $nextserver +1;
		if ($nextserver > 6) $nextserver = 1;
	}

	bot::send("DB Conversion Complete.",$sender);
}

?>