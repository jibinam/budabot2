#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gobject
import gtk
from addbotwizard import AddBotWizardController

class ControlPanelController(gobject.GObject):
	""""""
	
	# Define custom signals that this class can emit.
	__gsignals__ = {
		# This signal is emitted when user clicks item in bot's context menu.
		# First parameter is name of action and second is name of bot.
		'action_triggered': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, (gobject.TYPE_STRING, gobject.TYPE_STRING)),
		# this signal is emitted when user attempts to exit the application
		'exit_requested': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, ()),
		# this signal is emitted when control panel is either shown or hidden
		# first parameter is contains state of visibility, true = shown, false = hidden
		'visibility_changed': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, (gobject.TYPE_BOOLEAN,)),
	}
	
	def __init__(self, botModel, settingModel):
		"""Constructor method."""
		self.__gobject_init__()
		self.botModel = botModel
		self.settingModel = settingModel
		self.position = (200, 200)
		self.addBotWizardController = None
		# load controlpanel.glade file
		self.builder = gtk.Builder()
		self.builder.add_from_file('controlpanel.glade')
		
		self.view = self.builder.get_object('controlPanelWindow')
		self.botListView = self.builder.get_object('botListView')
		self.botListContextMenu = self.builder.get_object('botListContextMenu')
		self.contextItemOpen = self.builder.get_object('contextItemOpen')
		self.contextItemModify = self.builder.get_object('contextItemModify')
		self.contextItemRemove = self.builder.get_object('contextItemRemove')
		self.contextItemStart = self.builder.get_object('contextItemStart')
		self.contextItemRestart = self.builder.get_object('contextItemRestart')
		self.contextItemShutdown = self.builder.get_object('contextItemShutdown')
		self.contextItemTerminate = self.builder.get_object('contextItemTerminate')
		
		self.botListView.set_model(self.botModel)
		
		# add cell renderer
		renderer = gtk.CellRendererText()
		renderer.set_property('height', 50)
		column = gtk.TreeViewColumn('Bot', renderer, text = 1)
		self.botListView.append_column(column)
		
		# set default action as bold
		# TODO: to helper function
		label = self.contextItemOpen.get_children()
		label = label[0]
		label.set_markup('<b>' + label.get_text() + '</b>')

		self.view.connect('delete-event', self.onDeleteEvent)
		self.view.connect('show', self.onViewShown)
		self.view.connect('hide', self.onViewHidden)
		
		self.botListView.connect('button-press-event', self.onBotListViewMousePressed)
		
		self.botListView.connect('row-activated', self.onBotListViewRowActivated)
		
		self.contextItemOpen.connect('activate', self.onContextMenuItemClicked, 'open')
		self.contextItemModify.connect('activate', self.onContextMenuItemClicked, 'modify')
		self.contextItemRemove.connect('activate', self.onContextMenuItemClicked, 'remove')
		self.contextItemStart.connect('activate', self.onContextMenuItemClicked, 'start')
		self.contextItemRestart.connect('activate', self.onContextMenuItemClicked, 'restart')
		self.contextItemShutdown.connect('activate', self.onContextMenuItemClicked, 'shutdown')
		self.contextItemTerminate.connect('activate', self.onContextMenuItemClicked, 'terminate')
		
		self.builder.get_object('addBotButton').connect('clicked', self.onAddBotClicked)
		self.builder.get_object('exitButton').connect('clicked', self.onExitClicked)

	def show(self):
		"""This method shows the dialog to user."""
		self.view.move(self.position[0], self.position[1])
		self.view.show_all()

	def hide(self):
		"""This method hides the dialog from user."""
		self.position = self.view.get_position()
		self.view.hide()

	def toggle(self):
		"""This method either shows or hides the dialog."""
		if self.view.get_property('visible'):
			self.hide()
		else:
			self.show()

	def onDeleteEvent(self, sender, event):
		"""This method catches delete event and instead of simply deleting the
		dialog, it is hidden instead. Doing this it is possible to re-show the
		dialog next time.
		"""
		self.hide()
		return True

	def onBotListViewRowActivated(self, sender, path, column):
		"""This signal handler is called when user double clicks a row in the bot list view."""
		self.emit('action_triggered', 'open', self.getCurrentlySelectedBot().getName())

	def onBotListViewMousePressed(self, sender, event):
		"""Signal handler for events which occur when user presses mouse button
		down on top of bot list view.
		Returns true if the event was handled by this handler, false if not.
		"""
		if event.type == gtk.gdk.BUTTON_PRESS and event.button == 3:
			# select the the item which currently is under mouse cursor
			selection = self.botListView.get_selection()
			selection.unselect_all()
			pathArray = self.botListView.get_path_at_pos(int(event.x), int(event.y))
			if pathArray:
				path = pathArray[0]
				selection.select_path(path)
			# popup the context menu
			bot = self.getCurrentlySelectedBot()
			apiAccessible = bot.get_property('apiAccessible')
			isRunning = bot.get_property('isRunning')
			self.contextItemRestart.set_sensitive(isRunning and apiAccessible)
			self.contextItemShutdown.set_sensitive(isRunning and apiAccessible)
			self.contextItemStart.set_sensitive(not isRunning)
			self.contextItemTerminate.set_sensitive(isRunning)
			self.botListContextMenu.popup(None, None, None, event.button, event.get_time())
			return True
		return False

	def onAddBotClicked(self, sender):
		""""""
		if not self.addBotWizardController:
			self.addBotWizardController = AddBotWizardController(self.botModel, self.settingModel)
		self.addBotWizardController.show()

	def onExitClicked(self, sender):
		"""This signal handler is called when user clicks Exit-button."""
		self.emit('exit_requested')

	def onContextMenuItemClicked(self, sender, action):
		"""This signal handler is called when user clicks a menu item in
		bot list's context menu.
		Emits context_item_clicked signal.
		"""
		self.emit('action_triggered', action, self.getCurrentlySelectedBot().getName())

	def onViewShown(self, sender):
		"""This signal handler is called when the control panel window is shown."""
		self.emit('visibility_changed', True)

	def onViewHidden(self, sender):
		"""This signal handler is called when the control panel window is hidden."""
		self.emit('visibility_changed', False)

	def getCurrentlySelectedBot(self):
		""""""
		selected = self.botListView.get_selection().get_selected()
		bot = self.botModel.get_value(selected[1], 0)
		return bot


# register class so that custom signals will work
gobject.type_register(ControlPanelController)
