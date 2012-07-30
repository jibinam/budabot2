<?php

/**
 * @Instance
 *
 * Commands this controller contains:
 *	@DefineCommand(
 *		command     = 'items',
 *		accessLevel = 'all',
 *		description = 'Searches for an item',
 *		help        = 'items.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'updateitems',
 *		accessLevel = 'guild',
 *		description = 'Downloads the latest version of the items db',
 *		help        = 'updateitems.txt'
 *	)
 */
class ItemsController {
	/** @Inject */
	public $db;

	/** @Inject */
	public $chatBot;

	/** @Inject */
	public $setting;

	public $moduleName;

	/**
	 * @Setting("maxitems")
	 * @Description("Number of Items shown on the list")
	 * @Visibility("edit")
	 * @Type("number")
	 * @Options("30;40;50;60")
	 */
	public $defaultMaxitems = "40";

	/** @Setup */
	public function setup() {
		$this->db->loadSQLFile($this->moduleName, "aodb");
	}

	/**
	 * This command handler searches for an item.
	 *
	 * @HandlesCommand("items")
	 * @Matches("/^items ([0-9]+) (.+)$/i")
	 * @Matches("/^items (.+)$/i")
	 */
	public function itemsCommand($message, $channel, $sender, $sendto, $args) {
		if (count($args) == 3) {
			$ql = $args[1];
			if (!($ql >= 1 && $ql <= 500)) {
				$msg = "QL must be between 1 and 500.";
				$sendto->reply($msg);
				return;
			}
			$search = $args[2];
		} else {
			$search = $args[1];
			$ql = false;
		}

		$search = htmlspecialchars_decode($search);
		$msg = $this->find_items_from_local($search, $ql);
		$sendto->reply($msg);
	}

	/**
	 * This command handler downloads the latest version of the items db.
	 *
	 * @HandlesCommand("updateitems")
	 */
	public function updateitemsCommand($message, $channel, $sender, $sendto) {
		$msg = $this->download_newest_itemsdb();
		$sendto->reply($msg);
	}

	/**
	 * @Event("24hrs")
	 * @Description("Check to make sure items db is the latest version available")
	 */
	public function checkForUpdate() {
		$this->download_newest_itemsdb();
	}

	public function download_newest_itemsdb() {
		$chatBot = Registry::getInstance('chatBot');
		$db = Registry::getInstance('db');
		$setting = Registry::getInstance('setting');

		LegacyLogger::log('INFO', 'ITEMS_MODULE', "Starting items db update");

		// get list of files in ITEMS_MODULE
		$data = file_get_contents("http://budabot2.googlecode.com/svn/trunk/modules/ITEMS_MODULE");
		$data = str_replace("<hr noshade>", "", $data);  // not valid xml

		try {
			$xml = new SimpleXmlElement($data);

			// find the latest items db version on the server
			$latestVersion = null;
			forEach ($xml->body->ul->li as $item) {
				if (preg_match("/^aodb(.*)\\.sql$/i", $item->a, $arr)) {
					if ($latestVersion === null) {
						$latestVersion = $arr[1];
					} else if (Util::compare_version_numbers($arr[1], $currentVersion)) {
						$latestVersion = $arr[1];
					}
				}
			}
		} catch (Exception $e) {
			LegacyLogger::log('ERROR', 'ITEMS_MODULE', "Error updating items db: " . $e->getMessage());
			return "Error updating items db: " . $e->getMessage();
		}

		if ($latestVersion !== null) {
			$currentVersion = $setting->get("aodb_db_version");

			// if server version is greater than current version, download and load server version
			if ($currentVersion === false || Util::compare_version_numbers($latestVersion, $currentVersion) > 0) {
				// download server version and save to ITEMS_MODULE directory
				$contents = file_get_contents("http://budabot2.googlecode.com/svn/trunk/modules/ITEMS_MODULE/aodb{$latestVersion}.sql");
				$fh = fopen("./modules/ITEMS_MODULE/aodb{$latestVersion}.sql", 'w');
				fwrite($fh, $contents);
				fclose($fh);

				$db->begin_transaction();

				// load the sql file into the db
				$db->loadSQLFile("ITEMS_MODULE", "aodb");

				$db->commit();

				LegacyLogger::log('INFO', 'ITEMS_MODULE', "Items db updated from '$currentVersion' to '$latestVersion'");
				$msg = "The items database has been updated to the latest version.  Version: $latestVersion";
			} else {
				LegacyLogger::log('INFO', 'ITEMS_MODULE', "Items db already up to date '$currentVersion'");
				$msg = "The items database is already up to date.  Version: $currentVersion";
			}
		} else {
			LegacyLogger::log('ERROR', 'ITEMS_MODULE', "Could not find latest items db on server");
			$msg = "There was a problem finding the latest version on the server";
		}

		LegacyLogger::log('INFO', 'ITEMS_MODULE', "Finished items db update");

		return $msg;
	}

	public function find_items_from_local($search, $ql) {
		$tmp = explode(" ", $search);
		$first = true;
		forEach ($tmp as $key => $value) {
			$value = str_replace("'", "''", $value);
			if ($first) {
				$query .= "`name` LIKE '%$value%'";
				$first = false;
			} else {
				$query .= " AND `name` LIKE '%$value%'";
			}
		}

		if ($ql) {
			$query .= " AND `lowql` <= $ql AND `highql` >= $ql";
		}

		$sql = "SELECT * FROM aodb WHERE $query ORDER BY `name` ASC, highql DESC LIMIT 0, " . $this->setting->get("maxitems");
		$data = $this->db->query($sql);
		$num = count($data);
		if ($num == 0) {
			if ($ql) {
				$msg = "No QL <highlight>$ql<end> items found matching <highlight>$search<end>.";
			} else {
				$msg = "No items found matching <highlight>$search<end>.";
			}
			return $msg;
		} else if ($num > 3) {
			$blob = "Version: " . $this->setting->get('aodb_db_version') . "\n";
			if ($ql) {
				$blob .= "Search: QL $ql $search\n\n";
			} else {
				$blob .= "Search: $search\n\n";
			}
			$blob .= $this->formatSearchResults($data, $ql, true);
			$xrdbLink = Text::make_chatcmd("XRDB", "/start http://www.xyphos.com/viewtopic.php?f=6&t=10000091");
			$budabotItemsExtractorLink = Text::make_chatcmd("Budabot Items Extractor", "/start http://budabot.com/forum/viewtopic.php?f=7&t=873");
			$blob .= "\n\n<highlight>Item DB rips provied by MajorOutage (RK1) using Xyphos' $xrdbLink tool and the $budabotItemsExtractorLink plugin<end>";
			$link = Text::make_blob("Item Search Results ($num)", $blob);

			return $link;
		} else {
			return trim($this->formatSearchResults($data, $ql, false));
		}
	}

	public function formatSearchResults($data, $ql, $showImages) {
		$list = '';
		forEach ($data as $row) {
			if ($showImages) {
				$list .= "<img src='rdb://".$row->icon."'> \n";
			}
			if ($ql) {
				$list .= "QL $ql ".Text::make_item($row->lowid, $row->highid, $ql, $row->name);
			} else {
				$list .= Text::make_item($row->lowid, $row->highid, $row->highql, $row->name);
			}
			if ($row->lowql != $row->highql) {
				$list .= " (QL".$row->lowql." - ".$row->highql.")\n";
			} else {
				$list .= " (QL".$row->lowql.")\n";
			}
			if ($showImages) {
				$list .= "\n";
			}
		}
		return $list;
	}
}

?>