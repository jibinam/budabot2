<?php

require_once 'Process.class.php';
require_once 'ControlPanelController.class.php';
require_once 'budapi/Budapi.php';
require_once 'SystrayController.class.php';
require_once 'BotWindowController.class.php';
require_once 'BotModel.class.php';
require_once 'SettingModel.class.php';

class Application {

	private $botModel;
	private $settingModel;
	private $systrayController;
	
	/**
	 * Constructor method.
	 */
	public function __construct() {
	}

	/**
	 *
	 */
	public function execute() {
		$this->settingModel = new SettingModel();
		$this->botModel = new BotModel($this->settingModel);

		$this->systrayController = new SystrayController();
		$botWindowController = new BotWindowController();
	
		$controlPanel = new ControlPanelController($this->botModel);
		$controlPanel->connect('action_triggered', array($this, 'onControlPanelAction'));
		$controlPanel->show();
		
		// open control panel when user double clicks systray icon
		$this->systrayController->connect_simple('activated', array($controlPanel, 'show'));

		// start GTK's event loop
		Gtk::main();
	}

	/**
	 *
	 */
	public function onControlPanelAction($object, $action, $botName) {

		switch ($action) {
		case 'open':
			$bot = $this->botModel->getBotByName($botName);
			$controller = $this->botWindowController($botName);
			$controller->connect('command_given', array($bot, 'sendCommand'));
			$controller->show();
			break;

		default:
			$this->showErrorMessage("This action is not implemented!");
		}
	}
	
	/**
	 *
	 */
	private function showErrorMessage($message) {
		$dialog = new GtkMessageDialog(null, Gtk::DIALOG_MODAL, Gtk::MESSAGE_ERROR, Gtk::BUTTONS_OK, 'Error');
		$dialog->set_markup($message);
		$dialog->run();
		$dialog->destroy();
	}

	private function botWindowController($botName) {
		if (!isset($this->botWindowControllers[$botName])) {
			$this->botWindowControllers[$botName] = new BotWindowController();
		}
		return $this->botWindowControllers[$botName];
	}
}
