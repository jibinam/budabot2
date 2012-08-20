<?php

class LoadError extends Exception {
}

interface CommandReply {
}

class FakeBudabot {
	public function registerInstance($MODULE_NAME, $name, &$obj) {
		// do nothing
	}
}

class FakeEventManager {

	public $events = array();
	public $setup = array();

	public function register($module, $type, $filename, $description = 'none', $help = '', $defaultStatus = null) {
		$register = array();
		$register['module']        = $module;
		$register['type']          = $type;
		$register['filename']      = $filename;
		$register['description']   = $description;
		$register['help']          = $help;
		$register['defaultStatus'] = $defaultStatus;
		if (strtolower($type) == 'setup') {
			$this->setup[$module] = $register;
		} else {
			$this->events[$module] []= $register;
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
		$this->registers[$module] []= $register;
	}
}

class FakeSubcommand {
	public $registers = array();

	public function register($module, $channel, $filename, $command, $admin, $parent_command, $description = 'none', $help = '', $defaultStatus = null) {
		$register = array();
		$register['module']        = $module;
		$register['channels']      = $channel;
		$register['filename']      = $filename;
		$register['command']       = $command;
		$register['accessLevel']   = $admin;
		$register['description']   = $description;
		$register['help']          = $help;
		$register['defaultStatus'] = $defaultStatus;
		$this->registers[$module] []= $register;
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
		$this->adds[$module] []= $add;
	}
}

class FakeDB {
	public $sqlFiles = array();
	public $tableReplaces = array();

	public function loadSQLFile($module, $name) {
		$this->sqlFiles[$module] []= $name;
	}

	public function add_table_replace($search, $replace) {
		$this->tableReplaces [] = array(
			'search' => $search,
			'replace' => $replace
		);
	}
}

class FakeCommandAlias {
	public $aliases = array();
	private $commandObj;

	public function __construct($commandObj) {
		$this->commandObj = $commandObj;
	}

	public function register($module, $command, $alias) {
		foreach ($this->commandObj->registers as &$cmdRegister) {
			if ($cmdRegister[$module]['command'] == $command) {
				if (!isset($cmdRegister['alias'])) {
					$cmdRegister['alias'] = $alias;
					return;
				}
			}
		}
		$this->aliases[$module] []= array(
			'command' => $command, 
			'alias'   => $alias
		);
	}
}

class ModuleLoader {

	public $commands;
	public $events;
	public $settings;
	public $setup;
	public $sqlFiles;
	public $tableReplaces;
	public $aliases;
	public $inNewFormat = false;
	public $modules = array();

	private $modulePath;

	public function __construct($modulePath) {
		$this->modulePath = $modulePath;
	}
	
	public function load() {
		$moduleName  = basename($this->modulePath);
		$MODULE_NAME = toCamelCase(rightStripString($moduleName, '_MODULE'));
		$filePath    = "{$this->modulePath}/{$moduleName}.php";
		if (!file_exists($filePath)) {
			// ignore modules which are already in new format
			$this->inNewFormat = true;
			return;
		}
		$chatBot      = new FakeBudabot();
		$event        = new FakeEventManager();
		$command      = new FakeCommandManager();
		$subcommand   = new FakeSubcommand();
		$setting      = new FakeSetting();
		$db           = new FakeDB();
		$commandAlias = new FakeCommandAlias($command);

		include $filePath;

		$this->modules = array_unique(array_merge(
			array_keys($command->registers),
			array_keys($subcommand->registers),
			array_keys($event->events),
			array_keys($event->setup),
			array_keys($setting->adds),
			array_keys($db->sqlFiles),
			array_keys($commandAlias->aliases)
		));

		foreach($this->modules as $module) {
			$commands    = isset($command->registers[$module])? $command->registers[$module]: array();
			$subcommands = isset($subcommand->registers[$module])? $subcommand->registers[$module]: array();
			$this->commands[$module] = array_merge($commands, $subcommands);
			usort($this->commands[$module], function($register1, $register2) {
				return strcmp($register1['command'], $register2['command']);
			});
		}

		$this->events        = $event->events;
		$this->setup         = $event->setup;
		$this->settings      = $setting->adds;
		$this->sqlFiles      = $db->sqlFiles;
		$this->tableReplaces = $db->tableReplaces;
		$this->aliases       = $commandAlias->aliases;
	}
}
