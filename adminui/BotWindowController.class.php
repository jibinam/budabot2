<?php

// register the class into GTK to enable custom signals
GObject::register_type('BotWindowController');

class BotWindowController extends GObject {

	private $botWindow;

	/**
	 * Define custom signals that this class can emit.
	 */
	public $__gsignals = array(
		// notifies that user has sent a command, first parameter is channel, second is the command
		'command_given' => array(GObject::SIGNAL_RUN_LAST, GObject::TYPE_NONE, array(GObject::TYPE_LONG, GObject::TYPE_STRING))
	);

	/**
	 * Constructor method.
	 */
	public function __construct() {
		parent::__construct();
		
		// load botwindow.glade file
		$botWindowBuilder = new GtkBuilder();
		$botWindowBuilder->add_from_file(dirname(__FILE__) . '/botwindow.glade');

		// get some widgets and objects for easier access
		$this->botWindow  = $botWindowBuilder->get_object('botwindow');
		$outputScrollArea = $botWindowBuilder->get_object('outputScrollArea');
		$this->outputView = $botWindowBuilder->get_object('outputView');
		$this->commandEntry = $botWindowBuilder->get_object('commandInputEntry');
		$this->destinationSelector = $botWindowBuilder->get_object('destinationSelector');

		// call scrollViewToBottom() when scroll area's vertical scrollbar changes
		$outputScrollArea->get_vadjustment()->connect('changed', array($this, 'scrollViewToBottom'));

		// call onCommandGiven() when user hits enter-key within the entry
		$this->commandEntry->connect_simple('activate', array($this, 'onCommandGiven'));
		
		// prevent deletion of the window on close
		$this->botWindow->connect('delete-event', array($this, 'onDeleteEvent'));
	}
	
	public function setConsoleModel($model) {
		$this->outputView->set_buffer($model);
	}
	
	public function show() {
		$this->botWindow->show_all();
	}

	/**
	 * This method catches delete event and instead of simply deleting the
	 * dialog, it is hidden instead. Doing this it is possible to re-show the
	 * dialog next time.
	 */
	public function onDeleteEvent() {
		$this->botWindow->hide();
		return true;
	}

	/**
	 * This callback is called when output view's vertical
	 * scrollbar ($adjustment) changes.
	 * Scrolls the scrollbar to bottom of the scrollable area.
	 */
	public function scrollViewToBottom($adjustment) {
		$adjustment->set_value($adjustment->upper - $adjustment->page_size);
	}
	
	/**
	 * This callback function is called when user hits enter-key in the command
	 * input entry.
	 * Sends the entry's text as a command to the bot and clears the entry field.
	 */
	public function onCommandGiven() {
		// get command and clear the input entry
		$command = $this->commandEntry->get_text();
		$this->commandEntry->set_text('');
		// get output channel
		$channel = $this->destinationSelector->get_model()->get_value($this->destinationSelector->get_active_iter(), 1);
		// notify of the command
		$this->emit('command_given', $channel, $command);
	}
}

