#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gtk
from utils import weakConnect
import weakref

class ConfigWindowController(object):
	
	def __init__(self, bot, configFile, parent):
		self.configFile = configFile
		self.builder = gtk.Builder()
		self.builder.add_from_file('configwindow.glade')
		self.dialog = self.builder.get_object('configDialog')
		self.botRef = weakref.ref(bot)
		self.parentRef = weakref.ref(parent)
		# append bot name to dialog's title
		self.dialog.set_title(self.dialog.get_title() % bot.getName())
		weakConnect(self.dialog, 'response', self.onConfigDialogResponse)

	def show(self):
		self.configFile.load()
		self.dialog.set_transient_for(self.parentRef())
		self.dialog.set_property('window-position', gtk.WIN_POS_CENTER_ON_PARENT)
		self.dialog.show_all()
		
	def onConfigDialogResponse(self, caller, responseId):
		if responseId == 0: # cancel
			self.dialog.destroy()
		elif responseId == 1: # save
			self.configFile.save()
			self.dialog.destroy()
			if self.botRef().get_property('isRunning'):
				self.restartDialog = gtk.MessageDialog(
					parent = self.parentRef(),
					flags = gtk.DIALOG_MODAL,
					type = gtk.MESSAGE_QUESTION,
					buttons = gtk.BUTTONS_YES_NO,
					message_format = "The bot is currently running. These changes will not affect it before it is restarted. Would you like to restart it now?"
				)
				weakConnect(self.restartDialog, 'response', self.onRestartDialogResponse)
				self.restartDialog.show_all()

	def onRestartDialogResponse(self, caller, responseId):
		if responseId == gtk.RESPONSE_YES:
			if self.botRef().get_property('apiAccessible'):
				self.botRef().restart()
			else:
				self.botRef().terminate()
				self.botRef().start()
		self.restartDialog.destroy()
