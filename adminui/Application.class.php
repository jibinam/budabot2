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

		$systrayController = new SystrayController();
		$botWindowController = new BotWindowController();
	
		$controlPanel = new ControlPanelController($this->botModel);
		$controlPanel->connect('action_triggered', array($this, 'onControlPanelAction'));
		$controlPanel->show();
		
		// open control panel when user double clicks systray icon
		$systrayController->connect_simple('open_requested', array($controlPanel, 'show'));

		// connect exit requests to quit()-method
		$controlPanel->connect_simple('exit_requested', array($this, 'quit'));
		$systrayController->connect_simple('exit_requested', array($this, 'quit'));
		
		// start GTK's event loop
		Gtk::main();
	}

	/**
	 * Calling this method will stop the event loop and execution returns
	 * from execute().
	 */
	public function quit() {
		$dialog = new GtkMessageDialog(null, Gtk::DIALOG_MODAL, Gtk::MESSAGE_QUESTION, Gtk::BUTTONS_OK_CANCEL, 'Exiting');
		$dialog->set_markup("Exiting from the Bot Manager will terminate any running bots, are you sure?");
		if ($dialog->run() == Gtk::RESPONSE_OK) {
			// hop out of event loop
			Gtk::main_quit();
		}
		$dialog->destroy();
	}
	
	/**
	 *
	 */
	public function onControlPanelAction($object, $action, $botName) {

		$bot = $this->botModel->getBotByName($botName);

		switch ($action) {
		case 'open':
			$controller = $this->botWindowController($botName);
			$controller->connect('command_given', array($bot, 'sendCommand'));
			$controller->show();
			break;
			
		case 'start':
			$bot->start();
			break;

		case 'restart':
			$bot->restart();
			break;

		case 'shutdown':
			$bot->shutdown();
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
			$bot = $this->botModel->getBotByName($botName);
			$this->botWindowControllers[$botName] = new BotWindowController();
			$this->botWindowControllers[$botName]->setConsoleModel($bot->getConsoleModel());
		}
		return $this->botWindowControllers[$botName];
	}
}
