#! /usr/bin/env python
# -*- coding: utf-8 -*-

import os
import gtk
import gobject
from botconfigfile import BotPhpConfigFile

class AddBotWizardController:
	""""""
	
	def __init__(self, botModel, settingModel):
		self.settingModel = settingModel

		# load addbotwizard.glade file
		self.builder = gtk.Builder()
		self.builder.add_from_file('addbotwizard.glade')

		self.assistant = Assistant()
		self.assistant.set_forward_page_func(self.getNextPage)
		self.assistant.connect('apply', self.onApplyClicked)
		self.assistant.connect('cancel', self.onCancelClicked)
		self.assistant.connect('close', self.onCloseClicked)

		self.assistant.appendPage(SelectActionPage(self.builder))
		self.assistant.appendPage(SelectImportPage(self.builder, settingModel))
		self.assistant.appendPage(FinishPage(self.builder))

	def show(self):
		"""This method shows the wizard to user."""
		self.assistant.show_all()

	def hide(self):
		"""This method hides the wizard from user."""
		self.assistant.hide()

	def getNextPage(self, currentPageIndex):
		"""This method is called by GtkAssistant's implementation to determine
		to which page index the wizard should change when user clicks
		forward-button. -1 means error.
		"""
		page = self.assistant.getPageByIndex(currentPageIndex)
		if isinstance(page, SelectActionPage):
			if page.getChoice() == SelectActionPage.ADD_BOT:
				return -1
			elif page.getChoice() == SelectActionPage.IMPORT_BOT:
				return self.assistant.getPageByType(SelectImportPage).index
		elif isinstance(page, SelectImportPage):
			return self.assistant.getPageByType(FinishPage).index
		return -1

	def onApplyClicked(self, caller):
		""""""
		print 'apply clicked'

	def onCancelClicked(self, caller):
		""""""
		self.hide()

	def onCloseClicked(self, caller):
		""""""
		print 'close clicked'

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
		self.connect('prepare', self.onPreparePage)

	def appendPage(self, page):
		"""Helper method for adding pages to the wizard."""
		self.pages.append(page)
		page.index = self.append_page(page.widget)
		self.set_page_title(page.widget, page.getTitle())
		self.set_page_type(page.widget, page.getType())
		page.connect('completeness_changed', self.onPageCompletenessChanged)

	def getPageByIndex(self, index):
		for page in self.pages:
			if page.index == index:
				return page
		return None

	def getPageByType(self, type):
		for page in self.pages:
			if isinstance(page, type):
				return page
		return None

	def onPreparePage(self, caller, pageWidget):
		for page in self.pages:
			if page.widget == pageWidget:
				page.prepare()
				self.set_page_complete(page.widget, page.isComplete())

	def onPageCompletenessChanged(self, page):
		self.set_page_complete(page.widget, page.isComplete())

class Page(gobject.GObject):
	# Define custom signals that this class can emit.
	__gsignals__ = {
		'completeness_changed': (gobject.SIGNAL_RUN_LAST, gobject.TYPE_NONE, ()),
	}

	def __init__():
		self.__gobject_init__()
		self.index = -1
		self.widget = None

	def isComplete(self):
		return True

	def prepare(self):
		pass
	
	def getTitle(self):
		return ''

	def getType(self):
		return None

# register class so that custom signals will work
gobject.type_register(Page)

class SelectActionPage(Page):
	ADD_BOT = 0
	IMPORT_BOT = 1

	def __init__(self, builder):
		super(Page, self).__init__()
		self.widget = builder.get_object('selectActionPage')
		self.addBotRadioButton = builder.get_object('addBotRadioButton')
		self.importBotRadioButton = builder.get_object('importBotRadioButton')
		self.importBotRadioButton.set_group(self.addBotRadioButton)

	def getChoice(self):
		if self.addBotRadioButton.get_property('active'):
			return self.ADD_BOT
		elif self.importBotRadioButton.get_property('active'):
			return self.IMPORT_BOT
		# no radio button selected, wtf?
		return None

	def getTitle(self):
		return 'Add or import bot'

	def getType(self):
		return gtk.ASSISTANT_PAGE_INTRO

class SelectImportPage(Page):
	def __init__(self, builder, settingModel):
		super(Page, self).__init__()
		self.widget = builder.get_object('selectImportPage')
		self.settingModel = settingModel
		dirChooser = builder.get_object('botImportDirChooser')
		dirChooser.connect('current-folder-changed', self.onBotImportDirChoosen)
		dirChooser.set_current_folder(self.settingModel.getDefaultBotRootPath())
		self.botImportModel = BotImportModel()
		self.botView = builder.get_object('importBotListView')
		self.botView.set_model(self.botImportModel)
		self.botView.get_selection().connect('changed', self.onBotSelected)

	def isComplete(self):
		# we can proceed if at least one bot is selected in the bot list view
		selected = self.botView.get_selection().get_selected()
		return selected[1] != None

	def getTitle(self):
		return 'Import existing bot'

	def getType(self):
		return gtk.ASSISTANT_PAGE_CONTENT

	def onBotImportDirChoosen(self, chooser):
		"""This signal handler is called when user chooses a directory in import wizard."""
		modelPath = os.path.join(chooser.get_filename(), 'conf')
		if os.path.isdir(modelPath):
			self.botImportModel.load(modelPath)
			# save current path to settings for later use if bots were found
			if len(self.botImportModel) > 0:
				self.settingModel.setDefaultBotRootPath(chooser.get_filename())
				self.settingModel.save()
		else:
			self.botImportModel.clear()

	def onBotSelected(self, caller):
		self.emit('completeness_changed')

class FinishPage(Page):
	def __init__(self, builder):
		super(Page, self).__init__()
		self.widget = builder.get_object('finishPage')

	def getTitle(self):
		return 'Summary'

	def getType(self):
		return gtk.ASSISTANT_PAGE_CONFIRM
