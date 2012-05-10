<?php

class SettingModel {
	private $apiUsername;
	private $apiPassword;
	private $apiHost;
	private $apiPort;
	
	public function getApiUsername() {
		return $this->apiUsername;
	}

	public function getApiPassword() {
		return $this->apiPassword;
	}

	public function getApiHost() {
		return $this->apiHost;
	}
	
	public function getApiPort() {
		return $this->apiPort;
	}

	public function setApiUsername($name) {
		$this->apiUsername = $name;
	}

	public function setApiPassword($password) {
		$this->apiPassword = $password;
	}

	public function setApiHost($host) {
		$this->apiHost = $host;
	}
	
	public function setApiPort($port) {
		$this->apiPort = $port;
	}
}
