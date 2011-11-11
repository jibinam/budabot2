<?php

if (preg_match("/^playfields$/i", $message)) {
	$blob = "<header>:::::: Playfields ::::::<end>\n\n";
	
	$sql = "SELECT * FROM playfields ORDER BY long_name";
	$db->query($sql);
	while ($row = $db->fObject()) {
		$blob .= "{$row->id}   <green>{$row->long_name}<end>   <cyan>({$row->short_name})<end>\n";
	}
	
	$msg = $this->makeLink("Playfields", $blob, 'blob');
	$this->send($msg, $sendto);
}

?>