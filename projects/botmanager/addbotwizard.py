#! /usr/bin/env python
# -*- coding: utf-8 -*-

import webbrowser
import gtk
from botconfigfile import BotPhpConfigFile
from addbotwizardpages import SelectActionPage, SelectImportPage, NameBotPage
from addbotwizardpages import FinishPage, SelectBotInstallDirectoryPage, EnterAccountInfoPage
from addbotwizardpages import EnterCharacterInfoPage, SelectBotTypePage, EnterSuperAdminPage
from addbotwizardpages import SelectDatabaseSettingsPage, SelectDefaultModuleStatusPage
from addbotwizardpages import SelectDatabaseTypePage, EnterSqliteSettingsPage, EnterMysqlSettingsPage

class AddBotWizardController:
	""""""
	
	def __init__(self, parentWindow, botModel, settingModel):
		"""Constructor method."""
		self.settingModel = settingModel

		# load addbotwizard.glade file
		self.builder = gtk.Builder()
		self.builder.add_from_file('addbotwizard.glade')

		self.assistant = Assistant()
		self.assistant.set_property('title', 'Budabot - Add Bot Wizard')

		# position the wizard on top of control panel's window
		self.assistant.set_transient_for(parentWindow)
		self.assistant.set_property('window-position', gtk.WIN_POS_CENTER_ON_PARENT)

		self.assistant.connect('apply', self.onApplyClicked)
		self.assistant.connect('cancel', self.onCancelClicked)
		self.assistant.connect('close', self.onCloseClicked)
		self.assistant.connect('prepare', self.onPreparePage)
		# create pages
		self.selectActionPage        = SelectActionPage(self)
		self.selectImportPage        = SelectImportPage(self)
		self.selectBotInstallDirPage = SelectBotInstallDirectoryPage(self)
		self.enterAccountInfoPage    = EnterAccountInfoPage(self)
		self.enterCharacterInfoPage  = EnterCharacterInfoPage(self)
		self.selectBotTypePage       = SelectBotTypePage(self)
		self.enterSuperAdminPage     = EnterSuperAdminPage(self)
		self.selectDBSettingsPage    = SelectDatabaseSettingsPage(self)
		self.selectDBTypePage        = SelectDatabaseTypePage(self)
		self.enterSqliteSettingsPage = EnterSqliteSettingsPage(self)
		self.enterMysqlSettingsPage  = EnterMysqlSettingsPage(self)
		self.selectModuleStatusPage  = SelectDefaultModuleStatusPage(self)
		self.botNamePage             = NameBotPage(self)
		self.finishPage              = FinishPage(self)
		# Add pages to the wizard
		self.assistant.appendPage(self.selectActionPage) # first appended page is the starting page
		self.assistant.appendPage(self.selectImportPage)
		self.assistant.appendPage(self.selectBotInstallDirPage)
		self.assistant.appendPage(self.enterAccountInfoPage)
		self.assistant.appendPage(self.enterCharacterInfoPage)
		self.assistant.appendPage(self.selectBotTypePage)
		self.assistant.appendPage(self.enterSuperAdminPage)
		self.assistant.appendPage(self.selectDBSettingsPage)
		self.assistant.appendPage(self.selectDBTypePage)
		self.assistant.appendPage(self.enterSqliteSettingsPage)
		self.assistant.appendPage(self.enterMysqlSettingsPage)
		self.assistant.appendPage(self.selectModuleStatusPage)
		self.assistant.appendPage(self.botNamePage)
		self.assistant.appendPage(self.finishPage)

		self.selectImportPage.connect('notify::complete', self.onSelectImportPageComplete)

		# connect any 'activate-link' signals (if available) to onLink() handler
		for object in self.builder.get_objects():
			try:
				object.connect('activate-link', self.onLink)
			except TypeError:
				pass

	def getBotInstallPath(self):
		"""Returns currently selected bot install path."""
		if self.selectActionPage.getActionType() == SelectActionPage.TYPE_IMPORT:
			return self.selectImportPage.getSelectedBotRootPath()
		elif self.selectActionPage.getActionType() == SelectActionPage.TYPE_ADDNEW:
			return self.selectBotInstallDirPage.getSelectedBotRootPath()

	def getViewObject(self, name):
		"""Wrapper method for requesting objects from Gtk's Builder."""
		return self.builder.get_object(name)

	def getSettingModel(self):
		"""Returns the SettingModel object."""
		return self.settingModel

	def getAssistant(self):
		"""Returns assistant's object."""
		return self.assistant

	def show(self):
		"""This method shows the wizard to user."""
		self.assistant.show_all()

	def hide(self):
		"""This method hides the wizard from user."""
		self.assistant.hide()

	def onLink(self, caller, uri):
		"""Handles any clicked hyperlinks by opening them to default browser."""
		webbrowser.open(uri)
		return True

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
