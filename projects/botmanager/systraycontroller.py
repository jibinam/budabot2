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

	def __init__(self):
		"""Constructor method."""
		self.__gobject_init__()

	def onSystrayClicked(self, sender):
		"""This callback handler is called when user clicks the systray icon."""
		pass

	def onOpenClicked(self, sender):
		"""This callback handler is called when user attempts to open the control panel."""
		pass

	def onExitClicked(self, sender):
		"""This callback handler is called when user attempts to exit the application."""
		pass

	def startCloseTimout(self):
		"""This method starts the timeout which closes the context menu automatically."""
		pass

	def stopCloseTimout(self):
		"""This method stops the timeout which closes the context menu automatically."""
		pass

	def closeContextMenu(self):
		"""This method closes the context menu."""
		pass

	def onMenu(self):
		"""This callback handler is called when popup menu should be shown."""
		pass

	def onControlPanelVisibilityChanged(self, sender, visibility):
		"""This callback handler is called control panel is either shown or hidden."""
		pass

# register class so that custom signals will work
gobject.type_register(SystrayController)