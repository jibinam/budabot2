#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gtk
from utils import weakConnect
import weakref

class ConfigWindowController(object):
	"""Controller class for config file editor window."""
	
	RESPONSE_CANCEL = 0
	RESPONSE_SAVE = 1
	
	def __init__(self, bot, configFile, parent):
		"""Constructor method.
		
		bot        - bot object which is being edited
		configFile - bot's config file which is being edited
		parent     - a top level window, the config window will positioned on top this
		"""
		self.configFile = configFile
		self.builder = gtk.Builder()
		self.builder.add_from_file('configwindow.glade')
		self.dialog = self.builder.get_object('configDialog')
		self.botRef = weakref.ref(bot)
		self.parentRef = weakref.ref(parent)
		# append bot name to dialog's title
		self.dialog.set_title(self.dialog.get_title() % bot.getName())
		weakConnect(self.dialog, 'response', self.onConfigDialogResponse)
		weakConnect(self.dialog, 'response', self.onConfigDialogResponse)
		weakConnect(self.builder.get_object('dbTypeCombobox'), 'changed', self.onDbTypeChanged)
		weakConnect(self.builder.get_object('useProxyCheckbox'), 'toggled', self.onUseProxyToggled)
		# add buttons, must be added in Gnome's preferred order, see:
		# http://developer.gnome.org/hig-book/3.4/windows-alert.html.en#alert-button-order
		self.dialog.add_button(gtk.STOCK_CANCEL, self.RESPONSE_CANCEL)
		saveButton = self.dialog.add_button(gtk.STOCK_SAVE, self.RESPONSE_SAVE)
		saveButton.grab_default()
		# add alternative order for Windows, see:
		# http://msdn.microsoft.com/en-us/library/windows/desktop/aa511268.aspx#commitButtons
		self.dialog.set_alternative_button_order([self.RESPONSE_SAVE, self.RESPONSE_CANCEL])

	def show(self):
		"""Shows the config window to user."""
		self.loadConfigFile()
		self.dialog.set_transient_for(self.parentRef())
		self.dialog.set_property('window-position', gtk.WIN_POS_CENTER_ON_PARENT)
		self.dialog.show_all()
	
	def loadConfigFile(self):
		"""Loads variables from the config file and populates the
		window's input widgets.
		"""
		def loadEntry(configName, entryName):
			"""Helper function for setting value from config file to a
			gtk entry widget in the window.
			"""
			value = self.configFile.getVar(configName)
			self.builder.get_object(entryName).set_text(str(value))

		def loadCombo(configName, comboboxName):
			"""Helper function for setting value from config file to a
			gtk combobox widget in the window.
			"""
			value = self.configFile.getVar(configName)
			model = self.builder.get_object(comboboxName).get_model()
			for index in range(0, len(model)):
				if model[index][0] == value:
					self.builder.get_object(comboboxName).set_active(index)
					break
		
		def loadCheck(configName, checkboxName):
			"""Helper function for setting value from config file to a
			gtk checkbutton widget in the window.
			"""
			value = self.configFile.getVar(configName)
			self.builder.get_object(checkboxName).set_active(value)

		self.configFile.load()
		loadEntry('login',                 'loginNameEntry')
		loadEntry('password',              'loginPasswordEntry')
		loadEntry('name',                  'botNameEntry')
		loadCombo('dimension',             'botDimensionCombobox')
		loadEntry('my_guild',              'botOrganizationEntry')
		loadEntry('SuperAdmin',            'superAdminEntry')
		loadCombo('DB Type',               'dbTypeCombobox')
		loadEntry('DB Name',               'dbNameEntry')
		loadEntry('DB Host',               'dbHostEntry')
		loadEntry('DB username',           'dbUsernameEntry')
		loadEntry('DB password',           'dbPasswordEntry')
		loadCheck('use_proxy',             'useProxyCheckbox')
		loadEntry('proxy_server',          'proxyServerEntry')
		loadEntry('proxy_port',            'proxyPortEntry')
		loadCheck('default_module_status', 'moduleStatusCheckbox')
		loadCheck('show_aoml_markup',      'showAomlCheckbox')
		loadEntry('cachefolder',           'cacheFolderEntry')
		loadEntry('API Port',              'apiPortLabel')
	
	def onDbTypeChanged(self, comboBox):
		"""This signal handler is called when database type's value changes.
		
		Enables database username and password entries if the new type
		is 'mysql', and disables if 'sqlite'.
		"""
		index = comboBox.get_active()
		enableCredentials = comboBox.get_model()[index][0] == 'mysql'
		self.builder.get_object('dbUsernameEntry').set_sensitive(enableCredentials)
		self.builder.get_object('dbPasswordEntry').set_sensitive(enableCredentials)

	def onUseProxyToggled(self, checkbox):
		"""This signal handler is called when proxy checkbox's state changes.
		
		Enables proxy's server and port entries when the checkbox is ticked
		and disables when it is unticked.
		"""
		proxyEnabled = checkbox.get_active()
		self.builder.get_object('proxyServerEntry').set_sensitive(proxyEnabled)
		self.builder.get_object('proxyPortEntry').set_sensitive(proxyEnabled)
		
	def onConfigDialogResponse(self, caller, responseId):
		"""This signal handler is called when user clicks either
		Save or Cancel button.
		
		In both cases the window is closed, but if Save is clicked the
		config file is also saved.
		"""
		if responseId == self.RESPONSE_CANCEL:
			self.dialog.destroy()
		elif responseId == self.RESPONSE_SAVE:
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
		"""This signal handler is called when user closes the restart question
		dialog.
		
		If user clicked Yes-button then the bot is restarted.
		"""
		if responseId == gtk.RESPONSE_YES:
			if self.botRef().get_property('apiAccessible'):
				self.botRef().restart()
			else:
				self.botRef().terminate()
				self.botRef().start()
		self.restartDialog.destroy()
