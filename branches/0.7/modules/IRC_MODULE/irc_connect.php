<?php
   /*
   ** Author: Legendadv (RK2)
   ** IRC RELAY MODULE
   **
   ** Developed for: Budabot(http://aodevs.com/index.php/topic,512.0.html)
   **
   */
   
global $socket;
stream_set_blocking($socket, 0);
set_time_limit(0);
//settings
if(Settings::get('irc_server') == "") {
	$this->send("The IRC <highlight>server address<end> seems to be missing. Please <highlight>/tell <myname> <symbol>help irc<end> for details on setting this");
	return;
}
if(Settings::get('irc_port') == "") {
	$this->send("The IRC <highlight>server port<end> seems to be missing. Please <highlight>/tell <myname> <symbol>help irc<end> for details on setting this");
	return;
}

$nick = Settings::get('irc_nickname');
 
// Connection
if (preg_match("/^startirc$/i", $message)) {
	$this->send("Intialized IRC connection. Please wait...",$sender);
}
Logger:log(__FILE__, "Intialized IRC connection. Please wait...", INFO);
$socket = fsockopen(Settings::get('irc_server'), Settings::get('irc_port'));
fputs($socket,"USER $nick $nick $nick $nick :$nick\n");
fputs($socket,"NICK $nick\n");
while ($logincount < 10) {
	$logincount++;
	$data = fgets($socket, 128);
	if (Settings::get('irc_debug_all') == 1) {
		Logger:log(__FILE__, trim($data), DEBUG);
	}
	// Separate all data
	$ex = explode(' ', $data);

	// Send PONG back to the server
	if($ex[0] == "PING"){
		fputs($socket, "PONG ".$ex[1]."\n");
	}
	flush();
}
sleep(1);
fputs($socket,"JOIN ".Settings::get('irc_channel')."\n");

while ($data = fgets($socket)) {
	if (Settings::get('irc_debug_all') == 1) {
		Logger:log(__FILE__, trim($data), DEBUG);
	}
	if (preg_match("/(ERROR)(.+)/", $data, $sandbox)) {
		if (preg_match("/^startirc$/i", $message)) {
			$this->send("[red]Could not connect to IRC", $sender);
		}
		Logger::log(__FILE__, trim($data), ERROR);
		return;
	}
	if ($ex[0] == "PING") {
		fputs($socket, "PONG ".$ex[1]."\n");
	}
	if (preg_match("/(End of \/NAMES list)/", $data, $discard)) {
		break;
	}
	flush();
}
if (preg_match("/^startirc$/i", $message)) {
	$this->send("Finished connecting to IRC",$sender);
}
Logger:log(__FILE__, "Finished connecting to IRC", INFO);
Settings::save("irc_status", "1");
?>