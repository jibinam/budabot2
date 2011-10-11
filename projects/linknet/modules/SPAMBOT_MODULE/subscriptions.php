<?php
   /*
   ** Author: CerYn (RK2)
   ** Description: Registrations
   ** Version: 1.0
   **
   */

global $maxslaves;
global $nextserver;

$message = str_ireplace("'", "`", $message);
$message = str_ireplace('"', '', $message);

 /*
 **
 **  Register
 **
 */


if(eregi("^register$", $message, $arr)) 
{


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

	$whois = Player::get_by_name($sender);

	if( ($main == $sender) && ($whois->level < 175) )
	{	
		bot::send("You must be at least level 175 to register as a main.", $sendto);
		return;
	}

	if( $whois->level < 10 )
	{	
		bot::send("You must be at least level 10 to register as an alt.", $sendto);
		return;
	}

	$who_org = $whois->guild;
	
	if ($who_org == "") $who_org = "ok";
	
	if($this->banlist["$sender"]["name"] == $sender  || $this->banlist["$who_org"]["name"] == $who_org)
	{
		bot::send("I don't think so..",$sender);
		return;
	}


   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0){
		$default = "no";	
		$db->query("INSERT INTO spammembers (`server`,`name`,`pvp`,`raids`,`general`,`shopping`,`twinknet`,`pvpnet`,`taranet`) VALUES ('$nextserver','$sender','$default','$default','$default','$default','$default','$default','$default')");
		bot::send("You have been added to this bot.",$sender);
		$nextserver = $nextserver +1;
		if ($nextserver > $maxslaves) $nextserver = 1;		
		

	   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
		if($db->numrows() == 0)
			bot::send("You should not see this message, please speak to an administrator.",$sender);
		else
		{
			$row = $db->fObject();

			$list = "<header>::::: Omni Mass Message Network :::::<end>\n";
			$list .= "\n\n<white>Your current subscriptions<end>\n\n";

			$list .= "<green>General Channels<end> ";
			$list .= "<a href='chatcmd:///tell <myname> subscribegroup general'>Subscribe to group</a>";
			$list .= " / ";
			$list .= "<a href='chatcmd:///tell <myname> unsubscribegroup general'>Unsubscribe from group</a>";
			$list .= "\n";

			if ($row->raids == "yes")
				$list .= "<white>Raid messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe raids'>Unsubscribe</a>\n";
			else
				$list .= "<white>Raid messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe raids'>Subscribe</a>\n";

			if ($row->general == "yes")
				$list .= "<white>General messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe general'>Unsubscribe</a>\n";
			else
				$list .= "<white>General messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe general'>Subscribe</a>\n";
			
			if ($row->shopping == "yes")
				$list .= "<white>Shopping messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe shopping'>Unsubscribe</a>\n";
			else
				$list .= "<white>Shopping messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe shopping'>Subscribe</a>\n";
			
			$list .= "\n";

			$list .= "<green>PvP Channels<end> ";
			$list .= "<a href='chatcmd:///tell <myname> subscribegroup pvp'>Subscribe to group</a>";
			$list .= " / ";
			$list .= "<a href='chatcmd:///tell <myname> unsubscribegroup pvp'>Unsubscribe from group</a>";
			$list .= "\n";

			if ($row->pvp == "yes")
				$list .= "<white>Pvp messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe pvp'>Unsubscribe</a><br>";
			else
				$list .= "<white>Pvp messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe pvp'>Subscribe</a>\n";

			$whois = Player::get_by_name($sender);
			
			if($whois->level >= 10)
			{
				if ($row->twinknet == "yes")
					$list .= "<white>Twinknet messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe twinknet>Unsubscribe</a>\n";
				else
					$list .= "<white>Twinknet messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe twinknet'>Subscribe</a>\n";
			}

			if($whois->level >= 190)
			{
				if ($row->pvpnet == "yes")
					$list .= "<white>Pvpnet messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe pvpnet>Unsubscribe</a>\n";
				else
					$list .= "<white>Pvpnet messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe pvpnet'>Subscribe</a>\n";
			}


			if($whois->level >= 100)
			{
				if ($row->taranet == "yes")
					$list .= "<white>Tarasque messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe tarabot>Unsubscribe</a>\n";
				else
					$list .= "<white>Tarasque messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe tarabot'>Subscribe</a>\n";
			}

			 $msg = bot::makeLink("Your Subscription Status", $list);

			bot::send($msg,$sender);
		}
	}
	else
		bot::send("You have already been added.",$sender);
		
	$this->add_buddy($sender, 'spammember');
}



 /*
 **
 **  Unregister
 **
 */


