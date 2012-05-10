<?php

require_once 'Process.class.php';
require_once 'budapi/Budapi.php';

class Bot {
	
	public function __construct($settings) {
		$this->api = new Budapi();
		$this->process = new Process();
		$this->settings = $settings;
	}
	
	public function start() {
	}
	
	public function restart() {
	}
	
	public function shutdown() {
	}
	
	public function terminate() {
	}
	
	public function sendCommand($command) {
	}
	
}
