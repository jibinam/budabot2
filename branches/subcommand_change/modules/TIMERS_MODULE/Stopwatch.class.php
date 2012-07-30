<?php

/**
 * Commands this class contains:
 *	@DefineCommand(
 *		command     = 'stopwatch',
 *		accessLevel = 'guild',
 *		description = 'Adds a repeating timer',
 *		help        = 'stopwatch.txt'
 *	)
 */
class Stopwatch {

	/** @Inject */
	public $chatBot;

	/** @Inject */
	public $util;

	private $time = 0;

	/**
	 * This command handler adds a repeating timer.
	 * @HandlesCommand("stopwatch")
	 */
	public function stopwatchCommand($message, $channel, $sender, $sendto) {
		if (preg_match("/^stopwatch start$/i", $message)) {
			$msg = $this->start();
		} else if (preg_match("/^stopwatch stop$/i", $message)) {
			$msg = $this->stop();
		} else {
			return false;
		}

		$sendto->reply($msg);
	}

	public function start() {
		if ($this->time != 0) {
			return "The stopwatch is already running.";
		}

		$this->time = time();
		return "Stopwatch has been started.";
	}

	public function stop() {
		if ($this->time == 0) {
			return "The stopwatch is not running.";
		}

		$time = time() - $this->time;
		$this->time = 0;

		$timeString = $this->util->unixtime_to_readable($time);

		return "Stopwatch has been stopped. Duration: $timeString";
	}
}

?>