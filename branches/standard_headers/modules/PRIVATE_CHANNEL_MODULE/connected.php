<?php

$sql = "SELECT name FROM members_<myname> WHERE autoinv = 1";
$data = $db->query($sql);
forEach ($data as $row) {
	Buddylist::add($row->name, 'member');
}

?>