elseif(eregi("^unregister$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0){	
		bot::send("You are not registered with this bot.",$sender);
	}
	else
	{
		$db->query("DELETE FROM spammembers WHERE `name` = '$sender'");
		$msg = "You have been removed from the subscription list.";

		// Alts
		    $db->query("SELECT * FROM alts WHERE `main` = '$sender'");
		    if($db->numrows() != 0)
		    {
		             	$db->query("DELETE FROM alts WHERE `main` = '$sender'");
			$msg = "You and all your alts, have been removed from the subscription list.";			
		    }

	bot::send($msg,$sender);
	}
	
	$this->remove_buddy($sender, 'spammember');
		
}




 /*
 **
 **  Subscribe
 **
 */


elseif(eregi("^subscribe pvp$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$row = $db->fObject();
		if ($row->pvp == "yes")
			bot::send("You are already subscribed to PvP messages.",$sender);
		else
		{
			$db->query("UPDATE spammembers SET `pvp`= 'yes' WHERE `name` = '$sender'");
			bot::send("You are now subscribed to receive PvP messages.",$sender);
		}

	}
}
elseif(eregi("^subscribe raids$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$row = $db->fObject();
		if ($row->raids == "yes")
			bot::send("You are already subscribed to raid messages.",$sender);
		else
		{
			$db->query("UPDATE spammembers SET `raids`= 'yes' WHERE `name` = '$sender'");
			bot::send("You are now subscribed to receive raid messages.",$sender);
		}

	}
}
elseif(eregi("^subscribe general$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$row = $db->fObject();
		if ($row->general == "yes")
			bot::send("You are already subscribed to general messages.",$sender);
		else
		{
			$db->query("UPDATE spammembers SET `general`= 'yes' WHERE `name` = '$sender'");
			bot::send("You are now subscribed to receive general messages.",$sender);
		}

	}
}
elseif(eregi("^subscribe shopping$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not subscribed to this bot.",$sender);
	else
	{
		$row = $db->fObject();
		if ($row->shopping == "yes")
			bot::send("You are already subscribed to shopping messages.",$sender);
		else
		{
			$db->query("UPDATE spammembers SET `shopping`= 'yes' WHERE `name` = '$sender'");
			bot::send("You are now subscribed to receive shopping messages.",$sender);
		}

	}
}

elseif(eregi("^subscribe twinknet$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$whois = Player::get_by_name($sender);
		if ($whois->level < 10)
		{
			bot::send("You must be at least level 10 to subscribe to this channel.",$sender);
			return;
		}

		$row = $db->fObject();
		if ($row->twinknet == "yes")
			bot::send("You are already subscribed to Twinknet messages.",$sender);
		else
		{
			$db->query("UPDATE spammembers SET `twinknet`= 'yes' WHERE `name` = '$sender'");
			bot::send("You are now subscribed to receive Twinknet messages.",$sender);
		}

	}
}

elseif(eregi("^subscribe pvpnet$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$whois = Player::get_by_name($sender);
		if ($whois->level < 190)
		{
			bot::send("You must be at least level 190 to subscribe to this channel.",$sender);
			return;
		}

		$row = $db->fObject();
		if ($row->pvpnet == "yes")
			bot::send("You are already subscribed to Pvpnet messages.",$sender);
		else
		{
			$db->query("UPDATE spammembers SET `pvpnet`= 'yes' WHERE `name` = '$sender'");
			bot::send("You are now subscribed to receive Pvpnet messages.",$sender);
		}

	}
}

elseif(eregi("^subscribe tarabot$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{

		$whois = Player::get_by_name($sender);
		if ($whois->level < 100)
		{
			bot::send("You must be at least level 100 to subscribe to this channel.",$sender);
			return;
		}

		$row = $db->fObject();
		if ($row->taranet == "yes")
			bot::send("You are already subscribed to Tarasque messages.",$sender);
		else
		{
			$db->query("UPDATE spammembers SET `taranet`= 'yes' WHERE `name` = '$sender'");
			bot::send("You are now subscribed to receive Tarasque messages.",$sender);
		}

	}
}



