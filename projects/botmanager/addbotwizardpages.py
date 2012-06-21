#! /usr/bin/env python
# -*- coding: utf-8 -*-

"""This module contains all page classes used in wizard for adding and
importing bots.

This module also provides following constants which are used to identify
each page:

  SELECT_ACTION_PAGE_ID - This is the first page where user can select if he is
                          going add a new bot or import and existing bot.
  SELECT_IMPORT_PAGE_ID - This is first page in the import functionality where
                          user can browse for location of the bot.
  NAME_BOT_PAGE_ID      - In this page user can give the bot a name.
  FINISH_PAGE_ID        - This is the final page which shows summary of the
                          bot settings.
"""

import os
import gtk
import gobject
from botconfigfile import BotPhpConfigFile

SELECT_ACTION_PAGE_ID        = 1
SELECT_IMPORT_PAGE_ID        = 2
SELECT_BOT_DIRECTORY_PAGE_ID = 3
NAME_BOT_PAGE_ID             = 4
FINISH_PAGE_ID               = 5

class Page(gobject.GObject):
	"""A common base class for each page class.
	
	In order to use this class you need to either create an object of this or
	one the derived classes. You need to provide ID, type, title string, and
	a widget which is shown inside the Assistant.
	
	In addition, the page object must be added to the Assistant with
	appendPage() before it can be used.
	"""

	# custom properties
	__gproperties__ = {
		'complete' : (gobject.TYPE_BOOLEAN, 'complete', 'is page complete', False, gobject.PARAM_READWRITE),
	}

	def __init__(self, id):
		"""Constructor method.
		
		The given id value is used to identify the page object by the
		Assistant to which it is appended.
		"""
		self.__gobject_init__()
		self.id = id
		self.index = -1
		self.widget = None
		self.type = gtk.ASSISTANT_PAGE_CONTENT
		self.title = ''
		self.completenessFunc = lambda: True
		self.completenessFuncArgs = []
		self.nextPageIdFunc = lambda: None
		self.nextPageIdFuncArgs = []

	def do_get_property(self, property):
		"""Returns value of given property.
		This is required to make GTK's properties to work.
		"""
		if property.name == 'complete':
			return self.completenessFunc(*self.completenessFuncArgs)

	def prepare(self):
		pass

	def getWidget(self):
		"""Returns widget object which represents the visible contents
		of the page.
		
		This method is used by the Assistant.
		"""
		return self.widget

	def getNextPageId(self):
		"""Returns ID value of the next page to where the wizard should change
		when user clicks 'Forward' button.
		
		This method is used by the Assistant.
		
		Internally this method calls function object set with
		setNextPageIdFunc() and returns what it returns.
		"""
		return self.nextPageIdFunc(*self.nextPageIdFuncArgs)

	def getTitle(self):
		"""Returns title string of this page which should be shown in the Assistant.
		
		This method is used by the Assistant when it needs to draw the
		title to screen.
		"""
		return self.title

	def getType(self):
		"""This method returns the GTK type of this page.
		
		This method is used by the Assistant.
		"""
		return self.type

	def setType(self, type):
		"""This method sets the GTK type of the page.
		
		By default the type is gtk.ASSISTANT_PAGE_CONTENT, if some other type
		is required then is method should be called to set it correctly.
		
		List of other possible constants can be found from here:
		  http://www.pygtk.org/docs/pygtk/gtk-constants.html#gtk-assistant-page-type-constants
		"""
		self.type = type

	def setTitle(self, title):
		"""This method sets given string as page's title.
		
		By default the title string is an empty string and any sub classes
		should set the title explcitly by calling this method.
		"""
		self.title = title

	def setNextPageIdFunc(self, function, *args):
		"""Sets function which should return id of the next page."""
		self.nextPageIdFunc = function
		self.nextPageIdFuncArgs = args

	def setCompletenessFunc(self, function, *args):
		"""Sets function which should return boolean value which indicates if
		the page is complete or not.
		"""
		self.completenessFunc = function
		self.completenessFuncArgs = args

	def updateCompleteness(self, *args):
		"""Notifies any connected listeners that the page complete-status might
		have changed and should be updated.
		"""
		self.notify('complete')

# register class so that custom signals will work
gobject.type_register(Page)

class SelectActionPage(Page):
	"""This page class lets user select should he add a new bot or import
	a existing bot.
	"""

	def __init__(self, builder):
		"""Constructor method."""
		super(SelectActionPage, self).__init__(SELECT_ACTION_PAGE_ID)
		self.setType(gtk.ASSISTANT_PAGE_INTRO)
		self.setTitle('Add or import bot')
		self.setNextPageIdFunc(self.nextPageId)
		self.widget = builder.get_object('selectActionPage')
		self.addBotRadioButton = builder.get_object('addBotRadioButton')
		self.importBotRadioButton = builder.get_object('importBotRadioButton')
		self.importBotRadioButton.set_group(self.addBotRadioButton)

	def nextPageId(self):
		"""Returns ID of the next page to where wizard should change."""
		if self.addBotRadioButton.get_property('active'):
			return SELECT_BOT_DIRECTORY_PAGE_ID
		elif self.importBotRadioButton.get_property('active'):
			return SELECT_IMPORT_PAGE_ID
		return None

