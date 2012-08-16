<?php

class FakeEventManager {

	public $events = array();
	public $setup;

	public function register($module, $type, $filename, $description = 'none', $help = '', $defaultStatus = null) {
		$register = array();
		$register['module']        = $module;
		$register['type']          = $type;
		$register['filename']      = $filename;
		$register['description']   = $description;
		$register['help']          = $help;
		$register['defaultStatus'] = $defaultStatus;
		if (strtolower($type) == 'setup') {
			$this->setup = $register;
		} else {
			$this->events []= $register;
		}
	}
}

class FakeCommandManager {

	public $registers = array();

	public function register($module, $channel, $filename, $command, $admin, $description, $help = '', $defaultStatus = null) {
		$register = array();
		$register['module']        = $module;
		$register['channels']      = $channel;
		$register['filename']      = $filename;
		$register['command']       = $command;
		$register['accessLevel']   = $admin;
		$register['description']   = $description;
		$register['help']          = $help;
		$register['defaultStatus'] = $defaultStatus;
		$this->registers []= $register;
	}
}

class FakeSetting {

	public $adds = array();

	public function add($module, $name, $description, $mode, $type, $value, $options = '', $intoptions = '', $admin = 'mod', $help = '') {
		$add = array();
		$add['module'] = $module;
		$add['name'] = $name;
		$add['description'] = $description;
		$add['mode'] = $mode;
		$add['type'] = $type;
		$add['value'] = $value;
		$add['options'] = $options;
		$add['intoptions'] = $intoptions;
		$add['admin'] = $admin;
		$add['help'] = $help;
		$this->adds []= $add;
	}
}

class FakeDB {
	public $sqlFiles = array();

	public function loadSQLFile($module, $name) {
		$this->sqlFiles []= $name;
	}
}

class ModuleLoader {

	public $commands;
	public $events;
	public $settings;
	public $setup;
	public $sqlFiles;
	public $inNewFormat = false;

	private $modulePath;

	public function __construct($modulePath) {
		$this->modulePath = $modulePath;
	}
	
	public function load() {
		$moduleName  = basename($this->modulePath);
		$MODULE_NAME = strtoupper($moduleName);
		$filePath    = "{$this->modulePath}/{$moduleName}.php";
		if (!file_exists($filePath)) {
			// ignore modules which are already in new format
			$this->inNewFormat = true;
			return;
		}
		$event       = new FakeEventManager();
		$command     = new FakeCommandManager();
		$setting     = new FakeSetting();
		$db          = new FakeDB();

		include $filePath;

		$this->commands = $command->registers;
		$this->events   = $event->events;
		$this->setup    = $event->setup;
		$this->settings = $setting->adds;
		$this->sqlFiles = $db->sqlFiles;
	}
}
