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
	if (preg_match("/^id ([0-9 ]+)$/i", $message, $arr)) {
		$arr = $arr[1];
		$item_count = 0;
		$msg = "";
		forEach ($xml->children() as $base_container) {// Loops through inventory and bank
			forEach ($base_container->children() as $base_slot) {// Loops through items and backpacks
				if ($base_slot->getName() == "item" && preg_match("/\b".$base_slot['id']."\b/i", $arr)) {
					$msg .= Text::make_item($base_slot['lowid'], $base_slot['highid'], $base_slot['ql'], $base_slot['name'])."\nItem ID: ".$base_slot['id']."\nLocation: ".ucwords($base_container->getName())."\n\n";
					$item_count++;
				} else if ($base_slot->getName() == "backpack") {
					forEach ($base_slot->children() as $item) {// Loops through items in backpacks
						if (preg_match("/\b".$item['id']."\b/i", $arr)) {
							$msg .= Text::make_item($item['lowid'], $item['highid'], $item['ql'], $item['name'])."\nItem ID: ".$item['id']."\nLocation: ".ucwords($base_container->getName())." > Backpack #".$base_slot['id']."\n\n";
							$item_count++;
						}
					}
				}
			}
		}
		if ($item_count == 0) {
			$link = "No items found.";
		} else {
			$link = Text::make_blob($item_count." bank items found", $msg);
		}
		$chatBot->send($link, $sendto);
	} else {
		$syntax_error = true;
	}
} else {
	$msg = "Error! Bank.xml file not found! Please contact an administrator.";
	$chatBot->send($msg, $sendto);
}
?>