<?php

global $maxslaves;
global $nextserver;

$maxslaves = 6;
$nextserver = 1;

$tablehistory = "spamhistory";
$tableorgbot = "spamorgbot";
$tablecleanup = "spamlastseen";
$tablemembers = "spammembers";

$db->query("CREATE TABLE IF NOT EXISTS $tablemembers (`server` int, `name` VARCHAR(25) , `pvp` VARCHAR(25) , `raids` VARCHAR(25) , `general` VARCHAR(25) , `shopping` VARCHAR(25)  , `twinknet` VARCHAR(25), `pvpnet` VARCHAR(25) , `taranet` VARCHAR(25) )");
$db->query("CREATE TABLE IF NOT EXISTS $tablehistory ( `name` VARCHAR(25) , `type` VARCHAR(25) , `message` VARCHAR(1000),  `settime` int)");
$db->query("CREATE TABLE IF NOT EXISTS $tableorgbot ( `name` VARCHAR(25) , `orgbot` VARCHAR(25) , `orgname` VARCHAR(100)  )");
$db->query("CREATE TABLE IF NOT EXISTS $tablecleanup ( `name` VARCHAR(25) , `lastseen` INT  )");
?> 