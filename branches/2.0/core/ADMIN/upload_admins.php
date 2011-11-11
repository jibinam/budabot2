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

$superAdmin = Player::create($chatBot->SuperAdmin);

if ($superAdmin === null) {
	Logger::log(__FILE__, "could not get char_id for super admin: '$superAdmin->name'", ERROR);
} else {
	// demote any current super admins to admins
	$admins = Admin::find_by_access_level(SUPERADMIN);
	forEach ($admins as $admin) {
		if ($admin->uid != $superAdmin->uid) {
			Admin::update($admin, ADMIN);
		}
	}
		
	// remove the new super admin from any other admin levels
	Admin::remove($superAdmin);
	
	// add new super admin
	Admin::add($superAdmin, SUPERADMIN);
	$superAdmin->add_to_buddylist('admin');
}

?>