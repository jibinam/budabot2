<?php

if (preg_match("/spam (.+)/", $message, $arr)) {
	$this->send("$sender $arr[1]", $this->settings['otspambot']);
	$this->send($this->settings['otspambot'] . ' has been notified.', $sendto);
} else {
	$syntax_error = true;
}

?>