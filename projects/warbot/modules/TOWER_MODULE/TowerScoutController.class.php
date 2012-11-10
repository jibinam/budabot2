<?php
/**
 * @Instance
 *
 * Commands this controller contains:
 *	@DefineCommand(
 *		command     = 'planttimer',
 *		accessLevel = 'all',
 *		description = 'Sets a timer to indicate when a tower site is plantable',
 *		help        = 'planttimer.txt',
 *		alias       = 'planttimers'
 *	)
 *	@DefineCommand(
 *		command     = 'forcescout',
 *		accessLevel = 'guild',
 *		description = 'Add tower info to watch list (bypasses some of the checks)',
 *		help        = 'scout.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'scout',
 *		accessLevel = 'guild',
 *		description = 'Add tower info to watch list',
 *		help        = 'scout.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'scouthistory',
 *		accessLevel = 'guild',
 *		description = 'See scout history for a site',
 *		help        = 'scout.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'scoutneeded',
 *		accessLevel = 'guild',
 *		description = 'See which sites need scouting',
 *		help        = 'scout.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'opentimes',
 *		accessLevel = 'guild',
 *		description = 'Show opentimes of towers',
 *		help        = 'open.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'open',
 *		accessLevel = 'guild',
 *		description = 'Show tower sites that are currently open',
 *		help        = 'open.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'unplanted',
 *		accessLevel = 'guild',
 *		description = 'Show tower sites are unplanted',
 *		help        = 'unplanted.txt'
 *	)
 */
class TowerScoutController {

	/**
	 * Name of the module.
	 * Set automatically by module loader.
	 */
	public $moduleName;

	/** @Inject */
	public $playfieldController;

	/** @Inject */
	public $towerController;

	/** @Inject */
	public $text;
	
	/** @Inject */
	public $chatBot;

	/** @Inject */
	public $setting;

	/** @Inject */
	public $db;

	/** @Inject */
	public $util;
	
	/** @Inject */
	public $timerController;

	/** @Logger */
	public $logger;
	
	const PLANT_TIME = 1200;  // 20 minutes
	const FACTION = "Neutral";
	
	/**
	 * @Setting("check_close_time_on_scout")
	 * @Description("Check that close time is within one hour of last victory on site")
	 * @Visibility("edit")
	 * @Type("options")
	 * @Options("true;false")
	 * @Intoptions("1;0")
	 * @AccessLevel("mod")
	 */
	public $defaultCheckCloseTimeOnScout = "1";
	
	/**
	 * @Setting("check_guild_name_on_scout")
	 * @Description("Check that guild name has attacked or been attacked before")
	 * @Visibility("edit")
	 * @Type("options")
	 * @Options("true;false")
	 * @Intoptions("1;0")
	 * @AccessLevel("mod")
	 */
	public $defaultCheckGuildNameOnScout = "1";
	
	/**
	 * @Setup
	 */
	public function setup() {
		$this->db->loadSQLFile($this->moduleName, 'scout_info');
		
		$row = $this->db->queryRow("SELECT * FROM scout_info");
		if ($row === null) {
			$this->db->loadSQLFile($this->moduleName, 'load_scout_info');
		}
	}

