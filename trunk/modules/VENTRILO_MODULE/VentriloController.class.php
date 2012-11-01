<?php

require_once "vent.inc.php";
require_once "ventrilostatus.php";

/**
 * Authors: 
 *  - Tyrence (RK2)
 *
 * @Instance
 *
 * Commands this controller contains:
 *	@DefineCommand(
 *		command     = 'vent',
 *		accessLevel = 'all', 
 *		description = 'Show Ventrilo Server info', 
 *		help        = 'vent.txt'
 *	)
 */
class VentriloController {

	/**
	 * Name of the module.
	 * Set automatically by module loader.
	 */
	public $moduleName;
	
	/** @Inject */
	public $setting;

	/** @Inject */
	public $text;
	
	/** @Inject */
	public $util;
	
	/** @Setup */
	public function setup() {
		$this->setting->add($this->moduleName, "ventaddress", "Ventrilo Server Address", "edit", "text", "unknown");
		$this->setting->add($this->moduleName, "ventport", "Ventrilo Server Port", "edit", "number", "unknown");
		$this->setting->add($this->moduleName, "ventpass", "Ventrilo Server Password", "edit", "text", "unknown");

		$this->setting->add($this->moduleName, "ventimplementation", "Platform your bot runs on", "edit", "options", "1", "Windows;Linux", "1;2");
		$this->setting->add($this->moduleName, "showventpassword", "Show password with vent info?", "edit", "options", "1", "true;false", "1;0");
		$this->setting->add($this->moduleName, "showextendedinfo", "Show extended vent server info?", "edit", "options", "1", "true;false", "1;0");
	}
	
	/**
	 * @Event("logOn")
	 * @Description("Sends Vent status to org members logging on")
	 * @DefaultStatus("0")
	 */
	public function sendVentStatusLogonEvent($eventObj) {
		if ($this->chatBot->is_ready() && isset($this->chatBot->guildmembers[$eventObj->sender])) {
			$msg = $this->getVentStatus();
			$this->chatBot->sendTell($msg, $eventObj->sender);
		}
	}

	/**
	 * @HandlesCommand("vent")
	 * @Matches("/^vent$/i")
	 */
	public function ventCommand($message, $channel, $sender, $sendto, $args) {
		$sendto->reply($this->getVentStatus());
	}
	
	public function getVentStatus() {
		$stat = new CVentriloStatus;
		$stat->m_cmdprog	= '"' . getcwd() . '/modules/VENTRILO_MODULE/ventrilo_status.exe"';
		$stat->m_cmdcode	= "2";					// Detail mode. 1=General Status, 2=Detail

		// change config below this line only
		$stat->m_cmdhost	= $this->setting->get("ventaddress");	// enter your vent server ip or hostname here
		$stat->m_cmdport	= $this->setting->get("ventport");		// enter your vent server port number
		$stat->m_cmdpass	= $this->setting->get("ventpass");		// Status password if necessary.

		$lobby = new CVentriloChannel;
		$lobby->m_cid = 0;			// Channel ID.
		$lobby->m_pid = 0 ;			// Parent channel ID.
		$lobby->m_prot = 0;			// Password protected flag.
		$lobby->m_name = "Lobby";	// Channel name.
		$lobby->m_comm = "This is the lobby";	// Channel comment.
		$stat->m_channellist[] = $lobby;

		$msg = '';
		$error = false;
		if ($this->setting->get("ventimplementation") == 1) {

			$rc = $stat->Request();
			if ($rc) {
				$error =  "Could not get ventrilo info: $stat->m_error";
			}

		} else if ($this->setting->get("ventimplementation") == 2) {

			$vent = new Vent;
			$vent->setTimeout( 500000 );		// 300 ms timeout

			if (!$vent->makeRequest(2, $stat->m_cmdhost, $stat->m_cmdport)) {

				$error = "Could not get ventrilo info.";

			} else {
				$rawresponse = $vent->getResponse();

				$nohtmltags = strip_tags($rawresponse);
				$formattedResponse = preg_split("/[\r\n]+/", $nohtmltags, 0, REG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

				forEach($formattedResponse as $line) {
					$stat->Parse( $line );
				}
			}
		}

		if ($error === false) {

			$page = "This is a <orange>PRIVATE<end> Ventrilo server.\n";
			$page .= "Please DO NOT give out this information without permission.\n";
			$page .= "Channels highlighted <orange>ORANGE<end> are password protected.\n\n";
			$page .= "Hostname: <white>{$stat->m_cmdhost}<end>\n";
			$page .= "Port Number: <white>{$stat->m_cmdport}<end>\n";

			if ($this->setting->get("showventpassword") == 1) {
				$page .= "Password: <white>{$stat->m_cmdpass}<end>\n";
			}

			$page .= "\nServer Name: <white>{$stat->m_name}<end>\n";
			$page .= "Users: <white>{$stat->m_clientcount} / {$stat->m_maxclients}<end>\n";

			if ($this->setting->get("showextendedinfo") == 1) {
				$page .= "Voice Encoder: <white>{$stat->m_voicecodec_code}<end> - <grey>{$stat->m_voicecodec_desc}<end>\n";
				$page .= "Voice Format: <white>{$stat->m_voiceformat_code}<end> - <grey>{$stat->m_voiceformat_desc}<end>\n";
				$page .= "Server Uptime: " . $this->util->unixtime_to_readable($stat->m_uptime, false) . "\n";
				$page .= "Server Platform: <white>{$stat->m_platform}<end>\n";
				$page .= "Server Version: <white>{$stat->m_version}<end>\n";
				$page .= "Number of channels: <white>{$stat->m_channelcount}<end>\n";
			}
			$page .= "\nChannels:\n";

			forEach ($stat->m_channellist as $channel) {
				displayChannel($channel, $stat->m_clientlist, "", $page);
			}

			$page .= "\n\n*Please note that sometimes the server will not return the right information. If this happens, please try again.\n";
			$msg = $this->text->make_blob("Ventrilo Info ({$stat->m_clientcount})", $page);

		} else {
			$msg = "<orange>$error<end>";
		}
		return $msg;
	}
}