<?php

if (preg_match("/^playfields$/i", $message)) {
	$blob = "<header>:::::: Playfields ::::::<end>\n\n";
	
	$sql = "SELECT * FROM playfields ORDER BY long_name";
	$db->query($sql);
	while ($row = $db->fObject()) {
		$blob .= "{$row->id}   <green>{$row->long_name}<end>   <cyan>({$row->short_name})<end>\n";
	}
	
	$msg = Text::make_link("Playfields", $blob, 'blob');
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>