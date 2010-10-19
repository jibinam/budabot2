<?php
	include 'buffstuffdb.php';
	
	// help screen
	$header = "<header>::::: Buff item helper - Version 1.00 :::::<end>\n\n";
	$footer = "by Imoutochan, RK1";

	$help = $header;
	$help .= "<font color=#3333CC>How to use 'what buffs what':</font>\n";
	$help .= "/tell <myname> <symbol>whatbuffs [<orange>skill<end>]\n";
	$help .= "[<orange>skill<end>] = full or partial name of the skill\n\n";
	$help .= "Example:\n";
	$help .= "You want to know what item is commonly used to get extra computer literacy skill.\n";
	$help .= "<a href='chatcmd:///tell <myname> <symbol>whatbuffs comp lit'>/tell <myname> <symbol>whatbuffs comp lit</a>\n\n";
	$help .= $footer;

	$helplink = Text::make_link("::How to use 'what buffs what?'::", $help);

	if (preg_match("/^whatbuffs (.+)$/i", $message, $arr)) {
		$name = trim($arr[1]);
		// check if key words are unambiguous
		$skills = array();
		$results = array();
		forEach ($skill_list as $skill) {
			if (matches($skill, $name)) {
				array_unshift($skills, $skill);
			}
		}

		switch (sizeof($skills)) {
			case 0:
				// skill does not exist
				$chatBot->send("There is no such skill, or at least no twink relevant skill going by that name.", $sendto);
				return;
			case 1:
				// exactly one matching skill
				$info = "";
				$found = 0;
				forEach ($buffitems as $key => $item_info) {	
					if (contains($item_info, $skills[0])) {
						$found++;
						$info .= "- <a href='chatcmd:///tell <myname> <symbol>buffitem $key'>$key</a>\n";
					}
				}

				// found items that modify this skill
				if ($found > 0) {
					$inside = $header;
					$inside .= "Your query of <yellow>$name<end> yielded the following results:\n\n";
					$inside .= "Items that buff ".$skills[0].":\n\n";
					$inside .= $info;
					$inside .= "\n\nClick the item(s) for more info\n\n".$footer;
					$windowlink = Text::make_link(":: Your \"What buffs ...?\" results ::", $inside);
					$chatBot->send($windowlink, $sendto); 
					$chatBot->send("<highlight>$found<end> result(s) in total", $sendto);
					return;
				} else {
					$chatBot->send("Nothing that buffs ".$skills[0]." in my database, sorry.", $sendto); return; 
				}
			default:
				// found more than 1 matching skill
				$info = "";
				forEach ($skills as $skill) {
					$info .= "- <a href='chatcmd:///tell <myname> <symbol>whatbuffs ".$skill."'>$skill</a>\n";
				}
				$inside = $header;
				$inside .= "Your query of <yellow>$name<end> matches more than one skill:\n\n";
				$inside .= $info."\n";
				$inside .= "Which of those skills did you mean?\n\n";
				$inside .= $footer;
				$windowlink = Text::make_link(":: Your \"What buffs ...?\" results ::", $inside);
				$chatBot->send($windowlink, $sendto); 
				$chatBot->send("Found several skills matching your key words.", $sendto);
				return;
		}
	} else {
		$chatBot->send($helplink, $sendto);
	}
?>