elseif(eregi("^subscribegroup pvp$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$whois = Player::get_by_name($sender);

		if ($whois->level >= 10)
		{
			$db->query("UPDATE spammembers SET `twinknet`= 'yes' WHERE `name` = '$sender'");
		}
		if ($whois->level >= 100)
		{
			$db->query("UPDATE spammembers SET `taranet`= 'yes' WHERE `name` = '$sender'");	
		}
		if ($whois->level >= 190)
		{
			$db->query("UPDATE spammembers SET `pvpnet`= 'yes' WHERE `name` = '$sender'");
		}
			$db->query("UPDATE spammembers SET `pvp`= 'yes' WHERE `name` = '$sender'");
			bot::send("You are now subscribed to receive PvP Group messages.",$sender);
	}
}

elseif(eregi("^subscribegroup general$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
			$db->query("UPDATE spammembers SET `general`= 'yes' WHERE `name` = '$sender'");
			$db->query("UPDATE spammembers SET `shopping`= 'yes' WHERE `name` = '$sender'");	
			$db->query("UPDATE spammembers SET `raids`= 'yes' WHERE `name` = '$sender'");
			bot::send("You are now subscribed to receive General Group messages.",$sender);
	}
}




 /*
 **
 **  Unsubscribe
 **
 */


elseif(eregi("^unsubscribe pvp$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$row = $db->fObject();
		if ($row->pvp == "yes")
		{
			$db->query("UPDATE spammembers SET `pvp`= 'no' WHERE `name` = '$sender'");
			bot::send("You will no longer receive PvP messages.",$sender);
		}
		else
		{
			bot::send("You are not subscribed to receive PvP messages.",$sender);
		}

	}
}
elseif(eregi("^unsubscribe raids$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$row = $db->fObject();
		if ($row->raids == "yes")
		{
			$db->query("UPDATE spammembers SET `raids`= 'no' WHERE `name` = '$sender'");
			bot::send("You will no longer receive raid messages.",$sender);
		}
		else
		{
			bot::send("You are not subscribed to receive raid messages.",$sender);
		}

	}
}
elseif(eregi("^unsubscribe general$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$row = $db->fObject();
		if ($row->general == "yes")
		{
			$db->query("UPDATE spammembers SET `general`= 'no' WHERE `name` = '$sender'");
			bot::send("You will no longer receive general messages.",$sender);
		}
		else
		{
			bot::send("You are not subscribed to receive general messages.",$sender);
		}

	}
}
elseif(eregi("^unsubscribe shopping$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$row = $db->fObject();
		if ($row->shopping == "yes")
		{
			$db->query("UPDATE spammembers SET `shopping`= 'no' WHERE `name` = '$sender'");
			bot::send("You will no longer receive shopping messages.",$sender);
		}
		else
		{
			bot::send("You are not subscribed to receive shopping messages.",$sender);
		}

	}
}

elseif(eregi("^unsubscribe twinknet$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$whois = Player::get_by_name($sender);
		if ($whois->level < 10)
		{
			bot::send("You must be at least level 10 to unsubscribe from this channel.",$sender);
			return;
		}

		$row = $db->fObject();
		if ($row->twinknet == "yes")
		{
			$db->query("UPDATE spammembers SET `twinknet`= 'no' WHERE `name` = '$sender'");
			bot::send("You will no longer receive Twinknet messages.",$sender);
		}
		else
		{
			bot::send("You are not subscribed to receive Twinknet messages.",$sender);
		}

	}
}
elseif(eregi("^unsubscribe pvpnet$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$whois = Player::get_by_name($sender);
		if ($whois->level < 190)
		{
			bot::send("You must be at least level 190 to unsubscribe from this channel.",$sender);
			return;
		}

		$row = $db->fObject();
		if ($row->pvpnet == "yes")
		{
			$db->query("UPDATE spammembers SET `pvpnet`= 'no' WHERE `name` = '$sender'");
			bot::send("You will no longer receive Pvpnet messages.",$sender);
		}
		else
		{
			bot::send("You are not subscribed to receive Pvpnet messages.",$sender);
		}

	}
}

elseif(eregi("^unsubscribe tarabot$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$whois = Player::get_by_name($sender);
		if ($whois->level < 100)
		{
			bot::send("You must be at least level 100 to unsubscribe from this channel.",$sender);
			return;
		}

		$row = $db->fObject();
		if ($row->taranet == "yes")
		{
			$db->query("UPDATE spammembers SET `taranet`= 'no' WHERE `name` = '$sender'");
			bot::send("You will no longer receive Tarasque messages.",$sender);
		}
		else
		{
			bot::send("You are not subscribed to receive Tarasque messages.",$sender);
		}

	}
}

