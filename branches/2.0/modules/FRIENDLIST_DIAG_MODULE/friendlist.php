<?php
   /*
   ** Author: Lucier (RK1)
   ** Description: Friendlist_Diag_Module (Shows why a name is on the friendslist)
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 30.06.2007
   ** Date(last modified): 30.06.2007
   */
 
if (preg_match("/^friendlist(.+)?$/i", $message, $arg)) {
	if ($arg[1] == " clean") {
		$cleanup = true;
	}

	$chatBot->send("One momment... (".count($chatBot->buddyList)." names to check.)", $sendto);

	$orphanCount = 0;
	if (count($chatBot->buddyList) == 0) {
		$chatBot->send("Didn't find any names in the friendlist.", $sendto);
		return;
	}

	forEach ($chatBot->buddyList as $key => $value) {
		$removed = '';
		if (count($value['types']) == 0) {
			$orphanCount++;
			if ($cleanup) {
				Buddylist::remove($key);
				$removed = "<red>REMOVED<end>";
			}
		}

		$blob .= $value['name'] . " $removed " . implode(' ', array_keys($value['types'])) . "\n";
	}

	if ($cleanup) {
		$blob .="\n\nRemoved: ($orphanCount)";
	} else {
		$blob .= "\n\nUnknown: ($orphanCount) ";
		if ($orphanCount > 0) {
			$blob .= Text::make_link('Remove Orphans', '/tell <myname> <symbol>friendlist clean', 'chatcmd');
		}
	}
	$chatBot->send(Text::make_blob("Friendlist Details", $blob), $sendto);
}
?>