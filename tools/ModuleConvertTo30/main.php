<?php
require_once 'tokenstream.php';
require_once 'scanner.php';
require_once 'template.php';

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


$pathToModule = $argv[1];
$moduleName   = basename($pathToModule);

$scanner = new ModuleScanner($pathToModule);
$scanner->scanModule();

$template = new ControllerClassTemplate();
$template->setModuleName($moduleName);
$template->setCommands($scanner->commands);
$template->setEvents($scanner->events);
$template->setMemberVars($scanner->memberVars);
$template->setInjectVars($scanner->injectVars);
print $template->runTemplate();

