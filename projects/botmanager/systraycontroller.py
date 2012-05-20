#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gobject

class SystrayController(gobject.GObject):
	""""""

	# Define custom signals that this class can emit.
	__gsignals__ = {
		# this signal is emitted when user attempts to open the control panel
		'open_requested': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, ()),
		# this signal is emitted when user attempts to change control panel's visibility
		'toggle_requested': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, ()),
		# this signal is emitted when user attempts to exit the application
		'exit_requested': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, ()),
	}

	def __init__(self, botModel):
		"""Constructor method."""
		self.super(SystrayController, self).__init__()

	def show(self):
		"""This method shows the dialog to user."""
		pass

	def hide(self):
		"""This method hides the dialog from user."""
		pass

	def toggle(self):
		"""This method either shows or hides the dialog."""
		pass

	def onDeleteEvent(self):
		"""This method catches delete event and instead of simply deleting the
		dialog, it is hidden instead. Doing this it is possible to re-show the
		dialog next time.
		"""
		pass

	def onBotListViewRowActivated(self):
		"""This signal handler is called when user double clicks a row in the bot list view."""
		pass

	def onBotListViewMousePressed(self):
		"""Signal handler for events which occur when user presses mouse button
		down on top of bot list view.
		Returns true if the event was handled by this handler, false if not.
		"""
		pass

	def onExitClicked(self):
		"""This signal handler is called when user clicks Exit-button."""
		pass

	def onContextMenuItemClicked(self):
		"""This signal handler is called when user clicks a menu item in
		bot list's context menu.
		Emits context_item_clicked signal.
		"""
		pass

	def onViewShown(self):
		"""This signal handler is called when the control panel window is shown."""
		pass

	def onViewHidden(self):
		"""This signal handler is called when the control panel window is hidden."""
		pass

	def getCurrentlySelectedBotName(self):
		""""""
		pass

# register class so that custom signals will work
gobject.type_register(SystrayController)