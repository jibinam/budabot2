<?php

if (isset($this->guildmembers[$sender]) && time() >= $this->vars["logondelay"]) {
	$msg = "";
	list($numonline, $msg, $list) = online($sender, $sendto, $this);
	if ($numonline != 0) {
		$blob = Text::make_link($msg, $list);
		$chatBot->send($blob, $sender);
	} else {
		$chatBot->send($msg, $sender);
	}
}
?>
