<?php

if (preg_match("/^leprocs? (.+)$/i", $message, $arr)) {
    $profession = $arr[1];

	$data = $db->query("SELECT * FROM leprocs WHERE profession LIKE '%$profession%' ORDER BY proc_type ASC, research_lvl DESC");
	$num = $db->numrows();
	if ($num == 0) {
	    $msg = "No procs found for profession '$profession'.";
	} else {
		$blob = '';
		$type = '';
		forEach ($data as $row) {
			if ($type != $row->proc_type) {
				$type = $row->proc_type;
				$blob .= "\n<tab>$type\n";
			}
			$blob .= "<yellow>$row->name<end> $row->duration <orange>$row->modifiers<end>\n";
		}

		$msg = Text::makeBlob('LE Proc results for $profession', $blob);
	}
	$chatBot->send($msg, $sendto);
}

?>