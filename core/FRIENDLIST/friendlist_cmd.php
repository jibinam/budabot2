<?php
   /*
   ** Author: Lucier (RK1)
   ** Description: Friendlist_Module (Shows why a name is on the friend list)
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 30.06.2007
   ** Date(last modified): 30.06.2007
   */
   
if (preg_match("/^friendlist$/i", $message, $arg) || preg_match("/^friendlist (clean)$/i", $message, $arg)) {
	if ($arg[1] == "clean") {
		$cleanup = true;
	}

	$chatBot->send("One moment... (".count($chatBot->buddyList)." names to check.)", $sendto);

	$orphanCount = 0;
	if (count($chatBot->buddyList) == 0) {
		$chatBot->send("Didn't find any names in the friend list.", $sendto);
	} else {
		$blob = "<header> :::::: Friend List :::::: <end>\n\n";
		forEach ($chatBot->buddyList as $key => $value) {
			$removed = '';
			if (count($value['types']) == 0) {
				$orphanCount++;
				if ($cleanup) {
					Buddylist::remove($value['name']);
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
		$chatBot->send(Text::make_link("Friendlist Details", $blob), $sendto);
	}
} else if (preg_match("/^friendlist (.*)$/i", $message, $arg)) {
	$search = $arg[1];
	
	$chatBot->send("One momment... (".count($chatBot->buddyList)." names to check.)", $sendto);

	if (count($chatBot->buddyList) == 0) {
		$chatBot->send("Didn't find any names in the friend list.", $sendto);
	} else {
		$count = 0;
		$blob = "Friend Search: '{$search}'\n\n";
		forEach ($chatBot->buddyList as $key => $value) {
			$removed = '';
			if (preg_match("/$search/i", $value['name'])) {
				$count++;
				$blob .= $value['name'] . " " . implode(' ', array_keys($value['types'])) . "\n";
			}
		}

		if ($count > 0) {
			$chatBot->send(Text::make_link("Friend List Search Details", $blob), $sendto);
		} else {
			$chatBot->send("No friends on the friend list found containing '$search'", $sendto);
		}
	}
}

?>