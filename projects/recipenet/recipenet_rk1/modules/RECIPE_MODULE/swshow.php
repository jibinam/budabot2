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

		$category = eregi_replace("^swshow ","", $message);
		
		$recipe_text = "<center>";
		
   		$db->query("SELECT recipe_name, recipe_id FROM recipes WHERE recipe_name like '$category%' AND recipe_type = '8'");
		$recipe_count = $db->numrows();
		if ($recipe_count == 0) {
        	$msg = "No recipes found .. sorry.";
		} else {
			$header_text = "\n<center><header>:::: Recipe Results ::::<end>\n\n\n<font color=#9CD6DE>--------------------------------------</font>\n\n<font color=#63E78C>Standard Weapon Recipes beginning with: ".$category."</font>\n\n";
			while($row = $db->fObject()) {
						$recipe_id = $row->recipe_id;
						$recipe_name = $row->recipe_name;
						$recipe_list .= "<a href='chatcmd:///tell <myname> rshow ".$recipe_id."'>".$recipe_name."</a>\n\n";
			}
	$footer_text = "\n\n<font color=#9CD6DE>====================================</font>";
	$footer_text .= "\n<a href='chatcmd:///tell <myname> recipe'><myname> Main Menu</a> / <a href='chatcmd:///tell <myname> help'>Help</a> / <a href='chatcmd:///tell <myname> about'>About</a>\n";
	$footer_text .= "<font color=#9CD6DE>====================================</font>\nThank you for using <myname>.\nPart of the <highlight>A<end>narchy <highlight>O<end>nline bot <highlight>NET<end>work\n\n<font color=#FFFFFF>Visit our new website! <a href='chatcmd:///start http://aorecipenet.com'>AORecipeNET.com</a></font>\n\n</center>";
			$recipe_list = $header_text.$recipe_list.$footer_text;

    	$msg = bot::makeLink('Standard Weapons - '.$category, $recipe_list);
		}
		
        bot::send($msg, $sender);

?>