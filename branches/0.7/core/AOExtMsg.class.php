<?php

/*
* $Id: AOExtMsg.class.php,v 1.1 2006/12/08 15:17:54 genesiscl Exp $
*
* Modified to handle the recent problem with the integer overflow
*
* Copyright (C) 2002-2005  Oskari Saarenmaa <auno@auno.org>.
*
* AOChat, a PHP class for talking with the Anarchy Online chat servers.
* It requires the sockets extension (to connect to the chat server..)
* from PHP 4.2.0+ and either the GMP or BCMath extension (for generating
* and calculating the login keys) to work.
*
* A disassembly of the official java chat client[1] for Anarchy Online
* and Slicer's AO::Chat perl module[2] were used as a reference for this
* class.
*
* [1]: <http://www.anarchy-online.com/content/community/forumsandchat/>
* [2]: <http://www.hackersquest.org/ao/>
*
* Updates to this class can be found from the following web site:
*   http://auno.org/dev/aochat.html
*
**************************************************************************
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
* USA
*
*/

/* New "extended" messages, parser and abstraction.
* These were introduced in 16.1.  The messages use postscript
* base85 encoding (not ipv6 / rfc 1924 base85).  They also use
* some custom encoding and references to further confuse things.
*
* Messages start with the magic marker ~& and end with ~
* Messages begin with two base85 encoded numbers that define
* the category and instance of the message.  After that there
* are an category/instance defined amount of variables which
* are prefixed by the variable type.  A base85 encoded number
* takes 5 bytes.  Variable types:
*
* s: string, first byte is the length of the string
* i: signed integer (b85)
* u: unsigned integer (b85)
* f: float (b85)
* R: reference, b85 category and instance
* F: recursive encoding
* ~: end of message
*
*
* Reference categories:
*  509 : House types (?)
*        0x00 : Normal House
* 2005 : Faction
*        0x00 : Neutral
*        0x01 : Clan
*        0x02 : Omni
*
*/

class AOExtMsg {
	private static $ref_cat = array(
		509 => array(
			0x00 => "Normal House",
			0x02 => "Market",
			0x03 => "Grid",
			0x04 => "Guard House",
			0x05 => "Radar Station",
			0x06 => "Cloaking Device"
		),
		
		2005 => array(
			0x00 => "neutral",
			0x01 => "clan",
			0x02 => "omni"
		)
	);
	public $type, $args, $category, $instance;

	function AOExtMsg($str = NULL) {
		$this->type = AOEM_UNKNOWN;
		if(!empty($str))
		$this->read($str);
	}
	
	function arg($n) {
		$key = "{".strtoupper($n)."}";
		if(isset($this->args[$key]))
		return $this->args[$key];
		return NULL;
	}

	function read($msg) {
		if (substr($msg, 0, 2) !== "~&") {
			return false;
		}
		$msg = substr($msg, 2);
		$this->category = $this->b85g($msg);
		$this->instance = $this->b85g($msg);
		
		$args = array();
		while ($msg != '') {
			$data_type = $msg[0];
			$msg = substr($msg, 1); // skip the data type id
			switch($data_type) {
				case "s":
					$len = ord($msg[0])-1;
					$str = substr($msg, 1, $len);
					$msg = substr($msg, $len +1);
					$args[] = $str;
					break;

				case "i":
				case "u":
					$num = $this->b85g($msg);
					$args[] = $num;
					break;
					
				case "R":
					$cat = $this->b85g($msg);
					$ins = $this->b85g($msg);
					if (!isset(self::$ref_cat[$cat]) || !isset(self::$ref_cat[$cat][$ins])) {
						$str = "Unknown ($cat, $ins)";
					} else {
						$str = self::$ref_cat[$cat][$ins];
					}
					$args[] = $str;
					break;

				case "~":
					// the last iteration is the closing tilde
					// for which we need to do nothing
					break;

				default:
					echo "Error! could not parse argument: '$data_type' for category: '$this->category' and instance: '$this->instance'\n";
					break;
			}
		}
		
		$this->type = '';  // remove AOEM_UNKNOWN value
		$this->args = $args;
	}

	function b85g(&$str) {
		$n = 0;
		for($i=0; $i<5; $i++) {
			$n = $n*85 + ord($str[$i])-33;
		}
		$str = substr($str, 5);
		return $n;
	}
	
	public static function get_extended_message($em) {
		global $db;
	
		$db->query("SELECT category, entry, message FROM mmdb_data WHERE category = $em->category AND entry = $em->instance");
		if ($row = $db->fObject()) {
			$message = vsprintf($row->message, $em->args);
		} else {
			echo "Error: cannot find extended message with category: '$em->category' and instance: '$em->instance'\n";
		}
		return $message;
	}
}

?>