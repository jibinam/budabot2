<?php
   /*
   ** Author: Sebuda (RK2)
   ** Description: Uploads admins to local var
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 21.01.2006
   ** Date(last modified): 21.11.2006
   ** 
   ** Copyright (C) 2006 Carsten Lohmann
   **
   ** Licence Infos: 
   ** This file is part of Budabot.
   **
   ** Budabot is free software; you can redistribute it and/or modify
   ** it under the terms of the GNU General Public License as published by
   ** the Free Software Foundation; either version 2 of the License, or
   ** (at your option) any later version.
   **
   ** Budabot is distributed in the hope that it will be useful,
   ** but WITHOUT ANY WARRANTY; without even the implied warranty of
   ** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   ** GNU General Public License for more details.
   **
   ** You should have received a copy of the GNU General Public License
   ** along with Budabot; if not, write to the Free Software
   ** Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
   */

$db->query("CREATE TABLE IF NOT EXISTS admin_<myname> (uid INT NOT NULL PRIMARY KEY, `adminlevel` INT NOT NULL)");

$superAdmin = ucfirst(strtolower(Settings::get("Super Admin")));
$uid = $this->get_uid($superAdmin);

if ($uid === FALSE) {
	echo "Error! could not get char_id for super admin: '$superAdmin'";
} else {
	$db->query("SELECT * FROM admin_<myname> WHERE `adminlevel` = " . SUPERADMIN);
	if ($db->numrows() == 0) {
		$db->query("DELETE FROM admin_<myname> WHERE `uid` = $uid");
		$db->query("INSERT INTO admin_<myname> (`adminlevel`, `uid`) VALUES (" . SUPERADMIN . ", $uid)");
	} else {
		$db->query("UPDATE admin_<myname> SET `uid` = $uid WHERE `adminlevel` = " . SUPERADMIN);
	}
}

?>