	/**
	 * @HandlesCommand("planttimer")
	 * @Matches("/^planttimer$/i")
	 */
	public function planttimerCommand($message, $channel, $sender, $sendto, $args) {
		$time = time() - self::PLANT_TIME;
		$sql = "
			SELECT
				p.short_name,
				t.site_number,
				v.time
			FROM
				tower_victory_<myname> v
				JOIN tower_attack_<myname> t
					ON v.attack_id = t.id
				JOIN scout_info s
					ON t.playfield_id = s.playfield_id AND t.site_number = s.site_number
				JOIN playfields p
					ON t.playfield_id = p.id
				JOIN (SELECT
						playfield_id,
						site_number,
						MAX(a.time) max_time
					FROM
						tower_victory_<myname> a
						JOIN tower_attack_<myname> a2
							ON a.attack_id = a2.id
					GROUP BY
						playfield_id, site_number) tt
					ON t.playfield_id = tt.playfield_id AND t.site_number = tt.site_number
			WHERE
				s.is_current <> 1
				AND v.time = tt.max_time
				AND v.time > ?
			ORDER BY
				v.time DESC";
		$data = $this->db->query($sql, $time);
		
		$count = count($data);
		if ($count > 0) {
			// split list into sites that have timers set and sites that don't
			$list1 = array();
			$list2 = array();
			forEach ($data as $row) {
				$t = $this->getPlantTimer("$row->short_name $row->site_number");
				if ($t === null) {
					$list1 []= $row;
				} else {
					$list2 []= $t;
				}
			}
			
			$blob = '';

			// show sites that don't have a plant timer
			if (count($list1) > 0) {
				$blob .= "<header2>Sites without planttimers<end>\n";
				forEach ($list1 as $row) {
					$planttimerLink = $this->text->make_chatcmd("$row->short_name $row->site_number", "/tell <myname> planttimer $row->short_name $row->site_number");
					$timeSince = $this->util->unixtime_to_readable(time() - $row->time);
					$blob .= "$planttimerLink ($timeSince ago)\n";
				}
				$blob .= "\n";
			}

			// show sites that have a plant timer
			if (count($list2) > 0) {
				$blob .= "<header2>Running timers<end>\n";
				forEach ($list2 as $timer) {
					$timeSince = $this->util->unixtime_to_readable($timer->timer - time());
					$blob .= "<highlight>$timer->name<end> ($timeSince left)\n";
				}
			}

			$msg = $this->text->make_blob("Plant Timers", $blob);
		} else {
			$msg = "There are no recently taken sites.";
		}
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("planttimer")
	 * @Matches("/^planttimer ([0-9a-z]+) ([0-9]+)$/i")
	 */
	public function planttimerSetCommand($message, $channel, $sender, $sendto, $args) {
		$playfield_name = $args[1];
		$site_number = $args[2];
		
		$playfield = $this->playfieldController->get_playfield_by_name($playfield_name);
		if ($playfield === null) {
			$msg = "Invalid playfield.";
			$sendto->reply($msg);
			return;
		}
		
		$towerInfo = $this->towerController->get_tower_info($playfield->id, $site_number);
		if ($towerInfo === null) {
			$msg = "Invalid site number.";
			$sendto->reply($msg);
			return;
		}
		
		$name = "$playfield->short_name $site_number";
		
		$time = time() - self::PLANT_TIME;
		$sql = "
			SELECT
				v.time
			FROM
				tower_victory_<myname> v
				JOIN tower_attack_<myname> t
					ON v.attack_id = t.id
				JOIN scout_info s
					ON t.playfield_id = s.playfield_id AND t.site_number = s.site_number
			WHERE
				s.is_current <> 1
				AND t.playfield_id = ?
				AND t.site_number = ?
				AND v.time > ?
			ORDER BY
				v.time DESC";
		$row = $this->db->queryRow($sql, $playfield->id, $site_number, $time);
		
		if ($row === null) {
			$msg = "Could not find recent attack for <highlight>$name<end>.";
		} else if ($this->getPlantTimer($name) !== null) {
			$msg = "A plant timer for <highlight>$name<end> has already been set.";
		} else {
			$runTime = $row->time + self::PLANT_TIME - time();
			$msg = $this->addPlantTimer($sender, $name, $runTime, $towerInfo);
		}
		$sendto->reply($msg);
	}
	
	public function addPlantTimer($sender, $name, $runTime, $towerInfo) {
		$endTime = time() + $runTime;
		
		$alerts = array();
		
		$levelRange = " ($towerInfo->min_ql - $towerInfo->max_ql)";
		
		if ($endTime - 60*15 > time()) {
			$alert = new stdClass;
			$alert->message = "15 minutes to plant $name $levelRange";
			$alert->time = $endTime - 60*15;
			$alerts []= $alert;
		}
		
		if ($endTime - 60*10 > time()) {
			$alert = new stdClass;
			$alert->message = "10 minutes to plant $name $levelRange";
			$alert->time = $endTime - 60*10;
			$alerts []= $alert;
		}
		
		if ($endTime - 60*5 > time()) {
			$alert = new stdClass;
			$alert->message = "5 minutes to plant $name $levelRange";
			$alert->time = $endTime - 60*5;
			$alerts []= $alert;
		}
		
		if ($endTime - 60*2 > time()) {
			$alert = new stdClass;
			$alert->message = "2 minutes to plant $name $levelRange";
			$alert->time = $endTime - 60*2;
			$alerts []= $alert;
		}
		
		if ($endTime - 60 > time()) {
			$alert = new stdClass;
			$alert->message = "1 minute to plant $name";
			$alert->time = $endTime - 60;
			$alerts []= $alert;
		}
		
		if ($endTime - 30 > time()) {
			$alert = new stdClass;
			$alert->message = "<yellow>~*30*~ <red>seconds to plant<end> $name<end>";
			$alert->time = $endTime - 30;
			$alerts []= $alert;
		}
		
		if ($endTime - 15 > time()) {
			$alert = new stdClass;
			$alert->message = "<yellow>~*15*~ <red>seconds to plant<end> $name<end>";
			$alert->time = $endTime - 15;
			$alerts []= $alert;
		}
		
		if ($endTime - 10 > time()) {
			$alert = new stdClass;
			$alert->message = "<yellow>~*10*~ <red>seconds to plant<end> $name<end>";
			$alert->time = $endTime - 10;
			$alerts []= $alert;
		}
		
		if ($endTime - 5 > time()) {
			$alert = new stdClass;
			$alert->message = "<yellow>~*5*~ <red>seconds to plant<end> $name<end>";
			$alert->time = $endTime - 5;
			$alerts []= $alert;
		}
		
		if ($endTime - 4 > time()) {
			$alert = new stdClass;
			$alert->message = "<yellow>~*4*~ <red>seconds to plant<end> $name<end>";
			$alert->time = $endTime - 4;
			$alerts []= $alert;
		}
		
		if ($endTime - 3 > time()) {
			$alert = new stdClass;
			$alert->message = "<yellow>~*3*~ <red>seconds to plant<end> $name<end>";
			$alert->time = $endTime - 3;
			$alerts []= $alert;
		}
		
		if ($endTime - 2 > time()) {
			$alert = new stdClass;
			$alert->message = "<yellow>~*2*~ <red>seconds to plant<end> $name<end>";
			$alert->time = $endTime - 2;
			$alerts []= $alert;
		}
		
		if ($endTime - 1 > time()) {
			$alert = new stdClass;
			$alert->message = "<yellow>~*1*~ <red>second to plant<end> $name<end>";
			$alert->time = $endTime - 1;
			$alerts []= $alert;
		}
		
		if ($endTime > time()) {
			$alert = new stdClass;
			$alert->message = "<red>Plant <yellow>$name<end> now!<end>";
			$alert->time = $endTime;
			
			// spam 4 times
			$alerts []= $alert;
			$alerts []= $alert;
			$alerts []= $alert;
			$alerts []= $alert;
		}
		
		return $this->timerController->addTimer($sender, $name, $runTime, 'priv', $alerts);
	}
	
	public function getPlantTimer($name) {
		return $this->timerController->get($name);
	}
	
	/**
	 * @HandlesCommand("scout")
	 * @Matches("/^scout ([a-z0-9]+) ([0-9]+) ([0-9]{1,2}:[0-9]{2}:[0-9]{2}) ([0-9]+) ([a-z]+) (.*)$/i")
	 */
	public function scoutCommand($message, $channel, $sender, $sendto, $args) {
		$skip_checks = false;
	
		$playfield_name = $args[1];
		$site_number = $args[2];
		$closing_time = $args[3];
		$ct_ql = $args[4];
		$faction = ucfirst(strtolower($args[5]));
		$guild_name = $args[6];
	
		$msg = $this->addScoutInfo($sender, $playfield_name, $site_number, $closing_time, $ct_ql, $faction, $guild_name, $skip_checks);
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("forcescout")
	 * @Matches("/^forcescout ([a-z0-9]+) ([0-9]+) ([0-9]{1,2}:[0-9]{2}:[0-9]{2}) ([0-9]+) ([a-z]+) (.*)$/i")
	 */
	public function forcescoutCommand($message, $channel, $sender, $sendto, $args) {
		$skip_checks = true;
	
		$playfield_name = $args[1];
		$site_number = $args[2];
		$closing_time = $args[3];
		$ct_ql = $args[4];
		$faction = ucfirst(strtolower($args[5]));
		$guild_name = $args[6];
	
		$msg = $this->addScoutInfo($sender, $playfield_name, $site_number, $closing_time, $ct_ql, $faction, $guild_name, $skip_checks);
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("scouthistory")
	 * @Matches("/^scouthistory ([0-9a-z]+) ([0-9]+)$/i")
	 */
	public function scouthistoryCommand($message, $channel, $sender, $sendto, $args) {
		$playfield_name = $args[1];
		$site_number = $args[2];
		
		$playfield = $this->playfieldController->get_playfield_by_name($playfield_name);
		if ($playfield === null) {
			$msg = "Invalid playfield.";
			$sendto->reply($msg);
			return;
		}
		
		$tower_info = $this->towerController->get_tower_info($playfield->id, $site_number);
		if ($tower_info === null) {
			$msg = "Invalid site number.";
			$sendto->reply($msg);
			return;
		}
		
		$data = $this->db->query("SELECT * FROM scout_info_history WHERE playfield_id = ? AND site_number = ?", $playfield->id, $site_number);
		$count = count($data);
		if ($count === 0) {
			$msg = "No scout history entries are available.";
		} else {
			forEach ($data as $row) {
				$blob .= print_r($row, true);
			}
			$msg = $this->text->make_blob("History for $playfield->short_name $site_number ($count)", $blob);
		}
		
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("scoutneeded")
	 * @Matches("/^scoutneeded$/i")
	 */
	public function scoutneededCommand($message, $channel, $sender, $sendto, $args) {
		$msg = $this->getScoutneeded();
		if ($msg === null) {
			$msg = "No bases require scouting.";
		}
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("opentimes")
	 * @Matches("/^opentimes (\d+) (\d+)$/i")
	 */
	public function opentimesCommand($message, $channel, $sender, $sendto, $args) {
		$lowql = $args[1];
		$highql = $args[2];
		
		$sql = "
			SELECT
				*
			FROM
				tower_site t
				JOIN scout_info s ON (t.playfield_id = s.playfield_id AND s.site_number = t.site_number)
				JOIN playfields p ON (t.playfield_id = p.id)
			WHERE
				(s.ct_ql BETWEEN ? AND ?)
				AND (s.faction <> ?)
			ORDER BY
				close_time";
		$data = $this->db->query($sql, $lowql, $highql, self::FACTION);
		$count = count($data);
		
		if ($count > 0) {
			forEach ($data as $row) {
				$site_link = $this->text->make_chatcmd("$row->short_name $row->site_number", "/tell <myname> lc $row->short_name $row->site_number");
				$open_time = $row->close_time - (3600 * 6);
				if ($open_time < 0) {
					$open_time += 86400;
				}
				
				$blob .= "$site_link - {$row->min_ql}-{$row->max_ql}, $row->ct_ql CT, $row->guild_name, open from " . date("H:i T", $open_time) . " to " . date("H:i T", $row->close_time) . " [by $row->scouted_by]\n";
			}
	
			$msg = $this->text->make_blob("Scouted bases with CT QL {$lowql}-{$highql} ($count)", $blob);
		} else {
			$msg = "No sites found.";
		}
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("open")
	 * @Matches("/^open (neutral|omni|clan) (\d+) (\d+)$/i")
	 * @Matches("/^open (\d+) (\d+)$/i")
	 * @Matches("/^open (neutral|omni|clan)$/i")
	 */
	public function openCommand($message, $channel, $sender, $sendto, $args) {
		if (count($args) == 4) {
			$lowql = $args[2];
			$highql = $args[3];
			$faction = ucfirst(strtolower($args[1]));
			
			$title = "$faction sites open in the next hour with CT QL {$lowql}-{$highql}";
			$side_sql = "AND (s.faction = ?)";
		} else if (count($args) == 3) {
			$lowql = $args[1];
			$highql = $args[2];
			$faction = self::FACTION;
			
			$title = "Sites open in the next hour with CT QL {$lowql}-{$highql}";
			$side_sql = "AND (s.faction <> ?)";
		} else {
			$lowql = 1;
			$highql = 300;
			$faction = ucfirst(strtolower($args[1]));
			
			$title = "$faction sites open in the next hour with CT QL {$lowql}-{$highql}";
			$side_sql = "AND (s.faction = ?)";
		}
		
		$openTimeSql = $this->getOpenTimeSql(time() % 86400);
		
		$sql = "
			SELECT
				*
			FROM
				tower_site t
				JOIN scout_info s ON (t.playfield_id = s.playfield_id AND s.site_number = t.site_number)
				JOIN playfields p ON (t.playfield_id = p.id)
			WHERE
				$openTimeSql
				AND (s.ct_ql BETWEEN ? AND ?)
				$side_sql
			ORDER BY
				close_time";
		$data = $this->db->query($sql, $lowql, $highql, $faction);
		$count = count($data);
		
		$blob = '';
		forEach ($data as $row) {
			$gas_level = $this->towerController->getGasLevel($row->close_time);
			$site_link = $this->text->make_chatcmd("$row->short_name $row->site_number", "/tell <myname> lc $row->short_name $row->site_number");
			$gas_change_string = "$gas_level->color$gas_level->gas_level - $gas_level->next_state in " . $this->util->unixtime_to_readable($gas_level->gas_change) . "<end>";
			
			$blob .= "$site_link - {$row->min_ql}-{$row->max_ql}, $row->ct_ql CT, $row->guild_name, $gas_change_string [by $row->scouted_by]\n";
		}
		
		if ($count > 0) {
			$msg = $this->text->make_blob($title . " ($count)", $blob);
		} else {
			$msg = "No sites found.";
		}
		$sendto->reply($msg, $sendto);
	}
	
	/**
	 * @HandlesCommand("unplanted")
	 * @Matches("/^unplanted$/i")
	 */
	public function unplantedListCommand($message, $channel, $sender, $sendto, $args) {
		$sql = "SELECT * FROM scout_info s
			JOIN playfields p ON (s.playfield_id = p.id)
			JOIN tower_site t ON (s.playfield_id = t.playfield_id AND s.site_number = t.site_number)
			WHERE s.is_current = 2
			ORDER BY p.long_name ASC, s.site_number ASC";

		$data = $this->db->query($sql);
		$count = count($data);
		if ($count == 0) {
			$msg = "No bases are unplanted.";
			$sendto->reply($msg);
			return;
		}
		
		forEach ($data as $row) {
			$site_link = $this->text->make_chatcmd("$row->short_name $row->site_number", "/tell <myname> lc $row->short_name $row->site_number");
			$plantersLink = $this->text->make_chatcmd("Find planters", "/tell <myname> planters $row->short_name $row->site_number");
			$blob .= "$site_link - {$row->min_ql}-{$row->max_ql} $plantersLink\n";
		}
		
		$msg = $this->text->make_blob("Bases which are unplanted ($count)", $blob);
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("unplanted")
	 * @Matches("/^unplanted ([0-9a-z]+) ([0-9]+)$/i")
	 */
	public function unplantedSetCommand($message, $channel, $sender, $sendto, $args) {
		$playfield_name = $args[1];
		$site_number = $args[2];
		
		$playfield = $this->playfieldController->get_playfield_by_name($playfield_name);
		if ($playfield === null) {
			$msg = "Invalid playfield.";
			$sendto->reply($msg);
			return;
		}
		
		$sql = "SELECT * FROM scout_info s
			JOIN playfields p ON (s.playfield_id = p.id)
			WHERE s.playfield_id = ? AND s.site_number = ?
			ORDER BY p.long_name ASC, s.site_number ASC";

		$row = $this->db->queryRow($sql, $playfield->id, $site_number);
		if ($row === null) {
			$msg = "Could not find <highlight>$playfield->short_name $site_number<end>.";
		} else if ($row->is_current == 1) {
			$msg = "Tower site <highlight>$playfield->short_name $site_number<end> is already scouted.";
		} else if ($row->is_current == 2) {
			$msg = "Tower site <highlight>$playfield->short_name $site_number<end> is already marked as unplanted.";
		} else {
			$msg = "Tower site <highlight>$playfield->short_name $site_number<end> has been marked as unplanted.";
			$this->db->exec("UPDATE scout_info SET is_current = ?, scouted_on = ? WHERE playfield_id = ? AND site_number = ?", '2', time(), $playfield->id, $site_number);
		}
		$sendto->reply($msg);
	}
	
	/**
	 * @Event("1hr")
	 * @Description("Move sites on unplanted list back to scoutneeded list after 24 hours")
	 */
	public function checkUnplantedEvent($eventObj) {
		$time = 60*60*24; // 24 hours
		$this->db->exec("UPDATE scout_info SET is_current = ? WHERE is_current = ? AND scouted_on < ?", '0', '2', time() - $time);
	}
	
	/**
	 * @Event("joinPriv")
	 * @Description("Send scountneeded list to players who join the private channel")
	 */
	public function sendScoutneededPrivJoinEvent($eventObj) {
		$msg = $this->getScoutneeded();
		if ($msg !== null) {
			$this->chatBot->sendTell($msg, $eventObj->sender);
		}
	}
	
	/**
	 * @Event("1hr")
	 * @Description("Spam scoutneeded to private channel")
	 */
	public function spamScoutneededPrivateChannelEvent($eventObj) {
		$msg = $this->getScoutneeded();
		if ($msg !== null) {
			$this->chatBot->sendPrivate($msg);
		}
	}
	
	public function getScoutneeded() {
		$sql = "SELECT * FROM scout_info s
			JOIN playfields p ON (s.playfield_id = p.id)
			WHERE s.is_current = 0
			ORDER BY p.long_name ASC, s.site_number ASC";

		$data = $this->db->query($sql);
		$count = count($data);
		if ($count == 0) {
			return null;
		}
		
		$current_playfield_id = -1;
		forEach ($data as $row) {
			if ($current_playfield_id != $row->playfield_id) {
				$playfield_long_name = $this->text->make_chatcmd($row->long_name, "/tell <myname> lc $row->short_name");
				$blob .= "\n$playfield_long_name ($row->short_name): ";
				$current_playfield_id = $row->playfield_id;
			}
			$blob .= "$row->site_number ";
		}
		
		return $this->text->make_blob("Bases which require scouting ($count)", $blob);
	}

	public function addScoutInfo($sender, $playfield_name, $site_number, $closing_time, $ct_ql, $faction, $guild_name, $skip_checks) {
		if ($faction != 'Omni' && $faction != 'Neutral' && $faction != 'Clan') {
			return "Valid values for faction are: 'Omni', 'Neutral', and 'Clan'.";
		}
	
		$playfield = $this->playfieldController->get_playfield_by_name($playfield_name);
		if ($playfield === null) {
			return "Invalid playfield.";
		}
	
		$tower_info = $this->towerController->get_tower_info($playfield->id, $site_number);
		if ($tower_info === null) {
			return "Invalid site number.";
		}
	
		if ($ct_ql < $tower_info->min_ql || $ct_ql > $tower_info->max_ql) {
			return "$playfield->short_name $tower_info->site_number can only accept Control Tower of ql {$tower_info->min_ql}-{$tower_info->max_ql}";
		}
	
		$closing_time_array = explode(':', $closing_time);
		$closing_time_seconds = $closing_time_array[0] * 3600 + $closing_time_array[1] * 60 + $closing_time_array[2];
	
		if (!$skip_checks && $this->setting->get('check_close_time_on_scout') == 1) {
			$last_victory = $this->get_last_victory($tower_info->playfield_id, $tower_info->site_number);
			if ($last_victory !== null) {
				$victory_time_of_day = $last_attack->time % 86400;
				if ($victory_time_of_day > $closing_time_seconds) {
					$victory_time_of_day -= 86400;
				}
	
				if ($closing_time_seconds - $victory_time_of_day > 3600) {
					$check_blob .= "- <green>Closing time<end> The closing time you have specified is more than 1 hour after the site was destroyed.";
					$check_blob .= " Please verify that you are using the closing time and not the gas change time and that the closing time is correct.\n\n";
				}
			}
		}
	
		if (!$skip_checks && $this->setting->get('check_guild_name_on_scout') == 1) {
			if (!$this->check_guild_name($guild_name)) {
				$check_blob .= "- <green>Org name<end> The org name you entered has never attacked or been attacked.\n\n";
			}
		}
	
		if ($check_blob) {
			$forceCmd = "forcescout $playfield->short_name $site_number $closing_time $ct_ql $faction $guild_name";
			$forcescoutLink = $this->text->make_chatcmd("<symbol>$forceCmd", "/tell <myname> $forceCmd");
			$check_blob .= "Please correct these errors, or, if you are sure the values you entered are correct, use !forcescout to bypass these checks.\n\n";
			$check_blob .= $forcescoutLink;
			
			return $this->text->make_blob('Scouting problems', $check_blob);
		} else {
			$this->add_scout_site($playfield->id, $site_number, $closing_time_seconds, $ct_ql, $faction, $guild_name, $sender);
			return "Scout info has been updated.";
		}
	}
	
	private function get_last_victory($playfield_id, $site_number) {
		$sql = "
			SELECT
				*
			FROM
				tower_victory_<myname> v
				JOIN tower_attack_<myname> a ON (v.attack_id = a.id)
			WHERE
				a.`playfield_id` = ?
				AND a.`site_number` >= ?
			ORDER BY
				v.`time` DESC
			LIMIT 1";

		return $this->db->queryRow($sql, $playfield_id, $site_number);
	}
	
	private function check_guild_name($guild_name) {
		$sql = "SELECT * FROM tower_attack_<myname> WHERE `att_guild_name` LIKE ? OR `def_guild_name` LIKE ? LIMIT 1";

		$data = $this->db->query($sql, $guild_name, $guild_name);
		if (count($data) === 0) {
			return false;
		} else {
			return true;
		}
	}
	
	private function add_scout_site($playfield_id, $site_number, $close_time, $ct_ql, $faction, $guild_name, $scouted_by) {
		$sql = "
			INSERT INTO scout_info_history (
				`playfield_id`,
				`site_number`,
				`scouted_on`,
				`scouted_by`,
				`ct_ql`,
				`guild_name`,
				`faction`,
				`close_time`
			) VALUES (
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?
			)";

		$this->db->exec($sql, $playfield_id, $site_number, time(), $scouted_by, $ct_ql, $guild_name, $faction, $close_time);

		$sql = "
			UPDATE scout_info SET 
				`scouted_on` = ?,
				`scouted_by` = ?,
				`ct_ql` = ?,
				`guild_name` = ?,
				`faction` = ?,
				`close_time` = ?,
				`is_current` = ?
			WHERE
				`playfield_id` = ?
				AND `site_number` = ?";

		$this->db->exec($sql, time(), $scouted_by, $ct_ql, $guild_name, $faction, $close_time, '1', $playfield_id, $site_number);
	}
	
	private function getOpenTimeSql($current_time) {
		$first_high_val = $current_time + (3600*7);
		$first_low_val = $current_time;
		$second_high_val = $current_time - 86400 + (3600*7);
		$second_low_val = $current_time - 86400;

		return "((s.close_time BETWEEN $first_low_val AND $first_high_val) OR (s.close_time BETWEEN $second_low_val AND $second_high_val))";
	}
}

?>