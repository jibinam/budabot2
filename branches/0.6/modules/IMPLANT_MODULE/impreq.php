<?php
   /*
   ** Module: IMPLANT
   ** Author: Tyrence/Whiz (RK2)
   ** Description: Allows you lookup information on a specific ql of implant.
   ** Version: 2.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 13-October-2007
   ** Date(last modified): 9-Mar-2010
   **
   ** Copyright (C) 2009 Jason Wheeler (bigwheels16@hotmail.com)
   **
   ** Licence Infos:
   ** This file is an addon to Budabot.
   **
   ** This module is free software; you can redistribute it and/or modify
   ** it under the terms of the GNU General Public License as published by
   ** the Free Software Foundation; either version 2 of the License, or
   ** (at your option) any later version.
   **
   ** This module is distributed in the hope that it will be useful,
   ** but WITHOUT ANY WARRANTY; without even the implied warranty of
   ** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   ** GNU General Public License for more details.
   **
   ** You should have received a copy of the GNU General Public License
   ** along with this module; if not, write to the Free Software
   ** Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
   **
   ** This module may be obtained at: http://www.box.net/shared/bgl3cx1c3z
   **
   */

require_once('implant_functions.php');

// curly braces keep module variables from becoming global
{
	$invalidInputMsg = "<br />Usage: <symbol>impreq &lt;ability_skill&gt; &lt;treatment_skill&gt;<br />You must enter values between 1 and 3000.";
	$msg = "";
	$arr = array();

	if (!eregi("^impreq ([0-9]+) ([0-9]+)$", $message, $arr)) {

		$msg = $invalidInputMsg;

	} else {

		// get the argument and set the ability and treatment variables
		$ability = $arr[1];
		$treatment = $arr[2];

		if ($treatment < 11 || $ability < 6) {

			$msg = "You do not have enough requirements to wear an implant.";

		} else {

			$obj = findMaxImplantQlByReqs($ability, $treatment);
			$clusterInfo = formatClusterBonuses($obj);
			$link = bot::makeLink("ql $obj->ql", $clusterInfo, 'text');

			$msg = "\nThe highest ql implant you can wear is $link which requires:\nTreatment: $obj->treatment\nAbility: $obj->ability";
		}
	}

	if ($type == "msg") {
	    bot::send($msg, $sender);
	} else if ($type == "priv") {
	    bot::send($msg);
	} else if ($type == "guild") {
	    bot::send($msg, "guild");
	}
}

?>
