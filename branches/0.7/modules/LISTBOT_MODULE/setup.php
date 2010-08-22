<?php
$db->execute("CREATE TABLE IF NOT EXISTS waitlist_<myname> (`owner` VARCHAR(25), `name` VARCHAR(25), `position` INT, `time` INT)");

global $listbot_waitlist;
if (!is_array($listbot_waitlist)) {
	$data = $db->query("SELECT * FROM waitlist_<myname>");
	forEach ($data as $row) {
		$listbot_waitlist[$row->owner][] = array("name" => $row->name, "position" => $row->position);
	}
}
?>