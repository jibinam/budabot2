<?php
require_once 'tokenstream.php';
require_once 'scanner.php';
require_once 'template.php';
require_once 'moduleloader.php';

ini_set('short_open_tag', '1');

function toCamelCase($str) {
	$str = strtolower($str);
	$str[0] = strtoupper($str[0]);
	$func = function($c) {
		return strtoupper($c[1]);
	};
	return preg_replace_callback('/_([a-z])/i', $func, $str);
}

function rightStripString($string, $strip) {
	$index = strrpos($string, $strip);
	if ($index === false) {
		return $string;
	}
	return substr($string, 0, $index);
}

function trimQuotes($value) {
	return trim($value, "\"'");
}


$pathToModule = $argv[1];
$moduleName   = basename($pathToModule);

$loader = new ModuleLoader($pathToModule);
$loader->load();

if ($loader->inNewFormat) {
	file_put_contents('php://stderr', 'Already in new format, nothing to do.');
	exit(0);
}

$memberVars = array();

foreach ($loader->modules as $module) {
	$scanner = new ModuleScanner($pathToModule);

	$events = array();
	if (isset($loader->events[$module])) {
		foreach ($loader->events[$module] as $event) {
			$event['contents'] = $scanner->scanEventHandlerFile($event['filename']);
			$events []= $event;
		}
	}
	$commands = array();
	if (isset($loader->commands[$module])) {
		foreach ($loader->commands[$module] as $command) {
			$commands[$command['command']] = $command;
			$scanner->scanCommandHandlerFile($command['command'], $command['filename']);
		}
	}
	$settings = isset($loader->settings[$module])? $loader->settings[$module]: array();
	$sqlFiles = isset($loader->sqlFiles[$module])? $loader->sqlFiles[$module]: array();
	$aliases  = isset($loader->aliases[$module])? $loader->aliases[$module]: array();

	$template = new ControllerClassTemplate();
	$template->setModuleName($module);
	$template->setCommands($commands);
	$template->setEvents($events);
	$template->setSettings($settings);
	$template->setCommandHandlers($scanner->commandHandlers);
	$template->setMemberVars($scanner->memberVars);
	$template->setInjectVars($scanner->injectVars);
	$template->setSqlFiles($sqlFiles);
	$template->setTableReplaces($loader->tableReplaces);
	$template->setAliases($aliases);
	if ($loader->setup[$module]) {
		$setup = $scanner->scanEventHandlerFile($loader->setup[$module]['filename']);
		$template->setSetupEvent($setup);
	}
	$template->setLogger($scanner->hasLogger);

	// make sure that the separate output controllers don't try to share common
	// member variables
	foreach ($scanner->memberVars as $var) {
		if (in_array($var, $memberVars)) {
			throw new Exception("Member variable \$this->$var (transformed from \$chatBot->data['$var']) is used in multiple controllers!");
		}
	}
	$memberVars = array_merge($memberVars, $scanner->memberVars);

	print $template->runTemplate();
}
