<?php

// register the class into GTK to enable custom signals
GObject::register_type('SystrayController');

class SystrayController extends GObject {

	private $icon;
	private $contextMenu;
	
	/**
	 * Define custom signals that this class can emit.
	 */
	public $__gsignals = array(
		'activated' => array(GObject::SIGNAL_RUN_LAST, GObject::TYPE_NONE, array())
	);

	/**
	 * Constructor method.
	 */
	public function __construct() {
		parent::__construct();
		$this->icon = new GtkStatusIcon();
		$this->icon->set_from_stock(Gtk::STOCK_FILE);
		$this->icon->connect_simple('activate', array($this, 'onActivate'));
		$this->icon->connect_simple('popup-menu', array($this, 'onMenu'));
		$this->icon->set_visible(true);
		$this->icon->set_blinking(false);

		// build context menu
		$this->contextMenu = new GtkMenu();
		$itemOpen = new GtkMenuItem('Open');
		$itemOpen->set_visible(true);
		$itemOpen->connect_simple('activate', array($this, 'onActivate'));
		$this->contextMenu->append($itemOpen);
		$itemExit = new GtkMenuItem('Exit');
		$itemExit->set_visible(true);
		$itemExit->connect_simple('activate', array('gtk', 'main_quit'));
		$this->contextMenu->append($itemExit);
		
		// set default action as bold
		$label = $itemOpen->get_children();
		$label = $label[0];
        $label->set_markup("<b>{$label->get_text()}</b>");

	}
	
	/**
	 * This callback handler is called when user double clicks the systray icon.
	 */
	public function onActivate() {
		$this->emit('activated');
	}
	
	/**
	 * This callback handler is called when popup menu should be shown.
	 */
	public function onMenu() {
		GtkStatusIcon::position_menu($this->contextMenu, $this->icon);
		$this->contextMenu->popup(null);
	}
}
