<?php

$db->query("SELECT * FROM tracked_users_<myname> WHERE uid = $player->uid");
if ($db->numrows() != 0) {
 	$db->query("INSERT INTO tracking_<myname> (uid, dt, event) VALUES ($player->uid, " . time() . ", 'logoff')");
}
?>