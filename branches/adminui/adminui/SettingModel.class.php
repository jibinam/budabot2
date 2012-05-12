<?php

/**
 * This model class ...
 */
class SettingModel {
	private $dom;
	
	/**
	 * Constructor method.
	 */
	public function __construct() {
		$this->load();
	}
	
	/**
	 * This method saves the settings to a file.
	 */
	public function load() {
		$this->dom = null;
		$dom = new DOMDocument();
		$result = $dom->load($this->getSettingsFilePath());
		if (!$result) {
			// TODO: needs better error handling
			echo "Failed to load settings-file!\n";
			echo "Loading default settings...\n";
			$result = $dom->load('adminui/settings.default.conf');
			if (!$result) {
				echo "Failed to load default settings-file!\n";
				return;
			}
		}
		$result = $dom->schemaValidate('adminui/settings.xsd');
		if (!$result) {
			// TODO: needs better error handling
			echo "Failed to validate settings-file!\n";
			return;
		}
		
		$this->dom = $dom;
	}

	/**
	 * This method loads the settings from a file.
	 */
	public function save() {
		if ($this->dom) {
			$result = $this->dom->save($this->getSettingsFilePath());
			if ($result === false) {
				// TODO: needs better error handling
				echo "Failed to save settings to file!\n";
			}
		}
	}
	
	public function getApiUsername() {
		return $this->getGlobalValue('apiusername');
	}

	public function getApiPassword() {
		return $this->getGlobalValue('apipassword');
	}

	public function getApiHost() {
		return $this->getGlobalValue('apihost');
	}
	
	public function getApiPort() {
		return $this->getGlobalValue('apiport');
	}

	public function setApiUsername($name) {
		$this->setGlobalValue('apiusername', $name);
	}

	public function setApiPassword($password) {
		$this->setGlobalValue('apipassword', $password);
	}

	public function setApiHost($host) {
		$this->setGlobalValue('apihost', $host);
	}
	
	public function setApiPort($port) {
		$this->setGlobalValue('apiport', $port);
	}

	public function getBotNames() {
		$names = array();
		if ($this->dom) {
			$bots = $this->dom->getElementsByTagName('bots')->item(0)->getElementsByTagName('bot');
			foreach($bots as $botElement) {
				$names []= $botElement->getAttribute('name');
			}
		}
		return $names;
	}
	
	/**
	 * Returns path to settings file where this class's data is saved.
	 */
	private function getSettingsFilePath() {
		return dirname(__FILE__) . '/../conf/adminui_settings.conf';
	}
	
	private function getGlobalValue($tagName) {
		if ($this->dom) {
			return $this->dom->getElementsByTagName($tagName)->item(0)->textContent;
		}
		return null;
	}

	private function setGlobalValue($tagName, $value) {
		if ($this->dom) {
			return $this->dom->getElementsByTagName($tagName)->item(0)->textContent = $value;
		}
		return null;
	}
}
