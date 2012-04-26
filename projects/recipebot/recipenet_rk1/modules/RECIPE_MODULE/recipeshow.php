<?
   /*
   ** Author: Captainzero (RK1)
   ** Description: Recipe module
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 11/11/2008
   ** Date(last modified): 11/11/2008
   ** 
   */

		bot::send('searching ...', $sender);

		$recipe_id = eregi_replace("^rshow ","", $message);
		
		$recipe_text = "<center>";
		
		$db->query("SELECT * FROM recipes WHERE recipe_id = '$recipe_id'");

	$rcount = $db->numrows();
		
		if ($rcount !== 0) {
			
			while($row = $db->fObject()) {
				$recipe_name = $row->recipe_name;
				$recipe_text .= "\n<heading>:::: Recipe for ".$recipe_name." ::::<end>\n\n";
				$recipe_text .= $row->recipe_text;
			}

	$recipe_text = ereg_replace("#C([0-9]+)","[16,\\1]",$recipe_text);
	$recipe_text = ereg_replace('#L "([^"]+)" "([0-9]+)"','#L "\\1" "/tell <myname> ishow \\2"',$recipe_text);
	$recipe_text = str_replace ("'","`",$recipe_text);
	$recipe_text = ereg_replace("#C([0-9]+)","[16,\\1]",$recipe_text);

$recipe_text = str_replace("[16,1]", "<font color=#FFFFFF>",
	str_replace("[16,2]", "</font><font color=#FFFFFF>",
	str_replace("[16,3]", "</font><font color=#FFFFFF>",
	str_replace("[16,4]", "</font><font color=#FFFFFF>",
	str_replace("[16,5]", "</font><font color=#FFFFFF>",
	str_replace("[16,6]", "</font><font color=#FFFFFF>",
	str_replace("[16,7]", "</font><font color=#FFFFFF>",
	str_replace("[16,8]", "</font><font color=#FFFFFF>",
	str_replace("[16,9]", "</font><font color=#FFFFFF>",
	str_replace("[16,10]","</font><font color=#FFFFFF>",
	str_replace("[16,11]","</font><font color=#FFFFFF>",
	str_replace("[16,12]","</font><font color=#FF0000>",
	str_replace("[16,13]","</font><font color=#FFFFFF>",
	str_replace("[16,14]","</font><font color=#FFFFFF>",
	str_replace("[16,15]","</font><font color=#FFFFFF>",
	str_replace("[16,16]","</font><font color=#FFFF00>",
	str_replace("[16,17]","</font><font color=#FFFFFF>",
	str_replace("[16,18]","</font><font color=#AAFF00>",
	str_replace("[16,19]","</font><font color=#FFFFFF>",
	str_replace("[16,20]","</font><font color=#009B00>",
	str_replace("[16,21]","</font><font color=#FFFFFF>",
	str_replace("[16,22]","</font><font color=#FFFFFF>",
	str_replace("[16,23]","</font><font color=#FFFFFF>",
	str_replace("[16,24]","</font><font color=#FFFFFF>",
	str_replace("[16,25]","</font><font color=#FFFFFF>",
	str_replace("[16,26]","</font><font color=#FFFFFF>",
	str_replace("[16,27]","</font><font color=#FFFFFF>",
	str_replace("[16,28]","</font><font color=#FFFFFF>",
	str_replace("[16,29]","</font><font color=#FFFFFF>",
	str_replace("[16,30]","</font><font color=#FFFFFF>",
	str_replace("[16,31]","</font><font color=#FFFFFF>",
	str_replace("[17]",chr(17),
	str_replace("[18]",chr(18),$recipe_text)))))))))))))))))))))))))))))))));
	$recipe_text = ereg_replace('#L "([^"]+)" "([^"]+)"',"<a href='chatcmd://\\2'>\\1</a>",$recipe_text);
	$recipe_text = ereg_replace('"',"&quot;",$recipe_text);
	$recipe_text .= "\n\n</font><font color=#9CD6DE>====================================";
	$recipe_text .= "\n<a href='chatcmd:///tell <myname> recipe'><myname> Main Menu</a> / <a href='chatcmd:///tell <myname> help'>Help</a> / <a href='chatcmd:///tell <myname> about'>About</a>\n";
	$recipe_text .= "====================================\nThank you for using <myname>.\nPart of the <highlight>A<end>narchy <highlight>O<end>nline bot <highlight>NET<end>work\n\n";
	$recipe_text .= "To report a problem or an incorrect recipe use:\n<highlight>/tell <myname> report -your message-<end>";
	$recipe_text .= "</font></center>";
	
    	$msg = bot::makeLink('Recipe for '.$recipe_name, $recipe_text);

		} else {
			
			$msg = "There is no such recipe ... sorry";
			
		}
        bot::send($msg, $sender);

?>