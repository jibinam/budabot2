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
$scanner = new ModuleScanner($pathToModule);

$loader->load();

$events = array();
foreach ($loader->events as $event) {
	$event['contents'] = $scanner->scanEventHandlerFile($event['filename']);
	$events []= $event;
}
$commands = array();
foreach ($loader->commands as $command) {
	$commands[$command['command']] = $command;
	$scanner->scanCommandHandlerFile($command['command'], $command['filename']);
}

$template = new ControllerClassTemplate();
$template->setModuleName($moduleName);
$template->setCommands($commands);
$template->setEvents($events);
$template->setSettings($loader->settings);
$template->setCommandHandlers($scanner->commandHandlers);
$template->setMemberVars($scanner->memberVars);
$template->setInjectVars($scanner->injectVars);
print $template->runTemplate();

