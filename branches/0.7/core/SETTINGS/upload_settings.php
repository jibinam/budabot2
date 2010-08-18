<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Uploads Settings to the db
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 05.02.2006
   ** Date(last modified): 05.02.2007
   ** 
   ** Copyright (C) 2006, 2007 Carsten Lohmann
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
   
if (!function_exists("save_setting_to_db")) {
	function save_setting_to_db($name, $value, $options, $intoptions, $description, $help) {
		Settings::add($name, 'Basic Settings', $description, 'edit', $value, $options, $intoptions, MODERATOR, $help, 1);
	}
}

save_setting_to_db('symbol', Settings::get("symbol"), '!;#;*;@;$;+;-', null, 'Prefix for Guild- or Privatechat Commands', null);
save_setting_to_db('debug', Settings::get("debug"), "Disabled;Show basic msg's;Show enhanced debug msg's;Show enhanced debug msg's + 1s Delay", '0;1;2;3', 'Show debug messages', null);
save_setting_to_db('echo', Settings::get("echo"), 'Disabled;Only Console;Console and Logfiles', '0;1;2' , 'Show messages in console and log them to files', null);
save_setting_to_db('guild admin level', Settings::get("guild admin level"), 'President;General;Squad Commander;Unit Commander;Unit Leader;Unit Member;Applicant', '0;1;2;3;4;5;6', 'Min Level for Rank Guildadmin', null);
save_setting_to_db('default_guild_color', Settings::get("default_guild_color"), 'color', null, 'Default Guild Color', null);
save_setting_to_db('default_priv_color', Settings::get("default_priv_color"), 'color', null, 'Default Private Color', null);
save_setting_to_db('default_window_color', Settings::get("default_window_color"), 'color', null, 'Default Window Color', null);
save_setting_to_db('default_tell_color', Settings::get("default_tell_color"), 'color', null, 'Default Tell Color', null);
save_setting_to_db('default_highlight_color', Settings::get("default_highlight_color"), 'color', null, 'Default Highlight Color', null);
save_setting_to_db('default_header_color', Settings::get("default_header_color"), 'color', null, 'Default Header Color', null);
save_setting_to_db('default_error_color', Settings::get("default_error_color"), 'color', null, 'Default Error Color', null);
save_setting_to_db('spam protection', Settings::get("spam protection"), 'ON;OFF', '1;0', 'Spam Protection for Private Chat', './core/SETTINGS/spam_help.txt');
save_setting_to_db('default module status', Settings::get("default module status"), 'ON;OFF', '1;0', 'Default Status for new Modules', './core/SETTINGS/module_status_help.txt');
save_setting_to_db('max_blob_size', Settings::get("max_blob_size"), 'number', null, 'Max chars for a window', './core/SETTINGS/max_blob_size_help.txt');

?>