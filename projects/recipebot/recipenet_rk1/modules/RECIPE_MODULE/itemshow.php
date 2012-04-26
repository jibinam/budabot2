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

$item_id = eregi_replace("^ishow ","", $message);

		$db->query("SELECT * FROM itemsdb WHERE item_id = '$item_id'");
		
			while($row = $db->fObject()) {
				$item_name = $row->item_name;
				$item_found = $row->item_found;
				$item_ql = $row->item_ql;
			}

		if ($item_found == "bbb") {
			$item_found = "can not be found, can only be made.";
		} else { 
			$item_found = "can be found ".$item_found;
		}
		
		$msg = 'The item ';
    	$msg .= '<a href="itemref://'.$item_id.'/'.$item_id.'/'.$item_ql.'">'.$item_name.'</a> ';
		$msg .= $item_found;
	
        bot::send($msg, $sender);

?>