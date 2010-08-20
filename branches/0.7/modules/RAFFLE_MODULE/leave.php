<?php
if (preg_match("/^leaveRaffle/i", $message, $arr)) {
	//check inprog and check if already in raffle
	if ($chatBot->vars["Raffles"]["inprog"]) {
		$index = array_search($sender, $chatBot->vars["Raffles"]["rafflees"]);
		if ($index === false) {
			$msg = "You are not currently signed up for the raffle.";
			$chatBot->send($msg, $sendto);
		} else {
			array_splice($chatBot->vars["Raffles"]["rafflees"], $index, 1);
			$msg = "$sender has left the raffle.";
			$chatBot->send($msg, "org");
			$chatBot->send($msg, "prv");
		}
	} else {
		$msg = "A raffle is not in progress.";
		$chatBot->send($msg, $sendto);
	}
}
?>