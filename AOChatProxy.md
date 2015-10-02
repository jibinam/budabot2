# Introduction #

This "proxy" program allows budabot to be used for an org with more than 1K members using slave bots, something budabot never had native support for before now.

# Details #

I wrote this proxy as a way for bots that don't have native support for slave bots to overcome the 1k friend list limit.

master bot  the bot that connects to the proxy
proxy bots  the connections the proxy makes to the ao chat servers
first proxy bot  the proxy bot configured as "bot1" in the chatbot.properties file

Basically, you change the server and port on your bot to connect to where ever you have this proxy running.  When your bot initiates a connection to the proxy, the proxy authenticates it as the ao server would, and then the proxy connects to the actual ao servers with how ever many bots you've configured.

Default port is 9993 but you can override this by sending a different command line argument.  In retrospect I probably should have put this in the config file.  Also, when the master bot connects to the proxy it needs to login with the same character name as the first proxy bot.  The proxy does not check username or password.

The proxy relays all packets it gets from the master bot to the first proxy bot with the exception of FriendUpdate and FriendRemove packets--it automatically load balances all FriendUpdate and FriendRemove packets between all available proxy bots.

For packets coming from the server, only packets sent to the first proxy bot are relayed back to the master bot.  This is done to prevent duplicate guild channel messages coming in when the proxy bots are in the same org.  This means that only the first proxy bot will respond to commands, tells, etc.

The master bot should not require any changes except to change the server and port where it connects to.  If there are any places where the 1k buddy limit is hard coded those will have to be removed of course.  Also, if it relies on the charid passed back with the CharacterList packet to be a valid charid that will have to be changed.

Only one master bot can connect to the proxy at a time.  When the proxy detects the connection to the master bot has been dropped, it disconnects all the proxy bots from the ao chat servers.  When the master bot makes a new connection it will reconnect the proxy bots to the ao chat servers.  This means that when the master bot restarts, the proxy will automatically restart as well, disconnecting all proxy bots and then reconnecting them.

The proxy does not connect the proxy bots to the chat servers until the master bot sends the LoginSelect packet.  This is so that the master bot gets all the packets that are sent from the ao chat servers immediately after login is completed.

chatbot.properties  file to configure the proxy bots.  you can configure an unlimited amount of proxy bots
log4j.properties  file to configure logging

