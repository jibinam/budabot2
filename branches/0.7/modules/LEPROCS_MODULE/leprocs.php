<?php
   /*
   ** The Majority of this code was written by Derroylo (RK2) for the 
   ** Budabot Items Module.  I just hacked it to use Nano DB from a
   ** Similar Bebot nano Module.
   **
   ** Healnjoo RK2
   */
   
if(preg_match("/^leprocs? (.+)$/i", $message, $arr)) {
    $profession = $arr[1];

	$db->query("SELECT * FROM leprocs WHERE profession LIKE '%$profession%' ORDER BY proc_type ASC, research_lvl DESC");
	$num = $db->numrows();
	if($num == 0) {
	    $msg = "No procs found for profession '$profession'.";
	} else {
		$blob = '';
		$type = '';
		while (($row = $db->fObject()) != FALSE) {
			if ($type != $row->proc_type) {
				$type = $row->proc_type;
				$blob .= "\n<tab>$type\n";
			}
			$blob .= "<yellow>$row->name<end> $row->duration <orange>$row->modifiers<end>\n";
		}

		$msg = Links::makeLink('LE Proc results', $blob);
	}
	$this->send($msg, $sendto);
}

?>