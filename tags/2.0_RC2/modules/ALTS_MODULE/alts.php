<?php
/*
 ** Author: Derroylo (RK2)
 ** Description: Alt Char Handling
 ** Version: 1.0
 **
 ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
 **
 ** Date(created): 23.11.2005
 ** Date(last modified): 21.11.2006
 **
 ** Copyright (C) 2005, 2006 Carsten Lohmann
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

if (preg_match("/^alts add (.+)$/i", $message, $arr)) {
	/* get all names in an array */
	$names = explode(' ', $arr[1]);
	
	$sender = ucfirst(strtolower($sender));
	
	$main = Alts::get_main($sender);
	$alts = Alts::get_alts($main);
	
	/* Pop a name from the array until none are left (checking for null) */
	while (null != ($name = array_pop($names))) {
		$name = ucfirst(strtolower($name));
		$uid = AoChat::get_uid($name);
		/* check if player exists */
		if (!$uid) {
			$names_not_existing []= $name;
			continue;
		}
		
		/* check if player is already an alt */
		if (in_array($name, $alts)) {
			$self_registered []= $name;
			continue;
		}
		
		/* check if player is already a main or assigned to someone else */
		$temp_alts = Alts::get_alts($name);
		$temp_main = Alts::get_main($name);
		if (count($temp_alts) != 0 || $temp_main != $name) {
			$other_registered []= $name;
			continue;
		}

		/* insert into database */
		Alts::add_alt($main, $name);
		$names_succeeded []= $name;
		
		// update character info
		Player::get_by_name($name);
	}
	
	$window = '';
	if ($names_succeeded) {
		$window .= "Alts added:\n" . implode(' ', $names_succeeded) . "\n\n";
	}
	if ($self_registered) {
		$window .= "Alts already registered to yourself:\n" . implode(' ', $self_registered) . "\n\n";
	}
	if ($other_registered) {
		$window .= "Alts already registered to someone else:\n" . implode(' ', $other_registered) . "\n\n";
	}
	if ($names_not_existing) {
		$window .= "Alts not existing:\n" . implode(' ', $names_not_existing) . "\n\n";
	}
	
	/* create a link */
	if (count($names_succeeded) > 0) {
		$link = 'Added '.count($names_succeeded).' alts to your list. ';
	}
	$failed_count = count($other_registered) + count($names_not_existing) + count($self_registered);
	if ($failed_count > 0) {
		$link .= 'Failed adding '.$failed_count.' alts to your list.';
	}
	$msg = $this->makeLink($link, $window);

	$this->send($msg, $sendto);
} else if (preg_match("/^alts (rem|del|remove|delete) (.+)$/i", $message, $arr)) {
	$name = ucfirst(strtolower($arr[2]));
	
	$main = Alts::get_main($sender);
	$alts = Alts::get_alts($main);
	
	if (!in_array($name, $alts)) {
		$msg = "<highlight>{$name}<end> is not registered as your alt.";
	} else {
		Alts::rem_alt($main, $name);
		$msg = "<highlight>{$name}<end> has been deleted from your alt list.";
	}
} else if (preg_match("/^alts main (.+)$/i", $message, $arr)) {
	$alt = $sender;
	$new_main = ucfirst(strtolower($arr[1]));

	$uid = $this->get_uid($new_main);
	if (!$uid)
	{
		$msg = " Player <highlight>$new_main<end> does not exist.";
		$this->send($msg, $sendto);
		return;
	}
	
	$current_main = Alts::get_main($sender);
	
	if ($current_main == $new_main) {
		$msg = "You are already registered as an alt of {$new_main}.";
		$this->send($msg, $sendto);
		return;
	}
	
	// let them know if they are changing the main for this char
	if ($current_main != $sender) {
		Alts::rem_alt($current_main, $sender);
		$msg = "You have been removed as an alt of $current_main.";
		$this->send($msg, $sendto);
	}

	Alts::add_alt($new_main, $sender);
	$msg = "You have been registered as an alt of {$new_main}.";
	$this->send($msg, $sendto);
} else if (preg_match('/^alts setmain (.+)$/i', $message, $arr)) {
	// check if new main exists
	$new_main = ucfirst(strtolower($arr[1]));
	$uid = $this->get_uid($new_main);
	if (!$uid) {
		$msg = "Player <highlight>{$new_main}<end> does not exist.";
		$this->send($msg, $sendto);
		return;
	}
	
	$current_main = Alts::get_main($sender);
	$alts = Alts::get_alts($current_main);
	
	if (!in_array($new_main, $alts)) {
		$msg = "<highlight>{$new_main}<end> must first be registered as your alt.";
		$this->send($msg, $sendto);
		return;
	}

	$db->beginTransaction();

	// remove all the old alt information
	$db->exec("DELETE FROM `alts` WHERE `main` = '{$current_main}'");

	// add current main to new main as an alt
	Alts::add_alt($new_main, $current_main);
	
	// add current alts to new main
	forEach ($alts as $alt) {
		if ($alt != $new_main) {
			Alts::add_alt($new_main, $alt);
		}
	}
	
	$db->commit();

	$msg = "Successfully set your new main as <highlight>{$new_main}<end>.";
	$this->send($msg, $sendto);
} else if (preg_match("/^alts (.+)$/i", $message, $arr) || preg_match("/^alts$/i", $message, $arr)) {
	if (isset($arr[1])) {
		$name = ucfirst(strtolower($arr[1]));
	} else {
		$name = $sender;
	}

	$main = Alts::get_main($name);
	$alts = Alts::get_alts($main);

	if (count($alts) == 0) {
		$msg = "No alts are registered for <highlight>{$name}<end>.";
		$this->send($msg, $sendto);
		return;
	}

	$list = "<header>::::: Alternative Character List :::::<end> \n \n";
	$list .= ":::::: Main Character\n";
	$list .= "<tab><tab>{$main}";
	$character = Player::get_by_name($main);
	if ($character !== null) {
		$list .= " (Level <highlight>{$character->level}<end>/<green>{$character->ai_level}<end> <highlight>{$character->profession}<end>)";
	}
	$online = $this->buddy_online($main);
	if ($online === null)
	{
		$list .= " - No status.\n";
	}
	elseif ($online == 1)
	{
		$list .= " - <green>Online<end>\n";
	}
	else
	{
		$list .= " - <red>Offline<end>\n";
	}
	$list .= ":::::: Alt Character(s)\n";
	forEach ($alts as $alt)
	{
		$list .= "<tab><tab>{$alt}";
		$character = Player::get_by_name($alt);
		if ($character !== null) {
			$list .= " (Level <highlight>{$character->level}<end>/<green>{$character->ai_level}<end> <highlight>{$character->profession}<end>)";
		}
		$online = $this->buddy_online($alt);
		if ($online === null)
		{
			$list .= " - No status.\n";
		}
		else if ($online == 1)
		{
			$list .= " - <green>Online<end>\n";
		}
		else
		{
			$list .= " - <red>Offline<end>\n";
		}
	}
	$msg = $this->makeLink($name."'s Alts", $list);
	$this->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>