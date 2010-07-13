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

//the history class is downloading/caching/verifying an player history XML file
class HistoryXML extends xml{
	public $name;
	public $data;
	public $errorInfo;
	public $errorCode = 0;

	//constructor of the class
	function __construct($name, $rk_num = 0, $cache = 0){
		//if no server number is specified use the one on which the bot is logged in
		if($rk_num == 0) {
			global $vars;
			$rk_num = $vars["dimension"];
		}

		//if no specific cachefolder is defined use the one from config.php
		if($cache == 0) {
			global $vars;
			$cache = $vars["cachefolder"];
		}

		//Making sure that the cache folder exists
		if(!dir($cache))
		mkdir($cache, 0777);

		$this->lookup($name, $rk_num, $cache);
	} //end constructor

	//the lookup function
	function lookup($name, $rk_num, $cache) {
		$data_found = false;
		$data_save = false;
		$name = ucfirst(strtolower($name));
		
		//Check if a xml file of the person exists and if it is uptodate
		if(file_exists("$cache/$name.$rk_num.history.xml")) {
			$mins = (time() - filemtime("$cache/$name.$rk_num.history.xml")) / 60;
			$hours = floor($mins/60);
			if($hours < 24 && $fp = fopen("$cache/$name.$rk_num.history.xml", "r")) {
				while(!feof ($fp))
				$playerhistory .= fgets ($fp, 4096);
				fclose($fp);
				if(xml::spliceData($playerhistory, '<nick>', '</nick>') == $name)
				$data_found = true;
				else {
					$data_found = false;
					unset($playerhistory);
					@unlink("$cache/$name.$rk_num.history.xml");
				}
			}
		}
		
		//If no old history file was found or it was invalid try to update it from auno.org
		if(!$data_found) {
			$playerhistory = xml::getUrl("http://auno.org/ao/char.php?output=xml&dimension=$rk_num&name=$name", 20);
			if(xml::spliceData($playerhistory, '<nick>', '</nick>') == $name) {
				$data_found = true;
				$data_save = true;
			} else {
				$data_found = false;
				unset($playerhistory);
			}
		}
		
		//If the site was not responding or the data was invalid and a xml file exists get that one
		if(!$data_found && file_exists("$cache/$name.$rk_num.history.xml")) {
			if ($fp = fopen("$cache/$name.$rk_num.history.xml", "r")) {
				while(!feof($fp))
				$playerhistory .= fgets($fp, 4096);
				fclose($fp);

				if(xml::spliceData($playerhistory, '<nick>', '</nick>') == $name)
				$data_found = true;
				else {
					$data_found = false;
					unset($playerhistory);
					@unlink("$cache/$name.$rk_num.history.xml");
				}
			}
		}
		
		//if there is still no valid data available give an error back
		if(!$data_found) {
			$this->errorCode = 1;
			$this->errorInfo = "Couldn't get History of $name";
			return;
		}

		//parsing of the xml file		
		$data = xml::spliceData($playerhistory, "<history>", "</history>");
		$data = xml::splicemultidata($data, "<entry", "/>");
		foreach($data as $hdata) {
			preg_match("/date=\"(.+)\" level=\"(.+)\" ailevel=\"(.*)\" faction=\"(.+)\" guild=\"(.*)\" rank=\"(.*)\"/i", $hdata, $arr);
			$this->data[$arr[1]]["level"] = $arr[2];
			$this->data[$arr[1]]["ailevel"] = $arr[3];
			$this->data[$arr[1]]["faction"] = $arr[4];
			$this->data[$arr[1]]["guild"] = $arr[5];
			$this->data[$arr[1]]["rank"] = $arr[6];																				
		}
		
		//if he downloaded a new xml file save it in the cache folder
		if($data_save) {
			$fp = fopen("$cache/$name.$rk_num.history.xml", "w");
			fwrite($fp, $playerbio);
			fclose($fp);
		}    	
	} //end of lookup
} //end of history class

?>
