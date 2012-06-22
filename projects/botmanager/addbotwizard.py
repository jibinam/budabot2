#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gtk
from botconfigfile import BotPhpConfigFile
from addbotwizardpages import SelectActionPage, SelectImportPage, NameBotPage
from addbotwizardpages import FinishPage, SelectBotInstallDirectoryPage, EnterAccountInfoPage
from addbotwizardpages import EnterCharacterInfoPage, SelectBotTypePage

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

		self.selectActionPage        = SelectActionPage(self.builder)
		self.selectImportPage        = SelectImportPage(self.builder, settingModel)
		self.selectBotInstallDirPage = SelectBotInstallDirectoryPage(self.builder, settingModel)
		self.enterAccountInfoPage    = EnterAccountInfoPage(self.builder)
		self.enterCharacterInfoPage  = EnterCharacterInfoPage(self.builder)
		self.selectBotTypePage       = SelectBotTypePage(self.builder)
		self.botNamePage             = NameBotPage(self.builder)
		self.finishPage              = FinishPage(self.builder)
		self.assistant.appendPage(self.selectActionPage)
		self.assistant.appendPage(self.selectImportPage)
		self.assistant.appendPage(self.selectBotInstallDirPage)
		self.assistant.appendPage(self.enterAccountInfoPage)
		self.assistant.appendPage(self.enterCharacterInfoPage)
		self.assistant.appendPage(self.selectBotTypePage)
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
		if self.botNamePage.getWidget() == pageWidget:
			# set default name to the bot name page
			name = '%s @ RK%d' % (self.botConfig.getVar('name'), self.botConfig.getVar('dimension'))
			self.botNamePage.setBotName(name)
			
		elif self.finishPage.getWidget() == pageWidget:
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

class Assistant(gtk.Assistant):
	def __init__(self):
		super(Assistant, self).__init__()
		self.pages = []
		self.set_forward_page_func(self.getNextPage)
		self.connect('prepare', self.onPreparePage)

	def appendPage(self, page):
		"""Helper method for adding pages to the wizard."""
		self.pages.append(page)
		page.index = self.append_page(page.getWidget())
		self.set_page_title(page.getWidget(), page.getTitle())
		self.set_page_type(page.getWidget(), page.getType())
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
			if page.getWidget() == pageWidget:
				page.prepare()
				self.set_page_complete(page.getWidget(), page.get_property('complete'))

	def onPageCompletenessChanged(self, page, property):
		self.set_page_complete(page.getWidget(), page.get_property('complete'))