elseif(eregi("^unsubscribegroup general$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
			$db->query("UPDATE spammembers SET `general`= 'no' WHERE `name` = '$sender'");
			$db->query("UPDATE spammembers SET `shopping`= 'no' WHERE `name` = '$sender'");	
			$db->query("UPDATE spammembers SET `raids`= 'no' WHERE `name` = '$sender'");
			bot::send("You are now unsubscribed from General Group messages.",$sender);
	}
}

elseif(eregi("^unsubscribegroup pvp$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
			$db->query("UPDATE spammembers SET `pvp`= 'no' WHERE `name` = '$sender'");
			$db->query("UPDATE spammembers SET `pvpnet`= 'no' WHERE `name` = '$sender'");
			$db->query("UPDATE spammembers SET `twinknet`= 'no' WHERE `name` = '$sender'");	
			$db->query("UPDATE spammembers SET `taranet`= 'no' WHERE `name` = '$sender'");
			bot::send("You are now unsubscribed from PvP Group messages.",$sender);
	}
}



 /*
 **
 **  Subscriptions
 **
 */



elseif(eregi("^subscriptions$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$row = $db->fObject();

		$list = "<header>::::: Omni Mass Message Network :::::<end>\n";
		$list .= "\n\n<white>Your current subscriptions<end>\n\n";
	
		$list .= "<green>General Channels<end> ";
		$list .= "<a href='chatcmd:///tell <myname> subscribegroup general'>Subscribe to group</a>";
		$list .= " / ";
		$list .= "<a href='chatcmd:///tell <myname> unsubscribegroup general'>Unsubscribe from group</a>";
		$list .= "\n";

		if ($row->raids == "yes")
			$list .= "<white>Raid messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe raids'>Unsubscribe</a>\n";
		else
			$list .= "<white>Raid messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe raids'>Subscribe</a>\n";

		if ($row->general == "yes")
			$list .= "<white>General messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe general'>Unsubscribe</a>\n";
		else
			$list .= "<white>General messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe general'>Subscribe</a>\n";
		
		if ($row->shopping == "yes")
			$list .= "<white>Shopping messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe shopping'>Unsubscribe</a>\n";
		else
			$list .= "<white>Shopping messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe shopping'>Subscribe</a>\n";
		
		$list .= "\n";
		$whois = Player::get_by_name($sender);

		$list .= "<green>PvP Channels<end> ";
		$list .= "<a href='chatcmd:///tell <myname> subscribegroup pvp'>Subscribe to group</a>";
		$list .= " / ";
		$list .= "<a href='chatcmd:///tell <myname> unsubscribegroup pvp'>Unsubscribe from group</a>";
		$list .= "\n";

		if ($row->pvp == "yes")
			$list .= "<white>Pvp messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe pvp'>Unsubscribe</a><br>";
		else
			$list .= "<white>Pvp messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe pvp'>Subscribe</a>\n";
		
		if($whois->level >= 10)
		{
		if ($row->twinknet == "yes")
			$list .= "<white>Twinknet messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe twinknet>Unsubscribe</a>\n";
		else
			$list .= "<white>Twinknet messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe twinknet'>Subscribe</a>\n";
		}

		if($whois->level >= 190)
		{
		if ($row->pvpnet == "yes")
			$list .= "<white>Pvpnet messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe pvpnet>Unsubscribe</a>\n";
		else
			$list .= "<white>Pvpnet messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe pvpnet'>Subscribe</a>\n";
		}

		if($whois->level >= 100)
		{
		if ($row->taranet == "yes")
			$list .= "<white>Tarasque messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe tarabot>Unsubscribe</a>\n";
		else
			$list .= "<white>Tarasque messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe tarabot'>Subscribe</a>\n";
		}



		 $msg = bot::makeLink("Your Subscription Status", $list);

		bot::send($msg,$sender);
	}
}

