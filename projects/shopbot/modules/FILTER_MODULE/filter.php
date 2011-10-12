<?php

if (preg_match("/^filter add content (.*)$/i", $message, $arr)) {
	$regex = str_replace("'", "''", $arr[1]);
	
	$db->exec("INSERT INTO filter_content (addedBy, regex) VALUES({$sender}', '{$regex}');");
	$msg = "Content filter added successfully.";
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^filter add player (.*)$/i", $message, $arr)) {
	$msg = "Not yet implemented";
	$chatBot->send($msg, $sendto);
} else {
	$msg = "Usage: !filter &gt;add|rem&lt; &gt;content|player&lt; &gt;regex|playername&lt;";
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>
