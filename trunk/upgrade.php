<?php

	/*
	 ** This file is part of Budabot.
	 **
	 ** Budabot is free software: you can redistribute it and/org modify
	 ** it under the terms of the GNU General Public License as published by
	 ** the Free Software Foundation, either version 3 of the License, or
	 ** (at your option) any later version.
	 **
	 ** Budabot is distributed in the hope that it will be useful,
	 ** but WITHOUT ANY WARRANTY; without even the implied warranty of
	 ** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	 ** GNU General Public License for more details.
	 **
	 ** You should have received a copy of the GNU General Public License
	 ** along with Budabot. If not, see <http://www.gnu.org/licenses/>.
	*/

	function checkIfColumnExists($db, $table, $column) {
		// If the table doesn't exist, return true since the table will be created with the correct column.
		try {
			$data = $db->query("SELECT * FROM $table");
		} catch (SQLException $e) {
			LegacyLogger::log("ERROR", 'Upgrade', $e->getMessage());
			return true;
		}

		// Else if the table exists but the column doesn't, return false so the table will be updated with the correct column.
		try {
			$data = $db->query("SELECT $column FROM $table");
		} catch (SQLException $e) {
			LegacyLogger::log("ERROR", 'Upgrade', $e->getMessage());
			return false;
		}
	
		// Else return true because both the table and the column exist.
		return true;
	}

	function upgrade($db, $sql, $params = null) {
		try {
			$db->exec($sql);
		} catch (SQLException $e) {
			LegacyLogger::log("ERROR", 'Upgrade', $e->getMessage());
		}
	}

	require_once 'core/PREFERENCES/Preferences.class.php';

	try {
		$data = $db->query("SELECT * FROM org_members_<myname>");
		if (property_exists($data[0], 'logon_msg') || property_exists($data[0], 'logoff_msg')) {
			forEach ($data as $row) {
				if (isset($row->logon_msg) && $row->logon_msg != '') {
					Preferences::save($row->name, 'logon_msg', $row->logon_msg);
				}
				if (isset($row->logoff_msg) && $row->logoff_msg != '') {
					Preferences::save($row->name, 'logoff_msg', $row->logoff_msg);
				}
			}
			upgrade($db, "UPDATE org_members_<myname> SET logon_msg = '', logoff_msg = ''");
		}
	} catch (SQLException $e) {
		// Table doesn't exist so don't update it.
	}

	if (!checkIfColumnExists($db, "news", "sticky")) {
		upgrade($db, "ALTER TABLE news ADD `sticky` TINYINT NOT NULL DEFAULT 0");
	}

	upgrade($db, "UPDATE eventcfg_<myname> SET type = LOWER(type)");

	upgrade($db, "UPDATE cmdcfg_<myname> SET admin = 'rl' WHERE admin = 'leader'");
	upgrade($db, "UPDATE hlpcfg_<myname> SET admin = 'rl' WHERE admin = 'leader'");
	upgrade($db, "UPDATE settings_<myname> SET admin = 'rl' WHERE admin = 'leader'");

	upgrade($db, "DELETE FROM cmd_alias_<myname> WHERE alias = 'kickuser'");
	upgrade($db, "DELETE FROM cmd_alias_<myname> WHERE alias = 'inviteuser'");

?>