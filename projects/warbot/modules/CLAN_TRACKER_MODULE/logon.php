<?php

$uid = $this->get_uid($sender);
$db->query("SELECT * FROM tracked_users_<myname> WHERE uid = $uid");
if ($db->numrows() != 0) {
 	$db->query("INSERT INTO tracking_<myname> (uid, dt, event) VALUES ($uid, " . time() . ", 'logon')");
}
?>