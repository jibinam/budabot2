<?php

// register the class into GTK to enable custom signals
GObject::register_type('SystrayController');

class SystrayController extends GObject {

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
	}
	
	/**
	 * This callback handler is called when user double clicks the systray icon.
	 */
	public function onActivate() {
		$this->emit('activated');
	}
}