elseif(eregi("^status$", $message, $arr)) 
{
   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if($db->numrows() == 0)
		bot::send("You are not registered with this bot.",$sender);
	else
	{
		$row = $db->fObject();

		$list = "<header>::::: Omni Mass Message Network :::::<end>\n";
		$list .= "\n\n<white>Your current subscriptions<end>\n\n";
	
		$list .= "<green>General Channels<end> ";
		$list .= "<a href='chatcmd:///tell <myname> subscribegroup general'>Subscribe to group</a>";
		$list .= " / ";
		$list .= "<a href='chatcmd:///tell <myname> unsubscribegroup general'>Unsubscribe from group</a>";
		$list .= "\n";

		if ($row->raids == "yes")
			$list .= "<white>Raid messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe raids'>Unsubscribe</a>\n";
		else
			$list .= "<white>Raid messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe raids'>Subscribe</a>\n";

		if ($row->general == "yes")
			$list .= "<white>General messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe general'>Unsubscribe</a>\n";
		else
			$list .= "<white>General messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe general'>Subscribe</a>\n";
		
		if ($row->shopping == "yes")
			$list .= "<white>Shopping messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe shopping'>Unsubscribe</a>\n";
		else
			$list .= "<white>Shopping messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe shopping'>Subscribe</a>\n";
		
		$list .= "\n";
		$whois = Player::get_by_name($sender);

		$list .= "<green>PvP Channels<end> ";
		$list .= "<a href='chatcmd:///tell <myname> subscribegroup pvp'>Subscribe to group</a>";
		$list .= " / ";
		$list .= "<a href='chatcmd:///tell <myname> unsubscribegroup pvp'>Unsubscribe from group</a>";
		$list .= "\n";

		if ($row->pvp == "yes")
			$list .= "<white>Pvp messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe pvp'>Unsubscribe</a><br>";
		else
			$list .= "<white>Pvp messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe pvp'>Subscribe</a>\n";
		
		if($whois->level >= 10)
		{
			if ($row->twinknet == "yes")
				$list .= "<white>Twinknet messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe twinknet>Unsubscribe</a>\n";
			else
				$list .= "<white>Twinknet messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe twinknet'>Subscribe</a>\n";
		}

		if($whois->level >= 190)
		{
			if ($row->pvpnet == "yes")
				$list .= "<white>Pvpnet messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe pvpnet>Unsubscribe</a>\n";
			else
				$list .= "<white>Pvpnet messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe pvpnet'>Subscribe</a>\n";
		}

		if($whois->level >= 100)
		{
			if ($row->taranet == "yes")
				$list .= "<white>Tarasque messages<end><blue> - Subscribed. <a href='chatcmd:///tell <myname> unsubscribe tarabot>Unsubscribe</a>\n";
			else
				$list .= "<white>Tarasque messages<end><blue> - Not Subscribed. <a href='chatcmd:///tell <myname> subscribe tarabot'>Subscribe</a>\n";
		}

		$msg = bot::makeLink("Your Subscription Status", $list);

		bot::send($msg,$sender);
	}
}

elseif(eregi("^regtwink (.+)$", $message, $arr)) 
{
    $name = ucfirst(strtolower($arr[1]));
    $uid = AoChat::get_uid($arr[1]);

    if(!$uid)
    {
        $msg = "<highlight>$name<end> does not exist.";
		bot::send($msg,$sender);
		return;
    }
    else
    {
		$whois = Player::get_by_name($arr[1]);
		
		if($whois->faction == "Clan")
		{
			$msg = "<red>$name is a dirty clanner! What are you thinking?<end>";	
			bot::send($msg,$sender);
			return;
		}

		$db->query("SELECT * FROM spammembers WHERE `name` = '$name'");	
		if ($db->numrows() != 0)
		{
			$msg = "<red>$name has already been registered.<end>";
			bot::send($msg,$sender);
			return;
		}
		else
		{
			$no = "no";
			$this->add_buddy($name, 'spammember');
			$db->query("INSERT INTO spammembers (`server`,`name`,`pvp`,`raids`,`general`,`shopping`,`twinknet`,`pvpnet`,`taranet`) VALUES ('$nextserver','$name','$no','$no','$no','$no','$no','$no','$no')");
			$nextserver = $nextserver +1;
			if ($nextserver > $maxslaves) $nextserver = 1;		
			$msg = "<highlight>$name<end> has been registered.";
			bot::send($msg,$sender);
			$msg = "You have been registered with <myname> by $sender. Please now check your subscriptions, using the <highlight><symbol>subscriptions<end> command.";
			bot::send($msg,$name);
		}
    }
	
}

elseif(eregi("^forceremove (.+)$", $message, $arr)) 
{
    $name = ucfirst(strtolower($arr[1]));
	
	$this->remove_buddy($name, 'spammember');

   	$db->query("SELECT * FROM spammembers WHERE `name` = '$name'");	
	if ($db->numrows() == 0)
	{
		$msg = "<red>$name is not registered.<end>";
		bot::send($msg,$sender);
		return;
	}
	else
	{
		$db->query("DELETE FROM spammembers WHERE `name` = '$name'");	
		$msg = "<highlight>$name<end> is been removed.";
		bot::send($msg,$sender);
		return;
	}
}

?>