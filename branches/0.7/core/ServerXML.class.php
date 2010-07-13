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

//class to get and parse the server statistics
class ServerXML extends XML {
	public $data;
	public $servermanager;
	public $clientmanager;
	public $chatserver;
	public $locked;
	public $omni;
	public $neutral;
	public $clan;
	public $name;
	public $errorInfo;
	public $errorCode = 0;

	//the constructor
	function __construct($rk_num = 0){
		//if no server was specified use the one where the bot is logged in
		if($rk_num == 0) {
			global $vars;
			$rk_num = $vars["dimension"];
		}

		//get the server status
		$this->lookup($rk_num);
	}

	function lookup($rk_num) {
		$serverstat = xml::getUrl("probes.funcom.com/ao.xml", 30);
		
		if($serverstat == NULL) {
			$this->errorCode = 1;
			$this->errorInfo = "Couldn't get Serverstatus for Dimension $rk_num";
			return;
		}

		if($rk_num == 4)
		$rk_num = "t";

		$data = xml::spliceData($serverstat, "<dimension name=\"d$rk_num", "</dimension>");
		preg_match("/locked=\"(0|1)\"/i", $data, $tmp);
		$this->locked = $tmp[1];

		preg_match("/<omni percent=\"([0-9.]+)\"\/>/i", $data, $tmp);
		$this->omni = $tmp[1];
		preg_match("/<neutral percent=\"([0-9.]+)\"\/>/i", $data, $tmp);
		$this->neutral = $tmp[1];
		preg_match("/<clan percent=\"([0-9.]+)\"\/>/i", $data, $tmp);
		$this->clan = $tmp[1];

		preg_match("/<servermanager status=\"([0-9]+)\"\/>/i", $data, $tmp);
		$this->servermanager = $tmp[1];
		preg_match("/<clientmanager status=\"([0-9]+)\"\/>/i", $data, $tmp);
		$this->clientmanager = $tmp[1];
		preg_match("/<chatserver status=\"([0-9]+)\"\/>/i", $data, $tmp);
		$this->chatserver = $tmp[1];
		
		preg_match("/display-name=\"(.+)\" loadmax/i", $data, $tmp);
		$this->name = $tmp[1];

		$data = xml::spliceMultiData($data, "<playfield", "/>");			
		foreach($data as $hdata) {
			if(preg_match("/id=\"(.+)\" name=\"(.+)\" status=\"(.+)\" load=\"(.+)\" players=\"(.+)\"/i", $hdata, $arr)) {
				$this->data[$arr[2]]["status"] = $arr[3];
				$this->data[$arr[2]]["load"] = $arr[4];
				$this->data[$arr[2]]["players"] = $arr[5];
			}				
		}
	} //end lookup function
} //end server class
?>
