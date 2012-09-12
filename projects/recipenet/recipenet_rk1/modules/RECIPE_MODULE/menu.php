<?
   /*
   ** Author: Captainzero (RK1)
   ** Description: Main Menu
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 11/11/2008
   ** Date(last modified): 11/11/2008
   ** 
   */

		$db->query("SELECT * FROM recipes WHERE recipe_type != '8'");
		$recipe_count = ($db->numrows()) + 1;	

	$main_menu = "\n<center><font color=#FFFF00>:::: <myname> Main Menu ::::</font>\n";
	$main_menu .= "\n<font color=#9CD6DE>--------------------------------------</font>\n\n";
	$main_menu .= "<img src=rdb://151011>\n";
	$main_menu .= "<font color=#DEDE42>\n<myname> Quick Guide:</font>\n";
	$main_menu .= "<font color=#FFFFFF>Drop items into <myname> to discover their use\n";
	$main_menu .= "To use:</font> /tell <myname> -drop item here-\n";
	$main_menu .= "<font color=#FFFFFF>or search using</font> /tell <myname> search -search string-</font>\n";
	$main_menu .= "<font color=#FFFFFF>or simply browse through the categories below...</font>\n";
	$main_menu .= "\n<font color=#9CD6DE>--------------------------------------</font>";
	$main_menu .= "\n\n<font color=#DEDE42>We currently have ".$recipe_count." recipes on our database</font>\n";
	$main_menu .= "\n<font color=#9CD6DE>--------------------------------------</font>\n\n";
		$db->query("SELECT * FROM type WHERE type_visible = '1'");
			while($row = $db->fObject()) {
				$type_id = $row->type_id;
				$type_name = $row->type_name;
				$main_menu .= "<a href='chatcmd:///tell <myname> tshow ".$type_id."'>".$type_name."</a>\n";
			}


$main_menu .= "\n\n<font color=#9CD6DE>======================================";
	$main_menu .= "\n<a href='chatcmd:///tell <myname> recipe'><myname> Main Menu</a> / <a href='chatcmd:///tell <myname> help'>Help</a> / <a href='chatcmd:///tell <myname> about'>About</a></font>\n\nVisit our new website: <a href='chatcmd:///start http://aorecipenet.com'>AORecipeNET.com</a>";

	$main_menu .= "";
	
	$msg = bot::makeLink('Main Menu', $main_menu);

	bot::send($msg, $sender);

?>