<?php
/*
** Author: Iamzipfile (RK2)
** Description: Searches a premade XML file with a tree of the contents of the orgs bank character
** Version: 0.1
**
** Developed for: Budabot(http://sourceforge.net/projects/budabot)
**
** Date(created): 23.08.2009
** Date(last modified): 24.08.2009
**
** The budabot bank module is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 3 of the License, or
** (at your option) any later version.
**
** Budabot is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
** GNU General Public License for more details.
**
** For more information please visit http://www.gnu.org/licenses/gpl-3.0.txt
*/

if ($xml = simplexml_load_file("modules/BANK_MODULE/bank.xml")) {
	if (preg_match("/^bank$/i", $message)) {
		$item_count = 0;
		$backpack_count = 0;
		$msg = "";
		forEach ($xml->children() as $base_container) {// Loops through inventory and bank
			$msg .= "- ".ucwords($base_container->getName())."\n";
			$packprelude = substr($base_container->getName(), 0, 1);
			forEach ($base_container->children() as $base_slot) {// Loops through items and backpacks
				if ($base_slot->getName()=='item') {
					$msg .= "<tab>> ".Text::makeItem($base_slot['lowid'], $base_slot['highid'], $base_slot['ql'], $base_slot['name'])." Item ID: ".$base_slot['id']."\n";
					$item_count++;
				} else if ($base_slot->getName()=='backpack') {
					$backpack_inside_count = 0;
					$backpack_count++;
					forEach ($base_slot->children() as $item) {// Loops through items in backpacks
						$backpack_inside_count++;
						$item_count++;
					}
					if ($backpack_inside_count) {
						$msg .= "<tab>+ ".Text::makeLink("Backpack #".$base_slot['id'], "/tell <myname> pack ".$packprelude.$base_slot['id'], "chatcmd")." Contains ".$backpack_inside_count." items\n";
					} else {
						$msg .= "<tab>- Backpack #".$base_slot['id']." Is empty\n";
					}
				}
			}
		}
		$link = Text::makeBlob("$item_count Items in total, $backpack_count Backpacks in total", $msg);
		$this->send($link, $sendto);
	} else {
		$syntax_error = true;
	}
} else {
	$msg = "Error! Bank.xml file not found! Please contact an administrator.";
	$this->send($msg, $sendto);
}
?>