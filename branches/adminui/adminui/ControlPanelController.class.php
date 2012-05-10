<?php

class ControlPanelController {

	private $builder;
	private $view;
	private $position;
	
	/**
	 * Constructor method.
	 */
	public function __construct() {
		$this->position = array(200, 200);
		// load controlpanel.glade file
		$this->builder = new GtkBuilder();
		$this->builder->add_from_file(dirname(__FILE__) . '/ControlPanel.glade');
		
		$this->view = $this->builder->get_object('controlPanelWindow');
		$this->botListView = $this->builder->get_object('botListView');
		$this->botListContextMenu = $this->builder->get_object('botListContextMenu');
		
		$this->view->connect('delete-event', array($this, 'onDeleteEvent'));
		$this->botListView->connect('button-press-event', array($this, 'onBotListViewMousePressed'));
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
	 * Event handler for events which occur when user presses mouse button
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
}

