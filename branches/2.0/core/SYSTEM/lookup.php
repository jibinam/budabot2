<?php

if (preg_match("/^lookup (.*)$/i", $message, $arr)) {
	$player = Player::create($arr[1]);
	$msg = Text::make_blob("Results for $player->name", print_r($player, true));
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>