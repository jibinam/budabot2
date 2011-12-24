<?php
	// since settings for channels are added dynamically, we need to add them manually
	$data = $db->query("SELECT * FROM settings_<myname> WHERE module = ? AND name LIKE ?", $MODULE_NAME, "worldnet%status");
	forEach ($data as $row) {
		Setting::add($row->module, $row->name, $row->description, $row->mode, $row->type, $row->value, $row->options, $row->intoptions, $row->admin, $row->help);
	}

	Event::register($MODULE_NAME, "extPriv", "incoming_message.php", 'Relays incoming messages to the guild/private channel');
	
	Setting::add($MODULE_NAME, 'worldnet_bot', 'Name of bot', 'edit', "text", "Worldnet", "Worldnet;Dnet", '', 'mod', 'worldnet');
	Setting::add($MODULE_NAME, "worldnet_allow_tell_subscriptions", "Send worldnet messages to characters via tells", "edit", "options", "0", "true;false", "1;0");
	
	// colors
	Setting::add($MODULE_NAME, 'worldnet_channel_color', "Color of channel text in worldnet messages", 'edit', "color", "<font color='#FFFFFF'>");
	Setting::add($MODULE_NAME, 'worldnet_message_color', "Color of message text in worldnet messages", 'edit', "color", "<font color='#FFFFFF'>");
	Setting::add($MODULE_NAME, 'worldnet_sender_color', "Color of sender text in worldnet messages", 'edit', "color", "<font color='#FFFFFF'>");

	Help::register($MODULE_NAME, "worldnet", "worldnet.txt", "all", "How to use Worldnet");
?>