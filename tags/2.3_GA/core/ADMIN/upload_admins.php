<?php

$db->query("CREATE TABLE IF NOT EXISTS admin_<myname> (`name` VARCHAR(25) NOT NULL PRIMARY KEY, `adminlevel` INT)");

$chatBot->vars["SuperAdmin"] = ucfirst(strtolower($chatBot->vars["SuperAdmin"]));

$db->query("SELECT * FROM admin_<myname> WHERE `name` = '{$chatBot->vars["SuperAdmin"]}'");
if ($db->numrows() == 0) {
	$db->exec("INSERT INTO admin_<myname> (`adminlevel`, `name`) VALUES (4, '{$chatBot->vars["SuperAdmin"]}')");
} else {
	$db->exec("UPDATE admin_<myname> SET `adminlevel` = 4 WHERE `name` = '{$chatBot->vars["SuperAdmin"]}'");
}

$db->query("SELECT * FROM admin_<myname>");
$data = $db->fObject('all');
forEach ($data as $row) {
	$chatBot->admins[$row->name]["level"] = $row->adminlevel;
}

?>