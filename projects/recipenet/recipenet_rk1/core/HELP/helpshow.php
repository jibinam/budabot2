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

$help_id = eregi_replace("^hshow ","", $message);

		$db->query("SELECT * FROM helpfiles WHERE help_id = '$help_id'");
		
			while($row = $db->fObject()) {
				$help_name = $row->help_topic;
				$help_text = $row->help_text;
			}
		$header_text .= "\n<center><header>:::: <myname> Help - ".$help_name." ::::<end>\n\n\n<font color=#9CD6DE>--------------------------------------</font>\n\n";
		$footer_text = "\n\n<font color=#9CD6DE>====================================</font>";
		$footer_text .= "\n<a href='chatcmd:///tell <myname> recipe'><myname> Main Menu</a> / <a href='chatcmd:///tell <myname> help'>Help</a> / <a href='chatcmd:///tell <myname> about'>About</a>\n";
		$footer_text .= "<font color=#9CD6DE>====================================</font>\nThank you for using <myname>.\nPart of the <highlight>A<end>narchy <highlight>O<end>nline bot <highlight>NET<end>work\n\n<font color=#FFFFFF>Visit our new website! <a href='chatcmd:///start http://aorecipenet.com'>AORecipeNET.com</a></font>\n\n</center>";

		$full_blob = $header_text.$help_text.$footer_text;
		
		$msg = bot::makeLink('Help on '.$help_name, $full_blob);

		bot::send($msg, $sender);

?>