class SelectImportPage(Page):
	"""This page class lets users browse for location of the Budabot
	installation and config file which should be imported to Bot Manager.
	"""
	
	def __init__(self, builder, settingModel):
		"""Constructor method."""
		super(SelectImportPage, self).__init__(SELECT_IMPORT_PAGE_ID)
		self.setTitle('Import existing bot')
		self.setNextPageIdFunc(lambda: NAME_BOT_PAGE_ID)
		self.setCompletenessFunc(lambda self: self.getSelectedBotConfFilePath() != None, self)
		self.widget = builder.get_object('selectImportPage')
		self.settingModel = settingModel
		self.modelPath = ''
		self.dirChooser = builder.get_object('botImportDirChooser')
		self.dirChooser.connect('current-folder-changed', self.onBotImportDirChoosen)
		self.dirChooser.set_current_folder(self.settingModel.getDefaultBotRootPath())
		self.botImportModel = BotImportModel()
		self.botView = builder.get_object('importBotListView')
		self.botView.set_model(self.botImportModel)
		self.botView.get_selection().connect('changed', self.updateCompleteness)

	def getSelectedBotRootPath(self):
		"""Returns path to the bot software's root folder."""
		return self.dirChooser.get_filename()

	def getSelectedBotConfFilePath(self):
		"""Returns path to the currently selected configuration file."""
		selected = self.botView.get_selection().get_selected()
		if selected[0] != None and selected[1] != None:
			filename = selected[0].get(selected[1], 0)[0]
			return os.path.join(self.modelPath, filename)
		return None

	def onBotImportDirChoosen(self, caller):
		"""This signal handler is called when user chooses a directory in import wizard."""
		self.modelPath = os.path.join(self.dirChooser.get_filename(), 'conf')
		if os.path.isdir(self.modelPath):
			self.botImportModel.load(self.modelPath)
			# save current path to settings for later use if bots were found
			if len(self.botImportModel) > 0:
				self.settingModel.setDefaultBotRootPath(self.dirChooser.get_filename())
				self.settingModel.save()
		else:
			self.botImportModel.clear()

class BotImportModel(gtk.ListStore):
	def __init__(self):
		"""Constructor method."""
		super(BotImportModel, self).__init__(gobject.TYPE_STRING, gobject.TYPE_STRING, gobject.TYPE_STRING)

	def load(self, path):
		"""Loads all config files from given path and adds them to the model."""
		self.clear()
		for fileName in os.listdir(path):
			# ignore the template file
			if fileName == 'config.template.php':
				continue
			try:
				# try to load the file as a config file
				filePath = os.path.join(path, fileName)
				configFile = BotPhpConfigFile(filePath)
				configFile.load()
				name = configFile.getVar('name')
				dimension = 'RK %s' % configFile.getVar('dimension')
			except (KeyError, IOError):
				# ignore files which are not valid config files
				continue
			# add to the config file to model
			self.append((fileName, name, dimension))

class SelectBotInstallDirectoryPage(Page):
	"""This page class lets users browse for location of the Budabot installation."""
	
	def __init__(self, builder, settingModel):
		"""Constructor method."""
		super(SelectBotInstallDirectoryPage, self).__init__(SELECT_BOT_DIRECTORY_PAGE_ID)
		self.pathIsValid = False
		self.setTitle('Select Budabot\'s Directory')
		self.setNextPageIdFunc(lambda: None)
		self.setCompletenessFunc(lambda self: self.pathIsValid, self)
		self.widget = builder.get_object('selectBotInstallDirectoryPage')
		self.settingModel = settingModel
		self.botPath = ''
		self.dirChooser = builder.get_object('botRootDirChooser')
		self.dirChooser.connect('current-folder-changed', self.onDirChoosen)
		self.dirChooser.set_current_folder(self.settingModel.getDefaultBotRootPath())

	def getSelectedBotRootPath(self):
		"""Returns path to the bot software's root directory."""
		return self.botPath

	def onDirChoosen(self, caller):
		"""This signal handler is called when user chooses a directory where
		the bot software has been installed.
		"""
		self.botPath = self.dirChooser.get_filename()
		# check that main.php exists in the directory before accepting the path
		if os.path.exists(os.path.join(self.botPath, 'main.php')):
			self.pathIsValid = True
			self.settingModel.setDefaultBotRootPath(self.botPath)
			self.settingModel.save()
		else:
			self.pathIsValid = False
		self.updateCompleteness()

class NameBotPage(Page):
	"""This page class lets user to give a name for the bot."""

	def __init__(self, builder):
		"""Constructor method."""
		super(NameBotPage, self).__init__(NAME_BOT_PAGE_ID)
		self.setTitle('Bot Name')
		self.setNextPageIdFunc(lambda: FINISH_PAGE_ID)
		# page is complete if given bot name is not empty
		self.setCompletenessFunc(lambda self: len(self.botNameEntry.get_text()) > 0, self)
		self.widget = builder.get_object('nameBotPage')
		self.botNameEntry = builder.get_object('botNameEntry')
		self.botNameEntry.connect('notify::text', self.updateCompleteness)

	def getBotName(self):
		return self.botNameEntry.get_text()

	def setBotName(self, name):
		self.botNameEntry.set_text(name)

class FinishPage(Page):
	"""The final page in the wizard. Lists a summary of the bot settings
	before it is added to the Bot Manager.
	"""

	def __init__(self, builder):
		"""Constructor method."""
		super(FinishPage, self).__init__(FINISH_PAGE_ID)
		self.setType(gtk.ASSISTANT_PAGE_CONFIRM)
		self.setTitle('Summary')
		self.widget = builder.get_object('finishPage')
		self.summaryLabel = builder.get_object('summaryLabel')

	def setValues(self, values):
		"""Sets a list of key-value tuples to be shown in the page."""
		contents = ''
		for key, value in values:
			if key == 'login' or key == 'password' or key == 'DB username' or key == 'DB password':
				value = len(value) * '**'
			contents += '<b>' + key + ':</b> ' + str(value) + '\n'
		self.summaryLabel.set_markup(contents)
