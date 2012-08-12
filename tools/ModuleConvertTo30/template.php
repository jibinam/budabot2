<?php

class Template {
	private $data = array();
	private $template;
	
	public function setData($name, $value) {
		$this->data[$name] = $value;
	}
	
	public function __construct($template) {
		$this->template = $template;
	}
	
	public function runTemplate() {
		ob_start();
		extract($this->data);
		include "tmpl/{$this->template}.tmpl.php";
		$__out = ob_get_contents();
		ob_end_clean();
		return $__out;
	}
	
	protected function indent($data, $levels) {
		$indention = str_repeat("\t", $levels);
		$data = preg_replace("/^(.+)$/m", "$indention\\1", $data);
		return $data;
	}
}

class ControllerClassTemplate extends Template {

	private $reservedNames = array();

	public function setModuleName($name) {
		$name = toCamelCase(rightStripString($name, '_MODULE'));
		$this->setData('moduleName', $name);
	}
	
	public function setCommands($commands) {
		$keySpace = 0;
		$defines = array();
		$allowedArgs = array('command', 'channels', 'accessLevel', 'description', 'help', 'defaultStatus');
		foreach ($commands as $command) {
			$define = array();
			foreach ($command as $key => $value) {
				if (in_array($key, $allowedArgs) && $value) {
					$keySpace = max(strlen($key), $keySpace);
					$define []= array($key, $value, false);
				}
			}
			$define[count($define)-1][2] = true;
			$defines []= $define;
		}
		$this->setData('defineKeySpace', $keySpace);
		$this->setData('defines', $defines);
	}

	public function setEvents($events) {
		$defines = array();
		foreach ($events as $event) {
			$eventObj = new StdClass();
			$eventObj->name        = $this->createMethodName(rightStripString($event['filename'], '.php'));
			$eventObj->description = strtolower($event['description']);
			$eventObj->contents    = $event['contents'];
			$eventObj->annos       = array();
			foreach ($event as $key => $value) {
				if ($value) {
					$annoName = '';
					if ($key == 'type') {
						$annoName = 'Type';
					} else if ($key == 'description') {
						$annoName = 'Description';
					} else if ($key == 'help') {
						$annoName = 'Help';
					} else if ($key == 'defaultStatus') {
						$annoName = 'DefaultStatus';
					}
					if ($annoName) {
						$eventObj->annos[$annoName] = $value;
					}
				}
			}
			$defines []= $eventObj;
		}
		$this->setData('events', $defines);
	}
	
	public function setMemberVars($vars) {
		$this->setData('vars', $vars);
	}

	public function setInjectVars($vars) {
		$this->setData('injects', $vars);
	}

	public function __construct() {
		parent::__construct('controllerclass');
	}

	private function createFreeName($name) {
		$newName = $name;
		$counter = 2;
		for ($i = 2; in_array($newName, $this->reservedNames); $i++) {
			$newName = $name . $i;
		}
		$this->reservedNames []= $newName;
		return $newName;
	}
	
	private function createMethodName($name) {
		$name = rightStripString($name, '.php');
		$name = toCamelCase($name);
		$name = lcfirst($name);
		$name = $this->createFreeName($name);
		return $name;
	}
}