<?php
/*
** Author: Sebuda, Derroylo (RK2)
** Description: AO xml abstaction layer for guild info, whois, player history and server status.
** Version: 1.1
**
** Developed for: Budabot(http://sourceforge.net/projects/budabot)
**
** Date(created): 01.10.2005
** Date(last modified): 16.01.2007
**
** Copyright (C) 2005, 2006, 2007 Carsten Lohmann and J. Gracik
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

//the whois class is downloading/caching/verifying an player XML file
class WhoisXML extends XML {
	public $firstname;
	public $name;
	public $lastname;
	public $level;
	public $breed;
	public $gender;
	public $faction;
	public $prof;
	public $prof_title;
	public $ai_rank;
	public $ai_level;
	public $organization_id;
	public $org;
	public $rank;
	public $rank_id;
	public $between;
	public $errorInfo;
	public $errorCode = 0;
	
	//construktor of the class
	function __construct($name, $rk_num = 0, $cache = 0){
		//if no server number is specified use the one on which the bot is logged in
		if ($rk_num == 0) {
			global $vars;
			$rk_num = $vars["dimension"];
		}

		//if no specific cachefolder is defined use the one from config.php
		if ($cache == 0) {
			global $vars;
			$cache = $vars["cachefolder"];
		}

		//Making sure that the cache folder exists
		if (!dir($cache)) {
			mkdir($cache, 0777);
		}

		//Character lookup        		
		$this->lookup($name, $rk_num, $cache);
	}

	//the player lookup itself
	function lookup($name, $rk_num, $cache) {
		$data_found = false;
		$data_save = false;
		$name = ucfirst(strtolower($name));
		
		//Check if a xml file of the person exists, that it isn't older then 24hrs and correct
		if (file_exists("$cache/$name.$rk_num.xml")) {
			$mins = (time() - filemtime("$cache/$name.$rk_num.xml")) / 60;
			$hours = floor($mins/60);
			if ($hours < 24 && $fp = fopen("$cache/$name.$rk_num.xml", "r")) {
				while (!feof ($fp)) {
					$playerbio .= fgets ($fp, 4096);
				}
				fclose($fp);
				if (xml::spliceData($playerbio, '<nick>', '</nick>') == $name) {
					$data_found = true;
				} else {
					$data_found = false;
					unset($playerbio);
					@unlink("$cache/$name.$rk_num.xml");
				}
			}
		}
		
		//If no file was found or it is outdated try to update it from anarchyonline.com
		if (!$data_found) {
			$playerbio = xml::getUrl("http://people.anarchy-online.com/character/bio/d/$rk_num/name/$name/bio.xml");
			if (xml::spliceData($playerbio, '<nick>', '</nick>') == $name) {
				$data_found = true;
				$data_save = true;
			} else {
				$data_found = false;
				unset($playerbio);
			}
		}
		
		//If ao.com was too slow to respond or got wrong data back try to update it from auno.org
		if (!$data_found) {
			$playerbio = xml::getUrl("http://auno.org/ao/char.php?output=xml&dimension=$rk_num&name=$name");
			if (xml::spliceData($playerbio, '<nick>', '</nick>') == $name) {
				$data_found = true;
				$data_save = true;
			} else {
				$data_found = false;
				unset($playerbio);
			}
		}
		
		//If both site were not responding or the data was invalid and a xml file exists get that one
		if (!$data_found && file_exists("$cache/$name.$rk_num.xml")) {
			if ($fp = fopen("$cache/$name.$rk_num.xml", "r")) {
				while(!feof ($fp))
				$playerbio .= fgets ($fp, 4096);
				fclose($fp);

				if(xml::spliceData($playerbio, '<nickname>', '</nickname>') == $name)
				$data_found = true;
				else {
					$data_found = false;
					unset($playerbio);
					@unlink("$cache/$name.$rk_num.xml");
				}
			}
		}
		
		//if there is still no valid data available give an error back
		if (!$data_found) {
			$this->firstname = "";
			$this->lastname = "";
			$this->rank_id = 6;
			$this->rank = "Applicant";
			$this->level = "1";
			$this->prof = "Unknown";
			$this->gender = "Unknown";
			$this->breed = "Unknown";
			$this->errorCode = 1;
			$this->errorInfo = "Couldn't get Character infos for $name";
			return;
		}

		//parsing of the player data		
		$this->firstname	= xml::spliceData($playerbio, '<firstname>', '</firstname>');
		$this->name         = xml::spliceData($playerbio, '<nick>', '</nick>');
		$this->lastname     = xml::spliceData($playerbio, '<lastname>', '</lastname>');
		$this->level        = xml::spliceData($playerbio, '<level>', '</level>');
		$this->breed        = xml::spliceData($playerbio, '<breed>', '</breed>');
		$this->gender       = xml::spliceData($playerbio, '<gender>', '</gender>');
		$this->faction      = xml::spliceData($playerbio, '<faction>', '</faction>');
		$this->prof         = xml::spliceData($playerbio, '<profession>', '</profession>');
		$this->prof_title   = xml::spliceData($playerbio, '<profession_title>', '</profession_title>');
		$this->ai_rank      = xml::spliceData($playerbio, '<defender_rank>', '</defender_rank>');
		$this->ai_level     = xml::spliceData($playerbio, '<defender_rank_id>', '</defender_rank_id>');
		$this->org_id       = xml::spliceData($playerbio, '<organization_id>', '</organization_id>');
		$this->org          = xml::spliceData($playerbio, '<organization_name>', '</organization_name>');
		$this->rank         = xml::spliceData($playerbio, '<rank>', '</rank>');
		$this->rank_id      = xml::spliceData($playerbio, '<rank_id>', '</rank_id>');

		//if a new xml file is downloaded save it		
		if ($data_save) {
			$fp = fopen("$cache/$name.$rk_num.xml", "w");
			fwrite($fp, $playerbio);
			fclose($fp);
		}
	}
} //end of whois

?>
