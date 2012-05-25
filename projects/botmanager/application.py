#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gtk
from settingmodel import SettingModel
from botmodel import BotModel
from systraycontroller import SystrayController
from botwindowcontroller import BotWindowController
from controlpanelcontroller import ControlPanelController
from bot import Bot

class Application:
	"""The main application class"""

	botWindowControllers = {}

	def execute(self):
		""""""

		# intialize thread support
		gtk.gdk.threads_init()

		# load Cillop-Midnite theme
		gtk.rc_add_default_file("themes/Cillop-Midnite/gtk-2.0/gtkrc")
		settings = gtk.settings_get_default()
		settings.set_string_property("gtk-theme-name", "Cillop-Midnite", "")

		settingModel = SettingModel()
		self.botModel = BotModel(settingModel)
		systrayController = SystrayController()
		botWindowController = BotWindowController()

		controlPanelController = ControlPanelController(self.botModel)
		controlPanelController.connect('action_triggered', self.onControlPanelAction)

		# open control panel when user select 'open' from systray's context menu
		systrayController.connect_object('open_requested', ControlPanelController.show, controlPanelController)
		# opens/closes control panel when user clicks systray icon
		systrayController.connect_object('toggle_requested', ControlPanelController.toggle, controlPanelController)

		# notify systray controller of control panel's visibility
		controlPanelController.connect('visibility_changed', systrayController.onControlPanelVisibilityChanged)

		# connect exit requests to quit()-method
		controlPanelController.connect_object('exit_requested', Application.quit, self)
		systrayController.connect_object('exit_requested', Application.quit, self)

		# show errors to user
		settingModel.connect('error', self.onError)

		settingModel.load()
		self.botModel.load()

		controlPanelController.show()

		gtk.main()
		
		systrayController.hideIcon()

	def quit(self):
		"""Calling this method will stop the event loop and execution returns
		from execute().
		"""
		dialog = gtk.MessageDialog(None, gtk.DIALOG_MODAL, gtk.MESSAGE_QUESTION, gtk.BUTTONS_OK_CANCEL, 'Exiting')
		dialog.set_markup("Exiting from the Bot Manager will terminate any running bots, are you sure?")
		if dialog.run() == gtk.RESPONSE_OK:
			# terminate all running bots
			for bot in self.botModel.getAllBots():
				bot.terminate()
			# hop out of event loop
			gtk.main_quit()
		dialog.destroy()

	def onControlPanelAction(self, sender, action, botName):
		"""This signal handler is called when user actives some action
		in control panel.
		"""
		bot = self.botModel.getBotByName(botName)
		if action == 'open':
			botController = self.botWindowController(botName)
			botController.show()
		elif action == 'start':
			bot.start()
		elif action == 'restart':
			bot.restart()
		elif action == 'shutdown':
			bot.shutdown()
		elif action == 'terminate':
			bot.terminate()
		else:
			self.showErrorMessage("This action is not implemented!")

	def onError(self, sender, message):
		"""This signal handler is called when an error occurs within the application"""
		self.showErrorMessage(message)

	def showErrorMessage(self, message):
		"""Shows error dialog to user."""
		dialog = gtk.MessageDialog(None, gtk.DIALOG_MODAL, gtk.MESSAGE_ERROR, gtk.BUTTONS_OK, 'Error')
		dialog.set_markup(message)
		dialog.run()
		dialog.destroy()

	def botWindowController(self, botName):
		"""Creates bot window controller, if it doesn't exist yet, of
		name botName and returns it.
		"""
		if botName not in self.botWindowControllers:
			bot = self.botModel.getBotByName(botName)
			self.botWindowControllers[botName] = BotWindowController();
			self.botWindowControllers[botName].setConsoleModel(bot.getConsoleModel())
			self.botWindowControllers[botName].connect_object('command_given', Bot.sendCommand, bot)
		return self.botWindowControllers[botName]
