<?php

/**
 * Authors: 
 *	- Tyrence (RK2)
 *
 * @Instance
 *
 * Commands this controller contains:
 *	@DefineCommand(
 *		command     = 'shop',
 *		accessLevel = 'member',
 *		description = 'Searches shopping messages',
 *		help        = 'shop.txt'
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
	public $chatBot;
	
	/** @Inject */
	public $text;
	
	/** @Inject */
	public $util;
	
	/** @Inject */
	public $playerManager;
	
	/** @Inject */
	public $banManager;
	
	/** @Inject */
	public $itemsController;
	
	/** @Logger */
	public $logger;
	
	/** @Setup */
	public function setup() {
		$this->db->loadSQLFile($this->moduleName, "shopping_messages");
		$this->db->loadSQLFile($this->moduleName, "shopping_items");
		
		$this->settingManager->add($this->moduleName, "shop_message_age", "How long to keep shopping messages", "edit", "time", "10d", "1d;2d;5d;10d;15d;20d");
	}
	
	/**
	 * @HandlesCommand("shop")
	 * @Matches("/^shop (.+)$/i")
	 */
	public function shopCommand($message, $channel, $sender, $sendto, $args) {
		$search = $args[1];
		$sql = "
			SELECT
				sender,
				message,
				MAX(dt) as dt
			FROM
				shopping_items s1
				JOIN shopping_messages s2
					ON s1.message_id = s2.id
			WHERE
				s2.dimension = <dim>
				AND s1.name LIKE ?
			GROUP BY
				sender,
				message
			ORDER BY
				MAX(dt) DESC
			LIMIT
				40";
		$data = $this->db->query($sql, "%{$search}%");
		
		if (count($data) > 0) {
			$blob = '';
			forEach ($data as $row) {
				$senderLink = $this->text->make_userlink($row->sender);
				$timeString = $this->util->unixtime_to_readable(time()- $row->dt, false);
				$post = preg_replace('/<a href="itemref:\/\/(\d+)\/(\d+)\/(\d+)">([^<]+)<\/a>/', "<a href='itemref://\\1/\\2/\\3'>\\4</a>", $row->message);
				$blob .= "[$senderLink]: {$post} - <highlight>($timeString ago)<end>\n\n";
			}
			
			$msg = $this->text->make_blob("Shopping Results for '$search'", $blob);
		} else {
			$msg = "No shopping results found for '$search'.";
		}
		$sendto->reply($msg, $sendto);
	}
	
	/**
	 * @Event("allpackets")
	 * @Description("Capture messages from shopping channel")
	 */
	public function captureShoppingMessagesEvent($eventObj) {
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
		
		if ($this->banManager->is_banned($sender)) {
			return;
		}
		
		$this->logger->log_chat($channel, $sender, $message);

		$message = preg_replace("/<font(.+)>/U", "", $message);
		$message = preg_replace("/<\/font>/U", "", $message);
		
		// messageType: 1=WTS, 2=WTB, 3=WTT, default to WTS
		$messageType = 1;
		if (preg_match("/^(.{0,3})wtb/i", $message)) {
			$messageType = 2;
		} else if (preg_match("/^(.{0,3})wtt/i", $message)) {
			$messageType = 3;
		}
		
		$matches = array();
		$pattern = '/<a href="itemref:\/\/(\d+)\/(\d+)\/(\d+)">([^<]+)<\/a>/';
		preg_match_all($pattern, $message, $matches, PREG_SET_ORDER);

		$sql = "INSERT INTO shopping_messages (dimension, message_type, channel, bot, sender, dt, message) VALUES ('<dim>', ?, ?, '<myname>', ?, ?, ?)";
		$this->db->exec($sql, $messageType, $channel, $sender, time(), $message);
		$id = $this->db->lastInsertId();
		
		forEach ($matches as $match) {
			$lowid = $match[1];
			$highid = $match[2];
			$ql = $match[3];
			$name = $match[4];

			$item = $this->itemsController->doXyphosLookup($lowid);
			$iconid = 0;
			if ($item !== null) {
				$iconid = $item->icon;
			}

			$sql = "INSERT INTO shopping_items (message_id, lowid, highid, ql, iconid, name) VALUES (?, ?, ?, ?, ?, ?)";
			$this->db->exec($sql, $id, $lowid, $highid, $ql, $iconid, $name);
		}
		
		$this->playerManager->get_by_name($sender);
	}
	
	/**
	 * @Event("24hrs")
	 * @Description("Remove old shopping messages from the database")
	 */
	public function removeOldMessagesEvent($eventObj) {
		$dt = time() - $this->settingManager->get('shop_message_age');

		$this->db->begin_transaction();

		$sql = "DELETE FROM shopping_messages WHERE dt < ?";
		$this->db->exec($sql, $dt);

		$sql = "DELETE FROM shopping_items WHERE message_id NOT IN (SELECT id FROM shopping_messages)";
		$this->db->exec($sql);

		$this->db->commit();
	}
}
	