<?php

require_once 'template.php';

$botDirPath     = realpath(__DIR__ . '/../../trunk');
$phpExePath     = "$botDirPath/win32/php.exe";
$phpExeCmd      = "win32/php.exe -c php-win.ini";
$modulesDirPath = "$botDirPath/modules/";
$coreDirPath    = "$botDirPath/core/";
$modules        = array(
	'ALIEN_MODULE',
	'BANK_MODULE',
	'BASIC_CHAT_MODULE',
	'BBIN_MODULE',
	'BIOR_GUARDIAN_MODULE',
	'BOSSLOOT_MODULE',
	'BROADCAST_MODULE',
	'CITY_MODULE',
	'DOJA_MODULE',
	'EVENTS_MODULE',
	'FEEDBACK_MODULE',
	'FUN_MODULE',
	'GUIDE_MODULE',
	'GUILD_MODULE',
	'HELPBOT_MODULE',
	'IMPLANT_MODULE',
	'IRC_MODULE',
	'ITEMS_MODULE',
	'LEVEL_MODULE',
	'NANO_MODULE',
	'NEWS_MODULE',
	'NOTES_MODULE',
	'ONLINE_MODULE',
	'ORGLIST_MODULE',
	'POCKETBOSS_MODULE',
	'PRIVATE_CHANNEL_MODULE',
	'QUOTE_MODULE',
	'RAFFLE_MODULE',
	'RAID_MODULE',
	'RELAY_MODULE',
	'SHOPPING_MODULE',
	'SKILLS_MODULE',
	'SPIRITS_MODULE',
	'SVN_MODULE',
	'TEAMSPEAK3_MODULE',
	'TIMERS_MODULE',
	'TOWER_MODULE',
	'TRACKER_MODULE',
	'TRICKLE_MODULE',
	'WAITLIST_MODULE',
	'WEATHER_MODULE',
	'VENTRILO_MODULE',
	'WHEREIS_MODULE',
	'WHOIS_MODULE',
	'WHOMPAH_MODULE',
	'WORLDNET_MODULE',
	'VOTE_MODULE',
	'SETTINGS',
	'SYSTEM',
	'ADMIN',
	'BAN',
	'HELP',
	'CONFIG',
	'LIMITS',
	'PLAYER_LOOKUP',
	'FRIENDLIST',
	'ALTS',
	'USAGE',
	'PREFERENCES',
	'API_MODULE',
	'HTTPAPI_MODULE'
);

$reports = array();
$failedReports = 0;
$successReports = 0;

function runGenerator($modulePath) {
	$descriptorspec = array(
		1 => array("file", sys_get_temp_dir() . '/genout.temp', "w"),
		2 => array("pipe", "w")
	);
	$mainPhp = __DIR__ . "/main.php";
	$process = proc_open("win32\php.exe -c php-win.ini $mainPhp $modulePath", $descriptorspec, $pipes, $botDirPath);

	if (is_resource($process)) {
		$errors = stream_get_contents($pipes[2]);
		fclose($pipes[2]);
		proc_close($process);
	}
	return $errors;
}

function runModules($modulesDirPath) {
	global $reports;
	global $modules;
	global $failedReports;
	global $successReports;

	$d = dir($modulesDirPath);
	while (false !== ($entry = $d->read())) {
		if (in_array($entry, $modules)) {
			$errors = runGenerator("$modulesDirPath/$entry");
			if ($errors == 'Already in new format, nothing to do.') {
				continue;
			}
			$report = new StdClass();
			$report->name = $entry;
			$report->success = !strlen($errors);
			$report->errors = $errors;
			$reports []= $report;
			$successReports += $report->success? 1: 0;
			$failedReports  += $report->success? 0: 1;
		}
	}
	$d->close();
}

runModules($modulesDirPath);
runModules($coreDirPath);

$template = new Template('testrunreport');
$template->setData('reports', $reports);
$template->setData('successReports', $successReports);
$template->setData('failedReports', $failedReports);
print $template->runTemplate();
