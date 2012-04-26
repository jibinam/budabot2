<?
   /*
   ** Author: Captainzero (RK1)
   ** Description: Search Feature
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 11/11/2008
   ** Date(last modified): 11/11/2008
   ** 
   */

	$search_string = eregi_replace("^search ","", $message);
	$search_string = eregi_replace(" ","%", $search_string);

    $db->query("SELECT * FROM recipes WHERE recipe_text like '%$search_string%' AND recipe_type != '8'");
	$recipes = $db->numrows();

	if($db->numrows() == 0) {
        $msg = "Could not find any recipes matching your query .. sorry.";
	} else {
		$header_text = "\n<center><header>:::: Recipe Results ::::<end>\n\n\n<font color=#9CD6DE>--------------------------------------</font>\n\n<font color=#63E78C>Your search found matches in the following recipes:</font>\n\n";

		while($row = $db->fObject()) {
			$recipe_name = $row->recipe_name;
			$recipe_id = $row->recipe_id;
			$recipe_list .= "<a href='chatcmd:///tell <myname> rshow ".$recipe_id."'>".$recipe_name."</a>\n\n";
		}
	$footer_text = "\n\n<font color=#9CD6DE>====================================</font>";
	$footer_text .= "\n<a href='chatcmd:///tell <myname> recipe'><myname> Main Menu</a> / <a href='chatcmd:///tell <myname> help'>Help</a> / <a href='chatcmd:///tell <myname> about'>About</a>\n";
	$footer_text .= "<font color=#9CD6DE>====================================</font>\nThank you for using <myname>.\nPart of the <highlight>A<end>narchy <highlight>O<end>nline bot <highlight>NET<end>work\n\n</center>";
			$recipe_list = $header_text.$recipe_list.$footer_text;
			$msg = bot::makeLink($recipes.' recipe(s)', $recipe_list);
			$msg .= " found containing this search string ...";
	}
     bot::send($msg, $sender);

?>