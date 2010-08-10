<?php
	$MODULE_NAME = "BOTCHANNEL_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, "private_chat");
    
    Command::register($MODULE_NAME, "members.php", "members", ALL, "Guest Channel Auto-Invitelist");
    Command::register($MODULE_NAME, "onlineguests.php", "onlineguests", ALL, "Guest Channellist");
	Command::register($MODULE_NAME, "autoinvite.php", "autoinvite", ALL, "Allows member to set whether he should be auto-invited to guest channel on logon or not");
    Command::register("guild msg", $MODULE_NAME, "join.php", "join", ALL, "Join command for guests");
	Command::register("priv msg", $MODULE_NAME, "leave.php", "leave", ALL, "Enables Privatechat Kick");

	Command::register($MODULE_NAME, "kickall.php", "kickall", MODERATOR, "Kicks all from the privgroup");
	Command::register($MODULE_NAME, "lock.php", "lock", RAIDLEADER, "Locks the privgroup");
	Command::register($MODULE_NAME, "lock.php", "unlock", RAIDLEADER, "Unlocks the privgroup");
	
	Command::register($MODULE_NAME, "invite.php", "inviteuser", ALL, "Enables Privatechat Join");
	Command::register($MODULE_NAME, "kick.php", "kickuser", ALL, "kick command for guests");
	Command::register($MODULE_NAME, "add.php", "adduser", ALL, "Enables Privatechat Join");
	Command::register($MODULE_NAME, "rem.php", "remuser", ALL, "Enables Privatechat Join");
	
	Settings::add("guest_man_join", $MODULE_NAME, "Mode of manual guestchannel join", "edit", "1", "Only for members of guestlist;Everyone", "1;0");
	Settings::add("guest_color_channel", $MODULE_NAME, "Color for Guestchannelrelay(ChannelName)", "edit", "<font color=#C3C3C3>", "color");
	Settings::add("guest_color_username", $MODULE_NAME, "Color for Guestchannelrelay(UserName)", "edit", "<font color=#C3C3C3>", "color");
	Settings::add("guest_color_guild", $MODULE_NAME, "Color for Guestchannelrelay(Text in Guild)", "edit", "<font color=#C3C3C3>", "color");
	Settings::add("guest_color_guest", $MODULE_NAME, "Color for Guestchannelrelay(Text in Guestchannel)", "edit", "<font color=#C3C3C3>", "color");
	Settings::add("guest_relay", $MODULE_NAME, "Relay of the Guestchannel", "edit", "1", "ON;OFF", "1;0");
	Settings::add("guest_relay_commands", $MODULE_NAME, "Relay commands and results from/to guestchannel", "edit", "0", "ON;OFF", "1;0");
	
	//Autoreinvite Players after a botrestart or crash
	Event::register("setup", $MODULE_NAME, "autoreinvite.php", "none", "Reinvites the players that were in the privgrp before restart/crash");
	
	Event::register("guild", $MODULE_NAME, "guest_channel_relay.php", "none", "Guest channel relay from guild channel");
	Event::register("priv", $MODULE_NAME, "guest_channel_relay.php", "none", "Guest channel relay from priv channel");
	Event::register("logOn", $MODULE_NAME, "logon_autoinvite.php", "none", "Auto-invite members on logon");
	
	//Show Char infos on privjoin
	Event::register("joinPriv", $MODULE_NAME, "notify.php", "none", "Show Infos about a Char when he joins the channel");
	Event::register("leavePriv", $MODULE_NAME, "notify.php", "none", "Show a msg when someone leaves the channel");
	
	//Verifies the Guestchannellist every 1hour
	Event::register("1hour", $MODULE_NAME, "guest_channel_check.php", "guest", "Guest channel check");

    Help::register("GuestChannel", $MODULE_NAME, "guestchannel.txt", GUILDMEMBER, "Guestchannel", "Basic Guild Commands");
	Help::register("join_leave", $MODULE_NAME, "joinleave.txt", ALL, "Joining and leaving the bot", "Raidbot");
	Help::register("kickall", $MODULE_NAME, "kickall.txt", RAIDLEADER, "Kick all players from the Bot", "Raidbot");
	Help::register("lock", $MODULE_NAME, "lock.txt", RAIDLEADER, "Lock the privategroup", "Raidbot");
?>