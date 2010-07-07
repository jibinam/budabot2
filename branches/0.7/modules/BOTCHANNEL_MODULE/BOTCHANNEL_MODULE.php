<?php
	$MODULE_NAME = "BOTCHANNEL_MODULE";
	
	$this->loadSQLFile($MODULE_NAME, "private_chat");
    
    $this->command("", "$MODULE_NAME/members.php", "members", ALL, "Guest Channel Auto-Invitelist");
    $this->command("", "$MODULE_NAME/onlineguests.php", "onlineguests", ALL, "Guest Channellist");
	$this->command("", "$MODULE_NAME/autoinvite.php", "autoinvite", ALL, "Allows member to set whether he should be auto-invited to guest channel on logon or not");
    $this->command("guild msg", "$MODULE_NAME/join.php", "join", ALL, "Join command for guests");
	$this->command("priv msg", "$MODULE_NAME/leave.php", "leave", ALL, "Enables Privatechat Kick");

	$this->command("", "$MODULE_NAME/kickall.php", "kickall", MODERATOR, "Kicks all from the privgroup");
	$this->command("", "$MODULE_NAME/lock.php", "lock", RAIDLEADER, "Locks the privgroup");
	$this->command("", "$MODULE_NAME/lock.php", "unlock", RAIDLEADER, "Unlocks the privgroup");
	
	$this->command("", "$MODULE_NAME/invite.php", "inviteuser", ALL, "Enables Privatechat Join");
	$this->command("", "$MODULE_NAME/kick.php", "kickuser", ALL, "kick command for guests");
	$this->command("", "$MODULE_NAME/add.php", "adduser", ALL, "Enables Privatechat Join");
	$this->command("", "$MODULE_NAME/rem.php", "remuser", ALL, "Enables Privatechat Join");
	
	$this->addsetting("guest_man_join", "Mode of manual guestchannel join", "edit", "1", "Only for members of guestlist;Everyone", "1;0");
	$this->addsetting("guest_color_channel", "Color for Guestchannelrelay(ChannelName)", "edit", "<font color=#C3C3C3>", "color");
	$this->addsetting("guest_color_username", "Color for Guestchannelrelay(UserName)", "edit", "<font color=#C3C3C3>", "color");
	$this->addsetting("guest_color_guild", "Color for Guestchannelrelay(Text in Guild)", "edit", "<font color=#C3C3C3>", "color");
	$this->addsetting("guest_color_guest", "Color for Guestchannelrelay(Text in Guestchannel)", "edit", "<font color=#C3C3C3>", "color");
	$this->addsetting("guest_relay", "Relay of the Guestchannel", "edit", "1", "ON;OFF", "1;0");
	$this->addsetting("guest_relay_commands", "Relay commands and results from/to guestchannel", "edit", "0", "ON;OFF", "1;0");
	
	//Autoreinvite Players after a botrestart or crash
	$this->event("connect", "$MODULE_NAME/autoreinvite.php", "none", "Reinvites the players that were in the privgrp before restart/crash");
	
	$this->event("guild", "$MODULE_NAME/guest_channel_relay.php", "none");
	$this->event("priv", "$MODULE_NAME/guest_channel_relay.php", "none");
	$this->event("logOn", "$MODULE_NAME/logon_autoinvite.php", "none");
	
	//Show Char infos on privjoin
	$this->event("joinPriv", "$MODULE_NAME/notify.php", "none", "Show Infos about a Char when he joins the channel");
	$this->event("leavePriv", "$MODULE_NAME/notify.php", "none", "Show a msg when someone leaves the channel");
	
	//Verifies the Guestchannellist every 1hour
	$this->event("1hour", "$MODULE_NAME/guest_channel_check.php", "guest");

    $this->help("GuestChannel", "$MODULE_NAME/guestchannel.txt", GUILDMEMBER, "Guestchannel", "Basic Guild Commands");
	$this->help("join_leave", "$MODULE_NAME/joinleave.txt", ALL, "Joining and leaving the bot", "Raidbot");
	$this->help("kickall", "$MODULE_NAME/kickall.txt", RAIDLEADER, "Kick all players from the Bot", "Raidbot");
	$this->help("lock", "$MODULE_NAME/lock.txt", RAIDLEADER, "Lock the privategroup", "Raidbot");
?>