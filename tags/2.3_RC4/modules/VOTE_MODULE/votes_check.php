<?php
   /*
   ** Author: Lucier (RK1)
   ** Description: Voting System
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 02.05.2007
   ** Date(last modified): 02.06.2007
   */
   
if (!isset($chatBot->data["Vote"]) || count($chatBot->data["Vote"]) == 0) {
	return;
}
$delimiter = "|";

// I hate seeing a function in a module/plugin. 
// But this is just temporary until 0.7.0.
if (!function_exists(timeLeft)) {
	function timeLeft($origtime, $showbiggest=4) {
		// deal with negative values?
		if ($origtime < 0) {
			$origtime = 0;
		}
		//week = day * 7, month = day*365/12, year = day * 365
		$set = array( array("label" => "year", 'length' => 31536000), array("label" => "month", 'length' => 2628000), 
		array("label" => "week", 'length' => 604800), array("label" => "day", 'length' => 86400), 
		array("label" => "hour", 'length' => 3600), array("label" => "minute", 'length' => 60), 
		array("label" => "second", 'length' => 0));
			
		$thisset = 0;
		while ($thisset <= 6) {
			if ($thisset < 6) {
				$val = floor($origtime / $set[$thisset]['length']);
			} else if ($thisset == 6) {
				$val = $origtime;
			}
				
			if ($val && $showbiggest > 0) {
				$retval .= "$val ".$set[$thisset]['label'];
				$retval .= ($val > 1) ? 's, ' : ', ';
				$showbiggest--;
				$origtime -= $val * $set[$thisset]['length'];
			}
			$thisset++;
		}

		if ($retval) {
			$retval = substr($retval, 0, strlen($retval) - 2);
		}
		return $retval;
	}
}


$table = "vote_<myname>";

forEach ($chatBot->data["Vote"] as $key => $value) {
   	
	$author = $chatBot->data["Vote"][$key]["author"];
	$question = $key;
	$started = $chatBot->data["Vote"][$key]["started"];
	$duration = $chatBot->data["Vote"][$key]["duration"];
	$answer = $chatBot->data["Vote"][$key]["answer"];
	$status = $chatBot->data["Vote"][$key]["status"];
	$lockout = $chatBot->data["Vote"][$key]["lockout"];
	// status = 0, just started, 1 = > 60 minutes left, 2 = 60 minutes left, 3 = 15 minutes left, 4 = 60 seconds, 9 = vote over
	
	$timeleft = ($started+$duration);
	$timeleft -= time();

	if ($timeleft <= 0) {
		$title = "Finished Vote: $question";
		$db->exec("UPDATE $table SET `status` = '9' WHERE `duration` = '$duration' AND `question` = '".str_replace("'", "''", $question)."'");
		unset($chatBot->data["Vote"][$key]);
	} else if ($status == 0) {
		$title = "Vote: $question";
		
		if ($timeleft > 3600) {$mstatus = 1;}
		else if ($timeleft > 900) {$mstatus = 2;}
		else if ($timeleft > 60) {$mstatus = 3;}	
		else {$mstatus = 4;}
		$chatBot->data["Vote"][$key]["status"]=$mstatus;
		
	} else if ($timeleft <= 60 && $timeleft > 0 && $status != 4) {
		$title = "60 seconds left: $question";
		$chatBot->data["Vote"][$key]["status"]=4;
	} else if ($timeleft <= 900 && $timeleft > 60 && $status != 3) {
		$title = "15 minutes left: $question";
		$chatBot->data["Vote"][$key]["status"]=3;
	} else if ($timeleft <= 3600 && $timeleft > 900 && $status != 2) {
		$title = "60 minutes left: $question";
		$chatBot->data["Vote"][$key]["status"]=2;
	} else {$title = "";}

	if($title != "") { // Send current results to guest + org chat.

		$db->query("SELECT * FROM $table WHERE `question` = '".str_replace("'", "''", $question)."'");

		$results = array();
		while ($row = $db->fObject()) {
			if ($row->duration) {
				$question = $row->question; $author = $row->author; $started = $row->started;
				$duration = $row->duration; $status = $row->status;
				$timeleft = $started+$duration-time();
			}
			$answer = $row->answer;

			if (strpos($answer, $delimiter) === false) { // A Vote: $answer = "yes";
				$results[$answer]++;
				$totalresults++;
			} else {				     // Main topic: $answer = "yes|no";
					
				$ans = explode($delimiter, $answer);
				foreach ($ans as $value) {
					if (!isset($results[$value])) {$results[$value] = 0;}
				}
			}
		}
			
		$msg = "$author's Vote: <highlight>".$question."<end>\n";
		if ($timeleft > 0) {
			$msg .= timeLeft($timeleft)." till this vote closes!\n\n";
		} else {
			$msg .= "<red>This vote has ended.<end>\n\n";
		}
			
		foreach ($results as $key => $value) {

			$val = number_format(100*($value/$totalresults),0);
			if ($val < 10) {$msg .= "<black>__<end>$val% ";}
			else if ($val < 100) {$msg .= "<black>_<end>$val% ";}
			else {$msg .= "$val% ";}
			
			if ($timeleft > 0) {
				$msg .= "<a href='chatcmd:///tell <myname> vote $question";
				$msg .= "$delimiter".$key."'>$key</a> (Votes: $value)\n";
			} else {
				$msg .= "<highlight>$key<end> (Votes: $value)\n";
			}
		}
		
		if ($timeleft > 0) {
			$msg .= "\n<black>___%<end> <a href='chatcmd:///tell <myname> vote remove $question'>Remove yourself from this vote</a>.\n";
		}
		if ($timeleft > 0 && Setting::get("vote_add_new_choices") == 1 && $status == 0) {
			$msg .="\n<highlight>Don't like these choices?  Add your own:<end>\n<tab>/tell ".$chatBot->vars['name']." <symbol>vote $question$delimiter"."<highlight>your choice<end>\n"; 
		}
		
		$msg .="\n<highlight>If you started this vote, you can:<end>\n";
		$msg .="<tab><a href='chatcmd:///tell <myname> vote kill $question'>Kill</a> the vote completely.\n";
		if ($timeleft > 0) {
			$msg .="<tab><a href='chatcmd:///tell <myname> vote end $question'>End</a> the vote early.";
		}
		
		$msg = Text::make_blob($title, $msg);
		
		if (Setting::get("vote_channel_spam") == 0 || Setting::get("vote_channel_spam") == 2) {
			$chatBot->send($msg, 'guild', true);
		}
	   	if (Setting::get("vote_channel_spam") == 1 || Setting::get("vote_channel_spam") == 2) {
			$chatBot->send($msg, 'priv', true);
		}
	}
}
?>