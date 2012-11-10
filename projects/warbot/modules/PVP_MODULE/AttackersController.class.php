<?php

/**
 * Authors: 
 *	- Tyrence (RK2)
 *
 * @Instance
 *
 * Commands this controller contains:
 *	@DefineCommand(
 *		command     = 'attackers', 
 *		accessLevel = 'all', 
 *		description = 'Show online attackers', 
 *		help        = 'attackers.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'attackers add .+', 
 *		accessLevel = 'mod', 
 *		description = 'Add players to the attackers list', 
 *		help        = 'attackers.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'attackers rem .+', 
 *		accessLevel = 'mod', 
 *		description = 'Remove players from the attackers list', 
 *		help        = 'attackers.txt'
 *	)
 */
class AttackersController {

	/**
	 * Name of the module.
	 * Set automatically by module loader.
	 */
	public $moduleName;

	/** @Inject */
	public $text;
	
	/** @Inject */
	public $util;
	
	/** @Inject */
	public $db;
	
	/** @Inject */
	public $chatBot;
	
	/** @Inject */
	public $buddylistManager;
	
	/** @Inject */
	public $playerManager;
	
	/** @Inject */
	public $towerController;
	
	/** @Logger */
	public $logger;
	
	/**
	 * @Setup
	 */
	public function setup() {
		$this->db->loadSQLFile($this->moduleName, 'attackers');
		$this->towerController->registerAttackListener(array($this, 'autoAddAttackers'));
	}
	
	public function autoAddAttackers($whois, $defender) {
		$time = 60*60*24*7; // 7 days
		$sql = "
			SELECT
				count(*) AS cnt
			FROM
				tower_attack_<myname>
			WHERE
				att_player = ?
				AND time > ?";
		$row = $this->db->queryRow($sql, $whois->name, time() - $time);
		if ($row->cnt > 3) {
			$msg = $this->addAttacker($whois->name, 'auto');
			if (preg_match("/has been added/", $msg)) {
				$this->chatBot->sendPrivate($msg);
			}
		}
	}
	
	/**
	 * @Event("connect")
	 * @Description("Adds all attackers to the friendlist")
	 */
	public function attackersUserConnectEvent($eventObj) {
		$sql = "
			SELECT
				a.charid,
				p.name
			FROM
				attackers a
				LEFT JOIN players p
					ON a.charid = p.charid";
		$data = $this->db->query($sql);
		forEach ($data as $row) {
			if (empty($row->name)) {
				$this->logger->log('WARN', "Could not add '$row->charid' to buddy list for attackers since no entry exists in players table");
			} else {
				$this->buddylistManager->add($row->name, 'attackers');
			}
		}
	}
	
	/**
	 * @Event("logOn")
	 * @Description("Records an attacker logging on")
	 */
	public function attackersLogonEvent($eventObj) {
		$charid = $this->chatBot->get_uid($eventObj->sender);
		$data = $this->db->query("SELECT * FROM attackers WHERE charid = ?", $charid);
		if (count($data) > 0) {
			$this->db->exec("UPDATE attackers SET online = ?, dt = ? WHERE charid = ?", '1', time(), $charid);
		}
	}
	
	/**
	 * @Event("logOff")
	 * @Description("Records an attacker logging off")
	 */
	public function attackersLogoffEvent($eventObj) {
		$charid = $this->chatBot->get_uid($eventObj->sender);
		$data = $this->db->query("SELECT * FROM attackers WHERE charid = ?", $charid);
		if (count($data) > 0) {
			$this->db->exec("UPDATE attackers SET online = ?, dt = ? WHERE charid = ?", '0', time(), $charid);
		}
	}

