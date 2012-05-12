<?php

// register the class into GTK to enable custom signals
GObject::register_type('SystrayController');

class SystrayController extends GObject {

	private $icon;
	private $contextMenu;
	private $closeTimerId;
	
	/**
	 * Define custom signals that this class can emit.
	 */
	public $__gsignals = array(
		// this signal is emitted when user attempts to open the control panel
		'open_requested' => array(GObject::SIGNAL_RUN_LAST, GObject::TYPE_NONE, array()),
		// this signal is emitted when user attempts to exit the application
		'exit_requested' => array(GObject::SIGNAL_RUN_LAST, GObject::TYPE_NONE, array())
	);

	/**
	 * Constructor method.
	 */
	public function __construct() {
		parent::__construct();
		$this->icon = new GtkStatusIcon();
		$this->icon->set_from_stock(Gtk::STOCK_FILE);
		$this->icon->connect_simple('activate', array($this, 'onOpenClicked'));
		$this->icon->connect_simple('popup-menu', array($this, 'onMenu'));
		$this->icon->set_visible(true);
		$this->icon->set_blinking(false);

		// build context menu
		$this->contextMenu = new GtkMenu();
		$this->contextMenu->connect_simple('enter-notify-event', array($this, 'stopCloseTimout'));
		$this->contextMenu->connect_simple('leave-notify-event', array($this, 'startCloseTimout'));
		$itemOpen = new GtkMenuItem('Open');
		$itemOpen->set_visible(true);
		$itemOpen->connect_simple('activate', array($this, 'onOpenClicked'));
		$this->contextMenu->append($itemOpen);
		$itemExit = new GtkMenuItem('Exit');
		$itemExit->set_visible(true);
		$itemExit->connect_simple('activate', array($this, 'onExitClicked'));
		$this->contextMenu->append($itemExit);
		
		// set default action as bold
		$label = $itemOpen->get_children();
		$label = $label[0];
        $label->set_markup("<b>{$label->get_text()}</b>");
		
		$this->closeTimerId = null;
	}
	
	/**
	 * This callback handler is called when user attempts to open the control panel.
	 */
	public function onOpenClicked() {
		$this->emit('open_requested');
	}
	
	/**
	 * This callback handler is called when user attempts to exit the application.
	 */
	public function onExitClicked() {
		$this->emit('exit_requested');
	}
	
	/**
	 * This method starts the timeout which closes the context menu automatically.
	 */
	public function startCloseTimout() {
		if ($this->closeTimerId === null) {
			$this->closeTimerId = Gtk::timeout_add(1000, array($this, 'closeContextMenu'));
		}
	}

	/**
	 * This method stops the timeout which closes the context menu automatically.
	 */
	public function stopCloseTimout() {
		if ($this->closeTimerId !== null) {
			Gtk::timeout_remove($this->closeTimerId);
			$this->closeTimerId = null;
		}
	}
	
	/**
	 * This method closes the context menu.
	 */
	public function closeContextMenu() {
		$this->contextMenu->popdown();
		return false;
	}
	
	/**
	 * This callback handler is called when popup menu should be shown.
	 */
	public function onMenu() {
		GtkStatusIcon::position_menu($this->contextMenu, $this->icon);
		$this->contextMenu->popup(null);
		$this->startCloseTimout();
	}
}
