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

	$item_name = eregi_replace("<a href=\"itemref:\/\/([0-9]+)\/([0-9]+)\/([0-9]+)\">","", $message);
	$item_name = eregi_replace("</a>", "", $item_name);
	
	$item_id = eregi_replace("^<a href=\"itemref://([0-9]+)/", "", $message);
	$item_id = split("/", $item_id);
	$item_id = $item_id[0];

    $db->query("SELECT * FROM itemsdb WHERE item_id = '$item_id'");
    if($db->numrows() == 0) {
        $msg = "This item does not exist on my database .. sorry.";
	} else {
		while($row = $db->fObject()) {
			$item_id = $row->item_id;
		}
   		$db->query("SELECT recipe_items.item_id, recipe_items.recipe_id, recipes.recipe_name FROM recipe_items INNER JOIN recipes ON recipe_items.recipe_id = recipes.recipe_id WHERE (((recipe_items.item_id)='$item_id'))");
		$recipes = $db->numrows();
		if ($recipes == 0) {
        	$recipe_msg = "This item is not used in any recipe I know of .. sorry.)";
		} else {
			$header_text = "\n<center><header>:::: Recipe Results ::::<end>\n\n\n<font color=#9CD6DE>--------------------------------------</font>\n\n<font color=#63E78C>The item:\n\n <highlight>".$item_name."<end>\n\nis used in the following recipes:</font>\n\n";
			while($row = $db->fObject()) {
						$recipe_id = $row->recipe_id;
						$recipe_name = $row->recipe_name;
						$recipe_list .= "<a href='chatcmd:///tell <myname> rshow ".$recipe_id."'>".$recipe_name."</a>\n\n";
			}
	$footer_text = "\n\n<font color=#9CD6DE>====================================</font>";
	$footer_text .= "\n<a href='chatcmd:///tell <myname> recipe'><myname> Main Menu</a> / <a href='chatcmd:///tell <myname> help'>Help</a> / <a href='chatcmd:///tell <myname> about'>About</a>\n";
	$footer_text .= "<font color=#9CD6DE>====================================</font>\nThank you for using <myname>.\nPart of the <highlight>A<end>narchy <highlight>O<end>nline bot <highlight>NET<end>work\n\n<font color=#FFFFFF>Visit our new website! <a href='chatcmd:///start http://aorecipenet.com'>AORecipeNET.com</a></font>\n\n</center>";
			$recipe_list = $header_text.$recipe_list.$footer_text;

		if ($recipes == 0) { $msg = "No recipes found for this item... "; } else {
			$msg = bot::makeLink($recipes.' recipe(s) ', $recipe_list);
			$msg .= " found containing this item... ";
			}
		}
	}

		// get computeur literacy of player
			$db->query("SELECT * FROM members_rk1 WHERE member_name = '$sender'");
			while($row = $db->fObject()) {
						$member_id = $row->member_id;
						$member_cl = $row->member_cl;
			}

		// get high and low ids of item
		
			$db->query("SELECT * FROM aodb WHERE lowid = '$item_id' OR highid = '$item_id'");
			while($row = $db->fObject()) {
						$low_id = $row->lowid;
						$high_id = $row->highid;
						$low_ql = $row->lowql;
						$high_ql = $row->highql;
			}

		$db->query("SELECT * FROM itemsdb WHERE item_id = '$low_id'");
			while($row = $db->fObject()) {
						$item_value = $row->item_value;
						$item_ql = $row->item_ql;
						$item_nodrop = $row->item_nodrop;
			}
			$nodropLv = $item_nodrop;
			$Lql = $item_ql;
			$Lv = $item_value;
			$db->query("SELECT * FROM aodb WHERE lowid = '$item_id' OR highid = '$item_id'");
			while($row = $db->fObject()) {
						$low_id = $row->lowid;
						$high_id = $row->highid;
						$low_ql = $row->lowql;
						$high_ql = $row->highql;
			}

		$db->query("SELECT * FROM itemsdb WHERE item_id = '$low_id'");
			while($row = $db->fObject()) {
						$item_value = $row->item_value;
						$item_ql = $row->item_ql;
						$item_nodrop = $row->item_nodrop;
			}
			$nodropHv = $item_nodrop;
			$Hql = $item_ql;
			$Hv = $item_value;

		if (($level-$Lql)<($Hql-$level)) 
			$nodrop=$nodropLv;
		else
			$nodrop=$nodropHv;
		// calculate shop values
		if ( $Hql==$Lql) 
			$Dv=1; 
		else 
			$Dv = (($Hv-$Lv)/($Hql-$Lql));
		$P0 = $Lv - ( $Dv * $Lql );
		$value = intval(($level * $Dv)+$P0);
		$PrixTrader = number_format($value*(0.0007)*(100+intval($CL/40)), 0, '.', ' ');
		$PrixOmni = number_format($value*(0.0006)*(100+intval($CL/40)), 0, '.', ' ');
		$PrixClan = number_format($value*(0.0004)*(100+intval($CL/40)), 0, '.', ' ');
		$value_text = "\n<center><header>:::: Shop Value ::::<end>\n\n\n<font color=#9CD6DE>--------------------------------------</font>\n\n";
		$value_text .= "With your CL of <font color=#08F708>".$member_cl."</font>, This item can be sold for:\n<font color=#FFFFFF>$PrixTrader</font> in a Trader Shop\n<font color=#FFFFFF>$PrixOmni</font> in an Omni Shop\n<font color=#FFFFFF>$PrixClan</font> in a Clan or Neutral Shop.";
		if ( $member_cl==0 ) $value_text.="\n\n<center>If your CL is not ".$member_cl." you can change it this way : /tell ".$char_AO." cl [Number].</center>";
		$value_text .= "\n\n<font color=#9CD6DE>====================================</font>";
		$value_text .= "\n<a href='chatcmd:///tell <myname> recipe'><myname> Main Menu</a> / <a href='chatcmd:///tell <myname> help'>Help</a> / <a href='chatcmd:///tell <myname> about'>About</a>\n";
		$value_text .= "<font color=#9CD6DE>====================================</font>\nThank you for using <myname>.\nPart of the <highlight>A<end>narchy <highlight>O<end>nline bot <highlight>NET<end>work\n\n<font color=#FFFFFF>Visit our new website! <a href='chatcmd:///start http://aorecipenet.com'>AORecipeNET.com</a></font>\n\n</center>";
		$text = bot::makeLink('Click Here',$value_text).' for the shop value of this item (*new* visit http://aorecipenet.com/) ';
		
		if ( $nodrop!=0 ) $text = "this item is NODROP. you can't sell it in a shop (*new* visit http://aorecipenet.com/) ";

		if ($recipes == 0) { $msg = "No recipes found for this item... "; }

        bot::send($msg.$text, $sender);

?>