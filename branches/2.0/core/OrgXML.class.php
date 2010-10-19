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

//the org class is downloading/caching/verifying an org XML file
class OrgXML extends XML {
	public $members;
	public $member;
	public $errorCode = 0;
	public $errorInfo;

	//contructor of the class
	function __construct($organization_id = 0, $rk_num = 0, $cache = 0, $force_update = false) {
		global $vars;
	
		//if no server number is specified use the one on which the bot is logged in
		if $rk_num == 0) {
			$rk_num = $vars["dimension"];
		}

		//if no specific cachefolder is defined use the one from config.php
		if ($cache == 0) {
			$cache = $vars["cachefolder"];
		}

		//Making sure that the cache folder exists
		if (!dir($cache)) {
			@mkdir($cache, 0777);
		}
		
		//organisation lookup
		$this->lookup($organization_id, $rk_num, $cache, $force_update);
	} //end of contructor
	
	//the organisation lookup function
	function lookup($organization_id, $rk_num, $cache, $force_update) {
		global $vars;
		$data_found = false;
		$data_save = false;
		
		//Check if a xml file of the person exists and if it is uptodate
		if (!force_update && file_exists("$cache/$organization_id.$rk_num.xml")) {
			$mins = (time() - filemtime("$cache/$organization_id.$rk_num.xml")) / 60;
			$hours = floor($mins/60);
			//if the file is not older then 24hrs and it is not the roster of the bot guild then use the cache one, when it the xml file from the org bot guild and not older then 6hrs use it
			if (($hours < 24 && $vars["guild_id"] != $organization_id) || ($hours < 6 && $vars["guild_id"] == $organization_id)) {
				$orgxml = file_get_contents("$cache/$organization_id.$rk_num.xml");
				if (xml::spliceData($orgxml, '<id>', '</id>') == $organization_id) {
					$data_found = true;
				} else {
					$data_found = false;
					unset($orgxml);
					@unlink("$cache/$organization_id.$rk_num.xml");
				}
			}
		}
		
		//If no file was found or it is outdated try to update it from anarchyonline.com
		if (!$data_found) {
			$orgxml = xml::getUrl("http://people.anarchy-online.com/org/stats/d/$rk_num/name/$organization_id/basicstats.xml", 30);
			if (xml::spliceData($orgxml, '<id>', '</id>') == $organization_id) {
				$data_found = true;
				$data_save = true;
			} else {
				$data_found = false;
				unset($orgxml);
			}
		}
		
		//If the site was not responding or the data was invalid and a xml file exists get that one
		if (!$data_found && file_exists("$cache/$organization_id.$rk_num.xml")) {
			$orgxml = file_get_contents("$cache/$organization_id.$rk_num.xml");
			if (xml::spliceData($orgxml, '<id>', '</id>') == $name) {
				$data_found = true;
			} else {
				$data_found = false;
				unset($orgxml);
				@unlink("$cache/$organization_id.$rk_num.xml");
			}
		}
		//if there is still no valid data available give an error back
		if (!$data_found) {
			$this->errorCode = 1;
			$this->errorInfo = "Couldn't get infos for the organization";
			return;
		}

		//parsing of the memberdata
		$members = xml::splicemultidata($orgxml, "<member>", "</member>");
		$this->orgname	= xml::spliceData($orgxml, "<name>", "</name>");
		$this->orgside	= xml::spliceData($orgxml, "<side>", "</side");

		global $chatBot;
		forEach ($members as $amember) {
			$name								= xml::splicedata($amember,"<nickname>", "</nickname>");
			$this->member[]						= $name;
			$this->members[$name]["firstname"]	= xml::spliceData($amember, '<firstname>', '</firstname>');
			$this->members[$name]["firstname"]	= xml::spliceData($amember, '<firstname>', '</firstname>');
			$this->members[$name]["name"] 		= xml::spliceData($amember, '<nickname>', '</nickname>');
			$this->members[$name]["lastname"]	= xml::spliceData($amember, '<lastname>', '</lastname>');
			$this->members[$name]["level"]		= xml::spliceData($amember, '<level>', '</level>');
			$this->members[$name]["breed"]		= xml::spliceData($amember, '<breed>', '</breed>');
			$this->members[$name]["gender"]		= xml::spliceData($amember, '<gender>', '</gender>');
			$this->members[$name]["faction"]	= $this -> orgside;
			$this->members[$name]["profession"]	= xml::spliceData($amember, '<profession>', '</profession>');
			$this->members[$name]["ai_rank"]	= xml::spliceData($amember, '<defender_rank>', '</defender_rank>');
			$this->members[$name]["ai_level"]	= xml::spliceData($amember, '<defender_rank_id>', '</defender_rank_id>');
			$this->members[$name]["rank"]		= xml::spliceData($amember, '<rank_name>', '</rank_name>');
			$this->members[$name]["rank_id"]	= xml::spliceData($amember, '<rank>', '</rank>');					
			$this->members[$name]["id"]			= $chatBot->get_uid($name);
		}

		//if a new xml file was downloaded, save it
		if ($data_save) {
			$fp = fopen("$cache/$organization_id.$rk_num.xml", "w");
			fwrite($fp, $orgxml);
			fclose($fp);
		}	
	} //end lookup
} //end class org

?>
