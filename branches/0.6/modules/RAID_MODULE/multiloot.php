<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Adds an item to the roll
   ** Version: 0.2
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 01.03.2006
   ** Date(last modified): 30.01.2007
   ** 
   ** Copyright (C) 2006, 2007 Carsten Lohmann
   **
   ** Licence Infos: 
   ** This file is part of Budabot.
   **
   ** Budabot is free software; you can redistribute it and/or modify
   ** it under the terms of the GNU General Public License as published by
   ** the Free Software Foundation; either version 2 of the License, or
   ** (at your option) any later version.
   **
   ** Budabot is distributed in the hope that it will be useful,
   ** but WITHOUT ANY WARRANTY; without even the implied warranty of
   ** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   ** GNU General Public License for more details.
   **
   ** You should have received a copy of the GNU General Public License
   ** along with Budabot; if not, write to the Free Software
   ** Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
   */

global $loot;
global $residual;
	
if(eregi("^multiloot (.+)$", $message, $arr)) {

	//Check if it is a valid multiloot
	if(eregi("^([0-9]+)x (.+)$", $arr[1], $lewt) || eregi("^([0-9]+) (.+)$", $arr[1], $lewt)){
		$multiloot = $lewt[1];
		}
	else{
		bot::send("The data you entered is not a multiloot. Please check <highlight>/tell <myname> <symbol>help multiloot<end> for the correct syntax.");
		return;
	}

 
	//Check if the item is a link
  	if(eregi("^<a href=\"itemref:\/\/([0-9]+)\/([0-9]+)\/([0-9]+)\">(.+)<\/a>(.*)$", $lewt[2], $item)) {
	    $item_ql = $item[3];
	    $item_highid = $item[1];
	    $item_lowid = $item[2];
	    $item_name = $item[4];
		}
	elseif(eregi("^(.+)<a href=\"itemref:\/\/([0-9]+)\/([0-9]+)\/([0-9]+)\">(.+)<\/a>(.*)$", $lewt[2], $item)){
	    $item_ql = $item[4];
	    $item_highid = $item[2];
	    $item_lowid = $item[3];
	    $item_name = $item[5];
		
	} else
		$item_name = $lewt[2];
		
	//Check if the item is already on the list (i.e. SMART LOOT)
	foreach($loot as $key => $item) {
		if(strtolower($item["name"]) == strtolower($item_name)){
			if($item["multiloot"]){
				if($multiloot){
					$loot[$key]["multiloot"] = $item["multiloot"]+$multiloot;
					}
				else{
					$loot[$key]["multiloot"] = $item["multiloot"]+1;
					}
				}
			else{
				if($multiloot){
					$loot[$key]["multiloot"] = 1+$multiloot;
					}
				else{
					$loot[$key]["multiloot"] = 2;
					}
				}
			$dontadd = 1;
			$itmref = $key;
			}
	}

	//get a slot for the item
  	if(is_array($loot)) {
	  	$num_loot = count($loot);
	  	$num_loot++;
	} else
		$num_loot = 1;
	
	//Check if max slots is reached
  	if($num_loot >= 30) {
	    $msg = "You can only roll 30items max at one time!";
	    bot::send($msg);
	    return;
	}

	$item_name = str_replace('"',"",$item_name);

	//Check if there is a icon available
	$item_name = str_replace("'", "\'", $item_name);
	$item_name = str_replace(":", "&#58;", $item_name);
	$item_name = str_replace("&", "&amp;", $item_name);
	$db->query("SELECT * FROM aodb WHERE `name` LIKE \"$item_name\"");
	if($db->numrows() != 0) {
		//Create an Object of the data
	  	$row = $db->fObject();
	  	$item_name = $row->name;
		//Return HTML to normal characters		
  		$item_name = str_replace("\'", "'", $item_name);
		$item_name = str_replace("&#58;", ":", $item_name);
		$item_name = str_replace("&amp;", "&", $item_name);
		//Save the icon
		$looticon = $row->icon;
		//Save the aoid and ql if not set yet
		if(!isset($item_highid)) {
			$item_lowid = $row->lowid;
			$item_highid = $row->highid;
			$item_ql = $row->highql;	  
		}
	}
	

	//Save item
	if(!$dontadd){
		if(isset($item_highid)) {
			$loot[$num_loot]["linky"] = "<a href='itemref://$item_lowid/$item_highid/$item_ql'>$item_name</a>";	
			}
			
		$loot[$num_loot]["name"] = $item_name;
		$loot[$num_loot]["icon"] = $looticon;

		//Save the person who has added the loot item
		$loot[$num_loot]["added_by"] = $sender;
	
		//Save multiloot
		$loot[$num_loot]["multiloot"] = $multiloot;

		//Send info
		if($multiloot){
			bot::send($multiloot."x <highlight>{$loot[$num_loot]["name"]}<end> will be rolled in Slot <highlight>#$num_loot<end>");
			}
		bot::send("To add use <symbol>add $num_loot, or <symbol>add 0 to remove yourself");
		}
	else{
		//Send info in case of SMART
		if($multiloot){
			bot::send($multiloot."x <highlight>{$loot[$itmref]["name"]}<end> added to Slot <highlight>#$itmref<end> as multiloot. Total: <yellow>{$loot[$itmref]["multiloot"]}<end>");
			}

		bot::send("To add use <symbol>add $itmref, or <symbol>add 0 to remove yourself");
		$dontadd = 0;
		$itmref = 0;
		if(is_array($residual)){
			$residual = "";
			}
		}
} else
	$syntax_error = true;
?>