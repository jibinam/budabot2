#! /usr/bin/env python
# -*- coding: utf-8 -*-

import os
import gtk
import gobject
from botconfigfile import BotPhpConfigFile

SELECT_ACTION_PAGE_ID = 1
SELECT_IMPORT_PAGE_ID = 2
NAME_BOT_PAGE_ID      = 3
FINISH_PAGE_ID        = 4


class AddBotWizardController:
	""""""
	
	def __init__(self, botModel, settingModel):
		self.settingModel = settingModel

		# load addbotwizard.glade file
		self.builder = gtk.Builder()
		self.builder.add_from_file('addbotwizard.glade')

		self.assistant = Assistant()
		self.assistant.connect('apply', self.onApplyClicked)
		self.assistant.connect('cancel', self.onCancelClicked)
		self.assistant.connect('close', self.onCloseClicked)
		self.assistant.connect('prepare', self.onPreparePage)

		self.selectActionPage = SelectActionPage(SELECT_ACTION_PAGE_ID, self.builder)
		self.selectImportPage = SelectImportPage(SELECT_IMPORT_PAGE_ID, self.builder, settingModel)
		self.botNamePage      = NameBotPage(NAME_BOT_PAGE_ID, self.builder)
		self.finishPage       = FinishPage(FINISH_PAGE_ID, self.builder)
		self.assistant.appendPage(self.selectActionPage)
		self.assistant.appendPage(self.selectImportPage)
		self.assistant.appendPage(self.botNamePage)
		self.assistant.appendPage(self.finishPage)

		self.selectImportPage.connect('notify::complete', self.onSelectImportPageComplete)

	def show(self):
		"""This method shows the wizard to user."""
		self.assistant.show_all()

	def hide(self):
		"""This method hides the wizard from user."""
		self.assistant.hide()

	def onApplyClicked(self, caller):
		""""""
		rootPath = self.selectImportPage.getSelectedBotRootPath()
		confPath = self.selectImportPage.getSelectedBotConfFilePath()
		name = self.botNamePage.getBotName()
		self.settingModel.addBot(name, confPath, rootPath)
		self.settingModel.save()

	def onCancelClicked(self, caller):
		""""""
		self.hide()

	def onCloseClicked(self, caller):
		""""""
		self.hide()

	def onPreparePage(self, caller, pageWidget):
		if self.botNamePage.widget == pageWidget:
			# set default name to the bot name page
			name = '%s @ RK%d' % (self.botConfig.getVar('name'), self.botConfig.getVar('dimension'))
			self.botNamePage.setBotName(name)
			
		elif self.finishPage.widget == pageWidget:
			values = []
			values.append(('Name', self.botNamePage.getBotName()))
			values.append(('Bot software path', self.selectImportPage.getSelectedBotRootPath()))
			values.append(('Bot config path', self.selectImportPage.getSelectedBotConfFilePath()))
			for key, value in self.botConfig:
				values.append((key, value))
			self.finishPage.setValues(values)

	def onSelectImportPageComplete(self, caller, property):
		path = self.selectImportPage.getSelectedBotConfFilePath()
		if path:
			config = BotPhpConfigFile(path)
			config.load()
			self.botConfig = config

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

class Assistant(gtk.Assistant):
	def __init__(self):
		super(Assistant, self).__init__()
		self.pages = []
		self.set_forward_page_func(self.getNextPage)
		self.connect('prepare', self.onPreparePage)

	def appendPage(self, page):
		"""Helper method for adding pages to the wizard."""
		self.pages.append(page)
		page.index = self.append_page(page.widget)
		self.set_page_title(page.widget, page.getTitle())
		self.set_page_type(page.widget, page.getType())
		page.connect('notify::complete', self.onPageCompletenessChanged)

	def getNextPage(self, currentPageIndex):
		"""This method is called by GtkAssistant's implementation to determine
		to which page index the wizard should change when user clicks
		forward-button. -1 means error.
		"""
		for page in self.pages:
			if page.index == currentPageIndex:
				for nextPage in self.pages:
					if nextPage.id == page.getNextPageId():
						return nextPage.index
		return -1

	def onPreparePage(self, caller, pageWidget):
		for page in self.pages:
			if page.widget == pageWidget:
				page.prepare()
				self.set_page_complete(page.widget, page.get_property('complete'))

	def onPageCompletenessChanged(self, page, property):
		self.set_page_complete(page.widget, page.get_property('complete'))

class Page(gobject.GObject):
	# custom properties
	__gproperties__ = {
		'complete' : (gobject.TYPE_BOOLEAN, 'complete', 'is page complete', False, gobject.PARAM_READWRITE),
	}

	def __init__(self, id):
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

	def getNextPageId(self):
		return self.nextPageIdFunc(*self.nextPageIdFuncArgs)

	def getTitle(self):
		return self.title

	def getType(self):
		return self.type

	def setType(self, type):
		"""This method sets type of the page."""
		self.type = type

	def setTitle(self, title):
		"""This method sets given string as page's title."""
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

	def __init__(self, id, builder):
		super(SelectActionPage, self).__init__(id)
		self.setType(gtk.ASSISTANT_PAGE_INTRO)
		self.setTitle('Add or import bot')
		self.setNextPageIdFunc(self.nextPageId)
		self.widget = builder.get_object('selectActionPage')
		self.addBotRadioButton = builder.get_object('addBotRadioButton')
		self.importBotRadioButton = builder.get_object('importBotRadioButton')
		self.importBotRadioButton.set_group(self.addBotRadioButton)

	def nextPageId(self):
		if self.addBotRadioButton.get_property('active'):
			return None
		elif self.importBotRadioButton.get_property('active'):
			return SELECT_IMPORT_PAGE_ID
		return None

class SelectImportPage(Page):
	def __init__(self, id, builder, settingModel):
		super(SelectImportPage, self).__init__(id)
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

class NameBotPage(Page):
	def __init__(self, id, builder):
		super(NameBotPage, self).__init__(id)
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
	def __init__(self, id, builder):
		super(FinishPage, self).__init__(id)
		self.setType(gtk.ASSISTANT_PAGE_CONFIRM)
		self.setTitle('Summary')
		self.widget = builder.get_object('finishPage')
		self.summaryLabel = builder.get_object('summaryLabel')

	def setValues(self, values):
		contents = ''
		for key, value in values:
			if key == 'login' or key == 'password' or key == 'DB username' or key == 'DB password':
				value = len(value) * '**'
			contents += '<b>' + key + ':</b> ' + str(value) + '\n'
		self.summaryLabel.set_markup(contents)

