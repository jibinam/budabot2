<?php

if(eregi("^dblookup (.+)$", $message, $arr)) 
{

	if(strlen($arr[1]) > 1)
	{
		bot::send("Usage: dblookup 'letter' (where letter is the first letter of the names you wish to view).",$sender);
		return;
	}

	if(is_numeric($arr[1]))
	{
		bot::send("Usage: dblookup 'letter' (where letter is the first letter of the names you wish to view).",$sender);
		return;
	}

	$query = $arr[1]."%";
	$uc = strtoupper($arr[1]);

	$db->query("SELECT * FROM spammembers WHERE `name` LIKE '$query' ORDER BY `name`");
	
	if($db->numrows() > 0)
	{

		$list = "<header>::::: Database Info - $uc :::::<end>\n\n";
	
		while($row = $db->fObject() )
		{
			$targetlist[] = $row->name;
		}
		
		foreach($targetlist as $key => $value)
		{
			$target = $targetlist[$key];
			$whois = Player::get_by_name($target);
			$altslist = "";

			$orgname = $whois->guild;
			if ($orgname == "") $orgname = "No Org";
			
			$db->query("SELECT * FROM spammembers WHERE `name` = '$target' ");
			$row = $db->fObject();
			$assignment = $row->server;
			
			$db->query("SELECT * FROM alts WHERE `main` = '$target' ");
			if ($db->numrows() > 0)
			{
				$altslist = "Alts: ";
				while($row = $db->fObject() )
				{
					$whoisalt = Player::get_by_name($row->alt);
					$altslist .= "<highlight>$row->alt<end>($whoisalt->level)";
					if ($whoisalt->faction == "Clan")
						$altslist .= "(<red>CLAN!!<end>) ";
					elseif ($whoisalt->faction == "Neutral")
						$altslist .= "(<grey>Neut<end>) ";
					else 
						$altslist .= " ";
				}
			
				$altslist .= "\n";
			}

			$db->query("SELECT * FROM alts WHERE `alt` = '$target' ");
			if ($db->numrows() > 0)
			{
				$row = $db->fObject();
				$altslist = "Main: <highlight>$row->main<end>";
				$altslist .= "\n";
			}


			$list .= "Name: <highlight>$whois->name<end> - Level: <highlight>$whois->level<end> - Organization: <highlight>$orgname<end> - Assignment: ";




			if ($this->banlist["$whois->name"]["name"] == "$whois->name") 
				$list .= "<red>**BANNED**<end>";
			else
				$list .= "<highlight>$assignment<end>";

			if ($whois->faction == "Clan") 
				$list .= " - Faction:<red> CLAN!!<end>";
			elseif ($whois->faction == "Neutral") 
				$list .= " - Faction:<grey> Neutral<end>";

			$list .= "\n".$altslist;

			
			if (strlen($this->banlist["$whois->name"]["reason"]) > 1)
			{
				$reason = $this->banlist["$whois->name"]["reason"];
				$bannedby = $this->banlist["$whois->name"]["admin"];
				$bandate = $this->banlist["$whois->name"]["when"];
				$list .= "<white>Banned For: <highlight>$reason<white> By <highlight>$bannedby <white> Date: <highlight>$bandate<end>\n";
				if(isset($this->banlist["$whois->name"]["banend"]))
					$list.= "<highlight><tab>Ban ends at:<end> ".date("m-d-y", $this->banlist["$whois->name"]["banend"])."\n";

			}

			$list .= "\n";

		}

		$msg = bot::makeLink('Database Records', $list);

	}
	else
		$msg = "No records found.";


	bot::send($msg,$sender);
}
elseif(eregi("^dblookup$", $message, $arr)) 
{
	bot::send("Usage: dblookup 'letter' (where letter is the first letter of the names you wish to view).",$sender);
	return;
}

?>