<?php

require_once 'Bot.class.php';

class BotModel extends GtkListStore {

	private $settingModel;

	/**
	 * Constructor method.
	 */
	public function __construct($settingModel) {
		parent::__construct(GObject::TYPE_PHP_VALUE, GObject::TYPE_STRING);
		$this->settingModel = $settingModel;
		$this->loadFromSettings();
	}
	
	public function loadFromSettings() {
		$names = $this->settingModel->getBotNames();
		foreach ($names as $name) {
			$bot = $this->getBotByName($name);
			if (!$bot) {
				// create new bot object and add it to model
				$bot = new Bot($name, $this->settingModel);
				$this->append(array($bot, $bot->getName()));
			}
			// TODO: load bot settings from settingModel...
		}
	}
	
	public function getBotByName($name) {
		// loop through rows and return a bot with given name if found
		for ($iterator = $this->get_iter_first(); $iterator != null; $iterator = $this->iter_next($iterator)) {
			$bot = $this->get_value($iterator, 0);
			if ($bot->getName() == $name) {
				return $bot;
			}
		}
		return null;
	}
	
	/**
	 * Returns array of all bots in the model.
	 */
	public function getAllBots() {
		$bots = array();
		// loop through rows and return a bot with given name if found
		for ($iterator = $this->get_iter_first(); $iterator != null; $iterator = $this->iter_next($iterator)) {
			$bots []= $this->get_value($iterator, 0);
		}
		return $bots;
	}
}

