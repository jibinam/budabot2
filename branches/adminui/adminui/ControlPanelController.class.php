<?php

class ControlPanelController {

	private $builder;
	private $view;
	
	public function __construct() {
		// load controlpanel.glade file
		$this->builder = new GtkBuilder();
		$this->builder->add_from_file(dirname(__FILE__) . '/ControlPanel.glade');
		
		$this->view = $this->builder->get_object('controlPanelWindow');
		$this->botListView = $this->builder->get_object('botListView');
		$this->botListContextMenu = $this->builder->get_object('botListContextMenu');
		
		$this->botListView->connect('button-press-event', array($this, 'onBotListViewMousePressed'));
	}

	public function show() {
		$this->view->show_all();
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

