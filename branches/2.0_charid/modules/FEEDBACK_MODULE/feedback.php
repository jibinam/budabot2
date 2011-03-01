<?php

if (preg_match("/^feedback ([a-z0-9-]*) (\\+1|\\-1) (.*)$/i", $message, $arr)) {
	$charid = $chatBot->get_uid($arr[1]);
	$name = ucfirst(strtolower($arr[1]));
	$rep = $arr[2];
	$comment = str_replace("'", "''", $arr[3]);
	$by_charid = $chatBot->get_uid($sender);

	if ($charid === false) {
		$chatBot->send("Could not find character '$name'.", $sendto);
		return;
	}
	
	if ($charid == $by_charid) {
		$chatBot->send("You cannot give yourself feedback.", $sendto);
		return;
	}
	
	$time = time() - 86400;
	
	$sql = "SELECT charid FROM feedback WHERE `by_charid` = '$by_charid' AND `charid` = '$charid' AND `dt` > '$time'";
	$db->query($sql);
	if ($db->numrows() > 0) {
		$chatBot->send("You may only submit feedback for a player once every 24 hours. Please try again later.", $sendto);
		return;
	}
	
	$sql = "SELECT charid FROM feedback WHERE `by_charid` = '$by_charid'";
	$db->query($sql);
	if ($db->numrows() > 3) {
		$chatBot->send("You may submit a maximum of 3 feedbacks in a 24 hour period. Please try again later.", $sendto);
		return;
	}

	$sql = "
		INSERT INTO feedback (
			`charid`,
			`reputation`,
			`comment`,
			`by_charid`,
			`dt`
		) VALUES (
			'$charid',
			'$rep',
			'$comment',
			'$by_charid',
			'" . time() . "'
		)";

	$db->exec($sql);
	$chatBot->send("Feedback for $name added successfully.", $sendto);
} else if (preg_match("/^feedback ([a-z0-9-]*)$/i", $message, $arr)) {
	$name = ucfirst(strtolower($arr[1]));
	$charid = $chatBot->get_uid($name);
	
	if ($charid === false) {
		$msg = "Plater <highlight>name<end> does not exist.";
		$chatBot->send($msg, $sendto);
		return;
	}
    
	$db->query("SELECT reputation, COUNT(*) count FROM feedback WHERE `charid` = $charid GROUP BY `reputation`");
	if ($db->numrows() == 0) {
		$msg = "<highlight>$name<end> has no feedback.";
	} else {
		$num_positive = 0;
		$num_negative = 0;
		while ($row = $db->fObject()) {
			if ($row->reputation == '+1') {
				$num_positive = $row->count;
			} else if ($row->reputation == '-1') {
				$num_negative = $row->count;
			}
		}

		$blob = "<header>::::: Feedback for {$name} :::::<end>\n\n";
		$blob .= "Positive feedback: <green>{$num_positive}<end>\n";
		$blob .= "Negative feedback: <orange>{$num_negative}<end>\n\n";
		$blob .= "Last 10 comments about this user:\n\n";
		
		$sql = "SELECT f.*, p.name AS by FROM feedback f LEFT JOIN players p ON f.by_charid = p.charid WHERE f.`charid` = $charid ORDER BY `dt` DESC LIMIT 10";
		$db->query($sql);
		$data = $db->fObject('all');
		forEach ($data as $row) {
			if ($row->reputation == '-1') {
				$blob .= "<orange>";
			} else {
				$blob .= "<green>";
			}

			$time = Util::unixtime_to_readable(time() - $row->dt);
			$blob .= "({$row->reputation}) $row->comment <end> $row->by <white>{$time} ago<end>\n\n";
		}
		
		$msg = Text::make_link("Feedback for {$name}", $blob, 'blob');
	}

	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>
