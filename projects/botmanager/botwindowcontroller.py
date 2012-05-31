#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gobject
import gtk

class BotWindowController(gobject.GObject):
	""""""
	
	# Define custom signals that this class can emit.
	__gsignals__ = {
	}
	
	def __init__(self, bot):
		"""Constructor method."""
		self.__gobject_init__()
		self.bot = bot
		
		# load botwindow.glade file
		self.builder = gtk.Builder()
		self.builder.add_from_file('botwindow.glade')

		# get some widgets and objects for easier access
		self.botWindow  = self.builder.get_object('botwindow')
		outputScrollArea = self.builder.get_object('outputScrollArea')
		self.outputView = self.builder.get_object('outputView')
		self.commandEntry = self.builder.get_object('commandInputEntry')
		self.destinationSelector = self.builder.get_object('destinationSelector')

		# call scrollViewToBottom() when scroll area's vertical scrollbar changes
		outputScrollArea.get_vadjustment().connect('changed', self.scrollViewToBottom)

		# call onCommandGiven() when user hits enter-key within the entry
		self.commandEntry.connect('activate', self.onCommandGiven)
		
		# prevent deletion of the window on close
		self.botWindow.connect('delete-event', self.onDeleteEvent)
		
		# be notified when bot's API becomes (in)accessible
		self.bot.connect('notify::apiAccessible', self.onApiAccessibilityChanged)

		self.outputView.set_buffer(self.bot.getConsoleModel())
		self.setApiRequiringActionsEnabled(self.bot.get_property('apiAccessible'))

	def setConsoleModel(self, model):
		"""Sets console window's buffer to given model."""
		if model:
			self.outputView.set_buffer(model)

	def show(self):
		"""Shows the window to user."""
		self.botWindow.show_all()

	def onDeleteEvent(self, sender, event):
		"""This method catches delete event and instead of simply deleting the
		dialog, it is hidden instead. Doing this it is possible to re-show the
		dialog next time.
		"""
		self.botWindow.hide()
		return True

	def scrollViewToBottom(self, adjustment):
		"""This callback is called when output view's vertical
		scrollbar (adjustment) changes.
		Scrolls the scrollbar to bottom of the scrollable area.
		"""
		adjustment.set_value(adjustment.upper - adjustment.page_size)

	def onCommandGiven(self, sender):
		"""This callback function is called when user hits enter-key in the command
		input entry.
		Sends the entry's text as a command to the bot and clears the entry field.
		"""
		# get command and clear the input entry
		command = self.commandEntry.get_text()
		self.commandEntry.set_text('')
		# get output channel
		channel = self.destinationSelector.get_model().get_value(self.destinationSelector.get_active_iter(), 1)
		# send the command
		self.bot.sendCommand(channel, command)

	def onApiAccessibilityChanged(self, caller, property):
		self.setApiRequiringActionsEnabled(self.bot.get_property('apiAccessible'))

	def setApiRequiringActionsEnabled(self, enabled):
		self.commandEntry.set_sensitive(enabled)
		self.destinationSelector.set_sensitive(enabled)

# register class so that custom signals will work
gobject.type_register(BotWindowController)
