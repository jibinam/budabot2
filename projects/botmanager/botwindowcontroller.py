#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gobject

class BotWindowController(gobject.GObject):
	""""""
	
	# Define custom signals that this class can emit.
	__gsignals__ = {
		# notifies that user has sent a command, first parameter is channel, second is the command
		'command_given': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, (gobject.TYPE_LONG, gobject.TYPE_STRING)),
	}
	
	def __init__(self):
		"""Constructor method."""
		self.__gobject_init__()
	
	def setConsoleModel(self, model):
		""""""
		pass
		
	def show(self):
		""""""
		pass
	
	def onDeleteEvent(self, sender):
		"""This method catches delete event and instead of simply deleting the
		dialog, it is hidden instead. Doing this it is possible to re-show the
		dialog next time.
		"""
		pass
		
	def scrollViewToBottom(self, adjustment):
		"""This callback is called when output view's vertical
		scrollbar (adjustment) changes.
		Scrolls the scrollbar to bottom of the scrollable area.
		"""
		pass

	def onCommandGiven(self, sender):
		"""This callback function is called when user hits enter-key in the command
		input entry.
		Sends the entry's text as a command to the bot and clears the entry field.
		"""
		pass

# register class so that custom signals will work
gobject.type_register(BotWindowController)
