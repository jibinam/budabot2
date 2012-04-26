<?php

/*
   ** Author: Captainzero (RK1)
   ** Description: Help Feature
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 11/11/2008
   ** Date(last modified): 11/11/2008
   ** 
   */

if(eregi("^about$", $message)) {
	$about_chosen = "true";
	$about = fopen("./core/HELP/about.txt", "r");
	while(!feof ($about))
		$data .= fgets ($about, 4096);
	fclose($about);
		$footer_text = "\n\n<font color=#9CD6DE>====================================</font>";
	$footer_text .= "\n<a href='chatcmd:///tell <myname> recipe'><myname> Main Menu</a> / <a href='chatcmd:///tell <myname> help'>Help</a> / <a href='chatcmd:///tell <myname> about'>About</a>\n";
	$footer_text .= "<font color=#9CD6DE>====================================</font>\nThank you for using <myname>.\nPart of the <highlight>A<end>narchy <highlight>O<end>nline bot <highlight>NET<end>work\n\n<font color=#FFFFFF>Visit our new website! <a href='chatcmd:///start http://aorecipenet.com'>AORecipeNET.com</a></font>\n\n</center>";
	$msg = bot::makeLink("About", $data.$footer_text);
	bot::send($msg, $sender);
} elseif (eregi("^help$", $message)) {
    $db->query("SELECT * FROM helpfiles");
	$recipes = $db->numrows();
	$help_all = 1;
} else {
	$search_string = eregi_replace("^help ","", $message);
	$search_string = eregi_replace(" ","%", $search_string);
    $db->query("SELECT * FROM helpfiles WHERE help_text like '%$search_string%'");
	$recipes = $db->numrows();
	if ($recipes == 0) { $bad_search = 1; } else { $bad_search = 0; }
}

if ($about_chosen != "true") {

		$header_text = "\n<center><header>:::: <myname> Help ::::<end>\n\n\n<font color=#9CD6DE>--------------------------------------</font>\n\n";
		$header_text .= "<img src=rdb://136330>\n\nTo use <myname> help simply type\n\n<highlight>/tell <myname> help<end>\n\nfor the full list of helpfiles, or ...\n\n<highlight>/tell <myname> help -searchstring-<end>\n\nto search all the helpfiles for a particular topic\n\n";
		if ($help_all == 1) {
		$header_text .= "<font color=#9CD6DE>--------------------------------------</font>\n\n Listing all help topics ...\n\n";
		} else if (($recipes != 0)&&($bad_search == 0)) {
		$header_text .= "<font color=#9CD6DE>--------------------------------------</font>\n\n Your search has found some matches!\n\n";
		} else if (($recipes != 0)&&($bad_search == 0)) {
		$header_text .= "<font color=#9CD6DE>--------------------------------------</font>\n\n Your search has found no matches!\n\n";
		}
		while($row = $db->fObject()) {
			$recipe_name = $row->help_topic;
			$recipe_id = $row->help_id;
			$recipe_list .= "<a href='chatcmd:///tell <myname> hshow ".$recipe_id."'>".$recipe_name."</a>\n\n";
		}
		$extra_help = "General <myname> help:\n\nTo use <myname> simply open a tell message with <highlight>/tell <myname><end>, then drop the item to inspect into chat and press enter\n\nOr you can search for recipes eg: <highlight>/tell <myname> search screwdriver<end>\n\n";
		$extra_help .="Each step of a recipe shows the skills required.  The codes for those skills are as follows:\n\nCL: Computer Literacy\nNP: Nano Programming\nME: Mech Engi\nEE: Elec Engi\nQT:Quantum FT\nWS: Weapon Smt\nPT: Pharma Tech\nCH: Chemistry\nBE: Break and Entry\nPS: Psychology
";
		$extra_help .= "\n\nTo report a problem with <myname> or an incorrect or missing recipe type <highlight>/tell <myname> report -your message-<end>";
	$footer_text = "\n\n<font color=#9CD6DE>====================================</font>";
	$footer_text .= "\n<a href='chatcmd:///tell <myname> recipe'><myname> Main Menu</a> / <a href='chatcmd:///tell <myname> help'>Help</a> / <a href='chatcmd:///tell <myname> about'>About</a>\n";
	$footer_text .= "<font color=#9CD6DE>====================================</font>\nThank you for using <myname>.\nPart of the <highlight>A<end>narchy <highlight>O<end>nline bot <highlight>NET<end>work\n\n<font color=#FFFFFF>Visit our new website! <a href='chatcmd:///start http://aorecipenet.com'>AORecipeNET.com</a></font>\n\n</center>";
			$recipe_list = $header_text.$recipe_list.$extra_help.$footer_text;
			if (($recipes != 0)&&($bad_search != 0)) { $link_name = "Matches found! Click Here"; } else {
			$link_name = "Click here for help"; }
			$msg = bot::makeLink($link_name, $recipe_list);
			bot::send($msg, $sender);
}
?>