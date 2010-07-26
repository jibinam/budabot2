<?php 
	$MODULE_NAME = "EVENTS_MODULE";
	$PLUGIN_VERSION = 1.0;
	
	//Setup
	Event::register("setup", $MODULE_NAME, "setup.php");

	//Commands
	Command::register("", $MODULE_NAME, "events.php", "events", ALL, "Views events");
	Command::register("", $MODULE_NAME, "edit_event.php", "event", GUILDADMIN, "Add/edit/remove events");
	Command::register("", $MODULE_NAME, "events.php", "joinevent", ALL, "Join an event");
	Command::register("", $MODULE_NAME, "events.php", "leaveevent", ALL, "Leave an event");
	Command::register("", $MODULE_NAME, "eventlist.php", "eventlist", ALL, "View event attendees");
	
	//Helpfile
	Help::register("events", $MODULE_NAME, "events.txt", ALL, "Adding/editing/removing events");
?>
