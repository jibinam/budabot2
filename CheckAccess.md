# How to check a character's Access Level #

Normally, you don't have to worry about access levels in the bot.  The bot will automatically restrict access to commands based on the access level setting on the command and the access level of the user trying to access the command.

However, there are some cases where you may need this functionality.  For instance, you may have a command that displays the names of the last ten people to send a tell to the bot.  You may wish to display a "ban" link when a moderator or higher uses that command.

The format of the command is:

`AccessLevel::check_access($player, $access_level);`

Where `$player` is the name of the player and `$access_level` is any one of: `superadmin`, `admininistrator`, `moderator`, `raidleader`, `guildadmin`, `leader`, `guild`, `member`, or `all`.

To check if a player named 'Tyrence' has raidleader access, you would do:

`AccessLevel("Tyrence", "raidleader");`

Note that this will return `true` if 'Tyrence' is a raidleader on your bot, but also if he is anything higher, such as moderator, administrator, or superadmin.