	/**
	 * @HandlesCommand("attackers")
	 * @Matches("/^attackers$/i")
	 */
	public function attackersListCommand($message, $channel, $sender, $sendto, $args) {
		$sql = "
			SELECT
				p.name,
				p.level,
				p.ai_level,
				p.profession,
				p.guild,
				p.faction,
				CASE
					WHEN level < 15 THEN '1'
					WHEN level < 50 THEN '2'
					WHEN level < 100 THEN '3'
					WHEN level < 150 THEN '4'
					WHEN level < 190 THEN '5'
					WHEN level < 205 THEN '6'
					ELSE '7'
				END as title_level,
				a.dt
			FROM
				attackers a
				LEFT JOIN players p
					ON a.charid = p.charid
			WHERE
				online = ?
			ORDER BY
				faction ASC, level ASC, name ASC";
				
		$data = $this->db->query($sql, 1);
		$count = count($data);
		if ($count > 0) {
			$blob = '';
			$currentFaction = '';
			$currentTitleLevel = '';
			forEach ($data as $row) {
				if ($row->faction != $currentFaction) {
					$currentFaction = $row->faction;
					$currentTitleLevel = '';
					$blob .= "\n<header> ::: $currentFaction ::: <end>";
				}
			
				if ($row->title_level != $currentTitleLevel) {
					$currentTitleLevel = $row->title_level;
					$blob .= "\n<header2>Title Level $currentTitleLevel<end>\n";
				}

				$onlineTime = $this->util->unixtime_to_readable(time() - $row->dt, false);
				if ($row->profession == null) {
					$blob .= "<highlight>$row->name<end> - Unknown (online for $onlineTime)\n";
				} else {
					$blob .= "<highlight>$row->name<end> - $row->level<green>/$row->ai_level<end> $row->profession, $row->guild (online for $onlineTime)\n";
				}
			}

			$msg = $this->text->make_blob("Attackers Online ($count)", trim($blob));
		} else {
			$msg = "No attackers are currently online.";
		}
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("attackers")
	 * @Matches("/^attackers all$/i")
	 */
	public function attackersListAllCommand($message, $channel, $sender, $sendto, $args) {
		$sql = "
			SELECT
				p.name,
				p.level,
				p.ai_level,
				p.profession,
				p.guild,
				p.faction,
				CASE
					WHEN level < 15 THEN '1'
					WHEN level < 50 THEN '2'
					WHEN level < 100 THEN '3'
					WHEN level < 150 THEN '4'
					WHEN level < 190 THEN '5'
					WHEN level < 205 THEN '6'
					ELSE '7'
				END as title_level,
				a.online,
				a.added_by,
				a.dt
			FROM
				attackers a
				LEFT JOIN players p
					ON a.charid = p.charid
			ORDER BY
				faction ASC, level ASC, name ASC";
				
		$data = $this->db->query($sql);
		$count = count($data);
		if ($count > 0) {
			$blob = '';
			$currentFaction = '';
			$currentTitleLevel = '';
			forEach ($data as $row) {
				if ($row->faction != $currentFaction) {
					$currentFaction = $row->faction;
					$currentTitleLevel = '';
					$blob .= "\n<header> ::: $currentFaction ::: <end>";
				}
			
				if ($row->title_level != $currentTitleLevel) {
					$currentTitleLevel = $row->title_level;
					$blob .= "\n<header2>Title Level $currentTitleLevel<end>\n";
				}

				if ($row->online == 1) {
					$onlineTime = $this->util->unixtime_to_readable(time() - $row->dt, false);
					$online = "(online for $onlineTime)";
				}
				
				if ($row->profession == null) {
					$blob .= "<highlight>$row->name<end> - Unknown $online [added by $row->added_by]\n";
				} else {
					$blob .= "<highlight>$row->name<end> - $row->level<green>/$row->ai_level<end> $row->profession, $row->guild $online [added by $row->added_by]\n";
				}
			}

			$msg = $this->text->make_blob("All Attackers ($count)", trim($blob));
		} else {
			$msg = "No players are currently on the attackers list.";
		}
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("attackers rem .+")
	 * @Matches("/^attackers rem (.+)$/i")
	 */
	public function attackersRemoveCommand($message, $channel, $sender, $sendto, $args) {
		$name = ucfirst(strtolower($args[1]));
		$charid = $this->chatBot->get_uid($name);

		if (!$charid) {
			$msg = "Player <highlight>$name<end> does not exist.";
		} else {
			$numrows = $this->db->exec("DELETE FROM attackers WHERE `charid` = ?", $charid);
			if ($numrows == 0) {
				$msg = "Player <highlight>$name<end> is not on the attackers list.";
			} else {
				
				$msg = "Player <highlight>$name<end> has been removed from the attackers list.";
				$this->buddylistManager->remove($name, 'attackers');
			}
		}

		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("attackers add .+")
	 * @Matches("/^attackers add (.+)$/i")
	 */
	public function attackersAddCommand($message, $channel, $sender, $sendto, $args) {
		$name = ucfirst(strtolower($args[1]));
		$msg = $this->addAttacker($name, $sender);
		$sendto->reply($msg);
	}
	
	public function addAttacker($name, $sender) {
		$charid = $this->chatBot->get_uid($name);
		if (!$charid) {
			return "Player <highlight>$name<end> does not exist.";
		}
		
		$whois = $this->playerManager->get_by_name($name);
		if ($whois === null) {
			return "Could not get info for player <highlight>$name<end>.";
		}

		$data = $this->db->query("SELECT * FROM attackers WHERE `charid` = ?", $charid);
		if (count($data) != 0) {
			return "Player <highlight>$name<end> is already on the attackers list.";
		}
		
		if ($this->buddylistManager->is_online($name) == 1) {
			$online = 1;
		} else {
			$online = 0;
		}
		$this->db->exec("INSERT INTO attackers (`charid`, `added_by`, `added_dt`, `online`, `dt`) VALUES (?, ?, ?, ?, ?)", $charid, $sender, time(), $online, time());
		$this->buddylistManager->add($name, 'attackers');
		return "Player <highlight>$name<end> has been added to the attackers list.";
	}
}
