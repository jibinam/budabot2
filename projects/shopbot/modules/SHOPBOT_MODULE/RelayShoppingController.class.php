<?php

/**
 * Authors: 
 *	- Tyrence (RK2)
 *
 * @Instance
 *
 * Commands this controller contains:
 *	@DefineCommand(
 *		command     = 'spam'
 *		accessLevel = 'member', 
 *		description = 'Spams message to public channel', 
 *		help        = 'spam.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'spamproxy'
 *		accessLevel = 'mod', 
 *		description = 'Spams message to public channel on behalf of another player', 
 *		help        = 'spamproxy.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'filter'
 *		accessLevel = 'mod', 
 *		description = 'Manage filters for incoming shopping messages', 
 *		help        = 'filter.txt'
 *	)
 */
class CaptureShoppingController {

	/**
	 * Name of the module.
	 * Set automatically by module loader.
	 */
	public $moduleName;
	
	/** @Inject */
	public $db;
	
	/** @Inject */
	public $settingManager;
	
	/** @Inject */
	public $commandManager;
	
	/** @Inject */
	public $chatBot;
	
	/** @Inject */
	public $text;
	
	/** @Inject */
	public $util;
	
	/** @Inject */
	public $accessLevel;
	
	/** @Inject */
	public $playerManager;
	
	/** @Inject */
	public $banManager;
	
	/** @Logger */
	public $logger;
	
	private $shopping_spam_protection = array();
	
	private $lastMessage;
	
	/** @Setup */
	public function setup() {
		$this->db->loadSQLFile($this->moduleName, "filtercontent");

		$this->settingManager->register($this->moduleName, "shopbot_master", "Set the shopbot master", "edit", "text", "0");
		$this->settingManager->register($this->moduleName, "time_between_messages", "Time users must wait between spamming messages", "edit", "time", "30m", "5m;10m;15m;20m;30m;45m;60m");
		$this->settingManager->register($this->moduleName, "add_ql_info", "Enable showing ql as part of item links", "edit", "options", "0", "true;false", "1;0");
	}
	
	/**
	 * @HandlesCommand("spam")
	 * @Matches("/^spam (shopping|ooc) (clan|omni|neut|all|both) (.+)$/i")
	 */
	public function spamCommand($message, $channel, $sender, $sendto, $args) {
		$spamchannel = strtolower($args[1]);
		$side = strtolower($args[2]);
		$spammsg = $args[3];

		$link = "<a $style href='user://$sender'>Send $sender a tell</a>";
		$msg = "$spammsg -> $link";

		$this->process_spam_request($sender, $msg, $spamchannel, $side);
	}
	
	/**
	 * @HandlesCommand("spam")
	 * @Matches("/^spamproxy (shopping|ooc) (clan|omni|neut|all|both) ([a-z0-9-]+) (.+)$/i")
	 */
	public function spamproxyCommand($message, $channel, $sender, $sendto, $args) {
		$spamchannel = strtolower($args[1]);
		$side = strtolower($args[2]);
		$replyTo = ucfirst(strtolower($args[3]));
		$spammsg = $args[4];

		$link = "<a $style href='user://$replyTo'>Send $replyTo a tell</a>";
		$msg = "$spammsg -> $link";

		$this->process_spam_request($sender, $msg, $spamchannel, $side);
	}
	
	/**
	 * @HandlesCommand("filter")
	 * @Matches("/^filter add content (.*)$/i")
	 */
	public function filterAddCommand($message, $channel, $sender, $sendto, $args) {
		$regex = $args[1];
	
		$this->db->exec("INSERT INTO filter_content (addedBy, regex) VALUES (?, ?)", $sender, $regex);
		$msg = "Content filter added successfully.";
		$sendto->reply($msg, $sendto);
	}
	
	/**
	 * @Event("allpackets")
	 * @Description("Relay shopping messages to private channel")
	 */
	public function relayShoppingMessagesEvent($eventObj) {
		$packet = $eventObj->packet;
		if ($packet->type != AOCP_GROUP_MESSAGE) {
			return;
		}

		$b = unpack("C*", $packet->args[0]);
		// check to make sure message is from a shopping channel
		// (first byte = 134; see http://aodevs.com/forums/index.php/topic,42.msg2192.html#msg2192)
		if ($b[1] != 134) {
			return;
		}

		$channel = $this->chatBot->get_gname($packet->args[0]);
		$sender	= $this->chatBot->lookup_user($packet->args[1]);
		$message = $packet->args[2];
		
		if ($sender == $this->chatBot->vars['name']) {
			return;
		}
		
		if ($this->banManager->is_banned($sender)) {
			return;
		}
		
		$blocked = false;
		$data = $this->db->query("SELECT regex FROM filter_content");
		forEach ($data as $row) {
			if (preg_match("/$row->regex/i", $message)) {
				$this->logger->log('INFO', "BLOCKED -- $message");
				return;
			}
		}
		
		if ($this->settingManager->get('add_ql_info') == 1) {
			$pattern = '/<a href="itemref:\/\/(\d+)\/(\d+)\/(\d+)">([^<]+)<\/a>/';
			$message = preg_replace($pattern, "<a href=\"itemref://\\1/\\2/\\3\">\\4 (QL \\3)</a>", $message);
		}

		if ($this->lastMessage != $message) {
			$newChannel = str_replace(" shopping 11-50", "", $channel);  // shorten channel name (e.g. remove "shopping " from "OT shopping 11-50" to get "OT")

			$senderLink = $this->text->make_userlink($sender);
			$this->chatBot->sendPrivate("[$newChannel] $senderLink: $message", $this->settingManager->get('shopbot_master'));
			$this->lastMessage = $message;
		} else {
			//echo "DUPLICATE-$message\n";
		}
	}
	
	/**
	 * @Event("msg")
	 * @Description("Relay incoming tells into private channel")
	 */
	public function tellMessagesEvent($eventObj) {
		$sender = $eventObj->sender;
		if (strtolower($sender) == strtolower($this->settingManager->get('shopbot_master'))) {
			return;
		} else if (!$this->accessLevelt->check_access($sender, 'member')) {
			$senderLink = $this->text->make_userlink($sender);
			$this->chatBot->sendPrivate("<green>[Inc. Msg.]<end> {$senderLink}: <green>{$message}<end>", $this->settingManager->get('shopbot_master'));

			// we don't want the bot to respond back to people
			throw new StopExecutionException();
		}
	}
	
	/**
	 * @Event("extJoinPrivRequest")
	 * @Description("Handle a private group invitation")
	 */
	public function inviteFromShopbotMasterEvent($eventObj) {
		$sender = $eventObj->sender;
		if (strtolower($sender) == strtolower($this->settingManager->get('shopbot_master'))) {
			$this->chatBot->privategroup_join($sender);
		}
	}
	
	/**
	 * @Event("extPriv")
	 * @Description("Process commands from shopbot master private channel")
	 */
	public function processExternalPrivateCommandEvent($eventObj) {
		$sender = $eventObj->sender;
		$message = $eventObj->message;
		$channel = $eventObj->channel;
		if ($message[0] == $this->settingManager->get('symbol') && strtolower($channel) == strtolower($this->settingManager->get('shopbot_master'))) {
			$message = substr($message, 1);
			$sendto = new PrivateMessageCommandReply($this->chatBot, $sender);
			$this->commandManager->process($channel, $message, $sender, $sendto);
		}
	}
}
	