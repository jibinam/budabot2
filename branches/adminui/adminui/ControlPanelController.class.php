<?php

// register the class into GTK to enable custom signals
GObject::register_type('ControlPanelController');

class ControlPanelController extends GObject {

	private $builder;
	private $view;
	private $position;
	private $botModel;
	
	/**
	 * Define custom signals that this class can emit.
	 */
	public $__gsignals = array(
		// This signal is emitted when user clicks item in bot's context menu.
		// First parameter is name of action and second is name of bot.
		'action_triggered' => array(GObject::SIGNAL_RUN_LAST, GObject::TYPE_NONE, array(GObject::TYPE_STRING, GObject::TYPE_STRING)),
		// this signal is emitted when user attempts to exit the application
		'exit_requested' => array(GObject::SIGNAL_RUN_LAST, GObject::TYPE_NONE, array())
	);
	
	/**
	 * Constructor method.
	 */
	public function __construct($botModel) {
		parent::__construct();
		
		$this->botModel = $botModel;
		$this->position = array(200, 200);
		// load controlpanel.glade file
		$this->builder = new GtkBuilder();
		$this->builder->add_from_file(dirname(__FILE__) . '/ControlPanel.glade');
		
		$this->view = $this->builder->get_object('controlPanelWindow');
		$this->botListView = $this->builder->get_object('botListView');
		$this->botListContextMenu = $this->builder->get_object('botListContextMenu');
		$this->contextItemOpen = $this->builder->get_object('contextItemOpen');
		$this->contextItemModify = $this->builder->get_object('contextItemModify');
		$this->contextItemRemove = $this->builder->get_object('contextItemRemove');
		$this->contextItemStart = $this->builder->get_object('contextItemStart');
		$this->contextItemRestart = $this->builder->get_object('contextItemRestart');
		$this->contextItemShutdown = $this->builder->get_object('contextItemShutdown');
		
		$this->botListView->set_model($this->botModel);
		
		// add cell renderer
		$renderer = new GtkCellRendererText();
		$renderer->set_property('height', 50);
		$column = new GtkTreeViewColumn('Bot', $renderer, 'text', 1);
		$this->botListView->append_column($column);
		
		// set default action as bold
		$label = $this->contextItemOpen->get_children();
		$label = $label[0];
        $label->set_markup("<b>{$label->get_text()}</b>");


		$this->view->connect('delete-event', array($this, 'onDeleteEvent'));
		$this->botListView->connect('button-press-event', array($this, 'onBotListViewMousePressed'));
		
		$this->botListView->connect_simple('row-activated', array($this, 'onBotListViewRowActivated'));
		
		$this->contextItemOpen->connect('activate', array($this, 'onContextMenuItemClicked'), 'open');
		$this->contextItemModify->connect('activate', array($this, 'onContextMenuItemClicked'), 'modify');
		$this->contextItemRemove->connect('activate', array($this, 'onContextMenuItemClicked'), 'remove');
		$this->contextItemStart->connect('activate', array($this, 'onContextMenuItemClicked'), 'start');
		$this->contextItemRestart->connect('activate', array($this, 'onContextMenuItemClicked'), 'restart');
		$this->contextItemShutdown->connect('activate', array($this, 'onContextMenuItemClicked'), 'shutdown');
		
		$this->builder->get_object('exitButton')->connect_simple('clicked', array($this, 'onExitClicked'));
	}

	/**
	 * This method shows and the dialog to user.
	 */
	public function show() {
		$this->view->move($this->position[0], $this->position[1]);
		$this->view->show_all();
	}
	
	/**
	 * This method catches delete event and instead of simply deleting the
	 * dialog, it is hidden instead. Doing this it is possible to re-show the
	 * dialog next time.
	 */
	public function onDeleteEvent() {
		$this->position = $this->view->get_position();
		$this->view->hide();
		return true;
	}
	
	/**
	 * This signal handler is called when user double clicks a row in the bot list view.
	 */
	public function onBotListViewRowActivated() {
		$this->emit('action_triggered', 'open', $this->getCurrentlySelectedBotName());
	}
	
	/**
	 * Signal handler for events which occur when user presses mouse button
	 * down on top of bot list view.
	 * Returns true if the event was handled by this handler, false if not.
	 */
	public function onBotListViewMousePressed($widget, $event) {
		if ($event->type == Gdk::BUTTON_PRESS && $event->button == 3) {

			// select the the item which currently is under mouse cursor
			$selection = $this->botListView->get_selection();
			$selection->unselect_all();
			$pathArray = $this->botListView->get_path_at_pos($event->x, $event->y);
			if ($pathArray) {
				$path = $pathArray[0];
				$selection->select_path($path);
			}
			// popup the context menu
			$this->botListContextMenu->popup(null);
			return true;
		}
		return false;
	}
	
	/**
	 * This signal handler is called when user clicks Exit-button.
	 */
	public function onExitClicked() {
		$this->emit('exit_requested');
	}
	
	/**
	 * This signal handler is called when user clicks a menu item in
	 * bot list's context menu.
	 * Emits context_item_clicked signal.
	 */
	public function onContextMenuItemClicked($object, $action) {
		$this->emit('action_triggered', $action, $this->getCurrentlySelectedBotName());
	}

	private function getCurrentlySelectedBotName() {
		list($model, $iter) = $this->botListView->get_selection()->get_selected();
		$name = $model->get_value($iter, 1);
		return $name;
	}
}

