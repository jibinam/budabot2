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

$type_id = eregi_replace("^tshow ","", $message);

		bot::send('searching ...', $sender);

		$db->query("SELECT * FROM type WHERE type_id = '$type_id'");
			while($row = $db->fObject()) {
				$type_name = $row->type_name;
			}


		$db->query("SELECT * FROM recipes WHERE recipe_type = '$type_id' ORDER BY recipe_name");
		$recipe_count = $db->numrows();

		$msg = "\n<center><font color=#FFFF00>:::: <myname> found <highlight>".$recipe_count."<end> recipes for ".$type_name." ::::</font>\n";
		$msg .= "\n--------------------------------------\n\n";
		
			while($row = $db->fObject()) {
				$recipe_name = $row->recipe_name;
				$recipe_id = $row->recipe_id;
				$msg .= "<a href='chatcmd:///tell <myname> rshow ".$recipe_id."'>".$recipe_name."</a>\n";
			}

	$msg .= "\n======================================";
	$msg .= "\n<a href='chatcmd:///tell <myname> recipe'><myname> Main Menu</a> / <a href='chatcmd:///tell <myname> help'>Help</a> / <a href='chatcmd:///tell <myname> about'>About</a>\n\n<font color=#FFFFFF>Visit our new website! <a href='chatcmd:///start http://aorecipenet.com'>AORecipeNET.com</a></font>\n\n</center>";

		$msg = bot::makeLink('List of Recipes', $msg);

        bot::send($msg, $sender);

?>