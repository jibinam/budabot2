<?php

/**
 * @author Tyrence(RK2)
 * @description updated to fix duplicate channel issue and no response from server
 *
 */

require_once("vent.inc.php");
require_once("ventrilostatus.php");

$stat = new CVentriloStatus;
$stat->m_cmdprog	= '"' . getcwd() . '/modules/VENTRILO_MODULE/ventrilo_status.exe"';
$stat->m_cmdcode	= "2";					// Detail mode. 1=General Status, 2=Detail

// change config below this line only
$stat->m_cmdhost	= $this->settings["ventaddress"];	// enter your vent server ip or hostname here
$stat->m_cmdport	= $this->settings["ventport"];		// enter your vent server port number
$stat->m_cmdpass	= $this->settings["ventpassword"];	// Status password if necessary.

$lobby = new CVentriloChannel;
$lobby->m_cid = 0;			// Channel ID.
$lobby->m_pid = 0 ;			// Parent channel ID.
$lobby->m_prot = 0;			// Password protected flag.
$lobby->m_name = "Lobby";	// Channel name.
$lobby->m_comm = "This is the lobby";	// Channel comment.
$stat->m_channellist[] = $lobby;

$msg = '';
$error = FALSE;
if ($this->settings["ventimplementation"] == 1) {

	$rc = $stat->Request();
	if ($rc) {
		$error =  "Could not get ventrilo info: $stat->m_error";
	}

} else if ($this->settings["ventimplementation"] == 2) {

	$vent = new Vent;
	$vent->setTimeout( 500000 );		// 300 ms timeout
	
	if (!$vent->makeRequest(2, $stat->m_cmdhost, $stat->m_cmdport)) {

		$error = "Could not get ventrilo info";
		
	} else {
		$rawresponse = $vent->getResponse();
	
		$nohtmltags = strip_tags($rawresponse);
		$formattedResponse = preg_split("/[\r\n]+/", $nohtmltags, 0, REG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

		foreach($formattedResponse as $line) {
			$stat->Parse( $line );
    	}	
	}
}

if ($error === FALSE) {
	
	$uptime = '';
	$unixtime = $stat->m_uptime;
    $days = floor($unixtime / 86400);
	if($days != 0) {
		$uptime .= "<white>".$days."<end><grey>day(s) <end>";
	}
    $hours = floor(($unixtime-($days*86400))/3600);
	if($hours != 0) {
		$uptime .= "<white>".$hours."<end><grey>hr(s) <end>";
	}
    $mins = floor(($unixtime-($days*86400)-$hours*3600)/60);
	if($mins != 0) {
		$uptime .= "<white>".$mins."<end><grey>min(s) <end>";
	}
    $secs = $unixtime-($days*86400)-($hours*3600)-$mins*60;
	if($secs != 0) {
		$uptime .= "<white>".$secs."<end><grey>sec(s)<end>";
	}
    
    $page = "<header>Ventrilo Server Information<end>\n\n";
    $page .= "This is a <orange>PRIVATE<end> Ventrilo server.\n";
    $page .= "Please DO NOT give out this information without permission.\n\n";
    $page .= "Hostname: <white>{$stat->m_cmdhost}<end>\n";
    $page .= "Port Number: <white>{$stat->m_cmdport}<end>\n";
    
    if ($this->settings["showventpassword"] == 1) {
    	$page .= "Password: <white>{$stat->m_cmdpass}<end>\n\n";
	}

    $page .= "Server Name: <white>{$stat->m_name}<end>\n";
    $page .= "Users: <white>{$stat->m_clientcount} / {$stat->m_maxclients}<end>\n";
    
    if ($this->settings["showextendedinfo"] == 1) {
		$page .= "Voice Encoder: <white>{$stat->m_voicecodec_code}<end> - <grey>{$stat->m_voicecodec_desc}<end>\n";
		$page .= "Voice Format: <white>{$stat->m_voiceformat_code}<end> - <grey>{$stat->m_voiceformat_desc}<end>\n";
		$page .= "Server Uptime: $uptime\n";
		$page .= "Server Platform: <white>{$stat->m_platform}<end>\n";
		$page .= "Server Version: <white>{$stat->m_version}<end>\n";
		$page .= "Number of channels: <white>{$stat->m_channelcount}<end>\n";
	}
    $page .= "\nChannels:\n";

    foreach($stat->m_channellist as $channel) {
		displayChannel($channel, $stat->m_clientlist, "", $page);
	}
	
	$page .= "\n\n*Please note that sometimes the server will not return the right information, if this happens, please try again.\n";
	$page = str_replace('"', "", $page);
	$msg = bot::makeLink("Vent Status", $page, 'blob');

} else {

	$msg = "<orange>$error<end>";
}

if($type == "msg")
	bot::send($msg, $sender);
elseif($type == "priv")
	bot::send($msg);
elseif($type == "guild")
	bot::send($msg, "guild");

?>
