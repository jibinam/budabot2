<?php

require_once 'Process.class.php';
require_once 'budapi/Budapi.php';

class Bot {

	private $name;
	private $settingModel;

	public function __construct($name, $settingModel) {
		$this->name = $name;
		$this->settingModel = $settingModel;
		$this->api = new Budapi();
		$this->process = new Process();
	}
	
	public function getName() {
		return $this->name;
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
